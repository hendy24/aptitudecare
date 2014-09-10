<?php /* Smarty version Smarty-3.1.19, created on 2014-09-09 16:58:26
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/users/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135031659853f41ed6b5a478-93058782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1158e4c02c7c249d3b9201a64f93747d1c71112e' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/users/edit.tpl',
      1 => 1410303505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135031659853f41ed6b5a478-93058782',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f41ed6b5c789_92580150',
  'variables' => 
  array (
    'available_locations' => 0,
    'location' => 0,
    'default_location' => 0,
    'k' => 0,
    'loc' => 0,
    'additional_locations' => 0,
    'groups' => 0,
    'group' => 0,
    'group_id' => 0,
    'clinicianTypes' => 0,
    'type' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f41ed6b5c789_92580150')) {function content_53f41ed6b5c789_92580150($_smarty_tpl) {?><script>
	$(document).ready(function () {
		var $clinician = $("#clinician");
		var $clinicianRow = $("#clinician-type-row");
		var $group = $("#group");

		if ($clinician.val() == '') {
			$clinicianRow.hide();
		} 

		if ($group.val() == 6) {
			$clinicianRow.show();
		}
		
		$("#edit").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				healthcare_facility: "required"
			}
		}); 

		$("#group").change(function() {
			if ($(this).val() == 6) {
				$clinicianRow.show();
			} else {
				$clinicianRow.hide();
			}
		});
	});
</script>


<h1>Edit User</h1>
	
	<?php echo $_smarty_tpl->getSubTemplate ("data/edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


	<tr>
		<td>Default Location:</td>
		<td>
			<select name="default_location" id="user-location">
				<option value="">Select a location...</option>
				<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['available_locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['default_location']->value==$_smarty_tpl->tpl_vars['location']->value->id) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr id="additional-locations">
		<td style="vertical-align:top">Additional Locations:</td>
		<td style="vertical-align: top">
		<?php  $_smarty_tpl->tpl_vars['loc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loc']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['available_locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['count']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['loc']->key => $_smarty_tpl->tpl_vars['loc']->value) {
$_smarty_tpl->tpl_vars['loc']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['loc']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['count']['iteration']++;
?>
			
			<input type="checkbox" name="user_location[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
]" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->id, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['additional_locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
?> <?php if ($_smarty_tpl->tpl_vars['location']->value->id==$_smarty_tpl->tpl_vars['loc']->value->id) {?> checked<?php }?><?php } ?> /> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->name, ENT_QUOTES, 'UTF-8');?>
<br>
			<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['count']['iteration']%8==0) {?>
				</td>
				<td style="vertical-align:top">
			<?php }?>
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td>Group:</td>
		<td>
			<select name="group" id="group">
				<option value="">Select a group role...</option>
				<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['group_id']->value==$_smarty_tpl->tpl_vars['group']->value->id) {?> selected <?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value->description, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
		
	</tr>
	<tr id="clinician-type-row">
		<td>Clinician Type:</td>
		<td>
			<select name="clinician" id="clinician">
				<option value="">Select the clinician type...</option>
				<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinicianTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->id, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->description, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td colspan="4"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form>`<?php }} ?>
