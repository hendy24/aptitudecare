<?php

class PageControllerReportproblem extends PageController {
	public function index() {
		
	} 
	
	
	public function submit() {
		require_once ENGINE_PROTECTED_PATH . "/lib/contrib/activecollab-apiwrapper/lib/include.php";

		  // Authenticate
		  ActiveCollab::setAPIUrl('https://projects.intercarve.net/public/api.php');
		  ActiveCollab::setKey('129-K9ltWRa7ppoy0aDVnx7AeUPg5V2LFGy0YSSNvqHG');

		  
		  $ticket = new ActiveCollabTicket(75);
		  $ticket->setName("this is a test " . rand());
		  $ticket->setBody("testing one two");
		  $ticket->save();
		  
		  exit;
		
	}
	
}