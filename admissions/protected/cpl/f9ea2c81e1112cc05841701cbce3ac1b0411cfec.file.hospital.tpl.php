<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:15:19
         compiled from "/home/aptitude/dev/protected/tpl/report/admission/hospital.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3846741563ad7d7c3f8c6-28447289%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f9ea2c81e1112cc05841701cbce3ac1b0411cfec' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/report/admission/hospital.tpl',
      1 => 1401406972,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3846741563ad7d7c3f8c6-28447289',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'orderByOpts' => 0,
    'k' => 0,
    'orderby' => 0,
    'v' => 0,
    'summaryReport' => 0,
    'SITE_URL' => 0,
    'type' => 0,
    'dateStart' => 0,
    'dateEnd' => 0,
    'filterby' => 0,
    'r' => 0,
    'countTotalAdmits' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7d7c75a51_23164974',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7d7c75a51_23164974')) {function content_563ad7d7c75a51_23164974($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Hospitals | Admission Report"),$_smarty_tpl);?>

<?php echo $_smarty_tpl->getSubTemplate ("patient/patient_search.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("patient/export_icons.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h1 class="text-center">Admission Report<br /><span class="text-16">for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</span></h1>
<?php echo $_smarty_tpl->getSubTemplate ("report/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	
<!--
<div class="sort-right">
	<strong>Order by:</strong>
	<select id="orderby">
		<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['orderByOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['orderby']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
	</select>
</div>
-->

<?php echo $_smarty_tpl->getSubTemplate ("elements/detail_options.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<div id="normal-view" class="right"><a class="button">Return to Normal View</a></div>
</div>

	
<table id="summary-table" cellpadding="5" cell-spacing="0">
		<tr>
			<th>Hospital Name</th>
			<th>Number of <br />Admissions</th>
			<th>% of <br />Total Admissions</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summaryReport']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
			<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
				<td style="text-align: left;"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=report&action=details&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&type=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8');?>
&start_date=<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['dateStart']->value,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
&end_date=<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['dateEnd']->value,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
&orderby=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['orderby']->value, ENT_QUOTES, 'UTF-8');?>
&filterby=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['filterby']->value, ENT_QUOTES, 'UTF-8');?>
&viewby=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['id'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a></td>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['numberOfAdmits'], ENT_QUOTES, 'UTF-8');?>
</td>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['percentageOfAdmits'], ENT_QUOTES, 'UTF-8');?>
%</td>
			</tr>
		<?php } ?>
		<tr>
			<td><strong>TOTAL ADMISSIONS</strong></td>
			<td><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['countTotalAdmits']->value, ENT_QUOTES, 'UTF-8');?>
</strong></td>
			<td></td>

		</tr>
	</table><?php }} ?>