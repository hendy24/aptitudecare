<?php /* Smarty version Smarty-3.1.19, created on 2014-08-20 15:33:54
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:53417039253f5135069c7c3-99846027%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51f08d33bc363c9e23f7dd3814c94ab121bcbcd8' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/edit.tpl',
      1 => 1408570432,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53417039253f5135069c7c3-99846027',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f513506afc30_07876245',
  'variables' => 
  array (
    'facilityTypes' => 0,
    'type' => 0,
    'location_type_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f513506afc30_07876245')) {function content_53f513506afc30_07876245($_smarty_tpl) {?><script>
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


<h1>Edit Healthcare Facility</h1>
	
	<?php echo $_smarty_tpl->getSubTemplate ("data/edit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<tr>
		<td>Location Type:</td>
		<td>
			<select name="location_type" id="location-type">
				<option value="">Select a location type...</option>
				<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilityTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['location_type_id']->value==$_smarty_tpl->tpl_vars['type']->value->id) {?> selected <?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->description, ENT_QUOTES, 'UTF-8');?>
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
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form><?php }} ?>
