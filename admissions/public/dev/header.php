<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$("li#facility-dashboard").hover(
		function() {
			$("ul#facility-dashboard-dropdown").fadeIn(500, function() {
				$("ul#facility-dashboard-dropdown").show();	
			});	
		}, function() {
			$("ul#facility-dashboard-dropdown").hide();
		}
	);
});
</script>
</head>

<body>

	<div id="header-container">
	
		<div id="header">
		
			<div id="user-info">
			
				Logged in as Michael Scott | <a href="#">Gmail</a> | <a href="#">Logout</a>
			
			</div>
	
			<img src="images/advanced-health-care.jpg" width="326" height="74" alt="Advanced Health Care" class="logo" />
			
			<div id="nav">
			
				<ul>
					<li id="facility-dashboard"><a href="#">AHC of Scottsdale Dashboard</a>
						<ul id="facility-dashboard-dropdown">
							<li><a href="#">AHC of Glendale Dashboard</a></li>
							<li><a href="#">AHC of Albuquerque Dashboard</a></li>
							<li><a href="#">AHC of Aurora</a></li>
						</ul>
					</li>
					<li><a href="#">Admissions Coordinator Dashboard</a></li>
				</ul>
			
			</div>
		
		</div>
		
	</div>
	
	<div id="content">
