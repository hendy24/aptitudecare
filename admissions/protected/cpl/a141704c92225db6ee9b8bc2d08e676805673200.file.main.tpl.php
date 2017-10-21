<?php /* Smarty version Smarty-3.1.13, created on 2016-02-08 10:26:30
         compiled from "/home/aptitude/dev/protected/tpl/main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173296793556b8cfc60469c1-04879712%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a141704c92225db6ee9b8bc2d08e676805673200' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/main.tpl',
      1 => 1417455709,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173296793556b8cfc60469c1-04879712',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ENGINE_URL' => 0,
    'SITE_URL' => 0,
    'SITE_EMAIL' => 0,
    'auth' => 0,
    'page' => 0,
    'action' => 0,
    'isPrint' => 0,
    'isTV' => 0,
    'siteCss' => 0,
    'isMicro' => 0,
    'logo' => 0,
    'HOMEHEALTH_URL' => 0,
    'facility' => 0,
    'defaultFacility' => 0,
    'myFacilities' => 0,
    'f' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56b8cfc6110076_51661595',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56b8cfc6110076_51661595')) {function content_56b8cfc6110076_51661595($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['cms_template_dir']->value)."/_head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['cms_template_dir']->value)."/_javascript_auto.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery.hoverintent.r5.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery-validate/additional-methods.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery.alerts-1.1/jquery.alerts.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery.maskedinput-011748c/src/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/jquery-ui-1.8.12.advancedhc/js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/jquery-ui-1.8.12.advancedhc/development-bundle/external/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/jquery-ui-1.8.12.advancedhc/js/jquery.qtip.min.js"></script>
<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/jquery-ui-1.8.12.advancedhc/css/jquery.qtip.min.css" type="text/css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/timeout.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/jquery-ui-1.8.12.advancedhc/css/custom-theme/jquery-ui-1.8.12.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery/jquery.alerts-1.1/jquery.alerts.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/shadowbox-3.0.3/shadowbox.js"></script>
<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/shadowbox-3.0.3/shadowbox.css" type="text/css">
<!-- <script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/modernizr.js"></script>
 -->
 <script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/general.js"></script>

<!-- Highcharts js framework -->
<script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/highcharts/js/highcharts.js"></script>

<!-- Leaflet map framework -->
<script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/leaflet-0.7.2/leaflet.js"></script>
<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
js/leaflet-0.7.2/leaflet.css" />

<script>
	Shadowbox.init({
		height: 425,
		width: 450,
		handleOversize: "resize",
		overlayColor: "#666",
		overlayOpacity: "0.25"
	});
</script>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('javascript', array()); $_block_repeat=true; echo smarty_javascript(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	var SITE_EMAIL = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_EMAIL']->value, ENT_QUOTES, 'UTF-8');?>
';
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_javascript(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->timeout){?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	startTimer();
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?>

<?php if (($_smarty_tpl->tpl_vars['page']->value=="patient"&&$_smarty_tpl->tpl_vars['action']->value=="printInquiry")||($_smarty_tpl->tpl_vars['page']->value=="patient"&&$_smarty_tpl->tpl_vars['action']->value=="printNursing")){?>
</head>
<body>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['content_tpl']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html>
<?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
css/modal.css" media="screen" />
<?php if ($_smarty_tpl->tpl_vars['isPrint']->value!=1){?><link type="text/css" rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
css/styles.css" media="all" /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?><link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
css/tv.css" type="text/css" media="all" /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['siteCss']->value){?><link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
css/site_css/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteCss']->value, ENT_QUOTES, 'UTF-8');?>
" type="text/css" media="all" /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['isPrint']->value==1){?><link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
css/print.css" type="text/css" media="all" /><?php }?>

<body <?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->timeout&&$_smarty_tpl->tpl_vars['isTV']->value==0){?>onmousemove="resetTimer()<?php }?>">
<?php if ($_smarty_tpl->tpl_vars['isMicro']->value==1){?>
	<?php echo $_smarty_tpl->getSubTemplate ("_feedback.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<br />
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['content_tpl']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="header-container">
		<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()){?>
		<?php $_smarty_tpl->tpl_vars['defaultFacility'] = new Smarty_variable($_smarty_tpl->tpl_vars['auth']->value->getRecord()->getDefaultFacility(), null, 0);?>
		<div id="header">
		
			<div id="user-info">
			
				Welcome, <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=userInfo"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['auth']->value->getRecord()->getFullName(), ENT_QUOTES, 'UTF-8');?>
</a><?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?> | <a href="<?php echo htmlspecialchars(setURLVar(currentURL(),'resOverride','desktop'), ENT_QUOTES, 'UTF-8');?>
">Desktop Mode</a><?php }else{ ?> | <a href="<?php echo htmlspecialchars(setURLVar(currentURL(),'resOverride','TV'), ENT_QUOTES, 'UTF-8');?>
">TV Mode</a><?php }?> | <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=login&amp;action=logout">Logout</a>
			
			</div>
			
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
images/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['logo']->value, ENT_QUOTES, 'UTF-8');?>
" alt="AptitudeCare Logo" class="logo" />
						
			<div id="nav">
			
				<ul>
					
						<li><a href="#">Data</a>
							<ul>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=caseManager&amp;action=manage">Case Managers</a></li>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=hospital&amp;action=manage">Healthcare Facilities</a></li>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=pharmacy&amp;action=manage">Pharmacies</a></li>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=physician&amp;action=manage">Physicians/Surgeons</a></li>
								<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->isAdmissionsCoordinator()==1){?>
									<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['HOMEHEALTH_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=users&amp;action=manage">Users</a></li>
								<?php }?>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=patient&amp;action=upload">Upload Patients</a></li>
							</ul>
						</li>	
					<li><a href="#">Facility Info</a>
						<ul>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=facility&amp;action=census&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Census</a></li>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=report&amp;action=index&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Reports</a></li>
						</ul>
					</li>
					<li><a href="#">Discharges</a>
						<ul>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=facility&amp;action=schedule_discharges">Schedule Discharge(s)</a></li>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=facility&amp;action=manage_discharges">Manage Discharges</a></li>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=coord&amp;action=trackHospitalVisits&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Return to Hospital</a></li>
						</ul>
					</li>		
					<li><a href="#">Admissions</a>
						<ul>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=coord&amp;action=admit">New Admit Request</a></li>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=coord&amp;action=pending_admissions&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Pending Admissions</a></li>
							<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->id==8||$_smarty_tpl->tpl_vars['auth']->value->getRecord()->id==9){?>
								<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=coord&amp;action=pending_transfers">Pending Transfers</a></li>
							<?php }?>
				
						</ul>
					</li>	
					<li id="facility-dashboard"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['defaultFacility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['defaultFacility']->value->name, ENT_QUOTES, 'UTF-8');?>
 Dashboard</a>
						<?php if (!empty($_smarty_tpl->tpl_vars['myFacilities']->value)){?>
						<ul id="facility-dashboard-dropdown">
						<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['myFacilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
							<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
 Dashboard</a></li>
						<?php } ?>
						</ul>
						<?php }?>
					</li>

					<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->isAdmissionsCoordinator()==1){?><li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=coord">Home</a></li><?php }?>
				</ul>
			
			</div>
		
		</div>
		
	</div>
	<?php }else{ ?>
		<div id="header">
		
			<div id="user-info">
			
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
?page=login">Login</a>			
			</div>
	
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
images/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['logo']->value, ENT_QUOTES, 'UTF-8');?>
" alt="AptitudeCare Logo" class="logo" />
					
		</div>
		
	</div>
	<?php }?>

	<div id="content">
	
		<div class="right" style="margin-top: -12px;"><?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?><a href="<?php echo htmlspecialchars(setURLVar(currentURL(),'resOverride','desktop'), ENT_QUOTES, 'UTF-8');?>
">Desktop Mode</a><?php }?></div>
	
		<?php echo $_smarty_tpl->getSubTemplate ("_feedback.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		
		<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['content_tpl']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


		<div style="display:  none;">
			<div id="remote-dialog">
				<div id="remote-dialog-frame"></div>
				
			</div>
		</div>
	</div>
	
	<div id="timeout-warning">
	    <p>Your session is about to timeout.  You will be automatically logged out in 1 minute. To remain logged in click the button below.</p>
	</div>


<?php }?>
<JAVASCRIPT_BOTTOM>
</body>
</html>
<?php }?><?php }} ?>