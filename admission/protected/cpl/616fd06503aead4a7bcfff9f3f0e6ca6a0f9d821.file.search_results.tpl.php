<?php /* Smarty version Smarty-3.1.13, created on 2014-07-15 10:39:45
         compiled from "/home/aptitude/dev/protected/tpl/patient/search_results.tpl" */ ?>
<?php /*%%SmartyHeaderCode:199362776653c559510724e9-69199957%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '616fd06503aead4a7bcfff9f3f0e6ca6a0f9d821' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/search_results.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199362776653c559510724e9-69199957',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'searchName' => 0,
    'ENGINE_URL' => 0,
    'results' => 0,
    'result' => 0,
    'r' => 0,
    'schedule' => 0,
    'statusOptions' => 0,
    'option' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_53c55951171614_71209929',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53c55951171614_71209929')) {function content_53c55951171614_71209929($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Search Results"),$_smarty_tpl);?>


<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	var deletePatient = new Array();
	$(".schedule-status").change(function(e) {
		$.getJSON(SITE_URL , { page: "patient", action: "setScheduleStatus", schedule: $(this).attr("rel"), status: $("option:selected", this).val() }, function(json) {
			if (json.status == true) {
				jAlert("The patient's status has been changed.", "Success!", function(r) {
					window.parent.location.href = SITE_URL + "/?page=patient&action=search_results&patient_name=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['searchName']->value, ENT_QUOTES, 'UTF-8');?>
";
				});
			} else {
				var msg = "";
				$.each(json.errors, function(i, v) {
					msg = msg + v + "\n";
				});
				jAlert(msg, "Error");
			}
		}, "json");
	});
	
	
	$(".schedule-datetime").datetimepicker({
		showOn: "button",
		buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
		buttonImageOnly: true,
		timeFormat: "hh:mm tt",
		stepMinute: 15,
		hour: 13,
		onClose: function(dateText, inst) {
			location.href = SITE_URL + '/?page=coord&action=setScheduleDatetimeAdmit&id=' + inst.input.attr("rel") + '&datetime=' + dateText + '&path=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
';
		}
		
	});
	
	$(".discharge-datetime").datetimepicker({
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


<input type="button" value="Return to Previous Page" onclick="history.go(-1)">
<h1 class="text-center">Search Results <br /><span class="text-18">for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['searchName']->value, ENT_QUOTES, 'UTF-8');?>
</span></h1>
	
<table id="report-table" cellpadding="5" cellspacing="0">

	<tr>
		<th>Patient Name</th>
		<th>&nbsp;</th>
		<th>Facility</th>
		<th>Hospital</th>
		<th>Physician Name</th>
		<th>Admission Date</th>
		<th>&nbsp;</th>
		<th>Discharge Date</th>
		<th>&nbsp;</th>
		<th>Schedule Status</th>
	</tr>
	
	<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
		<?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['result']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
			<?php $_smarty_tpl->tpl_vars['schedule'] = new Smarty_variable(CMS_Schedule::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->load($_smarty_tpl->tpl_vars['r']->value->schedule_id), ENT_QUOTES, 'UTF-8');?>

			<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
				<td><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['schedule']->value),$_smarty_tpl);?>
</td>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->facilityName, ENT_QUOTES, 'UTF-8');?>
</td>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->hospitalName, ENT_QUOTES, 'UTF-8');?>
</td>
				<td><?php if ($_smarty_tpl->tpl_vars['r']->value->physicianLast!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->physicianLast, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->physicianFirst, ENT_QUOTES, 'UTF-8');?>
<?php }?></td>
				<td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['r']->value->datetime_admit,"m/d/Y"), ENT_QUOTES, 'UTF-8');?>
</td>
				<td><input type="hidden" name="schedule" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['schedule']->value->datetime_admit), ENT_QUOTES, 'UTF-8');?>
" /></td>
				<td class="discharge-datetime"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['r']->value->datetime_discharge,"m/d/Y H:i a"), ENT_QUOTES, 'UTF-8');?>
</td>
				<td><input type="hidden" name="schedule" class="discharge-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge), ENT_QUOTES, 'UTF-8');?>
" /></td>
				<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->status, ENT_QUOTES, 'UTF-8');?>
</td>
				
					
<!--
					<select class="schedule-status" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value->schedule->pubid, ENT_QUOTES, 'UTF-8');?>
">
						<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['statusOptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
?>
							<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['r']->value->status==$_smarty_tpl->tpl_vars['option']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
</option>
						<?php } ?>
					</select>

				</td> -->
				
			</tr>
		<?php } ?>
	<?php } ?><?php }} ?>