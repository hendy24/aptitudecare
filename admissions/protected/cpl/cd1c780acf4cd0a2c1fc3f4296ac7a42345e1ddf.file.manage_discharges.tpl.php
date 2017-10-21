<?php /* Smarty version Smarty-3.1.13, created on 2015-09-22 13:13:38
         compiled from "/home/aptitude/dev/protected/tpl/facility/manage_discharges.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12549162565601a86273de10-57507487%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd1c780acf4cd0a2c1fc3f4296ac7a42345e1ddf' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/facility/manage_discharges.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12549162565601a86273de10-57507487',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ENGINE_URL' => 0,
    'facilities' => 0,
    'f' => 0,
    'facility' => 0,
    'discharges' => 0,
    'd' => 0,
    'schedule' => 0,
    'SITE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5601a862775d86_11092022',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5601a862775d86_11092022')) {function content_5601a862775d86_11092022($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Manage Discharges"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=facility&action=manage_discharges&facility=' + $("option:selected", this).val();
})

$(".schedule-datetime").datetimepicker({
	showOn: "button",
	buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 11,
	onSelect: function(dateText, inst) {
		$(this).parent().parent().find(".discharge-datetime").html(dateText);
	},
	onClose: function(dateText, inst) {
		requestData =  { page: "facility", action: "save_discharge", pubid: $(this).attr('rel'), date: dateText };
		$.post(SITE_URL, requestData);
		
			
	}
	
});

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<h1 class="text-center">Manage Discharges</h1>

<div id="census-options">
	<select id="facility">
		<option value="">Please Select a facility&nbsp;&nbsp;</option>
		<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['f']->value->id==$_smarty_tpl->tpl_vars['facility']->value->id){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
	</select>
</div>

<table id="census-report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Admission Date</th>
		<th width="125px">Discharge Date</th>
		<th width="75px">&nbsp;</th>
		<th width="80px">&nbsp;</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
		<?php $_smarty_tpl->tpl_vars['schedule'] = new Smarty_variable(CMS_Schedule::generate(), null, 0);?>
		<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->load($_smarty_tpl->tpl_vars['d']->value->schedule_id), ENT_QUOTES, 'UTF-8');?>

		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->number, ENT_QUOTES, 'UTF-8');?>
</td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
			<td style="text-align: left;"><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['schedule']->value),$_smarty_tpl);?>
</td>
			<td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['d']->value->datetime_admit,"m/d/Y"), ENT_QUOTES, 'UTF-8');?>
</td>
			<td class="discharge-datetime"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['d']->value->datetime_discharge,"m/d/Y H:i a"), ENT_QUOTES, 'UTF-8');?>
</td>
			<td><input type="hidden" name="schedule" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge), ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=discharge_details&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" class="button">Edit Details</a></td>
		</tr>
		
	<?php } ?>
</div><?php }} ?>