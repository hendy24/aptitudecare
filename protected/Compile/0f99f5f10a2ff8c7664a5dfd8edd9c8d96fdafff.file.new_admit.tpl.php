<?php /* Smarty version Smarty-3.1.19, created on 2014-07-29 18:01:08
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admission/new_admit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184592649753d29cca6d8fb9-92716607%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f99f5f10a2ff8c7664a5dfd8edd9c8d96fdafff' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/admission/new_admit.tpl',
      1 => 1406678467,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184592649753d29cca6d8fb9-92716607',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d29cca6e5b01_16334391',
  'variables' => 
  array (
    'siteUrl' => 0,
    'locations' => 0,
    'location' => 0,
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d29cca6e5b01_16334391')) {function content_53d29cca6e5b01_16334391($_smarty_tpl) {?><h1>New Admission Request</h1>

<form name="new_admission" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="module" value="HomeHealth" />
	<input type="hidden" name="page" value="admission" />
	<input type="hidden" name="action" value="new_admit" />
	<input type="hidden" name="submit" value="true" />
	<table class="form-table">
		<tr>
			<td><strong>Admit Date:</strong></td>
			<td colspan="2"><strong>Location:</strong></td>
		</tr>
		<tr>
			<td><input type="text" class="schedule-datetime" id="datepicker" value="" /></td>
			<td colspan="2">
				<select name="location" id="admit-request-location">
					<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>Admit From:</strong></td>
		</tr>
		<tr>
			<td colspan="3">
				<input type="text" id="admit-location-search" style="width: 300px" required />
				<input type="hidden" name="admit_from" id="admit-from" />
			</td>
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
			<td><input type="text" id="admit-request-last-name" name="last_name" style="width:200px;" required /></td>
			<td><input type="text" id="admit-request-first-name" name="first_name" style="width:150px;" required /></td>
			<td><input type="text" id="admit-request-middle-name" name="middle_name" /></td>
		</tr>
		<tr>
			<td colspan="2">Address</td>
			<td>Phone</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" id="admit-request-address" name="address" style="width: 350px;" /></td>
			<td><input type="text" id="admit-request-phone" name="phone" required /></td>
		</tr>
		<tr>
			<td>City</td>
			<td>State</td>
			<td>Zip</td>
		</tr>
		<tr>
			<td><input type="text" id="admit-request-city" name="city" style="width:180px;" /></td>
			<td>
				<input type="text" id="admit-request-state" style="width: 120px" />
				<input type="hidden" id="state" name="state" />
			</td>
			<td><input type="text" id="admit-request-zip" name="zip" /></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><input type="submit" value="Submit" id="admit-request-search" /></td>
		</tr>
	</table>
</form>

<script>
	$(document).ready(function() {
		$('#new_admission').validate();

		$('#admit-request-phone').mask("(999) 999-9999");
		$('#admit-request-zip').mask("99999");


		var suggestions = [];
		$("#admit-location-search").autocomplete({
			minLength: 4,
			source: function(request, response) {
				$.getJSON(SiteUrl,
					{ 	page: 'HealthcareFacility', 
						action: 'searchFacilityName', 
						term: request.term, location: $('#admit-request-location option:selected').val() 
					}, function (json) {
						$.each(json, function (i, val) {
							var obj = new Object;
							obj.value = val.id;
							obj.label = val.name + ' (' + val.state + ')';
							suggestions.push(obj);
						});
						
					}
				);
			}
			,select: function (e, ui) {
				e.preventDefault();
				$("#admit-from").val(ui.item.value);
				e.target.value = ui.item.label;	
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
				value: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
",
				label: "(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
) <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
"
			}
			<?php if ($_smarty_tpl->tpl_vars['state']->last!=true) {?>,<?php }?>
		<?php }?>
		<?php } ?>
		];


		$("#admit-request-state").autocomplete({
			minLength: 0,
			source: states,
			focus: function (event, ui) {
				$("#admit-request-state").val(ui.item.label);
				return false;
			},
			select: function (event, ui) {
				$("#admit-request-state").val(ui.item.label);
				$("#state").val(ui.item.value);
				return false;
			}
		});




	});
</script>
<?php }} ?>
