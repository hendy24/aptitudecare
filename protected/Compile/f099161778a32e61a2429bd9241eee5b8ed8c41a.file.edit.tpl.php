<?php /* Smarty version Smarty-3.1.19, created on 2014-08-20 15:46:39
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/physicians/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106430417853f5172011e483-94117466%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f099161778a32e61a2429bd9241eee5b8ed8c41a' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/physicians/edit.tpl',
      1 => 1408571198,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106430417853f5172011e483-94117466',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f517201273d2_99018297',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f517201273d2_99018297')) {function content_53f517201273d2_99018297($_smarty_tpl) {?><script>
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


<h1>Edit Physician</h1>
	
	<?php echo $_smarty_tpl->getSubTemplate ("data/edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form><?php }} ?>
