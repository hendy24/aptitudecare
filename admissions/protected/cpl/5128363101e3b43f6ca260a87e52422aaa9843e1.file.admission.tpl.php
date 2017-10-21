<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:15:16
         compiled from "/home/aptitude/dev/protected/tpl/report/admission.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1712994162563ad7d4ad8e45-21477560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5128363101e3b43f6ca260a87e52422aaa9843e1' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/report/admission.tpl',
      1 => 1400775304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1712994162563ad7d4ad8e45-21477560',
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
    'filterByOpts' => 0,
    'filterby' => 0,
    'admits' => 0,
    'a' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7d4b25504_86722452',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7d4b25504_86722452')) {function content_563ad7d4b25504_86722452($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"AHC Reports"),$_smarty_tpl);?>

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

	<div id="admission-report-details" class="left">
		<strong>View Details for:</strong><br />
		<select id="filterby">
			<option value="">Select an option...</option>
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['filterByOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['filterby']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php } ?>
		</select>
	</div>

	<div class="right-phrase">There were <strong><?php echo htmlspecialchars(count($_smarty_tpl->tpl_vars['admits']->value), ENT_QUOTES, 'UTF-8');?>
</strong> total admissions for the selected time period.</div>

<br />

<table id="report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room #</th>
		<th>Patient Name</th>
		<th>Admit Date</th>
		<th width="150px">Hospital</th>
		<th>Attending Physician</th>
		<th>Specialist/Surgeon</th>
		<th>Case Manager</th>
	</tr>	
				
	<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
	<tr class="text-left" bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->number, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['a']->value->datetime_admit,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->hospital_name, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php if ($_smarty_tpl->tpl_vars['a']->value->physician_last!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->physician_last, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->physician_first, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }else{ ?></td><?php }?></td>
		<td><?php if ($_smarty_tpl->tpl_vars['a']->value->surgeon_last!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->surgeon_last, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->surgeon_first, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }else{ ?></td><?php }?></td>

		<td><?php if ($_smarty_tpl->tpl_vars['a']->value->cm_last!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->cm_last, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->cm_first, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?></td><?php }?></td>
	</tr>
			
	<?php } ?>
</table><?php }} ?>