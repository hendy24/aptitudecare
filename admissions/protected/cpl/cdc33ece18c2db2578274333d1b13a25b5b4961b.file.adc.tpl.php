<?php /* Smarty version Smarty-3.1.13, created on 2014-05-19 15:31:06
         compiled from "/home/aptitude/dev/protected/tpl/report/adc.tpl" */ ?>
<?php /*%%SmartyHeaderCode:170874076537a781acb1493-81618323%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cdc33ece18c2db2578274333d1b13a25b5b4961b' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/report/adc.tpl',
      1 => 1400507853,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170874076537a781acb1493-81618323',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'view' => 0,
    'adc_info' => 0,
    'year' => 0,
    'adc' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_537a781accf659_59909935',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537a781accf659_59909935')) {function content_537a781accf659_59909935($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"ADC Report"),$_smarty_tpl);?>

<?php echo $_smarty_tpl->getSubTemplate ("patient/export_icons.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h1 class="text-center">Average Daily Census Report<br /><span class="text-16">for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</h1>
<?php echo $_smarty_tpl->getSubTemplate ("report/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<table id="report-table" cellpadding="5" cellspacing="0">
	<tr class="report-total">
		<th><?php echo htmlspecialchars(ucfirst($_smarty_tpl->tpl_vars['view']->value), ENT_QUOTES, 'UTF-8');?>
</th>
		<th># of Admissions</th>
		<th># of Discharges</th>
		<th>Average Daily Census</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['adc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['adc_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adc']->key => $_smarty_tpl->tpl_vars['adc']->value){
$_smarty_tpl->tpl_vars['adc']->_loop = true;
?>
	<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
		<td style="text-align: left"><?php if ($_smarty_tpl->tpl_vars['view']->value=="year"){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['year']->value, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['adc']->value->time_period,"%B"), ENT_QUOTES, 'UTF-8');?>
<?php }?></td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['adc']->value->admission_count, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['adc']->value->discharge_count, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['adc']->value->census, ENT_QUOTES, 'UTF-8');?>
</td>
	</tr>
	<?php } ?>
</table><?php }} ?>