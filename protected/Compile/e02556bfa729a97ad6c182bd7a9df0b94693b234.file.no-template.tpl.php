<?php /* Smarty version Smarty-3.1.19, created on 2014-08-19 22:36:38
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/error/no-template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:141757055053f421dcb2e0f6-95223108%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e02556bfa729a97ad6c182bd7a9df0b94693b234' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/error/no-template.tpl',
      1 => 1408509397,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '141757055053f421dcb2e0f6-95223108',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f421dcb41993_73230996',
  'variables' => 
  array (
    'auth' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f421dcb41993_73230996')) {function content_53f421dcb41993_73230996($_smarty_tpl) {?><h1>We're Sorry!</h1>
<p class="text-center">We cannot find the page you are looking for.</p>

<?php if ($_smarty_tpl->tpl_vars['auth']->value->is_admin()) {?>
	<p class="text-center">The template file is missing</p>
<?php }?><?php }} ?>
