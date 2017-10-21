<?php /* Smarty version Smarty-3.1.13, created on 2016-02-08 10:26:30
         compiled from "/home/aptitude/dev/protected/tpl/facility/census.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2552934156b8cfc6192996-49494227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fcd6a3268e5315f959f1ae6bdfb81bf6890074bd' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/facility/census.tpl',
      1 => 1430492892,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2552934156b8cfc6192996-49494227',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'ENGINE_URL' => 0,
    'datetime' => 0,
    'type' => 0,
    'SITE_URL' => 0,
    'auth' => 0,
    'facilities' => 0,
    'f' => 0,
    'physicians' => 0,
    'k' => 0,
    'physician' => 0,
    'p' => 0,
    'physicianTotal' => 0,
    'avgLength' => 0,
    'adc' => 0,
    'adcGoal' => 0,
    'assignedRooms' => 0,
    'numOfRooms' => 0,
    'rooms' => 0,
    'room' => 0,
    'occupant' => 0,
    'occupantSchedule' => 0,
    'ortho' => 0,
    'roomEmptyDate' => 0,
    'emptyDate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56b8cfc62ed0b6_36798852',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56b8cfc62ed0b6_36798852')) {function content_56b8cfc62ed0b6_36798852($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>((string)$_smarty_tpl->tpl_vars['facility']->value->name)." Census"),$_smarty_tpl);?>

	
<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$("#datetime").datetimepicker({
		showOn: "button",
		buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		onClose: function(dateText, inst) {
			location.href = SITE_URL + '/?page=facility&action=census&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&datetime=' + dateText + '&_path=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
';
		}
		
	});
	
	$("#facility").change(function(e) {
		window.location.href = SITE_URL + '/?page=facility&action=census&facility=' + $("option:selected", this).val() + '&datetime=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['datetime']->value, ENT_QUOTES, 'UTF-8');?>
&type=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8');?>
';
	})
	
	$("#type").change(function(e) {
	    window.location.href = SITE_URL + '/?page=facility&action=census&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&datetime=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['datetime']->value, ENT_QUOTES, 'UTF-8');?>
&type=' + $("option:selected", this).val();
	});
	
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
			$(this).parent().parent().css("background-color", "#FF6A6A");
		},
		onClose: function(dateText, inst) {
			requestData =  { page: "facility", action: "save_discharge", pubid: $(this).attr('rel'), date: dateText };
			$.post(SITE_URL, requestData);
			
				
		}
		
	});
	
	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<?php if ($_smarty_tpl->tpl_vars['facility']->value==''){?>
	<h1 class="text-center">AHC Facilities Census Page</h1>

	<br />
	<br />
<?php }else{ ?>
<div class="right clear"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&action=census&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&export=excel"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_xls.png" style="height: 42px;" /></a></a> <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&action=census&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&export=pdf" type="_blank"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_pdf.png" style="height: 42px;" /></a></a></div>
<h1 class="page-header">Census for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
<br /><span class="text-16">on <?php echo htmlspecialchars(smarty_modifier_date_format(datetimepickerformat($_smarty_tpl->tpl_vars['datetime']->value),"%a, %b %e, %Y at %l:%M %P"), ENT_QUOTES, 'UTF-8');?>
</span> <input type="hidden" id="datetime" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['datetime']->value), ENT_QUOTES, 'UTF-8');?>
" /></h1>

<?php }?>
<?php $_smarty_tpl->tpl_vars['facilities'] = new Smarty_variable($_smarty_tpl->tpl_vars['auth']->value->getRecord()->getFacilities(), null, 0);?>
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
	<div style="float: right; margin-bottom: 20px">
		<strong>Show:</strong>
		<select id="type">
			<option value="all"<?php if ($_smarty_tpl->tpl_vars['type']->value=='all'){?> selected<?php }?>>All Rooms</option>
			<?php if ($_smarty_tpl->tpl_vars['facility']->value->short_term){?>
				<option value="scheduled"<?php if ($_smarty_tpl->tpl_vars['type']->value=='scheduled'){?> selected<?php }?>>Scheduled Rooms</option>
				<option value="empty"<?php if ($_smarty_tpl->tpl_vars['type']->value=='empty'){?> selected<?php }?>>Empty Rooms&nbsp;&nbsp;</option>
			<?php }else{ ?>
				<option value="short_term"<?php if ($_smarty_tpl->tpl_vars['type']->value=='short_term'){?> selected<?php }?>>Short-term patients</option>
				<option value="long_term"<?php if ($_smarty_tpl->tpl_vars['type']->value=='long_term'){?> selected<?php }?>>Long-term patients&nbsp;&nbsp;</option>
			<?php }?>
		</select>
	</div>
</div>

<?php if ($_smarty_tpl->tpl_vars['facility']->value!=''){?>
	<?php if ($_smarty_tpl->tpl_vars['type']->value!='empty'){?>
		<?php if (!empty($_smarty_tpl->tpl_vars['physicians']->value)){?>
			<div id="physican-admits" class="grow">
				<div class="physician-stats inner-grow">
					<h2>Attending Physicians</h2>
					<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['physicians']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
$_smarty_tpl->tpl_vars['p']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['p']->key;
?>
						<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->load($_smarty_tpl->tpl_vars['k']->value), ENT_QUOTES, 'UTF-8');?>

						<?php if ($_smarty_tpl->tpl_vars['physician']->value->id!=''){?>			
							<p><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
: <span class="right"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value, ENT_QUOTES, 'UTF-8');?>
</span></p>
						<?php }?>
					<?php } ?>
					<p align="right">Total: <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physicianTotal']->value, ENT_QUOTES, 'UTF-8');?>
</strong></p>
				</div>
			</div>
		<?php }?>
	<?php }?>
	<div class="census-info success">
		<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['datetime']->value,"%B"), ENT_QUOTES, 'UTF-8');?>
 Avg LoS:&nbsp; <span class="text-16"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['avgLength']->value, ENT_QUOTES, 'UTF-8');?>
</span> days
	</div>
	<div class="census-info <?php if ($_smarty_tpl->tpl_vars['adc']->value>=$_smarty_tpl->tpl_vars['adcGoal']->value){?>success<?php }else{ ?> alert<?php }?>">
		<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['datetime']->value,"%B"), ENT_QUOTES, 'UTF-8');?>
 ADC:&nbsp; <span class="text-16"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['adc']->value, ENT_QUOTES, 'UTF-8');?>
</span>
	</div>
	
	<?php if ($_smarty_tpl->tpl_vars['type']->value=='assigned'||$_smarty_tpl->tpl_vars['type']->value=='all'){?>
		<div class="census-info <?php if ($_smarty_tpl->tpl_vars['assignedRooms']->value==$_smarty_tpl->tpl_vars['numOfRooms']->value){?> success <?php }else{ ?> alert<?php }?>">
			Current Census:&nbsp; <span class="text-16"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['assignedRooms']->value, ENT_QUOTES, 'UTF-8');?>
</span> <span class="text-11">of</span> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['numOfRooms']->value, ENT_QUOTES, 'UTF-8');?>

		</div>
	<?php }?>	
	
	<form name="patient-search" accept="post">
		<div id="facility-search-box">
			<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
		</div>
	</form>


<?php }?>

<table id="census-report-table" cellpadding="5" cellspacing="0">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Date of Birth</th>
		<th>Admission<br />Date</th>
		<th>Scheduled<br />Discharge Date</th>
		<th>&nbsp;</th>
		<th>Attending<br />Physician</th>
		<th>Surgeon/Specialist</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['room'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['room']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['rooms']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['room']->key => $_smarty_tpl->tpl_vars['room']->value){
$_smarty_tpl->tpl_vars['room']->_loop = true;
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
	<tr class="census border-bottom <?php if ($_smarty_tpl->tpl_vars['room']->value->status=='Under Consideration'){?>under-consideration<?php }?>" <?php if ($_smarty_tpl->tpl_vars['room']->value->datetime_discharge_bedhold_end!=''){?> bgcolor="yellow" <?php }elseif($_smarty_tpl->tpl_vars['room']->value->is_complete==0&&$_smarty_tpl->tpl_vars['room']->value->is_complete!=null&&$_smarty_tpl->tpl_vars['room']->value->datetime_discharge<$_smarty_tpl->tpl_vars['datetime']->value){?> bgcolor="#A65878"  <?php }elseif($_smarty_tpl->tpl_vars['room']->value->datetime_discharge!=''){?> bgcolor="#FF6A6A" <?php }elseif($_smarty_tpl->tpl_vars['occupant']->value==false){?> bgcolor="#FFA1A1" <?php }elseif($_smarty_tpl->tpl_vars['room']->value->transfer_request){?> bgcolor="orange"<?php }elseif($_smarty_tpl->tpl_vars['occupantSchedule']->value->long_term){?>bgcolor="#e8e8e8"<?php }else{ ?>bgcolor="#ffffff"" <?php }?>>

		<?php if ($_smarty_tpl->tpl_vars['occupant']->value!=false){?>
			<td class="text-center"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['room']->value->number, ENT_QUOTES, 'UTF-8');?>
</td>
			<td style="text-align: left;"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</td>
			<td style="text-align: left; width: 37px;"><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['occupantSchedule']->value),$_smarty_tpl);?>
</td>
			<td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['occupant']->value->birthday,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
</td>
			<td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_admit,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
</td>
			<td class="discharge-datetime"><?php if ($_smarty_tpl->tpl_vars['room']->value->datetime_discharge_bedhold_end!=''){?>
				Hold until<br /><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_discharge_bedhold_end,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>

				<?php }elseif($_smarty_tpl->tpl_vars['room']->value->datetime_sent!=''&&$_smarty_tpl->tpl_vars['room']->value->is_complete==0&&$_smarty_tpl->tpl_vars['room']->value->datetime_discharge==''){?>
				Sent on:<br />
				<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_sent,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>

				<?php }else{ ?>
				<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['room']->value->datetime_discharge,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>

				<?php }?>
			</td>
			<td><input type="hidden" name="schedule" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupantSchedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['occupantSchedule']->value->datetime_discharge), ENT_QUOTES, 'UTF-8');?>
" /></td>
			<?php if ($_smarty_tpl->tpl_vars['occupant']->value->physician_id!=''){?>
			<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->load($_smarty_tpl->tpl_vars['occupant']->value->physician_id), ENT_QUOTES, 'UTF-8');?>

			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }else{ ?>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->physician_name, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['occupant']->value->ortho_id!=''){?>
			<?php $_smarty_tpl->tpl_vars['ortho'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->load($_smarty_tpl->tpl_vars['occupant']->value->ortho_id), ENT_QUOTES, 'UTF-8');?>

			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }else{ ?>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->surgeon_name, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }?>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['occupant']->value==false){?>
			<?php $_smarty_tpl->tpl_vars['roomEmptyDate'] = new Smarty_variable(CMS_Room::generate(), null, 0);?>
			<?php $_smarty_tpl->tpl_vars['emptyDate'] = new Smarty_variable($_smarty_tpl->tpl_vars['roomEmptyDate']->value->getEmptyRoomDate($_smarty_tpl->tpl_vars['room']->value->id,$_smarty_tpl->tpl_vars['facility']->value->id), null, 0);?>
			<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['emptyDate']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>	
			
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['room']->value->number, ENT_QUOTES, 'UTF-8');?>
</td>
				<td style="text-align: left;" colspan="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			<?php } ?>
		<?php }?>
	</tr>
	<?php } ?>
</table>
<br />
<strong>Color Code Key:</strong><br />
<br />
<div class="color-code background-yellow">Patient has already been discharged, but there is a current Bed Hold</div>
<div class="color-code background-purple">Patient has been sent to the hospital, but not discharged</div>
<div class="color-code background-red">Patient has been scheduled to be discharged.</div>
<div class="color-code background-orange">There has been a request to transfer to a different AHC facility.</div>
<div class="color-code background-blue">Patient has not yet been approved.</div><?php }} ?>