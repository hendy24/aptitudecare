<?php /* Smarty version Smarty-3.1.19, created on 2014-09-08 09:21:43
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/login/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:53784962253d2a4f923f6b3-18984160%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb67212a75a7766c55b30f7327b06a1924ff2be3' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/login/index.tpl',
      1 => 1410189699,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53784962253d2a4f923f6b3-18984160',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d2a4f92bb1f6_45574332',
  'variables' => 
  array (
    'siteUrl' => 0,
    'current_url' => 0,
    'site_email' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d2a4f92bb1f6_45574332')) {function content_53d2a4f92bb1f6_45574332($_smarty_tpl) {?>

<div id="login-box">
	<h2>Login</h2>
	<br /><br />
	
	<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/login">
		<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />
		<input type="hidden" name="submit" value="1" />
		<table id="login">
			<tr>
				<td>Username:</td>
				<td><input type="text" name="email" value="" id="login_username" /></td>
			</tr>
			<?php if ($_smarty_tpl->tpl_vars['site_email']->value) {?>
			<tr>
				<td>&nbsp;</td>
				<td style="text-align: right"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_email']->value, ENT_QUOTES, 'UTF-8');?>
</td>
			</tr>
			<?php }?>
			
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" value="" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" value="Login" style="margin-top: 10px;" /></td>
			</tr>

		</table>
	</form>
</div>
<?php }} ?>
