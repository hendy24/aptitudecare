<?php

/*
 This is for future safety latch implementation
// obey queueing flags and either send immediately or queue for script pickup for normal operation
define('EMAIL_SAFETY_LATCH_NORMAL', 1);

// this will cause all outgoing emails to be marked as sent without actually sending them. If this flag has been on,
// turning it OFF won't trigger a deluge of emails going out that you thought you'd suppressed.
// this does not affect operation of Email::Deliver() itself, however; emails that were already queued up before you set
// this flag will still go out.
define('EMAIL_SAFETY_LATCH_ELEVATED', 2);


// this does the same thing as EMAIL_SAFETY_LATCH_ELEVATED except that it temporarily turns off email delivery
define('EMAIL_SAFETY_LATCH_HIGH', 3);
*/

class Email extends PHPMailer {

	protected static $overrideAddress = false;
	protected static $defaultFromAddress = false;
	protected static $defaultFromName = false;
	protected static $defaultReplyToAddress = false;
	protected static $defaultReplyToName = false;
	protected static $smtpCredentials = array();

	// if restricted is off, email will be sent to everyone.
	// if it's on, it will only be sent to emails that match the whitelist patterns
	protected static $enableRestricted = false;
	protected static $restrictionWhitelist = array();
	
	/* public static $safetyLatch = EMAIL_SAFETY_LATCH_NORMAL; */
	
	public static $disableQueue = false;
	public static $charset = "UTF-8";
	
	public $queueIsDisabled = null;

	protected $hasHTML = false;
	protected $hasTxt = false;
	protected $toRecipients = array();
	protected $replyTos = array();
	protected $phpMailerDefaultFromValue = '';
	protected $phpMailerDefaultFromNameValue = '';
	

	public static function setRestricted($bool) {
		static::$enableRestricted = $bool;
	}
	
	public static function setWhitelist(array $patterns) {
		static::$restrictionWhitelist = $patterns;
	}

	public static function setOverrideAddress($bool, $address) {
		if ($bool === true) {
			$validate = Validate::is_email($address);
			if (! $validate->success()) {
				throw new Exception($validate->message());
			} else {
				static::$overrideAddress = $address;
			}
		} elseif ($bool ===false) {
			static::$overrideAddress = false;
		}
	}

	public static function setDefaultFrom($address, $name) {
		$validate = Validate::is_email($address);
		if (! $validate->success()) {
			throw new Exception($validate->message());
		} else {
			static::$defaultFromAddress = $address;
			static::$defaultFromName = $name;
		}

	}

	public static function setDefaultReplyTo($address, $name) {
		$validate = Validate::is_email($address);
		if (! $validate->success()) {
			throw new Exception($validate->message());
		} else {
			static::$defaultReplyToAddress = $address;
			static::$defaultReplyToName = $name;
		}

	}
	
	//$secure can be '', 'ssl', or 'tls'
	public static function setupSMTP($host, $port, $auth = true, $secure = '', $username = false, $password = false) {
		static::$smtpCredentials = array(
			"host" => $host,
			"port" => $port,
			"auth" => $auth,
			"username" => $username,
			"password" => $password
		);
		if ($secure != '') {
			static::$smtpCredentials["secure"] = $secure;
		}
	}
	
	public function setQueueDisabled() {
		$this->queueIsDisabled = true;
	}
	
	public function setQueueEnabled() {
		$this->queueIsDisabled = false;
	}
	
	
	public function __construct($name, $pairs = array(), $exceptions = false) {
		// make a copy of PHPMailer parent class' From property.
		// this is used in Send() to see if we need to fall back on a defaultFrom value (if it was supplied)
		$this->phpMailerDefaultFromValue = $this->From;
		$this->phpMailerDefaultFromNameValue = $this->FromName;
		
		if ($name == '') {
			throw new Exception("You did not specify a named mail template.");
		}

		// construct the PHPMailer parent
		parent::__construct($exceptions);
		

		// language and charset		
		if (static::$charset !== false) {
			$this->CharSet = static::$charset;
		}

		$this->SetLanguage('en', ENGINE_PROTECTED_PATH . '/protected/lib/contrib/PHPMailer_v5.1/language');
		
		// queue disabling flag by default is set to whatever the app default is.
		// can be overridden with setQueueDisabled() and setQueueEnabled() after constructor has been called
		$this->queueIsDisabled = static::$disableQueue;
		
		// create the smarty objs
		$smarty_data = smarty()->createData(smarty());
		$smarty_data->assign($pairs);

		// figure out what formats to use
		$path_txt = APP_PROTECTED_PATH . "/tpl_email/{$name}_txt.tpl";
		$path_html = APP_PROTECTED_PATH . "/tpl_email/{$name}_html.tpl";

		// we assume the template files are in the site's tpl_dir. but they might be
		// in the CMSv2 dir. fall back on that.
		if (! (file_exists($path_txt) || file_exists($path_html))) {

			$path_txt = ENGINE_PROTECTED_PATH . "/tpl_email/{$name}_txt.tpl";
			$path_html = ENGINE_PROTECTED_PATH . "/tpl_email/{$name}_html.tpl";

		}

		// That didn't work. Bail out.
		if (! (file_exists($path_txt) || file_exists($path_html))) {
			throw new Exception("Could not find template files for email.");
		}

		if (file_exists($path_txt)) {
			$txt = smarty()->fetch($path_txt, $smarty_data);
			$this->hasTxt = true;
		}

		if (file_exists($path_html)) {
			$html = smarty()->fetch($path_html, $smarty_data);
			$this->hasHTML = true;
		}
		
	
		// Remove JavaScript from HTML
		if ($html != '') {
			$html = strip_only_tags($html, "<script>", true);
		}
		
		if ($this->hasTxt && $this->hasHTML) {
			// build a multipart message
			$this->Body = $html;
			$this->AltBody = $txt;
			$this->isHTML(true);
		} elseif ($this->hasTxt && ! $this->hasHTML) {
			// build a text-only message
			$this->Body = $txt;
			$this->isHTML(false);
		} elseif (! $this->hasTxt && $this->hasHTML) {
			// build an html-only message
			$this->Body = $html;
			$this->isHTML(true);
		}
		
	}
	
	public function getHTML() {
		if ($this->hasTxt && $this->hasHTML) {
			// this is a multipart message
			return $this->Body;
		} elseif ($this->hasTxt && ! $this->hasHTML) {
			// this is a text-only message
			return false;
		} elseif (! $this->hasTxt && $this->hasHTML) {
			// this is an html-only message
			return $this->Body;
		}
	}

	public function getTxt() {
		if ($this->hasTxt && $this->hasHTML) {
			// this is a multipart message
			return $this->AltBody;
		} elseif ($this->hasTxt && ! $this->hasHTML) {
			// this is a text-only message
			return $this->Body;
		} elseif (! $this->hasTxt && $this->hasHTML) {
			// this is an html-only message
			return false;
		}

	}

	public function getText() {
		return $this->getTxt();
	}
	
	public function SendNow() {
		$this->Send();
		return $this->Deliver();
	}

	// Override of AddAddress so we can track recipients on our own; PHPMailer stores
	// these in a $private that we can't access. 
	public function AddAddress($address, $name = '') {
		$address = strtolower($address);
		if (! array_key_exists($address, $this->toRecipients)) {
			$this->toRecipients[$address] = $name;
		}
	}

	// Override of AddReplyTo so we can track ReplyTo's on our own; PHPMailer stores
	// these in a $private that we can't access. 
	public function AddReplyTo($address, $name = '') {
		$address = strtolower($address);
		if (! array_key_exists($address, $this->replyTos)) {
			$this->replyTos[$address] = $name;
		}
	}
	
	public function loadAddresses() {
		// if programmer has specified a site-wide override recipient
		if (static::$overrideAddress !== false) {
			$this->clearAllRecipients();
			$this->toRecipients = array();
			$this->AddAddress(static::$overrideAddress);
		}
		
		// optionally block addresses if they don't match pre-approved patterns
		if (static::$enableRestricted) {
			// cycle through all recipients
			foreach ($this->toRecipients as $_email => $_name) {
				// cycle through the acceptable patterns until one matches
				$matched = false;
				foreach (static::$restrictionWhitelist as $pattern) {
					if (preg_match($pattern, $_email)) {
						$matched = true;
						break;
					}
				}
				// still no match? drop it from the recipients
				if (! $matched) {
					unset($this->toRecipients[$_email]);
				}
			}
		}		
		
		// if programmer hasn't set a 'From' property but HAS provided a site-wide 'from' address
		if ($this->From == $this->phpMailerDefaultFromValue && static::$defaultFromAddress !== false) {
			$this->From = static::$defaultFromAddress;
			if (static::$defaultFromName !== false) {
				$this->FromName = static::$defaultFromName;
			}
		}

		
		// if no replyTo's have been provided but there's a site-wide default, use that.
		if (count($this->replyTos) == 0 && static::$defaultReplyToAddress !== false) {
			parent::AddReplyTo(static::$defaultReplyToAddress, static::$defaultReplyToName);
		} else {
			foreach ($this->replyTos as $address => $name) {
				parent::AddReplyTo($address, $name);
			}
		}
		

		// import authorized addresses to PHPMailer parent class:
		foreach ($this->toRecipients as $_email => $_name) {
			parent::AddAddress($_email, $_name);
		}
				
	}
	
	public function loadSMTP() {
		if (count(static::$smtpCredentials) > 0) {
			$this->IsSMTP(True);
			$this->SMTPSecure = static::$smtpCredentials["secure"];
			$this->SMTPAuth = static::$smtpCredentials["auth"];
			$this->Username = static::$smtpCredentials["username"];
			$this->Password = static::$smtpCredentials["password"];
			$this->Host = static::$smtpCredentials["host"];
			$this->Port = static::$smtpCredentials["port"];
		}		
	}
	
	public function Send() {
		// load recipients
		$this->loadAddresses();
		
		// load SMTP config
		$this->loadSMTP();
		
		
		// build an object so we can store this in the DB
		$email = CMS_Email::generate();

		// UTC timestamp
		$email->datetime_created = datetimeTZ("UTC");
		
		// store comma-separated list of recipient emails
		$email->recipient_email = implode(",", array_keys($this->toRecipients));

		// email is ready -- pack it up and store it.
		$email->obj = armor($this);

		// queue is enabled: mark the email as unsent so that the script will pick it up and deliver OTA. save to DB.
		if ($this->queueIsDisabled == 0) {
			$email->is_sent = 0;
			$email->save();
			unset($email);
		}
		// queue is disabled: mark the email as sent, save to DB, and deliver OTA.
		else {
			$email->is_sent = 1;
			$email->save();
			unset($email);			
			$this->Deliver();
		}
	}
	
	public function Deliver() {
		// load recipients
		$this->loadAddresses();
		
		// load SMTP config
		$this->loadSMTP();
		
		if (count($this->toRecipients) > 0) {
			return parent::Send();			
		} else {
			return true;
		}
	}

	
	public static function sendAll() {
		$obj = CMS_Email::generate();
		$emails = $obj->fetch(array("is_sent" => 0));
		
		$output = "";
		
		foreach ($emails as $e) {
			// if email has no recipients, silently mark it as sent and continue.
			if (trim($e->recipient_email) == '') {
				$e->is_sent = 1;
				$e->save();
			} else {
			// otherwise, unpack and deliver
				$email = unarmor($e->obj);
				if ($email->Deliver()) {
					$output .= "id #$e->id; successfully delivered";
					$e->is_sent = 1;
					$e->save();
				} else {
					$output .= "id #$e->id; delivery failed; Reason: {$email->ErrorInfo}\n";
				}
			}
			unset($e);
			
		}	

		return $output;
		
	}
}