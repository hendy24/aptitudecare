<?php /* Smarty version Smarty-3.1.13, created on 2014-06-10 12:14:01
         compiled from "/home/aptitude/dev/protected/tpl/siteUser/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:120628543353974ae960ba46-42495590%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'debb05f0b3e347fabcac92aa98270539d36d8e36' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/siteUser/add.tpl',
      1 => 1401406972,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '120628543353974ae960ba46-42495590',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'user' => 0,
    'facilities' => 0,
    'f' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_53974ae9649781_88077520',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53974ae9649781_88077520')) {function content_53974ae9649781_88077520($_smarty_tpl) {?><?php echo smarty_set_title(array('title'=>"Add a new User"),$_smarty_tpl);?>

<script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/jquery-validation-1.12.0/dist/jquery.validate.min.js"></script>
<script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/form-validation.js"></script>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$('.phone').mask('(999) 999-9999');
	
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<h1 class="text-center">Add a New User</h1>

<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" method="post" id="newUser">
	<input type="hidden" name="page" value="siteUser" />
	<input type="hidden" name="action" value="submitAddUser" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><label for="first_name">First Name:</label></td>
			<td><input type="text" name="first" id="first_name" size="30" /></td>
		</tr>
		<tr>
			<td><label for="last_name">Last Name:</label></td>
			<td><input type="text" name="last" id="last_name" size="50" /></td>
		</tr>
		<tr>
			<td><label for="username">Username (Email Address):</label></td>
			<td><input type="text" name="email" id="username" size="50" /></td>
		</tr>
		<tr>
			<td><label for="new-password">New Password:</label></td>
			<td><input type="password" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->password, ENT_QUOTES, 'UTF-8');?>
" id="password" name="password" /></td>
		</tr>
		<tr>
			<td><label for="verify-password">Verify Password:</label></td>
			<td><input type="password" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->password, ENT_QUOTES, 'UTF-8');?>
" id="confirm_password-password" name="confirm_password" /></td>
		</tr>
		<tr>
			<td><label for="phone">Phone:</label></td>
			<td><input type="text" name="phone" size="10" id="phone" class="phone" /></td>
		</tr>
		<tr>	
			<td><label for="facility">Facility:</label></td>
			<td>
				<select name="facility" id="facility">
					<option value="">Select a facility...</option>
					<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
						<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input id="is-coordinator" type="checkbox" name="is_coordinator" value="1"> Is an Admissions Coordinator</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Add User" /></td>
		</tr>


	</table>
	
</form><?php }} ?>