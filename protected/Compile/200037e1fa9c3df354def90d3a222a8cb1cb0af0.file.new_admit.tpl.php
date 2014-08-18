<?php /* Smarty version Smarty-3.1.19, created on 2014-08-11 16:57:12
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/new_admit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:206459935753d83823960163-58486267%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '200037e1fa9c3df354def90d3a222a8cb1cb0af0' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admissions/new_admit.tpl',
      1 => 1407797820,
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
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d8382398bc96_00269044')) {function content_53d8382398bc96_00269044($_smarty_tpl) {?><script>
	$(document).ready(function() {
		// $('#new_admission').validate();
		$('#admit-request-phone').mask("(999) 999-9999");
		$('#admit-request-zip').mask("99999");


		$("#admit-from-search").autocomplete({
			serviceUrl: SiteUrl,
			params: { 
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName',
				location: $("#admit-request-location option:selected").val() 
			}, minChars: 3,
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
			}, minChars: 3,
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


	});
</script>





<h1>New Admission Request</h1>

<form name="new_admission" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="module" value="HomeHealth" />
	<input type="hidden" name="page" value="admissions" />
	<input type="hidden" name="action" value="new_admit" />
	<input type="hidden" name="submit" value="true">
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="submit" value="true" />
	<table class="form-table">
		<tr>
			<td><strong>Admit Date:</strong></td>
			<td colspan="2"><strong>Location:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="schedule-datetime" id="datepicker" name="admit_date" value="" required /></td>
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
			<td>
				<input type="text" id="admit-from-search" style="width: 250px" required />
				<input type="hidden" name="admit_from" id="admit-from" />
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
			<td><input type="text" id="admit-request-zip" name="zip" /></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><input type="submit" value="Search" id="admit-request-search" /></td>
		</tr>
	</table>
</form>


<?php }} ?>
