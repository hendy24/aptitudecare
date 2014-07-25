<?php /* Smarty version Smarty-3.1.19, created on 2014-07-24 16:46:38
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16147303053cede82cb0ea6-85450213%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '014198e8ee9b3498a15e58bc5e597ca7ae3fbed5' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/main.tpl',
      1 => 1406241997,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16147303053cede82cb0ea6-85450213',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53cede82ccf274_02530305',
  'variables' => 
  array (
    'title' => 0,
    'css' => 0,
    'auth' => 0,
    'siteUrl' => 0,
    'images' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53cede82ccf274_02530305')) {function content_53cede82ccf274_02530305($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8');?>
 &nbsp;|&nbsp; AptitudeCare</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="">
	<meta name="robots" content="">

	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css']->value, ENT_QUOTES, 'UTF-8');?>
/styles.css">
	    
</head>
<body>
	<div id="header-container">
		<div id="header">
			<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()) {?>
			<div id="user-info">
				Welcome, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['auth']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
 &nbsp;|&nbsp; <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/user/logout">Logout</a>
			</div>
			<?php }?>
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['images']->value, ENT_QUOTES, 'UTF-8');?>
/aptitudecare.png" alt="Logo" class="logo"/>
			<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()) {?>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>
		</div>
	</div>
	<div class="clear"></div>
	<div id="wrapper">
		<div id="content">	
				<?php if ($_smarty_tpl->tpl_vars['auth']->value->valid()) {?>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php }?>
			<div id="page-content">
				<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['content']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			</div>
		</div>
	</div>
	<div id="copyright">
		All content &copy; <?php echo htmlspecialchars(smarty_modifier_date_format(time(),"%Y"), ENT_QUOTES, 'UTF-8');?>
 AptitudeCare.  All rights reserved. <br>Powered by <a href="http://www.aptitudeit.net" target="_blank" alt="Application design and development by AptitudeIT, LLC">aptITude</a>
	</div>

	

	
</body>
</html><?php }} ?>
