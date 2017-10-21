<?php

class TestSuite_ORMLinking {

	public static function exec() {

		db()->rawQuery("drop table if exists modeltest1");
		db()->rawQuery("drop table if exists modeltest2");
		db()->rawQuery("drop table if exists modeltest3");
		db()->rawQuery("drop table if exists x_modeltest2_link_modeltest3");
		db()->rawQueryMulti("CREATE TABLE `modeltest1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pubid` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `modeltest2` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pubid` (`pubid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
		db()->rawQueryMulti("CREATE TABLE `modeltest2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pubid` varchar(20) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pubid` (`pubid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
		db()->rawQueryMulti("CREATE TABLE `modeltest3` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pubid` varchar(20) DEFAULT NULL,
  `name` text NOT NULL,
  `modeltest1` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pubid` (`pubid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
		db()->rawQueryMulti("CREATE TABLE `x_modeltest2_link_modeltest3` (
  `modeltest2` int(10) unsigned NOT NULL,
  `modeltest3` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		
		CMS_Table::importTableColumns(true);
		
		// start fresh
		db()->query("delete from modeltest1", array());
		db()->query("delete from modeltest2", array());
		db()->query("delete from modeltest3", array());
		
		//
		// create records
		//
		$objs = array();
		$alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		for ($i=1;$i<4;$i++) {
			for ($j=1;$j<10;$j++) {
				
				
				$cls = "CMS_Modeltest{$i}";
				$letter = $alphabet[$j-1];
				$objname = "mt{$i}{$letter}";
				${$objname} = new $cls;
				${$objname}->name = "Record #{$j}";
				${$objname}->save();
				
				$objs[] = ${$objname};
			}
		}
		

		
		///////////////////// begin tests ////////////////////
		
		
		//
		// test direct call of Validate::is_pubid()
		//
		
		echo "Testing direct calls of Validate::is_pubid()<br />";
		foreach ($objs as $obj) {
			echo "Testing " . get_class($obj) . ":";
			$result = Validate::is_pubid($obj->pubid);
			echo "{$obj->pubid} " . $result->message() . "<br />";
			echo '(note): failure is expected here if ' . get_class($obj) . "::\$pubid_length != 10";
			echo "<hr noshade />";
		}
		
		// 
		// test CMS_Table::is_pubid() call of Validate::is_pubid()
		//
		echo "Testing nested calls of Validate::is_pubid(), via models<br />";
		foreach ($objs as $obj) {
			$cls = get_class($obj);
			echo "Testing {$cls}...<br />";
			$result = $cls::is_pubid($obj->pubid);
			if ($result) {
				echo "{$obj->pubid} is a valid pubid<br />";
			} else {
				echo "{$obj->pubid} is not a valid pubid<br />";
			}
			echo "<hr noshade />";
		}
		
		
//modeltest1
//  outbound: modeltest2     (modeltest1.modeltest2 exists)
//  inbound: modeltest3      (modeltest3.modeltest1 exists)


//modeltest2
//  inbound: modeltest1      (modeltest1.modeltest2 exists)
//  linking: modeltest3      (x_modeltest2_link_modeltest3 exists)

//modeltest3
//  outbound: modeltest1    (modeltest3.modeltest1 exists)
//  linking: modeltest3      (x_modeltest2_link_modeltest3 exists)		 
	
		
		
		//
		// test outbound relationships with ids
		//
		echo "Testing outbound with natural id:<br />";
		$mt1a->modeltest2 = $mt2a->id;
		$mt1a->save();
		vd($mt1a);
		echo "In the above record, is modeltest2={$mt2a->id} ?<br />";
		echo "<hr noshade />";
		
		
		//
		// test outbound relationships with pubids
		//
		echo "Testing outbound with pubid:<br />";
		$mt1b->modeltest2 = $mt2b->pubid;
		$mt1b->save();
		vd($mt1b);
		echo "In the above record, is modeltest2={$mt2b->id} ?<br />";
		echo "<hr noshade />";

		//
		// test outbound relationships with objects
		//
		echo "Testing outbound with object:<br />";
		$mt1e->modeltest2 = $mt2e;
		$mt1e->save();
		vd($mt1e);
		echo "In the above record, is modeltest2={$mt2e->id} ?<br />";
		echo "<hr noshade />";

		
		//
		// test inbound relationships with ids
		//
		echo "Testing inbound with natural id:<br />";
		$mt1c->modeltest3 = array($mt3c->id);
		$mt1c->save();
		$mt3c->load($mt3c->pk());
		vd($mt3c);
		echo "In the above record, is modeltest1={$mt1c->id} ?<br />";
		echo "<hr noshade />";
		
		
		//
		// test inbound relationships with pubids
		//
		echo "Testing inbound with pubid:<br />";
		$mt1d->modeltest3 = array($mt3d->id);
		$mt1d->save();
		$mt3d->load($mt3d->pk());
		vd($mt3d);
		echo "In the above record, is modeltest1={$mt1d->id} ?<br />";
		echo "<hr noshade />";
		
		//
		// test inbound relationships with objects
		//
		echo "Testing inbound with object:<br />";
		$mt1f->modeltest3 = array($mt3f);
		$mt1f->save();
		$mt3f->load($mt3f->pk());
		vd($mt3f);
		echo "In the above record, is modeltest1={$mt1f->id} ?<br />";
		echo "<hr noshade />";


		//
		// test linking relationships with ids
		//
		echo "Testing linking with natural id:<br />";
		$mt2g->modeltest3 = array($mt3g->id);
		$mt2g->save();
		
		$records = db()->getRowsCustom("select * from x_modeltest2_link_modeltest3 where modeltest2={$mt2g->id}", array());
		vd($records);
		
		echo "The above rows should show modeltest2={$mt2g->id},modeltest3={$mt3g->id}<br />";
		echo "<hr noshade />";
		
		
		//
		// test linking relationships with pubids
		//
		echo "Testing linking with pubid:<br />";
		$mt2h->modeltest3 = array($mt3h->pubid);
		$mt2h->save();
		
		$records = db()->getRowsCustom("select * from x_modeltest2_link_modeltest3 where modeltest2={$mt2h->id}", array());
		vd($records);
		
		echo "The above rows should show modeltest2={$mt2h->id},modeltest3={$mt3h->id}<br />";
		echo "<hr noshade />";
		
		//
		// test linking relationships with objects
		//
		echo "Testing linking with objects:<br />";
		$mt2i->modeltest3 = array($mt3i);
		$mt2i->save();
		
		$records = db()->getRowsCustom("select * from x_modeltest2_link_modeltest3 where modeltest2={$mt2i->id}", array());
		vd($records);
		
		echo "The above rows should show modeltest2={$mt2i->id},modeltest3={$mt3i->id}<br />";
		echo "<hr noshade />";

		exit;
		
	}
}