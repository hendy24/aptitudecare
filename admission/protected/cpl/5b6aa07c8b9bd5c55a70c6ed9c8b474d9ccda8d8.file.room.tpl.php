<?php /* Smarty version Smarty-3.1.13, created on 2015-09-18 13:06:17
         compiled from "/home/aptitude/dev/protected/tpl/coord/room.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88767604255fc60a955b4d1-68836300%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5b6aa07c8b9bd5c55a70c6ed9c8b474d9ccda8d8' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/coord/room.tpl',
      1 => 1401406972,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88767604255fc60a955b4d1-68836300',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'schedule' => 0,
    'ENGINE_URL' => 0,
    'datetime' => 0,
    'auth' => 0,
    'patient' => 0,
    'SITE_URL' => 0,
    'goToApprove' => 0,
    'rooms' => 0,
    'room' => 0,
    'occupant' => 0,
    'occupantSchedule' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55fc60a95bec88_03882741',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fc60a95bec88_03882741')) {function content_55fc60a95bec88_03882741($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>((string)$_smarty_tpl->tpl_vars['facility']->value->name)." Room Assignment"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


$("#facility").change(function(e) {
	var $id = $("option:selected", this).val();
	location.href = SITE_URL + '/?page=coord&action=room&schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&facility=' + $id;
});

$("#schedule-datetime").datetimepicker({
	showOn: "button",
	buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
	buttonImageOnly: true,
	timeFormat: "hh:mm tt",
	stepMinute: 15,
	hour: 13,
	onClose: function(dateText, inst) {
		location.href = SITE_URL + '/?page=coord&action=room&schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&datetime=' + dateText + '&_path=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
';
	}
	
});

$("#type").change(function(e) {
    window.location.href = SITE_URL + '/?page=coord&action=room&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&datetime=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['datetime']->value, ENT_QUOTES, 'UTF-8');?>
&type=' + $("option:../facility/census.tplselected", this).val();
});

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('javascript', array()); $_block_repeat=true; echo smarty_javascript(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_javascript(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->tpl_vars['patient'] = new Smarty_variable($_smarty_tpl->tpl_vars['schedule']->value->getPatient(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['facilities'] = new Smarty_variable($_smarty_tpl->tpl_vars['auth']->value->getRecord()->getFacilities(), null, 0);?> 



<h1 class="text-center">Select a Room for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</h1>
<h2 class="text-center">at <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</h2>


<!-- section commented out below was removed by bjc -->


<br />
<br />
<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="page" value="coord" />
	<input type="hidden" name="action" value="setScheduleFacilityAndRoom" />
	<input type="hidden" name="schedule" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="_path" value="<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="goToApprove" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['goToApprove']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="facility" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	
	<table id="room-table" cellpadding="5" cellspacing="0">
		<tr>	
			<td colspan="4"><strong><?php if ($_smarty_tpl->tpl_vars['goToApprove']->value==1){?>Admission<?php }else{ ?> Room Assignment<?php }?> Date &amp; Time</strong> <input type="text" id="schedule-datetime" name="datetime_admit" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['datetime']->value,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>Room</th>
			<th>Patient Name</th>
			<th>Admission Date</th>
			<th>Scheduled Discharge Date</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['room'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['room']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['rooms']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['room']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['room']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['room']->key => $_smarty_tpl->tpl_vars['room']->value){
$_smarty_tpl->tpl_vars['room']->_loop = true;
 $_smarty_tpl->tpl_vars['room']->iteration++;
 $_smarty_tpl->tpl_vars['room']->last = $_smarty_tpl->tpl_vars['room']->iteration === $_smarty_tpl->tpl_vars['room']->total;
?>
			<?php if ($_smarty_tpl->tpl_vars['room']->value->patient_admit_pubid!=''){?>
				<?php $_smarty_tpl->tpl_vars['occupant'] = new Smarty_variable(CMS_Patient_Admit::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->load($_smarty_tpl->tpl_vars['room']->value->patient_admit_pubid), ENT_QUOTES, 'UTF-8');?>

				<?php $_smarty_tpl->tpl_vars['occupantSchedule'] = new Smarty_variable(CMS_Schedule::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupantSchedule']->value->load($_smarty_tpl->tpl_vars['room']->value->schedule_pubid), ENT_QUOTES, 'UTF-8');?>

			<?php }else{ ?>
				<?php $_smarty_tpl->tpl_vars['occupant'] = new Smarty_variable(false, null, 0);?>
			<?php }?>

		<!--table to display all current patients -->
		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">

			<td>
				<input type="radio" name="room" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['room']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['occupant']->value!=false&&$_smarty_tpl->tpl_vars['occupant']->value->id==$_smarty_tpl->tpl_vars['patient']->value->id){?> checked<?php }?> />
				<input type="hidden" name="previous_room" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->room, ENT_QUOTES, 'UTF-8');?>
" />
			</td>		
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['room']->value->number, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php if ($_smarty_tpl->tpl_vars['occupant']->value!=false){?>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</td>
				<td class="text-center"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_admit,"%b %e %Y"), ENT_QUOTES, 'UTF-8');?>
</td>
				<td class="text-center"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_discharge,"%b %e %Y"), ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }else{ ?>
				<td></td>
				<td></td>
				<td></td>	
			<?php }?>
		</tr>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" align="right"><input type="submit" value="Submit" /></td>
		</tr>
	</table>
	
</form>














<?php }} ?>