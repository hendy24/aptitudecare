<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $this->title; ?> &nbsp;|&nbsp; <?php echo $this->site_name; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="">
	<meta name="robots" content="">

	<link rel="stylesheet" href="<?php echo CSS; ?>styles.css">
	    
</head>
<body>
	<div id="header-container">
		<div id="header">
			<div id="user-info">
				Welcome, 
			</div>
			<img src="<?php echo IMAGES; ?>aptitudecare.png" alt="Logo" class="logo">
			<nav>
				<ul>
					<li>Home</li>
					<li>Admissions
						<ul>
							<li>New Admit</li>
							<li>Pending Admissions</li>
						</ul>
					</li>
					<li> Discharges
						<ul>
							<li>Schedule Discharges</li>
							<li>Manage Discharges</li>
						</ul>
					</li>
					<li>Data
						<ul>
							<li>Case Managers</li>
							<li>Clinicians</li>
							<li>Healthcare Facilities</li>
							<li>Users</li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>

	<div id="wrapper">
		<div id="content">	
			<?php include($this->content); ?>
		</div>
	</div>
	

	
</body>
</html>