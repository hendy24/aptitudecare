<?php /* Smarty version Smarty-3.1.13, created on 2014-06-10 12:14:49
         compiled from "/home/aptitude/dev/protected/tpl/siteUser/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:134217927253974b19c43aa6-82077226%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f194a5085d58bde65ba7727ff492f02971f63ad' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/siteUser/edit.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '134217927253974b19c43aa6-82077226',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'user' => 0,
    'facility' => 0,
    'roles' => 0,
    'role' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_53974b19cb51f6_47919869',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53974b19cb51f6_47919869')) {function content_53974b19cb51f6_47919869($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$('.phone').mask('(999) 999-9999');
	
	$('#deleteUser').click(function() {
		if (confirm('Are you sure you want to delete this user?  This cannot be undone.')) {
			window.location = <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
 + '/?page=siteUser&action=delete&user=' + <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->pubid, ENT_QUOTES, 'UTF-8');?>
;
		}
		return false;
	});
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<h1 class="text-center"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</h1>
<h2 class="text-center">Edit User Info for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</h2>

<form name="edit_user" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" method="post" id="edit-user">
	<input type="hidden" name="page" value="siteUser" />
	<input type="hidden" name="action" value="submitEdit" />
	<input type="hidden" name="user" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="facility" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
<table id="edit-data" cellspacing="5" cellpadding="5">
	<tr>
		<td>First Name:</td>
		<td><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->first, ENT_QUOTES, 'UTF-8');?>
" size="30" name="first" /></td>
	</tr>
	<tr>
		<td>Last Name:</td>
		<td><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->last, ENT_QUOTES, 'UTF-8');?>
" size="50" name="last" /></td>
	</tr>
	<tr>
		<td>Username (Email Address):</td>
		<td><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->email, ENT_QUOTES, 'UTF-8');?>
" size="40" name="email" /></td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td><input type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->phone, ENT_QUOTES, 'UTF-8');?>
" size="10" class="phone" name="phone" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input id="is-coordinator" type="checkbox" name="is_coordinator" value="1" <?php if ($_smarty_tpl->tpl_vars['user']->value->is_coordinator){?> checked<?php }?>> Is an Admissions Coordinator</td>
	</tr>
	<tr>
		<td>User Role:</td>
		<td>
			<select name="user_role">
				<option value="">Select a user role...</option>
				<?php  $_smarty_tpl->tpl_vars['role'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['role']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['roles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['role']->key => $_smarty_tpl->tpl_vars['role']->value){
$_smarty_tpl->tpl_vars['role']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['role']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['user']->value->role==$_smarty_tpl->tpl_vars['role']->value->id){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['role']->value->description, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=siteUser&action=reset_password&user=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Reset Password</a></td>
	</tr>
	<tr>
		<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=siteUser&action=delete&user=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" id="deleteUser" class="button">Delete</a></td>
		<td align="right"><input type="submit" value="Save" /></td>
	</tr>
	<tr>	
		<td colspan="2" align="right"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=siteUser&action=manage&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" style="margin-right: 5px;">Cancel</a></td>
	</tr>
</form>
</table><?php }} ?>