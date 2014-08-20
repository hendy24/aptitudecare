<?php /* Smarty version Smarty-3.1.19, created on 2014-08-18 17:50:22
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/error/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:74641265753d8719d43ffa3-29312277%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd58375b451f3f937ca535b9efc4564592eac164' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/error/index.tpl',
      1 => 1408405821,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '74641265753d8719d43ffa3-29312277',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d8719d45a742_79114670',
  'variables' => 
  array (
    'auth' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d8719d45a742_79114670')) {function content_53d8719d45a742_79114670($_smarty_tpl) {?><h1>Cannot find the page</h1>
<br>
<br>
<p class="text-center">We're sorry!  We are unable to find the page you are looking for.</p>

<?php if ($_smarty_tpl->tpl_vars['auth']->value->is_admin()) {?>
	<?php if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
	<br>
	<br>
	<h2>Error Message:</h2>
	<p class="text-center"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value, ENT_QUOTES, 'UTF-8');?>
</p>
	<?php }?>
<?php }?><?php }} ?>
