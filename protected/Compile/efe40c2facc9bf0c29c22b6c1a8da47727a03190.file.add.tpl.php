<?php /* Smarty version Smarty-3.1.19, created on 2014-08-21 19:59:57
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/physicians/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:131801827153f4351a094cc0-14097732%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efe40c2facc9bf0c29c22b6c1a8da47727a03190' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/physicians/add.tpl',
      1 => 1408672773,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '131801827153f4351a094cc0-14097732',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f4351a096ba5_84552875',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f4351a096ba5_84552875')) {function content_53f4351a096ba5_84552875($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("data/add.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form><?php }} ?>
