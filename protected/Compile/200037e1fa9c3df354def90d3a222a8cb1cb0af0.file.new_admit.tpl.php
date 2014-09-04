<?php /* Smarty version Smarty-3.1.19, created on 2014-08-28 16:49:34
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/new_admit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:206459935753d83823960163-58486267%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '200037e1fa9c3df354def90d3a222a8cb1cb0af0' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/new_admit.tpl',
      1 => 1409265894,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '206459935753d83823960163-58486267',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d8382398bc96_00269044',
  'variables' => 
  array (
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'siteUrl' => 0,
    'current_url' => 0,
    'locations' => 0,
    'location' => 0,
    'auth' => 0,
    'frameworkImg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d8382398bc96_00269044')) {function content_53d8382398bc96_00269044($_smarty_tpl) {?><script>
	$(document).ready(function() {
		// $('#new_admission').validate();
		$('#admit-request-phone').mask("(999) 999-9999");
		$('#admit-request-zip').mask("99999");
		var $clone = "";
		var location = "";
		var admitDate = "";
		var admitFrom = "";
		var referredBy = "";
		var referredByType = "";
		var phone = "";
		var zip = "";


		$("#admit-from-search").autocomplete({
			serviceUrl: SiteUrl,
			params: { 
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#admit-request-location option:selected").val() 
			}, minChars: 3,
			width: "300",
			onSelect: function (suggestion) {
				$("#admit-from").val(suggestion.data);
			}

		});

		$("#referral-source-search").autocomplete({
			serviceUrl: SiteUrl,
			params: {
				page: 'MainPage',
				action: 'searchReferralSources',
				location: $("#admit-request-location option:selected").val()
			}, minChars: 4,
			width: "300",
			onSelect: function (suggestion) {
				$("#referral-source").val(suggestion.data['id']);
				$("#referral-source-type").val(suggestion.data['type']);
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


		$("#admit-request-state").autocomplete({
			lookup: states,
			onSelect: function (suggestion) {
				$("#state").val(suggestion.data);
			}
		});



		$("#new-admission").validate({

			submitHandler: function(form) {
				var $patientDiv = $(".patient-search-result");
			
				if ($clone != '') {
					$("#patient-results").empty();
					$clone.empty();
					$clone = '';
				}

				$.post(SiteUrl, {
					module: $("#module").val(),
					page: $("#page").val(),
					action: "searchPrevPatients",
					last_name: $("#admit-request-last-name").val(),
					first_name: $("#admit-request-first-name").val(),
					middle_name: $("#admit-request-middle-name").val()
					}, function (data) {
						$.each(data, function(i,e) {
							
							if (e.datetime_discharge != null) {
								var date = new Date(e.datetime_discharge);
							} else {
								var date = "No discharge date";
							}
							$clone = $patientDiv.clone();

							$clone.find("input.previous-patient-id:hidden").val(e.public_id);
							$clone.find(".previous-patient-name").append(e.first_name + " " + e.last_name);
							$clone.find(".previous-patient-ssn").append(e.ssn);
							$clone.find(".previous-patient-location").append(e.location_name);
							$clone.find(".previous-discharge-date").append(date);
							if (e.status == "Pending") {
								$(".admit-previous-patient").empty();
							} else {
								$clone.find(".previous-admit-status").append(e.status);
							}
							
							$clone.appendTo($("#patient-results"));

						});
						$("#patient-results").find("div").first().remove();
						$("#patient-results").show();
						$("#submit-new-patient").show();
					},
					"json"
				);
			}

		});



		
		$("#submit-new-patient").click(function() {
			//  Save new patient to db
			var patientData = $("#new-admission").serialize();
			$.post(SiteUrl, patientData, function (response) {
					window.location.href = response.url;
				}, "json"
			);


		});


		$("#patient-results").on("click", ".admit-previous-patient", function() {
			var patientId = $(".patient-search-result").find("input.previous-patient-id:hidden").val();
			var patientData = $("#new-admission").serializeArray();
			$.each(patientData, function(index, data) {
				if (data.name == "admit_date") {
					admitDate = data.value;
				}
				if (data.name == "location") {
					location = data.value;
				}
				if (data.name == "admit_from") {
					admitFrom = data.value;
				}
				if (data.name == "referred_by_id") {
					referredBy = data.value;
				}
				if (data.name == "referred_by_type") {
					referredByType = data.value;
				}
				if (data.name == "phone") {
					phone = data.value;
				}
				if (data.name == "zip") {
					zip = data.value;
				}
			});
			
			$.post(SiteUrl, { 
				module: "HomeHealth",
				page: "Admissions",
				action: "submitPrevPatient",
				patient_id: patientId,
				admit_date: admitDate,
				location: location,
				admit_from: admitFrom,
				referred_by_id: referredBy,
				referred_by_type: referredByType,
				phone: phone,
				zip: zip
				}, function (response) {
					window.location.href = response.url;
				}, "json"
			);
		});

	});
</script>





<h1>New Admission Request</h1>

<form name="new_admission" id="new-admission" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" id="module" name="module" value="HomeHealth" />
	<input type="hidden" id="page" name="page" value="admissions" />
	<input type="hidden" name="action" value="submitNewAdmit" />
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="submit" value="true" />
	<table class="form">
		<tr>
			<td><strong>Admit Date:</strong></td>
			<td colspan="2"><strong>Location:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="datepicker" name="admit_date" value="" required /></td>
			<td colspan="2">
				<select name="location" id="admit-request-location">
					<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['location']->value->id==$_smarty_tpl->tpl_vars['auth']->value->getRecord()->default_location) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Admit From:</strong></td>
			<td colspan="2"><strong>Referral Source:</strong></td>
		</tr>
		<tr>
			<td style="width: 275px">
				<input type="text" id="admit-from-search" style="width: 250px" required />
				<input type="hidden" name="admit_from" id="admit-from" />
				<a href="/?page=healthcare_facilities&amp;action=add&amp;isMicro=1" rel="shadowbox;width=800;height=550">
					<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/add-black-bkgnd.png" class="add-button" alt="">
				</a>
			</td>
			<td colspan="2">
				<input type="text" id="referral-source-search" style="width: 250px" />
				<input type="hidden" id="referral-source" name="referred_by_id" />
				<input type="hidden" id="referral-source-type" name="referred_by_type" />
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><strong>Patient Info:</strong></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td>First Name</td>
			<td>Middle Name</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-last-name" name="last_name" style="width:250px;" required  /></td>
			<td><input type="text" id="admit-request-first-name" name="first_name" style="width:150px;" required /></td>
			<td><input type="text" id="admit-request-middle-name" name="middle_name" /></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td>Zip</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-phone" name="phone" required /></td>
			<td><input type="text" id="admit-request-zip" name="zip" required /></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><input type="submit" value="Search" id="admit-request-search" /></td>
		</tr>
	</table>
</form>




<!-- Hidden divs for previous patient info -->
<div id="patient-results">
	<h2>Previous Patient Search Results</h2>
	<div class="patient-search-result">
		<h2 class="previous-patient-name"></h2>
		<p class="previous-patient-ssn"><strong>SSN:</strong>&nbsp; </p>
		<p class="previous-patient-location"><strong>Location:</strong>&nbsp; </p>
		<p class="previous-discharge-date"><strong>Discharge Date:</strong>&nbsp; </p>
		<p class="previous-admit-status"><strong>Admission Status:</strong>&nbsp; </p>
		<input type="hidden" class="previous-patient-id" value="" />
		<input type="button" class="admit-previous-patient right" value="Admit" />
	</div>
	
</div>
<div id="submit-new-patient">
	<input type="button" id="submit-new-patient" class="right" value="This is a new Patient">
</div>
<div class="clear"></div>

<?php }} ?>
