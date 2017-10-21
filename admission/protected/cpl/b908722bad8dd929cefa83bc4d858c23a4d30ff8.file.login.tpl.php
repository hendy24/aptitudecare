<?php /* Smarty version Smarty-3.1.13, created on 2014-09-12 12:58:11
         compiled from "/home/aptitude/dev/protected/tpl/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12722547315413424397eaf8-55743191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b908722bad8dd929cefa83bc4d858c23a4d30ff8' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/login.tpl',
      1 => 1399595274,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12722547315413424397eaf8-55743191',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'path' => 0,
    'site_email' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54134243992926_22872286',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54134243992926_22872286')) {function content_54134243992926_22872286($_smarty_tpl) {?><?php echo smarty_set_title(array('title'=>"Census Dashboard"),$_smarty_tpl);?>


<div id="login-box">
	<h2>Login</h2>
	<br /><br />

	<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
">
		<input type="hidden" name="page" value="login" />
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="path" value="<?php echo htmlspecialchars(urlencode($_smarty_tpl->tpl_vars['path']->value), ENT_QUOTES, 'UTF-8');?>
" />
		<?php echo smarty_form_history_on(array('name'=>"login"),$_smarty_tpl);?>

		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="email" value="" id="login_username" /></td>
			</tr>
			<?php if ($_smarty_tpl->tpl_vars['site_email']->value){?>
			<tr>
				<td>&nbsp;</td>
				<td style="text-align: right"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_email']->value, ENT_QUOTES, 'UTF-8');?>
</td>
			</tr>
			<?php }?>
			
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" value="Login" style="margin-top: 10px;" /></td>
			</tr>

		</table>
	</form>
</div><?php }} ?>