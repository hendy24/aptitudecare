<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:15:19
         compiled from "/home/aptitude/dev/protected/tpl/patient/export_icons.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1453241744563ad7d7c7d135-93291897%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0e356af108c659311dd684aac37aad2a7f91050' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/export_icons.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1453241744563ad7d7c7d135-93291897',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'urlString' => 0,
    'SITE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7d7c83ef6_38231212',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7d7c83ef6_38231212')) {function content_563ad7d7c83ef6_38231212($_smarty_tpl) {?><div id="export-icons">

	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urlString']->value, ENT_QUOTES, 'UTF-8');?>
&export=excel">
		<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_xls.png" />
	</a>
	
<!--
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urlString']->value, ENT_QUOTES, 'UTF-8');?>
&export=pdf" target="_blank">
		<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_pdf.png" />
	</a>
-->
	
</div><?php }} ?>