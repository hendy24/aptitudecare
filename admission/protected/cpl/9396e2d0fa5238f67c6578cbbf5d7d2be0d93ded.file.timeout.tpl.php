<?php /* Smarty version Smarty-3.1.13, created on 2014-09-11 21:14:32
         compiled from "/home/aptitude/dev/protected/tpl/login/timeout.tpl" */ ?>
<?php /*%%SmartyHeaderCode:53884232554126518d22711-82230495%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9396e2d0fa5238f67c6578cbbf5d7d2be0d93ded' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/login/timeout.tpl',
      1 => 1402618206,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53884232554126518d22711-82230495',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54126518d28fe6_82858095',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54126518d28fe6_82858095')) {function content_54126518d28fe6_82858095($_smarty_tpl) {?><div id="timeout">
	<h1 class="text-center">Your session has timed out</h1>
	<p>Your session has timed out due to inactivity.  Please click below to login again.</p>
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=login" class="button">Login</a>
</div><?php }} ?>