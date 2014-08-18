<?php /* Smarty version Smarty-3.1.19, created on 2014-08-15 23:36:26
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/layouts/blank.tpl" */ ?>
<?php /*%%SmartyHeaderCode:120680725153da882e2ebe37-99635657%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd51103d14879f74f4ea918030e0d45218aebd1d6' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/layouts/blank.tpl',
      1 => 1408167338,
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
    'frameworkCss' => 0,
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
	<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkCss']->value, ENT_QUOTES, 'UTF-8');?>
/blank.css" />
</head>
<body>
	<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['content']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html><?php }} ?>
