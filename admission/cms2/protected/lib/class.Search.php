<?php

class Search extends Singleton {
	protected $query = NULL;
	protected $nLength = NULL;
	protected $token_tablename = NULL;
	protected $data_tablename = NULL;
	protected $log_tablename = NULL;
	protected $punc_patterns = array();
	protected $stop_patterns = array();
	protected $sortMethod = "mixed";
	protected $tables = array();
	protected $session_searches = array();
	protected $database;
	protected $search_id;
	protected $noquery = false;
	protected $alt_ordering = array();
	protected $returnType = "records";		// or "details"

	public $results;
	protected static $table_exists = false;
		
	public function init() {
		// the first time this class is loaded, make sure the table `content` is created
		if (static::$table_exists == false) {
			if (! db()->table_exists($this->table) ) {
				$sql = file_get_contents(ENGINE_PROTECTED_PATH . "/var/sql/search.sql");
				db()->rawQuery($sql);
				static::$table_exists = true;
			}
		}

		$this->nLength = 2;
		$this->token_tablename = "_search_index";
		$this->data_tablename = "_search_data";
		$this->log_tablename = "_search_log";
		
		$this->stemmer = new Stemmer();
	
		/* Open the stop words into an array */
		$noiseobj = new NoiseWords();
		$swords = $noiseobj->words;
		$this->stop_patterns = array();
		foreach ($swords as $word) {
			$word = rtrim($word);
			$this->stop_patterns[] = '/ ' . $word . ' /i' ;
		}
		
		/* Set the punctuation patterns */
		$this->punc_patterns = array(
			'/,/',
			'/\?/',
			'/\)/',
			'/\(/',
			'/\./',
			'/\!/',
			'/\$/',
			'/-/'
		);		
		
	}

	public function setReturnType($returnType) {
		$this->returnType = $returnType;
	}
	

	public function setNoQuery($bool) {
		$this->noquery = $bool;
	}
	
	public function setNewSearch() {
		$id = random_string();
		$this->search_id = $id;
		return $id;
	}
	
	public function set_search_id($search_id) {
		$this->search_id = $search_id;
	}
	
	public function get_search_id() {
		return $this->search_id;
	}
	
	
	// The purpose of this is to give finer control over result ordering by specifying a constraint expression in the column list. 
	// Example:
	//  $expression = "if (`tablename`.`foo` is null or `tablename`.`foo` = '', 'ZZZ', `tablename`.`foo`)"				USE FULL `TABLE`.`COLUMN` SYNTAX!!
	//  $col_name = "sort_col"
	//  $direction = "DESC"
	public function set_alt_ordering($table, $expression, $col_name, $direction) {
		$this->alt_ordering[$table]["expression"] = $expression;
		$this->alt_ordering[$table]["col_name"] = $col_name;
		$this->alt_ordering[$table]["direction"] = $direction;
	}
	
	public function clear_alt_ordering() {
		$this->alt_ordering = array();
	}
	
	public function reset() {
		$this->alt_ordering = array();		//TODO if I was smart I would just move alt_ordering values to the tables object...
		$this->tables = array();
	}
		
	
	public function clear_item($content_ids, $type) {
		$this->_clear_tokens($content_ids, $type);
		$this->_clear_data($content_ids, $type);
	}
	public function run() {
		$token_map = $this->_get_nLength_tokens($this->_get_individual_tokens($this->query), $this->nLength);
		$content_map = array();
		$content_map_reverse = array();
		$scores = array();
			
		$results = array();
		
		$content_idx = 0;
		
		if (empty($this->query)) {
			$this->setNoQuery(true);
		}

		foreach ($this->tables as $table) {
			foreach ($token_map as $token => $tcount) {
				//$sql = "SELECT T.*,D.data FROM `$this->token_tablename` AS T,`$this->data_tablename` AS D WHERE T.token='$token' AND T.content_type=D.content_type AND T.content_id=D.content_id AND ($tsql_s)";
				
				if (isset($this->alt_ordering[$table->table])) {
					$alt_expr = ", " . $this->alt_ordering[$table->table]["expression"] . " as " . $this->alt_ordering[$table->table]["col_name"]; 
				} else {
					$alt_expr = "";
				}

				$params = array();

				if ($this->noquery == true) {
					$sql = "select distinct `{$table->table}`.* {$alt_expr},{$this->token_tablename}.content_id, {$this->token_tablename}.content_type from `{$this->token_tablename}` inner join `{$table->table}` on `{$this->token_tablename}`.`content_id`=`{$table->table}`.`{$table->idfield}` where `content_type`=:content_type";
					$params[":content_type"] = $table->table;
				} else {
					$sql = "select * {$alt_expr} from `{$this->token_tablename}` inner join `{$table->table}` on `{$this->token_tablename}`.`content_id`=`{$table->table}`.`{$table->idfield}` where content_type=:content_type and token=:token";
					$params[":content_type"] = $table->table;
					$params[":token"] = $token;
				}
				if (! empty($table->where_clause) ) {
					$sql .= " and ({$table->where_clause})";
				}
				if ($this->noquery == true) {
					$sql .= " group by `content_id`";
				}
				if (isset($this->alt_ordering[$table->table])) {
					$sql .= " order by " . $this->alt_ordering[$table->table]["col_name"] . " " . $this->alt_ordering[$table->table]["direction"];
				}
				$results = db()->getRowsCustom($sql, $params, Model::clsname($table->table));
				foreach ($results as $result) {
					$weight = count(explode(" ", $result->token));
					$score = $result->tcount * $weight;
					
					/* Does a unique index exist for this type/id combo yet? */
					if (! isset($content_map[$result->content_type][$result->content_id]) ) {
						/* Apparently not. Use $content_idx in the content_map and increment it for the next go-around */
						$content_map[$result->content_type][$result->content_id] = "cid=$content_idx";
						$content_idx++;
						
						/* Make an entry in the reverse content_map so we can dig both ways */
						$content_map_reverse["cid=$content_idx"] = 	array(
							"content_type" => $result->content_type, 
							"content_id" => $result->content_id,
							"result" => $result 
						);
						
						/* Make sure we know to use our newly activated content_idx to store the score */
						$use_idx = $content_idx;		
					} else {
						/* That entry already exists. Dig for its content_idx and use it to store/update the score */
						$use_idx = str_replace("cid=", "", $content_map[$result->content_type][$result->content_id]);
					}
					
					/* Store/update the score for this content_idx */
					if ($this->sortMethod == "mixed") {
						if (! isset($scores["cid=$use_idx"]) ) {
							$scores["cid=$use_idx"] = 0;
						}
						$scores["cid=$use_idx"] += $score;
					} elseif ($this->sortMethod == "type") {
						if (! isset($scores[$result->content_type]["cid=$use_idx"])) {
							$scores[$result->content_type]["cid=$use_idx"] = 0;
						}
						$scores[$result->content_type]["cid=$use_idx"] += $score;
					}
					
				}
				if ($this->noquery == true) {
					/* Only execute this loop once if we have been instructed to ignore the query string */
					break;
				}
			}
		}		
		if ($this->sortMethod == "mixed") {
			
			/* Sort the scores */
			arsort($scores);
			
			/* Make the hits/results array based on the sorted score */
			$hits = array();
			foreach ($scores as $idx_str => $score) {
				$details = $content_map_reverse[$idx_str];
				
				/* Add the score to the details */
				$details["score"] = $score;
				
				$hits[] = $details;
			}
		} elseif ($this->sortMethod == "type") {
			foreach ($types as $type) {
				/* Sort the scores for this type */
				arsort($scores[$type]);		
						
				/* Make the hits/results array based on the sorted score */
				$hits[$type] = array();
				foreach ($scores[$type] as $idx_str => $score) {
					$details = $content_map_reverse[$idx_str];
					
					/* Add the score to the details */
					$details["score"] = $score;
					
					$hits[$type][] = $details;
				}
			}
		}
		
		$this->__stop_timer("search");
		
		/* Only log this search if it's the first time it's appeared in this session */
		if ( ! isset($this->session_searches[$this->search_id] )) {
			$this->session_searches[$this->search_id] = 1;
			//$this->_log_query($this->query);
		}
		
		if (! is_null($limit) && is_numeric($limit) && $limit > 0) {
			$hits = array_slice($hits, 0, $limit);
		}
		$this->results = $hits;		
	}
	
	public function appendData($index, $key, $value) {
		$this->results[$index][$key] = $value;
	}
	
	//every result in the results array has a few top-level keys. this returns an array containing the value for every record's instance of that key
	public function retrieveIndexingData($column) {	
		$a = array();
		foreach ($this->results as $r) {
			$a[] = $r[$column];
		}
		return $a;
	}
	
	public function retrieveResultColumn($column) {	
		$a = array();
		foreach ($this->results as $r) {
			$_r = $r["result"];
			$a[] = $_r->{$column};
		}
		return $a;
	}
	
	public function orderByResultColumn($column) {
	   $sorted = $this->results;
	   for ($i=0; $i < sizeof($sorted)-1; $i++) {
	     for ($j=0; $j<sizeof($sorted)-1-$i; $j++)
	       if ($sorted[$j]["result"]->$column > $sorted[$j+1]["result"]->$column) {
	         $tmp = $sorted[$j];
	         $sorted[$j] = $sorted[$j+1];
	         $sorted[$j+1] = $tmp;
	     }
	   }
	   $this->results = $sorted;
	}

	public function orderByIndexingData($column) {
  	   $sorted = $this->results;
	   for ($i=0; $i < sizeof($sorted)-1; $i++) {
	     for ($j=0; $j<sizeof($sorted)-1-$i; $j++)
	       if ($sorted[$j][$column] > $sorted[$j+1][$column]) {
	         $tmp = $sorted[$j];
	         $sorted[$j] = $sorted[$j+1];
	         $sorted[$j+1] = $tmp;
	     }
	   }
	  $this->results = $sorted;
	}
	
	/*
	 * @content_ids = array() or empty
	 * @types = array(), single type (tablename) or empty
	 * 
	 */
	function build_index($content_ids = NULL, $types = NULL) {
		$this->__start_timer("index");
		
		if (! is_array($types) ) {
			if (empty($types)) {
				$types = array();
				foreach ($this->tables as $t) {
					$types[] = $t->table;
				}
			} elseif (! empty($types) && ! is_array($types)) {
				$types = array($types);
			}
		}
		if (! is_array($content_ids)) {
			if (! empty($content_ids)) {
				$content_ids = array($content_ids);
			}
		}
		foreach ($types as $type) {
			if (! $table = $this->get_table($type)) {
				return false;
			}
					
			if (! is_array($content_ids) ) {
				/* Null $content_ids value indicates our desire to index entire table for $type */
				if (is_null($content_ids)) {
					$content_ids = array();
					$sql = "SELECT `{$table->idfield}` FROM `{$table->table}`";
					$query = db()->db->prepare($sql);
					$query->execute();
					//$res = mysql_query($sql, $this->database->db_handle) or die("Error on $sql while pulling content IDs\n");

					while ($row = $query->fetch(PDO::FETCH_OBJ)) {
						$content_ids[] = $row->{$table->idfield};	
					}
				} else {
					return false;
				}
			}
			
			$fields = implode(",", $table->fields);
			
			$count = 0;
			$total = count($content_ids);
			foreach ($content_ids as $content_id) {
				$count++;
				if ($this->is_console == true) {
					echo "[$type] - Indexing $count of $total\n";
				}
				
				/* Tokens */
				$sql = "SELECT $fields FROM `{$table->table}` WHERE `{$table->idfield}`='$content_id'";
				//$res = mysql_query($sql, $this->database->db_handle) or die("Error on $sql while pulling data:" . $this->database->error . "\n");
				//$result = mysql_fetch_row($res);
				$query = db()->db->prepare($sql);
				$query->execute();
				$result = array_values($query->fetch(PDO::FETCH_ASSOC));
				$tokenstr = implode(" ", $result);
				$tokenstr = preg_replace( '!<br.*>!iU', " ", $tokenstr);
				$tokenstr = strip_tags($tokenstr);
				
				$tokens = $this->_get_individual_tokens($tokenstr);
						
				$this->_clear_tokens($content_id, $type);
				$token_map = $this->_get_nLength_tokens($tokens, $this->nLength);
				$this->_add_tokens_to_database($token_map, $content_id, $type);
				
				/* Serialized data */
				$sql = "SELECT {$table->idfield},$fields FROM `{$table->table}` WHERE `{$table->idfield}`='$content_id'";
				//$res = mysql_query($sql, $this->database->db_handle) or die("Error on $sql while re-pulling data.\n");
				//$row = mysql_fetch_object($res);
				$query = db()->db->prepare($sql);
				$query->execute();
				$row = array_values($query->fetch(PDO::FETCH_ASSOC));
				$this->_clear_data($content_id, $type);
				$this->_add_data_to_database($content_id, $type, $row);
			}		
		}
		$this->__stop_timer("index");
		
	}	
	
	public function orderReverse() {
		$this->results = array_reverse($this->results);
	}
	
	public function setQuery($query) {
		$this->query = $query;
	}
	
	public function setnLength($nLength) {
		$this->nLength = $nLength;
	}
	
	public function setTokenTablename($table) {
		$this->token_tablename = $table;
	}
	
	public function setDataTablename($table) {
		$this->data_tablename = $table;
	}
	
	// can be "mixed" or "type"
	public function setSortMethod($method) {
		$this->sortMethod = $method;
	}
	
	public function addTable($obj) {
		$this->tables[$obj->table] = $obj;
	}

	public function setWhereClause($tableName, $where_clause) {
		if (isset($this->tables[$tableName])) {
			$obj =&$this->tables[$tableName];
			$obj->where_clause = $where_clause;
		}
	}

	
	protected function _get_unique_tokens($str) {
		$str = addslashes(trim($str));
		
		/* Put a leading and trailing space in so that the pattern matching detects all words */
		$str = ' ' . $str . ' ';
		
		/* Strip out stopwords */
		$str = $this->_strip_stopwords($str);
		
		/* Strip out excessive whitespace */
		$str = $this->_strip_whitespace($str);
		
		/* Turn into an array */
		$unstemmed_tokens = $this->_tokenize_str($str);
		
		/* Reduce the token list with stemming and array_unique() */
		return array_unique($this->stemmer->stem_list($unstemmed_tokens));	
	}
	
	protected function _get_individual_tokens($str) {
		$str = addslashes(trim($str));
		
		/* Put a leading and trailing space in so that the pattern matching detects all words */
		$str = ' ' . $str . ' ';
		
		/* Strip out stopwords */
		$str = $this->_strip_stopwords($str);
		
		/* Strip out excessive whitespace */
		//$str = $this->_strip_whitespace($str);
		
		/* Turn into an array */
		$unstemmed_tokens = $this->_tokenize_str($str);
		
		/* Reduce the token list with stemming */
		return $this->stemmer->stem_list($unstemmed_tokens);	
	}	
	
	/* Function name a misnomer; given n=3 you'll get all token strings of length n=1, n=2, n=3 */
	protected function _get_nLength_tokens(&$tokens, $n) {
		$results = array();
		
		if (count($tokens) == 1) {
			$results[$tokens[0]] = 1;
		} else {
		
			for ($i=0; $i<($n+1); $i++) {
				for ($j=0; $j<count($tokens); $j++) {
					$str = "";
					for ($k=$j; $k<($j+$i); $k++) {
						$str .= trim($tokens[$k]) . " ";
					}
					$str = trim($str);
					if (empty($str)) {
						continue;
					}
					if (! isset($results[$str])) {
						$results[$str] = 1;
					} else {
						$results[$str]++;
					}
				}
			}
		}
		
		return $results;

	}
	
	
	/* Remove stopwords from a string */
	protected function _strip_stopwords($str) {
		return preg_replace($this->stop_patterns, ' ', $str);
	}
	
	/* Remove superfluous whitespace from a string */
	protected function _strip_whitespace($str) {
		return preg_replace('/\s{2,}/', ' ', $str);						

	}
	
	/* Split a string on whitespace into an array */
	protected function _tokenize_str($str) {
		$vals = explode(" ", $str);
		$filtered = array();
		foreach ($vals as $val) {
			if (! empty($val) ) {
				$filtered[] = $val;
			}
		}
		return $filtered;
	}

	protected function _log_query($query) {
		global $_cfg;
		$site = $_cfg["site_name"];
		if ($this->log_tablename == false) {
			return false;
		}
		$datetime = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO $this->log_tablename SET query='$query', date='$datetime', site='$site'";
		return db()->rawQuery($sql);
	}
	
	/* Keep the master token database up to date */
	protected function _add_tokens_to_database(&$token_map, $content_id, $type) {
		foreach ($token_map as $token => $count) {
			$sql = "INSERT INTO $this->token_tablename SET token='$token', tcount='$count', content_id='$content_id', content_type='$type'";
			db()->rawQuery($sql) ;
		}
	}
	
	protected function _clear_tokens($content_ids, $type) {
		if (! is_array($content_ids)) {
			$ids = array($content_ids);
		}
		foreach ($ids as $i) {
			db()->rawQuery("DELETE FROM `$this->token_tablename` WHERE `content_id`='$i' AND `content_type`='$type'");
		}
	}
		
	protected function _clear_data($content_ids, $type) {
		if (! is_array($content_ids)) {
			$ids = array($content_ids);
		}
		foreach ($ids as $i) {
			db()->rawQuery("DELETE FROM `$this->data_tablename` WHERE `content_id`='$i' AND `content_type`='$type'");
		}
	}
	
	/* $row should be an object */
	protected function _add_data_to_database($content_id, $type, $row) {
		$data = base64_encode(serialize($row));
		$sql = "INSERT INTO $this->data_tablename SET content_id='$content_id', content_type='$type', data='$data'";
		return db()->rawQuery($sql);
	}

	/* Stem a token using the stemmer class */
	protected function _stem_token($token) {
		return $this->stemmer->stem($token);
	}
	

	protected function __start_timer($type) {
		$this->timers[$type]["start"] = microtime();
		//echo $this->timers[$type]["start"] . "<br>";
	}
	
	protected function __stop_timer($type) {
		$this->timers[$type]["end"] = microtime();
		//echo $this->timers[$type]["end"] . "<br>";
	}
	
	protected function get_runtime($type) {
		$timeend = $this->timers[$type]["end"];
		$timestart = $this->timers[$type]["start"];
		return number_format(((substr($timeend,0,9)) + (substr($timeend,-10)) - (substr($timestart,0,9)) - (substr($timestart,-10))),4);
	}	
	
	protected function get_table($table) {
		foreach ($this->tables as $t) {
			if ($t->table == $table) {
				return $t;
			}
		}
		return false;
	}
	
	
}

class Search_Table {
	public $table;
	public $idfield;
	public $fields;
	public $where_clause;
	
	function __construct($table, $fields, $idfield, $where_clause) {
		$this->table = $table;
		$this->idfield = $idfield;
		$this->fields = $fields;
		$this->where_clause = $where_clause;
	}			
	
}



class NoiseWords {
	var $words = false;
	
	function __construct() {
		$this->words = array(
		
"a",
"about",
"1",
"after",
"2",
"all",
"also",
"3",
"an",
"4",
"and",
"5",
"another",
"6",
"any",
"7",
"are",
"8",
"as",
"9",
"at",
"0",
"be",
"because",
"been",
"before",
"being",
"between",
"both",
"but",
"by",
"came",
"can",
"come",
"could",
"did",
"do",
"each",
"for",
"from",
"get",
"got",
"has",
"had",
"he",
"have",
"her",
"here",
"him",
"himself",
"his",
"how",
"if",
"in",
"into",
"is",
"it",
"like",
"make",
"many",
"me",
"might",
"more",
"most",
"much",
"must",
"my",
"never",
"now",
"of",
"on",
"only",
"or",
"other",
"our",
"out",
"over",
"said",
"same",
"see",
"should",
"since",
"some",
"still",
"such",
"take",
"than",
"that",
"the",
"their",
"them",
"then",
"there",
"these",
"they",
"this",
"those",
"through",
"to",
"too",
"under",
"up",
"very",
"was",
"way",
"we",
"well",
"were",
"what",
"where",
"which",
"while",
"who",
"with",
"would",
"you",
"your"		
		
	);
	}
}


/*************************************************************************
 *                                                                       *
 * class.stemmer.inc                                                     *
 *                                                                       *
 *************************************************************************
 *                                                                       *
 * Implementation of the Porter Stemming Alorithm                        *
 *                                                                       *
 * Copyright (c) 2003 Jon Abernathy <jon@chuggnutt.com>                  *
 * All rights reserved.                                                  *
 *                                                                       *
 * This script is free software; you can redistribute it and/or modify   *
 * it under the terms of the GNU General Public License as published by  *
 * the Free Software Foundation; either version 2 of the License, or     *
 * (at your option) any later version.                                   *
 *                                                                       *
 * The GNU General Public License can be found at                        *
 * http://www.gnu.org/copyleft/gpl.html.                                 *
 *                                                                       *
 * This script is distributed in the hope that it will be useful,        *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          *
 * GNU General Public License for more details.                          *
 *                                                                       *
 * Author(s): Jon Abernathy <jon@chuggnutt.com>                          *
 *                                                                       *
 * Last modified: 11/09/05                                               *
 *                                                                       *
 *************************************************************************/


/**
 *  Takes a word, or list of words, and reduces them to their English stems.
 *
 *  This is a fairly faithful implementation of the Porter stemming algorithm that
 *  reduces English words to their stems, originally adapted from the ANSI-C code found
 *  on the official Porter Stemming Algorithm website, located at
 *  http://www.tartarus.org/~martin/PorterStemmer and later changed to conform
 *  more accurately to the algorithm itself.
 *
 *  There is a deviation in the way compound words are stemmed, such as
 *  hyphenated words and words starting with certain prefixes. For instance,
 *  "international" should be reduced to "internation" and not "intern," but
 *  an unmodified version of the alorithm will do just that. Currently, only
 *  hyphenated words are accounted for.
 *
 *  Thanks to Mike Boone (http://www.boonedocks.net/) for finding a fatal
 *  error in the is_consonant() function dealing with short word stems beginning
 *  with "Y".
 *
 *  Additional thanks to Mark Plumbley for finding an additional problem with
 *  short words beginning with "Y"--the word "yves" for example. I fixed the
 *  _o() and is_consonant() functions to appropriately sanity check the values
 *  being passed around. Updated 3/12/04.
 *
 *  Thanks to Andrew Jeffries (http://www.nextgendevelopment.co.uk/) for
 *  discovering a bug for words beginning with "yy"--this would cause the
 *  is_consonant() method checking either of these first "y"s to fall into
 *  a recursive infinite loop and crash the program. Updated 9/23/05.
 *
 *  11/09/05, big update. Prompted by an email from Richard Shelquist, I went
 *  back over the class and fixed some errors in the algorithm; in particular
 *  I made sure to conform EXACTLY to the written algorithm found at
 *  the Stemmer website. This class now takes the test vocabulary file found at
 *  http://tartarus.org/~martin/PorterStemmer/voc.txt and stems every single
 *  word exactly as shown in the output file found at
 *  http://tartarus.org/~martin/PorterStemmer/output.txt, with two exceptions:
 *  "ycleped" and "ycliped", which I believe my version stems correctly, due
 *  to assuming the "Y" at the beginning of a word followed by a consonant--
 *  as in "Yvette"--is to be treated as a vowel and NOT a consonant. Yeah,
 *  that's arrogant; allow me some, okay?
 *  Of course, should someone find an exception after boasting of my arrogance,
 *  please let me know. I'm only human, after all.
 *
 *  @author Jon Abernathy <jon@chuggnutt.com>
 *  @version 2.0
 */
class Stemmer
{
    /**
     *  Takes a word and returns it reduced to its stem.
     *
     *  Non-alphanumerics and hyphens are removed, and if the word is less than
     *  three characters in length, it will be stemmed according to the five-step
     *  Porter stemming algorithm.
     *
     *  Note special cases here: hyphenated words (such as half-life) will only
     *  have the base after the last hyphen stemmed (so half-life would only have
     *  "life" subject to stemming).
     *
     *  @param string $word Word to reduce
     *  @access public
     *  @return string Stemmed word
     */
    public function stem( $word )
    {
        if ( empty($word) ) {
            return false;
        }
        
        $result = '';

        $word = strtolower($word);

        // Strip punctuation, etc.
        if ( substr($word, -2) == "'s" ) {
            $word = substr($word, 0, -2);
        }
        $word = preg_replace('/[^a-z0-9-]/', '', $word);

        $first = '';
        if ( strpos($word, '-') !== false ) {
            list($first, $word) = explode('-', $word);
            $first .= '-';
        }
        if ( strlen($word) > 2 ) {
            $word = $this->_step_1($word);
            $word = $this->_step_2($word);
            $word = $this->_step_3($word);
            $word = $this->_step_4($word);
            $word = $this->_step_5($word);
        }

        $result = $first . $word;

        return $result;
    }

    /**
     *  Takes a list of words and returns them reduced to their stems.
     *
     *  $words can be either a string or an array. If it is a string, it will
     *  be split into separate words on whitespace, commas, or semicolons. If
     *  an array, it assumes one word per element.
     *
     *  @param mixed $words String or array of word(s) to reduce
     *  @access public
     *  @return array List of word stems
     */
    public function stem_list( $words )
    {
        if ( empty($words) ) {
            return false;
        }

        $results = array();

        if ( !is_array($words) ) {
            $words = split("[ ,;\n\r\t]+", trim($words));
        }

        foreach ( $words as $word ) {
            if ( $result = $this->stem($word) ) {
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     *  Performs the public functions of steps 1a and 1b of the Porter Stemming Algorithm.
     *
     *  First, if the word is in plural form, it is reduced to singular form.
     *  Then, any -ed or -ing endings are removed as appropriate, and finally,
     *  words ending in "y" with a vowel in the stem have the "y" changed to "i".
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    public function _step_1( $word )
    {
		// Step 1a
		if ( substr($word, -1) == 's' ) {
            if ( substr($word, -4) == 'sses' ) {
                $word = substr($word, 0, -2);
            } elseif ( substr($word, -3) == 'ies' ) {
                $word = substr($word, 0, -2);
            } elseif ( substr($word, -2, 1) != 's' ) {
                // If second-to-last character is not "s"
                $word = substr($word, 0, -1);
            }
        }
		// Step 1b
        if ( substr($word, -3) == 'eed' ) {
			if ($this->count_vc(substr($word, 0, -3)) > 0 ) {
	            // Convert '-eed' to '-ee'
	            $word = substr($word, 0, -1);
			}
        } else {
            if ( preg_match('/([aeiou]|[^aeiou]y).*(ed|ing)$/', $word) ) { // vowel in stem
                // Strip '-ed' or '-ing'
                if ( substr($word, -2) == 'ed' ) {
                    $word = substr($word, 0, -2);
                } else {
                    $word = substr($word, 0, -3);
                }
                if ( substr($word, -2) == 'at' || substr($word, -2) == 'bl' ||
                     substr($word, -2) == 'iz' ) {
                    $word .= 'e';
                } else {
                    $last_char = substr($word, -1, 1);
                    $next_to_last = substr($word, -2, 1);
                    // Strip ending double consonants to single, unless "l", "s" or "z"
                    if ( $this->is_consonant($word, -1) &&
                         $last_char == $next_to_last &&
                         $last_char != 'l' && $last_char != 's' && $last_char != 'z' ) {
                        $word = substr($word, 0, -1);
                    } else {
                        // If VC, and cvc (but not w,x,y at end)
                        if ( $this->count_vc($word) == 1 && $this->_o($word) ) {
                            $word .= 'e';
                        }
                    }
                }
            }
        }
        // Step 1c
        // Turn y into i when another vowel in stem
        if ( preg_match('/([aeiou]|[^aeiou]y).*y$/', $word) ) { // vowel in stem
            $word = substr($word, 0, -1) . 'i';
        }
        return $word;
    }

    /**
     *  Performs the public function of step 2 of the Porter Stemming Algorithm.
     *
     *  Step 2 maps double suffixes to single ones when the second-to-last character
     *  matches the given letters. So "-ization" (which is "-ize" plus "-ation"
     *  becomes "-ize". Mapping to a single character occurence speeds up the script
     *  by reducing the number of possible string searches.
     *
     *  Note: for this step (and steps 3 and 4), the algorithm requires that if
     *  a suffix match is found (checks longest first), then the step ends, regardless
     *  if a replacement occurred. Some (or many) implementations simply keep
     *  searching though a list of suffixes, even if one is found.
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    public function _step_2( $word )
    {
        switch ( substr($word, -2, 1) ) {
            case 'a':
                if ( $this->_replace($word, 'ational', 'ate', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'tional', 'tion', 0) ) {
                    return $word;
                }
                break;
            case 'c':
                if ( $this->_replace($word, 'enci', 'ence', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'anci', 'ance', 0) ) {
                    return $word;
                }
                break;
            case 'e':
                if ( $this->_replace($word, 'izer', 'ize', 0) ) {
                    return $word;
                }
                break;
            case 'l':
                // This condition is a departure from the original algorithm;
                // I adapted it from the departure in the ANSI-C version.
				if ( $this->_replace($word, 'bli', 'ble', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'alli', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'entli', 'ent', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'eli', 'e', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ousli', 'ous', 0) ) {
                    return $word;
                }
                break;
            case 'o':
                if ( $this->_replace($word, 'ization', 'ize', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'isation', 'ize', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ation', 'ate', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ator', 'ate', 0) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'alism', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iveness', 'ive', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'fulness', 'ful', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ousness', 'ous', 0) ) {
                    return $word;
                }
                break;
            case 't':
                if ( $this->_replace($word, 'aliti', 'al', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iviti', 'ive', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'biliti', 'ble', 0) ) {
                    return $word;
                }
                break;
            case 'g':
                // This condition is a departure from the original algorithm;
                // I adapted it from the departure in the ANSI-C version.
                if ( $this->_replace($word, 'logi', 'log', 0) ) { //*****
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the public function of step 3 of the Porter Stemming Algorithm.
     *
     *  Step 3 works in a similar stragegy to step 2, though checking the
     *  last character.
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    public function _step_3( $word )
    {
        switch ( substr($word, -1) ) {
            case 'e':
                if ( $this->_replace($word, 'icate', 'ic', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ative', '', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'alize', 'al', 0) ) {
                    return $word;
                }
                break;
            case 'i':
                if ( $this->_replace($word, 'iciti', 'ic', 0) ) {
                    return $word;
                }
                break;
            case 'l':
                if ( $this->_replace($word, 'ical', 'ic', 0) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ful', '', 0) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'ness', '', 0) ) {
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the public function of step 4 of the Porter Stemming Algorithm.
     *
     *  Step 4 works similarly to steps 3 and 2, above, though it removes
     *  the endings in the context of VCVC (vowel-consonant-vowel-consonant
     *  combinations).
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    public function _step_4( $word )
    {
        switch ( substr($word, -2, 1) ) {
            case 'a':
                if ( $this->_replace($word, 'al', '', 1) ) {
                    return $word;
                }
                break;
            case 'c':
                if ( $this->_replace($word, 'ance', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ence', '', 1) ) {
                    return $word;
                }
                break;
            case 'e':
                if ( $this->_replace($word, 'er', '', 1) ) {
                    return $word;
                }
                break;
            case 'i':
                if ( $this->_replace($word, 'ic', '', 1) ) {
                    return $word;
                }
                break;
            case 'l':
                if ( $this->_replace($word, 'able', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ible', '', 1) ) {
                    return $word;
                }
                break;
            case 'n':
                if ( $this->_replace($word, 'ant', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ement', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ment', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'ent', '', 1) ) {
                    return $word;
                }
                break;
            case 'o':
                // special cases
                if ( substr($word, -4) == 'sion' || substr($word, -4) == 'tion' ) {
                    if ( $this->_replace($word, 'ion', '', 1) ) {
                        return $word;
                    }
                }
                if ( $this->_replace($word, 'ou', '', 1) ) {
                    return $word;
                }
                break;
            case 's':
                if ( $this->_replace($word, 'ism', '', 1) ) {
                    return $word;
                }
                break;
            case 't':
                if ( $this->_replace($word, 'ate', '', 1) ) {
                    return $word;
                }
                if ( $this->_replace($word, 'iti', '', 1) ) {
                    return $word;
                }
                break;
            case 'u':
                if ( $this->_replace($word, 'ous', '', 1) ) {
                    return $word;
                }
                break;
            case 'v':
                if ( $this->_replace($word, 'ive', '', 1) ) {
                    return $word;
                }
                break;
            case 'z':
                if ( $this->_replace($word, 'ize', '', 1) ) {
                    return $word;
                }
                break;
        }
        return $word;
    }

    /**
     *  Performs the public function of step 5 of the Porter Stemming Algorithm.
     *
     *  Step 5 removes a final "-e" and changes "-ll" to "-l" in the context
     *  of VCVC (vowel-consonant-vowel-consonant combinations).
     *
     *  @param string $word Word to reduce
     *  @access private
     *  @return string Reduced word
     */
    public function _step_5( $word )
    {
        if ( substr($word, -1) == 'e' ) {
            $short = substr($word, 0, -1);
            // Only remove in vcvc context...
            if ( $this->count_vc($short) > 1 ) {
                $word = $short;
            } elseif ( $this->count_vc($short) == 1 && !$this->_o($short) ) {
                $word = $short;
            }
        }
        if ( substr($word, -2) == 'll' ) {
            // Only remove in vcvc context...
            if ( $this->count_vc($word) > 1 ) {
                $word = substr($word, 0, -1);
            }
        }
        return $word;
    }

    /**
     *  Checks that the specified letter (position) in the word is a consonant.
     *
     *  Handy check adapted from the ANSI C program. Regular vowels always return
     *  FALSE, while "y" is a special case: if the prececing character is a vowel,
     *  "y" is a consonant, otherwise it's a vowel.
     *
     *  And, if checking "y" in the first position and the word starts with "yy",
     *  return true even though it's not a legitimate word (it crashes otherwise).
     *
     *  @param string $word Word to check
     *  @param integer $pos Position in the string to check
     *  @access public
     *  @return boolean
     */
    public function is_consonant( $word, $pos )
    {
        // Sanity checking $pos
        if ( abs($pos) > strlen($word) ) {
            if ( $pos < 0 ) {
                // Points "too far back" in the string. Set it to beginning.
                $pos = 0;
            } else {
                // Points "too far forward." Set it to end.
                $pos = -1;
            }
        }
        $char = substr($word, $pos, 1);
        switch ( $char ) {
            case 'a':
            case 'e':
            case 'i':
            case 'o':
            case 'u':
                return false;
            case 'y':
                if ( $pos == 0 || strlen($word) == -$pos ) {
                    // Check second letter of word.
                    // If word starts with "yy", return true.
                    if ( substr($word, 1, 1) == 'y' ) {
                        return true;
                    }
                    return !($this->is_consonant($word, 1));
                } else {
                    return !($this->is_consonant($word, $pos - 1));
                }
            default:
                return true;
        }
    }

    /**
     *  Counts (measures) the number of vowel-consonant occurences.
     *
     *  Based on the algorithm; this handy public function counts the number of
     *  occurences of vowels (1 or more) followed by consonants (1 or more),
     *  ignoring any beginning consonants or trailing vowels. A legitimate
     *  VC combination counts as 1 (ie. VCVC = 2, VCVCVC = 3, etc.).
     *
     *  @param string $word Word to measure
     *  @access public
     *  @return integer
     */
    public function count_vc( $word )
    {
        $m = 0;
        $length = strlen($word);
        $prev_c = false;
        for ( $i = 0; $i < $length; $i++ ) {
            $is_c = $this->is_consonant($word, $i);
            if ( $is_c ) {
                if ( $m > 0 && !$prev_c ) {
                    $m += 0.5;
                }
            } else {
                if ( $prev_c || $m == 0 ) {
                    $m += 0.5;
                }
            }
            $prev_c = $is_c;
        }
        $m = floor($m);
        return $m;
    }

    /**
     *  Checks for a specific consonant-vowel-consonant condition.
     *
     *  This public function is named directly from the original algorithm. It
     *  looks the last three characters of the word ending as
     *  consonant-vowel-consonant, with the final consonant NOT being one
     *  of "w", "x" or "y".
     *
     *  @param string $word Word to check
     *  @access private
     *  @return boolean
     */
    public function _o( $word )
    {
        if ( strlen($word) >= 3 ) {
            if ( $this->is_consonant($word, -1) && !$this->is_consonant($word, -2) &&
                 $this->is_consonant($word, -3) ) {
		        $last_char = substr($word, -1);
		        if ( $last_char == 'w' || $last_char == 'x' || $last_char == 'y' ) {
		            return false;
		        }
                return true;
            }
        }
        return false;
    }

    /**
     *  Replaces suffix, if found and word measure is a minimum count.
     *
     *  @param string $word Word to check and modify
     *  @param string $suffix Suffix to look for
     *  @param string $replace Suffix replacement
     *  @param integer $m Word measure value that the word must be greater
     *                    than to replace
     *  @access private
     *  @return boolean
     */
    public function _replace( &$word, $suffix, $replace, $m = 0 )
    {
        $sl = strlen($suffix);
        if ( substr($word, -$sl) == $suffix ) {
            $short = substr_replace($word, '', -$sl);
            if ( $this->count_vc($short) > $m ) {
                $word = $short . $replace;
            }
            // Found this suffix, doesn't matter if replacement succeeded
            return true;
        }
        return false;
    }

}