<?php /* Smarty version Smarty-3.1.19, created on 2014-08-21 19:59:44
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:44147170853f4c72f5c7450-42360821%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09efd9949ae0869cc0fab05a8cd1399851253e72' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/add.tpl',
      1 => 1408672749,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '44147170853f4c72f5c7450-42360821',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f4c72f5d1fc5_58955257',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f4c72f5d1fc5_58955257')) {function content_53f4c72f5d1fc5_58955257($_smarty_tpl) {?>	<?php echo $_smarty_tpl->getSubTemplate ("data/add.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


	<tr>
		<td>Healthcare Facility:</td>	
		<td>
			<input type="text" name="healthcare_facility" id="healthcare-facility-search" style="width: 250px" />
			<input type="hidden" name="healthcare_facility_id" id="healthcare-facility-id" value="" />
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
</form><?php }} ?>
