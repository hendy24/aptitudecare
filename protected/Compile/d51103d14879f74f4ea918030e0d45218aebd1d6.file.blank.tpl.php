<?php /* Smarty version Smarty-3.1.19, created on 2014-08-21 17:28:31
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/layouts/blank.tpl" */ ?>
<?php /*%%SmartyHeaderCode:120680725153da882e2ebe37-99635657%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd51103d14879f74f4ea918030e0d45218aebd1d6' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/layouts/blank.tpl',
      1 => 1408663707,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '120680725153da882e2ebe37-99635657',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53da882e2f59a8_12189014',
  'variables' => 
  array (
    'title' => 0,
    'frameworkJs' => 0,
    'frameworkCss' => 0,
    'siteUrl' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53da882e2f59a8_12189014')) {function content_53da882e2f59a8_12189014($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8');?>
</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jQuery-Autocomplete-master/content/styles.css" />
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkCss']->value, ENT_QUOTES, 'UTF-8');?>
/styles.css">
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.theme.min.css" />
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/shadowbox-3.0.3/shadowbox.css" />
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkCss']->value, ENT_QUOTES, 'UTF-8');?>
/blank.css" />

	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jQuery-Autocomplete-master/dist/jquery.autocomplete.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/datepicker.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/shadowbox-3.0.3/shadowbox.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/general.js"></script>

	<script>
		var SiteUrl = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
';
	</script>
	
</head>
<body>
	<div id="wrapper">
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['content']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		</div>
		
	</div>
</body>
</html><?php }} ?>
