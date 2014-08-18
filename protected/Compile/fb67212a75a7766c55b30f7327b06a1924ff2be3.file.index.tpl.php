<?php /* Smarty version Smarty-3.1.19, created on 2014-07-25 12:42:01
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/login/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:53784962253d2a4f923f6b3-18984160%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb67212a75a7766c55b30f7327b06a1924ff2be3' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/login/index.tpl',
      1 => 1406265440,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53784962253d2a4f923f6b3-18984160',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'flashMessages' => 0,
    'class' => 0,
    'message' => 0,
    'm' => 0,
    'siteUrl' => 0,
    'current_url' => 0,
    'site_email' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d2a4f92bb1f6_45574332',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d2a4f92bb1f6_45574332')) {function content_53d2a4f92bb1f6_45574332($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['flashMessages']->value) {?>
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


<div id="login-box">
	<h2>Login</h2>
	<br /><br />
	
	<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/login">
		<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />
		<input type="hidden" name="submit" value="1" />
		<table>
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
