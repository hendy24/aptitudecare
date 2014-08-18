<?php /* Smarty version Smarty-3.1.19, created on 2014-07-28 16:30:26
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/templates/main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:175756275953d6cf0255d8c8-90635366%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '848c556abd628408d7808d615f7037d8d3257f51' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/templates/main.tpl',
      1 => 1406318543,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '175756275953d6cf0255d8c8-90635366',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'frameworkCss' => 0,
    'frameworkJs' => 0,
    'siteUrl' => 0,
    'auth' => 0,
    'frameworkImg' => 0,
    'flashMessages' => 0,
    'class' => 0,
    'message' => 0,
    'm' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d6cf025e8396_05566708',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d6cf025e8396_05566708')) {function content_53d6cf025e8396_05566708($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8');?>
 &nbsp;|&nbsp; AptitudeCare</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkCss']->value, ENT_QUOTES, 'UTF-8');?>
/styles.css">
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.theme.min.css" />

	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/datepicker.js"></script>
	<script>
		var SiteUrl = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
';
	</script>
	   
</head>
<body>
	<div id="header-container">
		<div id="header">
			<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()) {?>
			<div id="user-info">
				Welcome, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['auth']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
 &nbsp;|&nbsp; <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/login/logout">Logout</a>
			</div>
			<?php }?>
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/aptitudecare.png" alt="Logo" class="logo"/>
			<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()) {?>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>
		</div>
	</div>
	<div class="clear"></div>
	<div id="wrapper">
		<div id="content">	
			<?php if ($_smarty_tpl->tpl_vars['flashMessages']->value) {?>
			<div id="flash-messages">
				
				<?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_smarty_tpl->tpl_vars['class'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['flashMessages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value) {
$_smarty_tpl->tpl_vars['message']->_loop = true;
 $_smarty_tpl->tpl_vars['class']->value = $_smarty_tpl->tpl_vars['message']->key;
?>
				<div class="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['class']->value, ENT_QUOTES, 'UTF-8');?>
">
					<?php if ($_smarty_tpl->tpl_vars['class']->value=="error") {?>
					<p>Please fix the following errors and try again:</p>
					<?php }?>
					<ul>
					<?php  $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['m']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['message']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['m']->key => $_smarty_tpl->tpl_vars['m']->value) {
$_smarty_tpl->tpl_vars['m']->_loop = true;
?>
						<li><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['m']->value, ENT_QUOTES, 'UTF-8');?>
</li>
					<?php } ?>
					</ul>
				</div>
				<?php } ?>
			</div>
			<?php }?>
			<div id="page-content">
				<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['content']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			</div>
			
		</div>
		<div id="copyright">
			<p>All content &copy; <?php echo htmlspecialchars(smarty_modifier_date_format(time(),"%Y"), ENT_QUOTES, 'UTF-8');?>
 AptitudeCare.  All rights reserved. Powered by <a href="http://www.aptitudeit.net" target="_blank">aptITude</a></p>
		</div>
	</div>

	
	
</body>
</html><?php }} ?>
