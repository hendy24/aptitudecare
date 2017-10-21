<?php /* Smarty version Smarty-3.1.13, created on 2016-02-08 10:26:20
         compiled from "/home/aptitude/dev/protected/tpl/coord/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106053059956b8cfbc219ed7-53385538%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9119c132c4f03e42aa9f9aff45c785befaf72ce1' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/coord/index.tpl',
      1 => 1439343903,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106053059956b8cfbc219ed7-53385538',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'ENGINE_URL' => 0,
    'CURRENT_URL' => 0,
    'auth' => 0,
    'week' => 0,
    'facility' => 0,
    'prevWeekSeed' => 0,
    'nextWeekSeed' => 0,
    'facilities' => 0,
    'emptyRoomCountByFacility' => 0,
    'day' => 0,
    'completedAdmitsByFacilityAndDate' => 0,
    'pendingAdmitsByFacilityAndDate' => 0,
    'dischargesByFacilityAndDate' => 0,
    'sentToHospitalByFacilityAndDate' => 0,
    'admits' => 0,
    'admit' => 0,
    'onsiteVisit' => 0,
    'transferFacility' => 0,
    'admitFrom' => 0,
    'onsite' => 0,
    'o' => 0,
    'PUBLIC_URL' => 0,
    'pendings' => 0,
    'pending' => 0,
    'pendingTransferFacility' => 0,
    'pendingHospital' => 0,
    'physician' => 0,
    'sents' => 0,
    'sent' => 0,
    'hospital' => 0,
    'discharges' => 0,
    'discharge' => 0,
    'dPhysician' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56b8cfbc3785a1_04704087',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56b8cfbc3785a1_04704087')) {function content_56b8cfbc3785a1_04704087($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Coordinator Dashboard"),$_smarty_tpl);?>


<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/draganddrop.js"></script>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


	$(".alt-week").datepicker({
		showOn: "button",
		buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
		buttonImageOnly: true,
		onSelect: function(dateText, inst) { 
			var tab = $(this).attr("rel");
			var href = '<?php echo htmlspecialchars(setURLVar($_smarty_tpl->tpl_vars['CURRENT_URL']->value,'weekSeed',''), ENT_QUOTES, 'UTF-8');?>
&weekSeed=' + dateText + tab;
			location.href = href;
		}
		
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


	$("#module").change(function(e) {
		e.preventDefault();
		var pathArray = window.location.href.split('/');
		var protocol = pathArray[0];
		var host = pathArray[2];
		var redirectUrl = protocol + '//' + host;

		window.location.href = redirectUrl + "/?page=login&action=admission_login&username=" + $("#username").val() + "&id=" + $("#user-id").val();

	});


	$("#coordinator-dashboard-tabs").tabs({
		cookie: { expires: 30 }
	});
	
	$(".pending-facility").change(function(e) {
		$.getJSON(SITE_URL , { page: 'coord', action: 'setScheduleFacility', schedule: $(this).attr("rel"), facility: $("option:selected", this).val() }, function(json) {
			//
		});
	});
	
	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->module_access){?>
<div id="change-module">
	Module:
	<select name="module" id="module">
		<option value="admission">Admission</option>
		<option value="home_health">Home Health</option>
	</select>
	<input type="hidden" id="username" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['auth']->value->getRecord()->email, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="user-id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['auth']->value->getRecord()->pubid, ENT_QUOTES, 'UTF-8');?>
" />
</div>
<?php }?>

<div id="facility-admit-section">
	<h1 class="text-center"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[0],"%B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
 <span class="text-16">to</span> <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[6],"%B %d, %Y"), ENT_QUOTES, 'UTF-8');?>

	<input type="hidden" class="alt-week" rel="#tab-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" /></h1>
</div>
<div id="week-nav"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=coord&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['prevWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
#facility-admit-section"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/prev-icon.png" /> Previous Week</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=coord&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nextWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
#facility-admit-section">Next Week <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/next-icon.png" /></a></div>

<form name="patient_search" accept="post">
<div id="search-box">
	<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
</div>
</form>

<?php $_smarty_tpl->tpl_vars['facilities'] = new Smarty_variable($_smarty_tpl->tpl_vars['auth']->value->getRecord()->related("facility"), null, 0);?>
<?php $_smarty_tpl->tpl_vars['allFacilities'] = new Smarty_variable(CMS_Facility::generate()->fetch(), null, 0);?>

<div id="action-menu">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=census&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" class="button">Census</a>
</div>

<br />

<!-- Look for duplicates button -->
<!-- <a class="button" style="float: right; margin-right: 10px" href="/?page=coord&action=duplicateEntries&facility=">Duplicate entries</a>
 -->
<br />
<br />
<br />
<div id="coordinator-dashboard-tabs">
	<ul>
	<?php  $_smarty_tpl->tpl_vars['facility'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['facility']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['facility']->key => $_smarty_tpl->tpl_vars['facility']->value){
$_smarty_tpl->tpl_vars['facility']->_loop = true;
?>
	<li><a href="#tab-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</a></li>
	<?php } ?>
	</ul>
<?php  $_smarty_tpl->tpl_vars['facility'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['facility']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['facility']->key => $_smarty_tpl->tpl_vars['facility']->value){
$_smarty_tpl->tpl_vars['facility']->_loop = true;
?>
	<div id="tab-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" class="dashboard-week">
		<div class="dashboard-tab-facility-title right">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
">Go to Facility Dashboard &raquo;</a><br />
			<br />
		</div>
		<div class="dashboard-tab-facility-date-range">
			<h1><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
</h1>
			<br />
			<i>Today is <?php echo htmlspecialchars(smarty_modifier_date_format(time()), ENT_QUOTES, 'UTF-8');?>
. There are currently <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&action=census&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&datetime=<?php echo htmlspecialchars(smarty_modifier_date_format(time(),"%Y-%m-%d %H:%M:%S"), ENT_QUOTES, 'UTF-8');?>
&type=empty"><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['emptyRoomCountByFacility']->value[$_smarty_tpl->tpl_vars['facility']->value->id], ENT_QUOTES, 'UTF-8');?>
</strong> empty rooms</a> at this facility.</i>
		</div>
		<div class="clear" style="height: 0px;"></div>
		<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
?>
		<?php $_smarty_tpl->tpl_vars['admits'] = new Smarty_variable($_smarty_tpl->tpl_vars['completedAdmitsByFacilityAndDate']->value[$_smarty_tpl->tpl_vars['facility']->value->id][$_smarty_tpl->tpl_vars['day']->value], null, 0);?>	
		<?php $_smarty_tpl->tpl_vars['pendings'] = new Smarty_variable($_smarty_tpl->tpl_vars['pendingAdmitsByFacilityAndDate']->value[$_smarty_tpl->tpl_vars['facility']->value->id][$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
		<?php $_smarty_tpl->tpl_vars['discharges'] = new Smarty_variable($_smarty_tpl->tpl_vars['dischargesByFacilityAndDate']->value[$_smarty_tpl->tpl_vars['facility']->value->id][$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
		<?php $_smarty_tpl->tpl_vars['sents'] = new Smarty_variable($_smarty_tpl->tpl_vars['sentToHospitalByFacilityAndDate']->value[$_smarty_tpl->tpl_vars['facility']->value->id][$_smarty_tpl->tpl_vars['day']->value], null, 0);?>	

		<div class="coordinator-day-box">
		
			<h3><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%A, %B %e"), ENT_QUOTES, 'UTF-8');?>
</h3>
		
			<div class="admits">
			
				<strong>Admit</strong><br />
				
				<div class="clear"></div>
			
				<div class="admits-confirmed">
				
					Confirmed<br />
					<br />
				
					<?php  $_smarty_tpl->tpl_vars['admit'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admit']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admit']->key => $_smarty_tpl->tpl_vars['admit']->value){
$_smarty_tpl->tpl_vars['admit']->_loop = true;
?>
					<?php $_smarty_tpl->tpl_vars['onsiteVisit'] = new Smarty_variable(CMS_Onsite_Visit::generate(), null, 0);?>
					<?php $_smarty_tpl->tpl_vars['onsite'] = new Smarty_variable($_smarty_tpl->tpl_vars['onsiteVisit']->value->fetchVisitInfo($_smarty_tpl->tpl_vars['admit']->value->id), null, 0);?>
					<div class="patient-box admit-confirmed">
						<?php if ($_smarty_tpl->tpl_vars['admit']->value->getPatient->transfer_facility!=''){?>
							<?php $_smarty_tpl->tpl_vars['transferFacility'] = new Smarty_variable(CMS_Facility::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['transferFacility']->value->load($_smarty_tpl->tpl_vars['admit']->value->getPatient()->transfer_facility), ENT_QUOTES, 'UTF-8');?>

						<?php }else{ ?>
							<?php $_smarty_tpl->tpl_vars['admitFrom'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admitFrom']->value->load($_smarty_tpl->tpl_vars['admit']->value->getPatient()->admit_from), ENT_QUOTES, 'UTF-8');?>

						<?php }?>
						<?php echo conflictAlert(array('schedule'=>$_smarty_tpl->tpl_vars['admit']->value),$_smarty_tpl);?>

						<span class="admit-name">Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
<br />
							<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
								<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PUBLIC_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
							<?php } ?>
							<?php if ($_smarty_tpl->tpl_vars['admit']->value->confirmed==1){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PUBLIC_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a><?php }?>
							<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Admit From: <?php if ($_smarty_tpl->tpl_vars['admit']->value->transfer_facility!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['transferFacility']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->getPatient()->admit_from!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admitFrom']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->getPatient()->hospitalName()!=''){?><?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['admit']->value->getPatient()->hospitalName())===null||$tmp==='' ? "Unknown" : $tmp), ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getPatient()->referral_org_name, ENT_QUOTES, 'UTF-8');?>
<?php }?><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['admit']->value),$_smarty_tpl);?>

						</span>
						<!-- <input type="hidden" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" /> -->
					
					</div>
					<?php } ?>
					&nbsp;
				</div>
				<div class="admits-pending">
				
					Pending<br />
					<br />
				
					<?php  $_smarty_tpl->tpl_vars['pending'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pending']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pendings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pending']->key => $_smarty_tpl->tpl_vars['pending']->value){
$_smarty_tpl->tpl_vars['pending']->_loop = true;
?>
					<?php $_smarty_tpl->tpl_vars['onsiteVisit'] = new Smarty_variable(CMS_Onsite_Visit::generate(), null, 0);?>
					<?php $_smarty_tpl->tpl_vars['onsite'] = new Smarty_variable($_smarty_tpl->tpl_vars['onsiteVisit']->value->fetchVisitInfo($_smarty_tpl->tpl_vars['pending']->value->id), null, 0);?>
					<?php if ($_smarty_tpl->tpl_vars['pending']->value->transfer_facility!=''){?>
						<?php $_smarty_tpl->tpl_vars['pendingTransferFacility'] = new Smarty_variable(CMS_Facility::generate(), null, 0);?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pendingTransferFacility']->value->load($_smarty_tpl->tpl_vars['pending']->value->transfer_facility), ENT_QUOTES, 'UTF-8');?>

					<?php }else{ ?>
						<?php $_smarty_tpl->tpl_vars['pendingHospital'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pendingHospital']->value->load($_smarty_tpl->tpl_vars['pending']->value->getPatient()->admit_from), ENT_QUOTES, 'UTF-8');?>

					<?php }?>
					
					<?php if ($_smarty_tpl->tpl_vars['pending']->value->getPatient()->physician_id!=''){?>
						<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->load($_smarty_tpl->tpl_vars['pending']->value->getPatient()->physician_id), ENT_QUOTES, 'UTF-8');?>

					<?php }?>
					
					<div class="patient-box drag <?php if ($_smarty_tpl->tpl_vars['pending']->value->referral){?>admit-pending<?php }else{ ?>admit-pending-no-referral<?php }?>" draggable="true">
						<?php echo conflictAlert(array('schedule'=>$_smarty_tpl->tpl_vars['pending']->value),$_smarty_tpl);?>
				
						<span class="admit-name">Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pending']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
<br />
							<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
								<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PUBLIC_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
							<?php } ?>
							
							<?php if ($_smarty_tpl->tpl_vars['pending']->value->confirmed==1){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PUBLIC_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a><?php }?>
							<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pending']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Admit From: <?php if ($_smarty_tpl->tpl_vars['pending']->value->transfer_facility!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pendingTransferFacility']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }elseif($_smarty_tpl->tpl_vars['pending']->value->getPatient()->admit_from!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pendingHospital']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pending']->value->getPatient()->referral_org_name, ENT_QUOTES, 'UTF-8');?>
<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['pending']->value->getPatient()->physician_id!=''){?>
	<br />Physician: Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>

	<?php }?>
	<?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['pending']->value),$_smarty_tpl);?>
</span>
						<input type="hidden" class="patient-pubid" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pending']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
						<input type="hidden" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pending']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars(datetimepickerformat($_smarty_tpl->tpl_vars['pending']->value->datetime_admit), ENT_QUOTES, 'UTF-8');?>
" />
					</div>
					<?php } ?>
					&nbsp;
					
				</div>
			
			</div>
			
			<div class="discharges">
				<strong>Discharge</strong><br />
				
				<div class="clear"></div>
			
				All<br />
				<br />
				
				<div class="clear"></div>

				<!-- Patients sent back to the hospital -->
				<?php  $_smarty_tpl->tpl_vars['sent'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sent']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sents']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sent']->key => $_smarty_tpl->tpl_vars['sent']->value){
$_smarty_tpl->tpl_vars['sent']->_loop = true;
?>
				<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->load($_smarty_tpl->tpl_vars['sent']->value->hospital), ENT_QUOTES, 'UTF-8');?>

					<?php if ((!$_smarty_tpl->tpl_vars['sent']->value->hasBedhold())){?>
						<div class="patient-box hospital">
							<span class="sent-name">Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
<br /><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Hospital: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['hospital']->value->name)===null||$tmp==='' ? "Unknown" : $tmp), ENT_QUOTES, 'UTF-8');?>
<?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['sent']->value),$_smarty_tpl);?>
</span>
							
						</div>
					<?php }?>
				<?php } ?>
				
				<!-- Patients scheduled for discharge -->
				<?php  $_smarty_tpl->tpl_vars['discharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharge']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharge']->key => $_smarty_tpl->tpl_vars['discharge']->value){
$_smarty_tpl->tpl_vars['discharge']->_loop = true;
?>
				<div class="patient-box discharge"<?php if ($_smarty_tpl->tpl_vars['discharge']->value->hasBedhold()){?> style="background-color: yellow;"<?php }?>>
					<?php if ($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id!=''){?>
						<?php $_smarty_tpl->tpl_vars['dPhysician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->load($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id), ENT_QUOTES, 'UTF-8');?>

					<?php }?>
					<span class="discharge-name">Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
<br /><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Physician: <?php if ($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_name, ENT_QUOTES, 'UTF-8');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['discharge']->value->discharge_to=='Discharge to Hospital (Bed Hold)'){?><br />Bed hold until <?php echo htmlspecialchars(smarty_datetime_format($_smarty_tpl->tpl_vars['discharge']->value->datetime_discharge_bedhold_end), ENT_QUOTES, 'UTF-8');?>
<?php }?><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['discharge']->value),$_smarty_tpl);?>
</span>
				
				</div>
				<?php } ?>

				
			</div>
		
		</div>
		<?php } ?>	

</div>
<?php } ?><?php }} ?>