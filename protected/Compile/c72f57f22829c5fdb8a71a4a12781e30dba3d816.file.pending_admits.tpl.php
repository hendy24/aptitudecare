<?php /* Smarty version Smarty-3.1.19, created on 2014-09-03 21:45:04
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/pending_admits.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11140204253d84843036421-27697142%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c72f57f22829c5fdb8a71a4a12781e30dba3d816' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/pending_admits.tpl',
      1 => 1409802303,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11140204253d84843036421-27697142',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d8484303f927_70751610',
  'variables' => 
  array (
    'admits' => 0,
    'a' => 0,
    'patientTools' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d8484303f927_70751610')) {function content_53d8484303f927_70751610($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<h2>Pending Admissions</h2>

<table class="view">
	<tr>
		<th>Patient Name</th>
		<th></th>
		<th>Admission Date</th>
		<th>Admission Location</th>
		<th>Primary Care Physician</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
	<tr>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patientTools']->value->menu($_smarty_tpl->tpl_vars['a']->value), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars(display_date($_smarty_tpl->tpl_vars['a']->value->datetime_admit), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->location_name, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['a']->value->physician_name)===null||$tmp==='' ? "Not Entered" : $tmp), ENT_QUOTES, 'UTF-8');?>
</td>
	</tr>
	<?php } ?>
</table><?php }} ?>
