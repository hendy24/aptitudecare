<?php /* Smarty version Smarty-3.1.19, created on 2014-08-20 20:53:14
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:145840076853efcc8d6e9d67-91053063%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '27ed9874472784de4a97d4ecdba6b47df44e8b71' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/edit.tpl',
      1 => 1408570156,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '145840076853efcc8d6e9d67-91053063',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53efcc8d6f3093_30207061',
  'variables' => 
  array (
    'healthcare_facility' => 0,
    'healthcare_facility_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53efcc8d6f3093_30207061')) {function content_53efcc8d6f3093_30207061($_smarty_tpl) {?><script>
	$(document).ready(function () {
		$("#edit").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				healthcare_facility: "required"
			}
		}); 
	});
</script>


<h1>Edit Case Manager</h1>
	
	<?php echo $_smarty_tpl->getSubTemplate ("data/edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>



	<tr>
		<td>Healthcare Facility:</td>	
		<td>
			<input type="text" name="healthcare_facility" id="healthcare-facility-search" style="width: 200px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['healthcare_facility']->value, ENT_QUOTES, 'UTF-8');?>
" />
			<input type="hidden" name="healthcare_facility_id" id="healthcare-facility-id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['healthcare_facility_id']->value, ENT_QUOTES, 'UTF-8');?>
" />
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form><?php }} ?>
