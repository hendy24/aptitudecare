<?php /* Smarty version Smarty-3.1.19, created on 2014-08-21 19:59:52
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:70796179553d83999a2e8d6-78784358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7bd6bedd4b0df92680257cbecb9d9888b7280334' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/add.tpl',
      1 => 1408672761,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70796179553d83999a2e8d6-78784358',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d83999a303d1_35490988',
  'variables' => 
  array (
    'facilityTypes' => 0,
    'type' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d83999a303d1_35490988')) {function content_53d83999a303d1_35490988($_smarty_tpl) {?><script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");
		$('#fax').mask("(999) 999-9999");
		$("#add").validate({
			rules: {
				name: "required",
				city: "required",
				state: "required",
				zip: "required",
				location_type: "required"
			}
		});

	});
	
</script>


	<?php echo $_smarty_tpl->getSubTemplate ("data/add.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
		<td><input class="right" type="submit" value="Save" /></td>
	</tr>
		
	</table>
</form>
<?php }} ?>
