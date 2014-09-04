<?php /* Smarty version Smarty-3.1.19, created on 2014-09-03 21:02:56
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/locations/census.tpl" */ ?>
<?php /*%%SmartyHeaderCode:169583525353f52fd60e4ab2-30171532%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '438d8f73f158f5d12f9abcb5dd48f2a26776506c' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/locations/census.tpl',
      1 => 1409799774,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '169583525353f52fd60e4ab2-30171532',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f52fd60edd84_17210019',
  'variables' => 
  array (
    'patients' => 0,
    'patient' => 0,
    'patientTools' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f52fd60edd84_17210019')) {function content_53f52fd60edd84_17210019($_smarty_tpl) {?><script>
	$(document).ready(function() {
		var url = SiteUrl + "/?module=HomeHealth&page=locations&action=census";

		$('#area').change(function() {
			window.location = url + "&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#patient-name").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=patient_name";
			window.location.href = redirectTo;
		});

		$("#admit-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=admit_date";
			window.location.href = redirectTo;
		});

		$("#discharge-date").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=discharge_date";
			window.location.href = redirectTo;
		});

		$("#pcp").click(function(e) {
			e.preventDefault();
			redirectTo = url + "&order_by=pcp";
			window.location.href = redirectTo;
		});


		$("#search-patient-name").keypress(function(e) {
			if (e.which == 13) {
				e.preventDefault();
				window.location.href = SiteUrl + "/?page=main_page&action=search_results&term=" + $(this).val();
			}
			
		});

	});
</script>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<h1 style="font-weight: normal">Census</h1>
<div id="patient-search">
	Search: <input type="text" placeholder="Type patient name (last, first or first last)" id="search-patient-name" />
</div>

<br>
<table class="view">
	<tr>
		<th><a href="" id="patient-name">Patient Name</a></th>
		<th></th>
		<th><a href="" id="admit-date">Admission Date</a></th>
		<th><a href="" id="discharge-date">Discharge Date</a></th>
		<th><a href="" id="pcp">Primary Care Physician</a></th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['patient'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['patient']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['patients']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['patient']->key => $_smarty_tpl->tpl_vars['patient']->value) {
$_smarty_tpl->tpl_vars['patient']->_loop = true;
?>
	<tr <?php if ($_smarty_tpl->tpl_vars['patient']->value->datetime_discharge!='') {?>class="background-red"<?php }?>>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patientTools']->value->menu($_smarty_tpl->tpl_vars['patient']->value), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars(display_date($_smarty_tpl->tpl_vars['patient']->value->datetime_admit), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars((($tmp = @display_date($_smarty_tpl->tpl_vars['patient']->value->datetime_discharge))===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->physician_name, ENT_QUOTES, 'UTF-8');?>
</td>
	</tr>
	<?php } ?>
</table><?php }} ?>
