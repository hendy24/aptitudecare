<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$title}</title>

	<link rel="stylesheet" href="{$CSS}/custom.css">
	<link rel="stylesheet" href="{$CSS}/pdf_styles.css" type="text/css" media="mpdf" />

	<script src="{$JS}/jquery-3.4.1.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
	<div id="blank-wrapper">
		<div id="content">
			{include file=$content}
		</div>

	</div>
</body>
</html>
