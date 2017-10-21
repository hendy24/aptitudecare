<?php /* Smarty version Smarty-3.1.13, created on 2015-09-22 13:13:45
         compiled from "/home/aptitude/dev/protected/tpl/facility/discharge_details.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2022797935601a86966c505-02328250%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd93285b33c7998b45ab11ead3a07cb23f2a46dd8' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/facility/discharge_details.tpl',
      1 => 1430484098,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2022797935601a86966c505-02328250',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'schedule' => 0,
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'datetime' => 0,
    'patient' => 0,
    'site_user' => 0,
    'SITE_URL' => 0,
    'facility' => 0,
    'dischargeToOptions' => 0,
    'option' => 0,
    'transferFacilities' => 0,
    'f' => 0,
    'transfer_schedule' => 0,
    'dischargeDispositionOptions' => 0,
    'dl' => 0,
    'serviceDisposition' => 0,
    'sd' => 0,
    'home_health' => 0,
    'data' => 0,
    'userRoles' => 0,
    'role' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5601a869770656_19535393',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5601a869770656_19535393')) {function content_5601a869770656_19535393($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Complete Discharge Disposition"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


$("#facility").change(function(e) {
	location.href = SITE_URL + '/?page=facility&action=discharge&facility=' + $("option:selected",this).val();
});

<?php if (!($_smarty_tpl->tpl_vars['schedule']->value->flag_readmission)){?>
	$(".flag-reason").hide();
<?php }?>

<?php $_smarty_tpl->tpl_vars['states'] = new Smarty_variable(getUSAStates(), null, 0);?>
var states = [
<?php  $_smarty_tpl->tpl_vars['state'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['state']->_loop = false;
 $_smarty_tpl->tpl_vars['abbr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['states']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['state']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['state']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['state']->key => $_smarty_tpl->tpl_vars['state']->value){
$_smarty_tpl->tpl_vars['state']->_loop = true;
 $_smarty_tpl->tpl_vars['abbr']->value = $_smarty_tpl->tpl_vars['state']->key;
 $_smarty_tpl->tpl_vars['state']->iteration++;
 $_smarty_tpl->tpl_vars['state']->last = $_smarty_tpl->tpl_vars['state']->iteration === $_smarty_tpl->tpl_vars['state']->total;
?>
<?php if ($_smarty_tpl->tpl_vars['state']->value!=''){?>
	{
		value: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
",
		label: "(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
) <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
"
	}
	<?php if ($_smarty_tpl->tpl_vars['state']->last!=true){?>,<?php }?>
<?php }?>
<?php } ?>
];

$("#state-search").autocomplete(
	{
		minLength: 0,
		source: states,
		focus: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			$( "#discharge-state" ).val( ui.item.value );
			return false;
		},
		select: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			$( "#discharge-state" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};

<!-- Discharge Type -->
$("#discharge-to").change(function(e) {
	if ($("option:selected", this).val() == "General Discharge") {
		$("#discharge-disposition").show();
	} else {
		$("#discharge-disposition").hide();
	}
	
	
	if ($("option:selected", this).val() == "Transfer to another AHC facility") {
		$("#transfer-facility").show();
		$("#transfer-datetime-box").show();
		$("#service-disposition").hide();
		$("#discharge-location-name").hide();
		$(".discharge-address-select").hide();
		$("#home-health-org").hide();
		$("#transfer-datetime").val('<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['datetime']->value,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
');
	} else {
		$("#transfer-facility").hide();
		$("#transfer-datetime-box").hide();
	}

	if ($("option:selected", this).val() == "Against Medical Advice" || $("option:selected", this).val() == "Insurance Denial") {
		$(".discharge-address-select").show();
	} else {
		$(".discharge-address-select").hide();
	}	
	
	if ($("option:selected", this).val() == "In-Patient Hospice" || $("option:selected", this).val() == "Transfer to other facility") {
		$("#discharge-location-name").show();
		$("#service-disposition").show();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Expired") {
		$("#service-disposition").hide();
	}
}).trigger("change");


$("input[name=discharge_address_checkbox]").click(function(e) {
	if ($(this).attr("checked") == "checked") {
		$(".address-info").show();
	} else {
		$(".address-info").hide();
	}
});

<!-- Discharge Disposition -->
$("#discharge-disposition").change(function(e) {
	if ($("option:selected", this).val() == "Home") {
		$("#service-disposition").show();
		$(".discharge-address-select").show();
	} else {
		$(".discharge-address").hide();
	}
	if ($("option:selected", this).val() == "Group Home" || $("option:selected", this).val() == "Assisted Living" || $("option:selected", this).val() == "SNF") {
		$("#service-disposition").show();
		$("#discharge-location-name").show();
		$(".discharge-address-select").hide();
	} else {
		$("#discharge-location-name").hide();
	}
	if ($("option:selected", this).val() == "Hospice") {
		$("#discharge-location-name").show();
		$(".discharge-address-select").hide();
		$("#service-disposition").hide();
	} 
		
}).trigger("change");

<!-- Service Disposition -->
$("#service-disposition").change(function() {
	if ($("#service option:selected").val() == "Home Health") {
		$("#home-health-org").show();
	} else {
		$("#home-health-org").hide();
	}
}).trigger("change");

$(".phone").mask("(999)-999-9999");

$("#facility-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', test: 'test', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#discharge_location").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#home-health-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#home_health_org").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

	
	$("#cancel-discharge").click(function() {
		var answer = confirm("Are you sure you want to cancel this discharge?  The patient's scheduled discharge will be cleared and the patient will return to a current patient status.");
		
		console.log ($('input[name="schedule"]').val());
		
		if (answer) {
			window.location.href = SITE_URL + "/?page=facility&action=cancelDischarge&schedule=" + $('input[name="schedule"]').val();
		}
		return false;
	});
	
	
	$("#datetime").datetimepicker({
		timeFormat: "hh:mm tt",
		stepMinute: 15, 
		hour: 11		
	});
	
	$("input[name=flag_readmission]").click(function(e) {
	if ($(this).attr("checked") == "checked") {
		$(".flag-reason").show();
	} else {
		$(".flag-reason").hide();
	}
});


<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>








<h1 class="text-center">Complete Discharge Disposition<br /><span class="text-14">for</span> <span class="text-18"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</span></h1>
<br />
<br />
<?php if ($_smarty_tpl->tpl_vars['schedule']->value->discharge_site_user_modified!=''){?>
	<?php $_smarty_tpl->tpl_vars['site_user'] = new Smarty_variable(CMS_Site_User::generate(), null, 0);?>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_user']->value->load($_smarty_tpl->tpl_vars['schedule']->value->discharge_site_user_modified), ENT_QUOTES, 'UTF-8');?>

	<br />
	<div class="text-center">This discharge was last updated on <strong><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['schedule']->value->discharge_datetime_modified,"%B %e, %Y at %l:%M %P"), ENT_QUOTES, 'UTF-8');?>
</strong> by <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_user']->value->first, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_user']->value->last, ENT_QUOTES, 'UTF-8');?>
</strong></div>
<?php }?>


<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" id="discharge-details-form">
	<input type="hidden" name="page" value="facility" />
	<input type="hidden" name="action" value="submitDischargeRequest" />
	<input type="hidden" name="schedule" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="facility" name="facility" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->facility, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="state" name="state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->state, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="_path" value="<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" />
	
	<table id="discharge" cellpadding="5px" style="width: 60%">
					<tr>
						
						<td style="text-align: right;"><strong>Time of Admission</strong>:</td>
						<td><?php echo htmlspecialchars(smarty_datetime_format($_smarty_tpl->tpl_vars['schedule']->value->datetime_admit), ENT_QUOTES, 'UTF-8');?>
</td>				
					</tr>
					<tr>
						<td style="width: 150px; text-align: right;"><strong>Time of Discharge</strong>:</td>
						<td><input type="text" size="20" name="datetime" id="datetime" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge,"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
"  /></td>
					</tr>
					<tr>
						<td style="text-align: right;"><strong>Discharge Type:</strong></td>
						<td>
							<select name="discharge_to" id="discharge-to">
								<option value="">Select discharge type...</option>
								<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dischargeToOptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
?>
									<?php if ($_smarty_tpl->tpl_vars['facility']->value->id==1){?>
										<?php if ($_smarty_tpl->tpl_vars['option']->value!='Co-Pay'&&$_smarty_tpl->tpl_vars['option']->value!='Insurance Denial'){?>
										<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->discharge_to==$_smarty_tpl->tpl_vars['option']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
</option>
										<?php }?>
									<?php }else{ ?>
										<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->discharge_to==$_smarty_tpl->tpl_vars['option']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
</option>
									<?php }?>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr id="transfer-datetime-box" class="hidden">
						<td></td>
						<td>
							<select id="transfer-facility" name="transfer_facility">
								<option value="">Select facility...</option>
							<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['transferFacilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
								<?php if ($_smarty_tpl->tpl_vars['f']->value->id!=$_smarty_tpl->tpl_vars['facility']->value->id){?>
								<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['f']->value->id==$_smarty_tpl->tpl_vars['transfer_schedule']->value->facility){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->getTitle(), ENT_QUOTES, 'UTF-8');?>
</option>
								<?php }?>
							<?php } ?>
							</select>
						</td>
					</tr>

					<tr id="discharge-disposition" class="hidden">
						<td style="text-align: right;"><strong>Discharge Disposition:</strong></td>
						<td colspan="2">
							<select id="selected-discharge-disposition" name="discharge_disposition">
								<option value="">Select...&nbsp;&nbsp;</option>
								<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dischargeDispositionOptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
?>
								<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->discharge_disposition==$_smarty_tpl->tpl_vars['option']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['option']->value, ENT_QUOTES, 'UTF-8');?>
</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php $_smarty_tpl->tpl_vars['dl'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dl']->value->load($_smarty_tpl->tpl_vars['schedule']->value->discharge_location_id), ENT_QUOTES, 'UTF-8');?>

					<?php if (($_smarty_tpl->tpl_vars['schedule']->value->discharge_location_id!='')){?>
						<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

							$("#discharge-location-name").show();
						<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

					<?php }?>

					<tr id="discharge-location-name" <?php if (($_smarty_tpl->tpl_vars['schedule']->value->discharge_location_id=='')){?> class="hidden"<?php }?>>
						<td style="text-align: right;"><strong>Discharge Facility Name:</strong></td>
						<td colspan="2">
							<input type="text" id="facility-search" style="width: 300px;" size="30" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dl']->value->name, ENT_QUOTES, 'UTF-8');?>
" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=hospital&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="new-admit-add-item" /></a>
							<input type="hidden" name="discharge_location_id" id="discharge_location" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dl']->value->id, ENT_QUOTES, 'UTF-8');?>
" />
						</td>
					</tr>
					<tr id ="service-disposition" class="hidden">
						<td style="text-align: right;"><strong>Service Disposition:</strong></td>
						<td colspan="2">
							<select id="service" name="service_disposition">
								<option value="">Select...&nbsp;&nbsp;</option>
								<?php  $_smarty_tpl->tpl_vars['sd'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sd']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['serviceDisposition']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sd']->key => $_smarty_tpl->tpl_vars['sd']->value){
$_smarty_tpl->tpl_vars['sd']->_loop = true;
?>
									<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sd']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->service_disposition==$_smarty_tpl->tpl_vars['sd']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sd']->value, ENT_QUOTES, 'UTF-8');?>
</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr id="home-health-org" class="hidden">
						<td style="text-align: right;"><strong>Home Health:</strong></td>
						<td colspan="2">
							<?php if ($_smarty_tpl->tpl_vars['schedule']->value->home_health_id!=''){?>
								<?php $_smarty_tpl->tpl_vars['home_health'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
								<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['home_health']->value->load($_smarty_tpl->tpl_vars['schedule']->value->home_health_id), ENT_QUOTES, 'UTF-8');?>

							<?php }?>
							<input type="text" id="home-health-search" style="width: 300px;" size="30" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['home_health']->value->name, ENT_QUOTES, 'UTF-8');?>
" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=hospital&action=add&type=Home%20Health&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="new-admit-add-item" /></a>
							<input type="hidden" name="home_health_org" id="home_health_org" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['home_health']->value->id, ENT_QUOTES, 'UTF-8');?>
" />
						</td>
					</tr>
	
					<div id="discharge-address">
						<tr class="discharge-address-select">
							<td>&nbsp;</td>
							<td  colspan="2" id="discharge-address-checkbox"><input type="checkbox" name="discharge_address_checkbox" value="1" /> Patient will be discharged to a different address.</td>
						</tr>
						<tr class="address-info"> 
							<td></td>
							<td colspan="2">Street Address:<br /> 
								<input type="text" name="discharge_address" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['address'], ENT_QUOTES, 'UTF-8');?>
" /> 
							</td> 
						</tr>
						<tr class="address-info">
							<td></td>							
							<td colspan="2">City:<br /> 
								<input type="text" name="discharge_city" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['city'], ENT_QUOTES, 'UTF-8');?>
" /> 
							</td> 
						</tr>
						<tr class="address-info">	
							<td></td>
							<td>State<br />
							<input type="text" id="state-search" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['state'], ENT_QUOTES, 'UTF-8');?>
" />
							<input type="hidden" name="discharge_state" id="discharge-state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['state'], ENT_QUOTES, 'UTF-8');?>
" />
							</td> 
							<td> 
								Zip<br /> 
								<input type="text" name="discharge_zip" style="width: 50px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['zip'], ENT_QUOTES, 'UTF-8');?>
" /> 
							</td> 
						</tr> 
						<tr class="address-info">
							<td></td>
							<td>Phone Number<br />
								<input type="text" name="discharge_phone" class="phone" size="15" value="" />
							</td>
						</tr>
						<tr class="address-info">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</div>
					<tr>
						<td style="vertical-align: top; text-align: right;"><strong>Comment:</strong></td>
						<td  colspan="2"><textarea name="discharge_comment" cols="60" rows="5"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->discharge_comment, ENT_QUOTES, 'UTF-8');?>
</textarea></td>
					</tr>
					<tr>
						<td class="text-right" id="flag"><input type="radio" value="1" <?php if ($_smarty_tpl->tpl_vars['schedule']->value->flag_readmission==1){?> checked<?php }?> name="flag_readmission" /></td>
						<td>Flag this patient for re-admission <a class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/information.png" /><span>When selected this patient will be flagged<br /> for review prior to re-admission.</span></a></td>
					</tr>
					
					<?php  $_smarty_tpl->tpl_vars['role'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['role']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['userRoles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['role']->key => $_smarty_tpl->tpl_vars['role']->value){
$_smarty_tpl->tpl_vars['role']->_loop = true;
?>
					<?php if ($_smarty_tpl->tpl_vars['role']->value->name=="facility_administrator"){?>
					<tr class="deny-admit">
						<td class="text-right" id="flag"><input id="deny" type="radio" name="flag_readmission" value="2" <?php if ($_smarty_tpl->tpl_vars['schedule']->value->flag_readmission==2){?> checked<?php }?>></td>
						<td>Deny re-admission for this patient<a class="tooltip"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/information.png" /><span>If this flag is set this patient will not be able to be re-admitted.</span></a></td>
					</tr>
					<?php }?>
					<?php } ?>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=cancelDischarge&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" id="cancel-discharge" class="button">Cancel Discharge</a></td>
						<td align="right"><input type="submit" value="Submit" id="submit-discharge-request" /></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=manage_discharges" style="margin-right: 8px;">Cancel</a></td>
					</tr>
				</table><?php }} ?>