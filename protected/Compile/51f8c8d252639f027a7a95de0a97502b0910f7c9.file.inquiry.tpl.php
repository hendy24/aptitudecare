<?php /* Smarty version Smarty-3.1.19, created on 2014-08-15 23:39:54
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/inquiry.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203725744953d86d29f262f9-47313977%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51f8c8d252639f027a7a95de0a97502b0910f7c9' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/inquiry.tpl',
      1 => 1408167588,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203725744953d86d29f262f9-47313977',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d86d29f2f280_25183997',
  'variables' => 
  array (
    'patient' => 0,
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'SiteUrl' => 0,
    'location' => 0,
    'currentUrl' => 0,
    'weekSeed' => 0,
    'ethnicities' => 0,
    'e' => 0,
    'languages' => 0,
    'language' => 0,
    'maritalStatuses' => 0,
    'ms' => 0,
    'schedule' => 0,
    'admit' => 0,
    'frameworkImg' => 0,
    'pcp' => 0,
    'surgeon' => 0,
    'dmEquipment' => 0,
    'dme' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d86d29f2f280_25183997')) {function content_53d86d29f2f280_25183997($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
?><script>
	$(document).ready(function() {
		$("#phone").mask("(999) 999-9999");

		<?php if ($_smarty_tpl->tpl_vars['patient']->value->date_of_birth=='') {?>
		$("#dob").mask("99/99/9999");
		<?php }?>

		$(".secondary-diagnosis-fields").hide();

		$("#add-diagnosis").click(function() {
			$(".secondary-diagnosis-fields").show();
			$(this).hide();
		});

		$("#secondary-ins-fields").hide();

		$("#add-insurance").click(function() {
			$(this).hide();
			$("#secondary-ins-fields").show();
		});

		if ($("#dob").val() != '') {
			$("#age").html(getAge($("#dob").val()));
		}

		$("#dob").blur(function() {	
			$("#age").html(getAge($(this).val()));
		});

		function getAge(date) {
			var now = new Date();
			var past = new Date(date);
			var nowYear = now.getFullYear();
			var pastYear = past.getFullYear();
			var age = nowYear - pastYear;
			return age
		}

		$("#admit-from-search").autocomplete({
			serviceUrl: SiteUrl,
			params: {
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#location").val()
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#admit-from").val(suggestion.data);
			}
		});


		$("#pcp-search").autocomplete({
			serviceUrl: SiteUrl,
			params: {
				page: 'Physicians',
				action: 'searchPhysicians',
				location: $("#location").val()
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#pcp").val(suggestion.data);
			}
		});

		<?php $_smarty_tpl->tpl_vars['states'] = new Smarty_variable(getUSAStates(), null, 0);?>
		var states = [
		<?php  $_smarty_tpl->tpl_vars['state'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['state']->_loop = false;
 $_smarty_tpl->tpl_vars['abbr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['states']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['state']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['state']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['state']->key => $_smarty_tpl->tpl_vars['state']->value) {
$_smarty_tpl->tpl_vars['state']->_loop = true;
 $_smarty_tpl->tpl_vars['abbr']->value = $_smarty_tpl->tpl_vars['state']->key;
 $_smarty_tpl->tpl_vars['state']->iteration++;
 $_smarty_tpl->tpl_vars['state']->last = $_smarty_tpl->tpl_vars['state']->iteration === $_smarty_tpl->tpl_vars['state']->total;
?>
		<?php if ($_smarty_tpl->tpl_vars['state']->value!='') {?>
			{
				value: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
 (<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
)",
				data: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
"
			}
			<?php if ($_smarty_tpl->tpl_vars['state']->last!=true) {?>,<?php }?>
		<?php }?>
		<?php } ?>
		];

		$("#state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$(this).val(suggestion.data);
			}
		});


		$("private-pay-state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$(this).val(suggestion.data);
			}
		});

	});
</script>





<h1>Pre-Admission Inquiry Record<br>
<span class="text-14">for</span> <br><span class="text-20"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->last_name, ENT_QUOTES, 'UTF-8');?>
</span></h1>

<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SiteUrl']->value, ENT_QUOTES, 'UTF-8');?>
" name="inquiry" method="post" id="inquiry-form">
	<input type="hidden" name="module" value="HomeHealth" />
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="inquiry" />
	<input type="hidden" name="patient" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="location" name="location_state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['currentUrl']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="weekSeed" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['weekSeed']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<table class="form-table">
			
		<!-- Patient Information -->
		<tr> 
			<th colspan="3">Patient Info</th> 
		</tr>
		<tr class="form-header-row">
			<td>
				<strong>First:</strong><br>
				<input type="text" name="first_name" id="first-name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->first_name, ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" />
			</td>
			<td>
				<strong>Middle:</strong><br>
				<input type="text" name="middle_name" id="middle-name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->middle_name, ENT_QUOTES, 'UTF-8');?>
" style="width: 200px;" />
			</td>
			<td>
				<strong>Last:</strong><br>
				<input type="text" name="last_name" id="last-name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->last_name, ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<strong>Address:</strong><br>
				<input type="text" name="address" id="address" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->address, ENT_QUOTES, 'UTF-8');?>
" style="width: 500px;" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->phone, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
		</tr>
		<tr>
			<td>
				<strong>City:</strong><br>
				<input type="text" name="city" id="city" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->city, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
			<td>
				<strong>State:</strong><br>
				<input type="text" name="state" id="state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->state, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
			<td>
				<strong>Zip:</strong><br>
				<input type="text" name="zip" id="zip" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->zip, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top">
				<strong>Date of Birth:</strong><br>	
				<input type="text" name="date_of_birth" id="dob" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['patient']->value->date_of_birth,'%m/%d/%Y'), ENT_QUOTES, 'UTF-8');?>
" />	

			</td>
			<td>
				<strong>Age:</strong>
				<div id="age"></div><br>
			</td>
			<td>
				<strong>Sex:</strong><br>
				<input type="radio" name="sex" value="Male" <?php if ($_smarty_tpl->tpl_vars['patient']->value->sex=="Male") {?> checked<?php }?>>Male<br>
				<input type="radio" name="sex" value="Female" <?php if ($_smarty_tpl->tpl_vars['patient']->value->sex=="Female") {?> checked<?php }?>>Female
			</td>
		</tr>
		<tr>
			<td>
				<strong>Ethnicity:</strong><br>
				<select name="ethnicity" id="ethnicity">
					<option value="">Select...</option>
					<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['e']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ethnicities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value) {
$_smarty_tpl->tpl_vars['e']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['e']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['patient']->value->ethnicity==$_smarty_tpl->tpl_vars['e']->value) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['e']->value, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
			<td>
				<strong>Language:</strong><br>
				<select name="language" id="language">
					<option value="">Select...</option>
					<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value) {
$_smarty_tpl->tpl_vars['language']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['patient']->value->language==$_smarty_tpl->tpl_vars['language']->value) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language']->value, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
			<td>
				<strong>Marital Status:</strong><br>
				<select name="marital_status" id="marital-status">
					<option value="">Select...</option>
					<?php  $_smarty_tpl->tpl_vars['ms'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ms']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['maritalStatuses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ms']->key => $_smarty_tpl->tpl_vars['ms']->value) {
$_smarty_tpl->tpl_vars['ms']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ms']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['patient']->value->marital_status==$_smarty_tpl->tpl_vars['ms']->value) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ms']->value, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>Religion:</strong><br>
				<input type="text" name="religion" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->religion, ENT_QUOTES, 'UTF-8');?>
" id="religion">
			</td>
			<td>
				<strong>Emergency Contact:</strong><br>
				<input type="text" name="emergency_contact" id="emergency-contact" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->emergency_contact, ENT_QUOTES, 'UTF-8');?>
" style="width: 200px" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="emergency_phone" id="emergency-phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->emergency_phone, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>



		<!-- Clinical Info -->
		<tr class="form-header-row">
			<th colspan="3">Clinical Info</th>
		</tr>
		<tr>
			<td>
				<strong>Admitting From:</strong><br>
				<?php $_smarty_tpl->tpl_vars['admit'] = new Smarty_variable(HealthcareFacility::generate($_smarty_tpl->tpl_vars['schedule']->value->admit_from_id), null, 0);?>
				<input type="text" id="admit-from-search" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->name, ENT_QUOTES, 'UTF-8');?>
" style="width:210px" />
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SiteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=HealthcareFacilities&amp;action=add&amp;isMicro=1" rel="shadowbox">
					<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/add-black-bkgnd.png" class="add-button" alt="">
				</a>
				<input type="hidden" name="admit_from_id" id="admit-from" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->id, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
			<td>
				<strong>Primary Care Physician:</strong><br>
				<?php $_smarty_tpl->tpl_vars['pcp'] = new Smarty_variable(Physician::generate($_smarty_tpl->tpl_vars['schedule']->value->pcp_id), null, 0);?>
				<input type="text" id="pcp-search" <?php if (isset($_smarty_tpl->tpl_vars['pcp']->value->id)) {?>value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pcp']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pcp']->value->first_name, ENT_QUOTES, 'UTF-8');?>
"<?php } else { ?>value=""<?php }?> style="width:200px" />
				<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/add-black-bkgnd.png" class="add-button" alt="">
				<input type="hidden" id="pcp" name="pcp_id" <?php if (isset($_smarty_tpl->tpl_vars['pcp']->value->id)) {?>value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pcp']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php } else { ?> value=""<?php }?> />
			</td>
			<td>
				<strong>Surgeon/Specialist:</strong><br>
				<?php $_smarty_tpl->tpl_vars['surgeon'] = new Smarty_variable(Physician::generate($_smarty_tpl->tpl_vars['schedule']->value->surgeon_id), null, 0);?>
				<input type="text" id="surgeon-search" style="width:200px" <?php if (isset($_smarty_tpl->tpl_vars['surgeon']->value->id)) {?>value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['surgeon']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['surgeon']->value->first_name, ENT_QUOTES, 'UTF-8');?>
"<?php } else { ?> value=""<?php }?> />
				<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/add-black-bkgnd.png" class="add-button" alt="">
				<input type="hidden" id="surgeon" name="surgeon_id" <?php if (isset($_smarty_tpl->tpl_vars['surgeon']->value->id)) {?>value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['surgeon']->value->id, ENT_QUOTES, 'UTF-8');?>
" <?php } else { ?> value=""<?php }?> />
			</td>
		</tr>
		<tr>
			<td><strong>Primary Diagnosis:</strong></td>
			<td colspan="2"><strong>Date/Onset:</strong> <input type="text" class="datepicker" name="diagnosis1_onset_date" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->diagnosis1_onset_date, ENT_QUOTES, 'UTF-8');?>
" style="width:75px" /></td>
		</tr>
		<tr>
			<td colspan="3">
				<textarea name="primary_diagnosis" id="primary-diagnosis" cols="110" rows="8"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->primary_diagnosis, ENT_QUOTES, 'UTF-8');?>
</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3"><a href="#secondary-diagnosis" id="add-diagnosis" class="right link">Add additional diagnosis</a></td>
		</tr>


		<!-- These fields will initially be hidden -->
		<tr class="secondary-diagnosis-fields">
			<td><strong>Secondary Diagnosis:</strong></td>
			<td colspan="2">
				<strong>Date/Onset:</strong> <input type="text" class="datepicker" name="diagnosis2_onset_date" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->diagnosis2_onset_date, ENT_QUOTES, 'UTF-8');?>
" style="width:75px" /></td>
		</tr>
		<tr class="secondary-diagnosis-fields">
			<td colspan="3">
				<textarea name="primary_diagnosis" id="secondary-diagnosis" cols="110" rows="8"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->primary_diagnosis, ENT_QUOTES, 'UTF-8');?>
</textarea>
			</td>
		</tr>

		<tr>
			<td>
				<strong>Diabetic:</strong><br>
				<input type="radio" name="diabetic" value="Yes" <?php if ($_smarty_tpl->tpl_vars['patient']->value->diabetic==1) {?> checked<?php }?>>Yes<br>
				<input type="radio" name="diabetic" value="No" <?php if ($_smarty_tpl->tpl_vars['patient']->value->diabetic==0) {?> checked<?php }?>>No
			</td>
			<td>
				<strong>IDDM:</strong><br>
				<input type="radio" name="iddm" value="Yes" <?php if ($_smarty_tpl->tpl_vars['patient']->value->iddm==1) {?> checked<?php }?>>Yes<br>
				<input type="radio" name="iddm" value="No" <?php if ($_smarty_tpl->tpl_vars['patient']->value->iddm==0) {?> checked<?php }?>>No
			</td>
			<td style="vertical-align: top">
				<strong>Allergies:</strong><br>
				<input type="text" name="allergies" id="allergies" style="width:225px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->allergies, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top">
				<strong>DME:</strong><br>
					<?php  $_smarty_tpl->tpl_vars['dme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dmEquipment']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dme']->key => $_smarty_tpl->tpl_vars['dme']->value) {
$_smarty_tpl->tpl_vars['dme']->_loop = true;
?>
					<input type="checkbox" name="dme" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dme']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['schedule']->value->dme==$_smarty_tpl->tpl_vars['dme']->value) {?> selected<?php }?>>&nbsp;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dme']->value, ENT_QUOTES, 'UTF-8');?>
<br>
					<?php } ?>
				</select>
			</td>
			<td colspan="2">
				<strong>Special Instructions:</strong><br>
				<textarea name="special_instructions" id="special-instructions" cols="70" rows="4"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->special_instructions, ENT_QUOTES, 'UTF-8');?>
</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		
		<tr>
			<th colspan="3">Insurance Info</th>
		</tr>
		<tr>
			<td>
				<strong>Primary Insurance:</strong><br>
				<input type="text" name="primary_insurance" id="primary-insurance" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->primary_insurance, ENT_QUOTES, 'UTF-8');?>
" style="width:220px" />
			</td>
			<td>
				<strong>Policy Number:</strong><br>
				<input type="text" name="primary_insurance_number" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->primary_insurance_number, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" />
			</td>
			<td>
				<strong>Group Number:</strong><br>
				<input type="text" name="primary_insurance_group" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->primary_insurance_group, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<a href="#secondary-ins" id="add-insurance" class="right link">Add additional Insurance</a>
			</td>
		</tr>

		<tr id="secondary-ins-fields">
			<td>
				<strong>Secondary Insurance:</strong><br>
				<input type="text" name="secondary_insurance" id="primary-insurance" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->secondary_insurance, ENT_QUOTES, 'UTF-8');?>
" style="width:220px" />
			</td>
			<td>
				<strong>Policy Number:</strong><br>
				<input type="text" name="secondary_insurance_number" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->secondary_insurance_number, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" />
			</td>
			<td>
				<strong>Group Number:</strong><br>
				<input type="text" name="secondary_insurance_group" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->secondary_insurance_group, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<strong>Private Pay:</strong><br>
				<input type="text" name="private_pay_party" id="responsible-party" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_party, ENT_QUOTES, 'UTF-8');?>
" style="width: 450px" />
			</td>
			<td>
				<strong>Phone:</strong><br>
				<input type="text" name="private_pay_phone" id="private-pay-phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_phone, ENT_QUOTES, 'UTF-8');?>
" >
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<strong>
					Address:<br>
					<input type="text" name="private_pay_address" id="private-pay-address" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_address, ENT_QUOTES, 'UTF-8');?>
" style="width:450px" />
				</strong>
			</td>
		</tr>
		<tr>
			<td>
				<strong>City:</strong><br>
				<input type="text" name="private_pay_city" id="private-pay-city" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_city, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
			<td>
				<strong>State:</strong><br>
				<input type="text" name="private_pay_state" id="private-pay-state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_state, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
			<td>
				<strong>Zip:</strong><br>
				<input type="text" name="private_pay_zip" id="private-pay-zip" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->private_pay_zip, ENT_QUOTES, 'UTF-8');?>
" />
			</td>
		</tr>



		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="window.location='SiteUrl'"></td>
			<td colspan="2"><input class="right" type="submit" value="Save"></td>
		</tr>
	</table>

</form><?php }} ?>
