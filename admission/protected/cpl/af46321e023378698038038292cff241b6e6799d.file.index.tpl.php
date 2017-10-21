<?php /* Smarty version Smarty-3.1.13, created on 2015-06-04 06:42:18
         compiled from "/home/aptitude/dev/protected/tpl/facility/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1553427723557047aa0748c1-05565100%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af46321e023378698038038292cff241b6e6799d' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/facility/index.tpl',
      1 => 1412144339,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1553427723557047aa0748c1-05565100',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'ENGINE_URL' => 0,
    'isTV' => 0,
    'auth' => 0,
    'SITE_URL' => 0,
    'weekSeed' => 0,
    'week' => 0,
    'retreatWeekSeed' => 0,
    'advanceWeekSeed' => 0,
    'emptyRoomCount' => 0,
    'day' => 0,
    'admitsByDate' => 0,
    'admits' => 0,
    'admit' => 0,
    'onsiteVisit' => 0,
    'transferFacility' => 0,
    'admitFrom' => 0,
    'physician' => 0,
    'onsite' => 0,
    'o' => 0,
    'ptName' => 0,
    'af' => 0,
    'case_manager' => 0,
    'weekStart' => 0,
    'dischargesByDate' => 0,
    'sentsByDate' => 0,
    'sents' => 0,
    'sent' => 0,
    'sPhysician' => 0,
    'hospital' => 0,
    'pName' => 0,
    'discharges' => 0,
    'discharge' => 0,
    'dPhysician' => 0,
    'ahc_facility' => 0,
    'location' => 0,
    'hh' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_557047aa29a288_42455411',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_557047aa29a288_42455411')) {function content_557047aa29a288_42455411($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>((string)$_smarty_tpl->tpl_vars['facility']->value->name)." Dashboard"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


$("#alt-week").datepicker({
	showOn: "button",
	buttonImage: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/calendar.png",
	buttonImageOnly: true,
	onSelect: function(dateText, inst) { 
		var href = SITE_URL + '/?page=facility&id=' + $("#facility-id").val() + '&weekSeed=' + dateText;
		location.href = href;
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


<?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?>

setInterval(function() {
	location.href = location.href;
}, 300000);

<?php }?>

	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});


<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php if ($_smarty_tpl->tpl_vars['isTV']->value!=1){?>
<script language="javascript">
$(window).load(function() {
	var admitHeight = 0;
	var dischargeHeight = 0;
	$(".facility-day-box-admit").each(function(e) {
		if ($(this).height() > admitHeight) {
			admitHeight = $(this).height();
		}
	});
	$(".facility-day-box-admit").height(admitHeight);
	
	$(".facility-day-box-discharge").each(function(e) {
		if ($(this).height() > dischargeHeight) {
			dischargeHeight = $(this).height();
		}
	});
	$(".facility-day-box-discharge").height(dischargeHeight);
	});
</script>


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

<div id="two-week-view"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=two_week_view&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['weekSeed']->value, ENT_QUOTES, 'UTF-8');?>
&type=excel"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_xls.png" style="height: 42px;" /></a> <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=two_week_view&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['weekSeed']->value, ENT_QUOTES, 'UTF-8');?>
&type=pdf" target="_blank"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/file_pdf.png" style="height: 42px;" /></a></div>
<br />

<?php }?>
<h1 class="text-center" style="margin-bottom: 10px"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
 Dashboard</h1> 
<h2 class="text-center" style="margin-bottom: 20px"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[0],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
 to <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[6],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
<input type="hidden" id="alt-week" /></h2>

<div id="facility-tools">
	<form name="patient-search" accept="post">
		<div id="facility-search-box-left">
			<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
		</div>
	</form>


	<div id="week-nav"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['retreatWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/prev-icon.png" /> Previous Week</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['advanceWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
">Next Week <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/next-icon.png" /></a></div>
	<div id="facility-census-button">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=census&amp;facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" class="button">Census</a></li>
	</div>
	
</div>



<div class="clear"></div>

<div class="tv-week-links">

	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['retreatWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
">&laquo; Previous Week</a> &nbsp; <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['advanceWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
">Next Week &raquo;</a>
	&nbsp;&nbsp;&nbsp;&nbsp;<i><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['emptyRoomCount']->value, ENT_QUOTES, 'UTF-8');?>
</strong> empty rooms</i>
</div>


<div class="clear"></div>

<div class="side-titles">&nbsp;</div>

<div class="facility-container">

<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
<div class="facility-day-text <?php if ($_smarty_tpl->tpl_vars['day']->last){?>facility-day-text-last<?php }?>">

	<h3><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%A, %B %e"), ENT_QUOTES, 'UTF-8');?>
</h3>

</div>
<?php } ?>



<!-- !Admissions -->

<div class="facility-admits">
	<input type="hidden" name="facility" id="facility-id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
	<div class="facility-day-box facility-day-box-admit <?php if ($_smarty_tpl->tpl_vars['day']->last){?>facility-day-box-last<?php }?> <?php echo smarty_function_cycle(array('name'=>"admitDayColumn",'values'=>"facility-day-box-blue, "),$_smarty_tpl);?>
">
		
		<?php $_smarty_tpl->tpl_vars['admits'] = new Smarty_variable($_smarty_tpl->tpl_vars['admitsByDate']->value[$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
		<div class="regular-titles"><strong>Admit</strong><br /><br /></div>
		<?php  $_smarty_tpl->tpl_vars['admit'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admit']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admit']->key => $_smarty_tpl->tpl_vars['admit']->value){
$_smarty_tpl->tpl_vars['admit']->_loop = true;
?>
			<?php $_smarty_tpl->tpl_vars['onsiteVisit'] = new Smarty_variable(CMS_Onsite_Visit::generate(), null, 0);?>
			<?php $_smarty_tpl->tpl_vars['onsite'] = new Smarty_variable($_smarty_tpl->tpl_vars['onsiteVisit']->value->fetchVisitInfo($_smarty_tpl->tpl_vars['admit']->value->id), null, 0);?>
			<div class="<?php if ($_smarty_tpl->tpl_vars['facility']->value->id==4&&$_smarty_tpl->tpl_vars['admit']->value->paymethod=="HMO"){?>facility-hmo<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->status=='Under Consideration'&&$_smarty_tpl->tpl_vars['admit']->value->referral){?> facility-admit-pending<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->status=='Under Consideration'){?>facility-pending-no-referral<?php }else{ ?>facility-admit<?php }?>">
				<?php if ($_smarty_tpl->tpl_vars['admit']->value->transfer_facility!=''){?>
					<?php $_smarty_tpl->tpl_vars['transferFacility'] = new Smarty_variable(CMS_Facility::generate(), null, 0);?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['transferFacility']->value->load($_smarty_tpl->tpl_vars['admit']->value->transfer_facility), ENT_QUOTES, 'UTF-8');?>

				<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->admit_from!=''){?>
					<?php $_smarty_tpl->tpl_vars['admitFrom'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admitFrom']->value->load($_smarty_tpl->tpl_vars['admit']->value->admit_from), ENT_QUOTES, 'UTF-8');?>

				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['admit']->value->getPatient()->physician_id!=''){?>
					<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->load($_smarty_tpl->tpl_vars['admit']->value->getPatient()->physician_id), ENT_QUOTES, 'UTF-8');?>

				<?php }?>
								
				<span class="admit-name">
				<?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?>
					<?php $_smarty_tpl->tpl_vars['ptName'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['admit']->value->getPatient()->fullName(),0,10)).("..."), null, 0);?>
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->transfer_facility!=''){?>
						<?php $_smarty_tpl->tpl_vars['admitFrom'] = new Smarty_variable($_smarty_tpl->tpl_vars['transferFacility']->value->name, null, 0);?>
					<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->admit_from!=''){?>
						<?php $_smarty_tpl->tpl_vars['admitFrom'] = new Smarty_variable($_smarty_tpl->tpl_vars['admitFrom']->value->name, null, 0);?>
					<?php }else{ ?>
						<?php $_smarty_tpl->tpl_vars['admitFrom'] = new Smarty_variable($_smarty_tpl->tpl_vars['admit']->value->getPatient()->referral_org_name, null, 0);?>
					<?php }?>
					<?php if (strlen($_smarty_tpl->tpl_vars['admitFrom']->value)>20){?>
						<?php $_smarty_tpl->tpl_vars['af'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['admitFrom']->value,0,20)).("..."), null, 0);?>
					<?php }else{ ?>
						<?php $_smarty_tpl->tpl_vars['af'] = new Smarty_variable($_smarty_tpl->tpl_vars['admitFrom']->value, null, 0);?>
					<?php }?>
					<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
						<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
					<?php } ?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>

					<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ptName']->value, ENT_QUOTES, 'UTF-8');?>
</strong><br />
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->getPatient()->physician_id!=''){?>
						Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
<br />
					<?php }?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['af']->value, ENT_QUOTES, 'UTF-8');?>

				<?php }else{ ?>
					Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
 <br />
					<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
						<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
					<?php } ?>
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->confirmed==1){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/star.png" style="height: 10px;" /><span>Elective admit has been confirmed.</span></a><?php }?>
					<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->transfer_facility!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['transferFacility']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }elseif($_smarty_tpl->tpl_vars['admit']->value->admit_from!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admitFrom']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->getPatient()->referral_org_name, ENT_QUOTES, 'UTF-8');?>
<?php }?><br />
					
					
					<!-- !ABQ specific functionality -->
					<?php if ($_smarty_tpl->tpl_vars['facility']->value->id==4){?>
						<?php if (($_smarty_tpl->tpl_vars['admit']->value->datetime_pickup!='')){?>
							Pickup: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->datetime_pickup, ENT_QUOTES, 'UTF-8');?>
<br />
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['admit']->value->case_manager_id!=''){?>
							<?php $_smarty_tpl->tpl_vars['case_manager'] = new Smarty_variable(CMS_Case_Manager::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['case_manager']->value->load($_smarty_tpl->tpl_vars['admit']->value->case_manager_id), ENT_QUOTES, 'UTF-8');?>

							CM: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['case_manager']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['case_manager']->value->first_name, ENT_QUOTES, 'UTF-8');?>
<br />
						<?php }?>
						
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->getPatient()->physician_id!=''){?> 
						Physician: Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
<br />
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['admit']->value->other_diagnosis!=''){?>
						DX: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->other_diagnosis, ENT_QUOTES, 'UTF-8');?>

					<?php }?>
					

					
					
					<div class="facility-day-box-tools"><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['admit']->value,'weekSeed'=>$_smarty_tpl->tpl_vars['weekStart']->value),$_smarty_tpl);?>
</div></span>
					<input type="hidden" class="schedule-datetime" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
				<?php }?>	
				</span>
			</div>
		<?php } ?>
		</ul>
	
	</div>
	<?php } ?>
	

</div>

<div class="clear"></div>

<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
<div class="facility-day-text facility-discharge-day-text <?php if ($_smarty_tpl->tpl_vars['day']->last){?>facility-day-text-last<?php }?>">

	<h3><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%A, %B %e"), ENT_QUOTES, 'UTF-8');?>
</h3>

</div>
<?php } ?>
<div class="facility-discharges">
	
	<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
	<div class="facility-day-box facility-day-box-discharge <?php if ($_smarty_tpl->tpl_vars['day']->last){?>facility-day-box-last<?php }?> <?php echo smarty_function_cycle(array('name'=>"dischargeDayColumn",'values'=>"facility-day-box-blue, "),$_smarty_tpl);?>
">

		<?php $_smarty_tpl->tpl_vars['discharges'] = new Smarty_variable($_smarty_tpl->tpl_vars['dischargesByDate']->value[$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
		<div class="regular-titles"><strong>Discharge</strong><br /><br /></div>

		<!-- Patients sent back to the hospital -->
		<?php $_smarty_tpl->tpl_vars['sents'] = new Smarty_variable($_smarty_tpl->tpl_vars['sentsByDate']->value[$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
		<?php  $_smarty_tpl->tpl_vars['sent'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sent']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sents']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sent']->key => $_smarty_tpl->tpl_vars['sent']->value){
$_smarty_tpl->tpl_vars['sent']->_loop = true;
?>
		<?php $_smarty_tpl->tpl_vars['onsiteVisit'] = new Smarty_variable(CMS_Onsite_Visit::generate(), null, 0);?>
		<?php $_smarty_tpl->tpl_vars['onsite'] = new Smarty_variable($_smarty_tpl->tpl_vars['onsiteVisit']->value->fetchVisitInfo($_smarty_tpl->tpl_vars['sent']->value->schedule), null, 0);?>
		<?php if ($_smarty_tpl->tpl_vars['sent']->value->physician_id!=''){?>
			<?php $_smarty_tpl->tpl_vars['sPhysician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sPhysician']->value->load($_smarty_tpl->tpl_vars['sent']->value->getPatient()->physician_id), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
		<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
		<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->load($_smarty_tpl->tpl_vars['sent']->value->hospital), ENT_QUOTES, 'UTF-8');?>

			<?php if ((!$_smarty_tpl->tpl_vars['sent']->value->hasBedhold())){?>
				<div class="facility-sent">
					<span class="sent-name">
					<?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?>
						<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
							<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
						<?php } ?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
&nbsp;
							<?php $_smarty_tpl->tpl_vars['ptName'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['sent']->value->getPatient()->fullName(),0,12)).("..."), null, 0);?>
							<?php if (strlen($_smarty_tpl->tpl_vars['hospital']->value->name)>22){?>
								<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['hospital']->value->name,0,22)).("..."), null, 0);?>
							<?php }else{ ?>
								<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable($_smarty_tpl->tpl_vars['hospital']->value->name, null, 0);?>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['sent']->value->getPatient()->physician_id!=''){?><?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable($_smarty_tpl->tpl_vars['sPhysician']->value->last_name, null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable($_smarty_tpl->tpl_vars['sent']->value->getPatient()->physician_name, null, 0);?><?php }?>
							<?php $_smarty_tpl->tpl_vars['pName'] = new Smarty_variable(substr($_smarty_tpl->tpl_vars['physician']->value,0,24), null, 0);?>
							<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ptName']->value, ENT_QUOTES, 'UTF-8');?>
</strong><br /><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value, ENT_QUOTES, 'UTF-8');?>
<br />Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pName']->value, ENT_QUOTES, 'UTF-8');?>
						</span>
					<?php }else{ ?>
						Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
<br />
						<?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['onsite']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
?>
							<?php if ($_smarty_tpl->tpl_vars['o']->value->id!=''){?><a href="#" class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/check.png" style="height: 14px;" /><span>Patient has had an on-site visit</span></a><?php }?>
						<?php } ?>
						<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Hospital: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['hospital']->value->name)===null||$tmp==='' ? "Unknown" : $tmp), ENT_QUOTES, 'UTF-8');?>
<br />Physician: <?php if ($_smarty_tpl->tpl_vars['sent']->value->getPatient()->physician_id!=''){?>Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sPhysician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sent']->value->getPatient()->physician_name, ENT_QUOTES, 'UTF-8');?>
<?php }?><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['sent']->value,'weekSeed'=>$_smarty_tpl->tpl_vars['weekStart']->value),$_smarty_tpl);?>
</a>
					<?php }?>
					</span>
				</div>
			<?php }?>
		<?php } ?>
		
		
		
		<!-- Discharges -->
		<?php  $_smarty_tpl->tpl_vars['discharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharge']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharge']->key => $_smarty_tpl->tpl_vars['discharge']->value){
$_smarty_tpl->tpl_vars['discharge']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id!=''){?>
			<?php $_smarty_tpl->tpl_vars['dPhysician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->load($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
			<div class="facility-discharge"<?php if ($_smarty_tpl->tpl_vars['discharge']->value->hasBedhold()){?> style="background-color: yellow;"<?php }?>>
				<span class="discharge-name">
					<?php if ($_smarty_tpl->tpl_vars['isTV']->value==1){?>
						<?php if (strlen($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->fullName())>20){?>
							<?php $_smarty_tpl->tpl_vars['ptName'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->fullName(),0,20)).("..."), null, 0);?>
						<?php }else{ ?>
							<?php $_smarty_tpl->tpl_vars['ptName'] = new Smarty_variable($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->fullName(), null, 0);?>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id!=''){?>
							<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable($_smarty_tpl->tpl_vars['dPhysician']->value->last_name, null, 0);?>
						<?php }else{ ?>
							<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_name, null, 0);?>
						<?php }?>
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
 <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ptName']->value, ENT_QUOTES, 'UTF-8');?>
</strong><br />
						Dr. <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value, ENT_QUOTES, 'UTF-8');?>

					<?php }else{ ?>
						Room <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getRoomNumber(), ENT_QUOTES, 'UTF-8');?>
 <br /><strong><?php if (($_smarty_tpl->tpl_vars['facility']->value->id==4&&$_smarty_tpl->tpl_vars['discharge']->value->discharge_to=='Co-Pay')){?><span class="text-11">$ </span><?php }?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->fullName(), ENT_QUOTES, 'UTF-8');?>
</strong><br />Physician: <?php if ($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_id!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dPhysician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->getPatient()->physician_name, ENT_QUOTES, 'UTF-8');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['discharge']->value->discharge_to=='Discharge to Hospital (Bed Hold)'){?><br />Bed hold until <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['discharge']->value->datetime_discharge_bedhold_end,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
<?php }?>
						
						<br />
						<?php if (($_smarty_tpl->tpl_vars['discharge']->value->discharge_to=="Transfer to another AHC facility")){?>
							<?php $_smarty_tpl->tpl_vars['ahc_facility'] = new Smarty_variable(CMS_Facility::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahc_facility']->value->load($_smarty_tpl->tpl_vars['discharge']->value->transfer_to_facility), ENT_QUOTES, 'UTF-8');?>

							Transfer to: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahc_facility']->value->name, ENT_QUOTES, 'UTF-8');?>

							<br />
						<?php }?>
						
						<?php if (($_smarty_tpl->tpl_vars['discharge']->value->discharge_to=="Transfer to other facility")){?>
							<?php $_smarty_tpl->tpl_vars['location'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->load($_smarty_tpl->tpl_vars['discharge']->value->discharge_location_id), ENT_QUOTES, 'UTF-8');?>

							Transfer to: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->name, ENT_QUOTES, 'UTF-8');?>

							<br />
						<?php }?>
						
						<?php if ($_smarty_tpl->tpl_vars['discharge']->value->service_disposition=="Other Home Health"&&$_smarty_tpl->tpl_vars['discharge']->value->home_health_id!=''){?>
							<?php $_smarty_tpl->tpl_vars['hh'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hh']->value->load($_smarty_tpl->tpl_vars['discharge']->value->home_health_id), ENT_QUOTES, 'UTF-8');?>

							HH: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hh']->value->name, ENT_QUOTES, 'UTF-8');?>

						<?php }else{ ?>
							HH: <?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['discharge']->value->service_disposition)===null||$tmp==='' ? "No service" : $tmp), ENT_QUOTES, 'UTF-8');?>

						<?php }?>
						
						<div class="facility-day-box-tools"><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['discharge']->value,'weekSeed'=>$_smarty_tpl->tpl_vars['weekStart']->value),$_smarty_tpl);?>
</div></span>	
					<?php }?>
				</span>
			</div>
		<?php } ?>
	</div>
	<?php } ?>

</div>

</div><?php }} ?>