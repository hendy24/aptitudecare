<?php /* Smarty version Smarty-3.1.19, created on 2014-08-21 19:16:04
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1882783853f3888e885373-93276799%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a731bb4e9d3d6700820c1fc081aa1903ec81a871' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/add.tpl',
      1 => 1408670163,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1882783853f3888e885373-93276799',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f3888e8902e2_76835995',
  'variables' => 
  array (
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'headerTitle' => 0,
    'siteUrl' => 0,
    'page' => 0,
    'isMicro' => 0,
    'current_url' => 0,
    'columns' => 0,
    'column' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f3888e8902e2_76835995')) {function content_53f3888e8902e2_76835995($_smarty_tpl) {?><script>
	$(document).ready(function() {
		$("#phone").mask("(999) 999-9999");
		$("#fax").mask("(999) 999-9999");
		$("#zip").mask("99999");

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
				$("#state").val(suggestion.data);
			}
		});

		$("#healthcare-facility-search").autocomplete({
			serviceUrl: SiteUrl,
			params: { 
				module: 'HomeHealth',
				page: 'HealthcareFacilities',
				action: 'searchFacilityName'
				//location: $("#admit-request-location option:selected").val() 
			}, minChars: 3,
			onSelect: function (suggestion) {
				$("#healthcare-facility-id").val(suggestion.data);
			}

		});

		$("#add").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				phone: "required",
				email: "email",
				city: "required",
				state: "required",
				zip: "required",
				healthcare_facility: "required"
			}
		}); 

	});
</script>

<h1>Add a new <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8');?>
</h1>
<br>
<form name="add" id="add" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="page" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="isMicro" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['isMicro']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />

	<table class="form">
	<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['columns']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value) {
$_smarty_tpl->tpl_vars['column']->_loop = true;
?>
	<tr>
		<td ><?php echo htmlspecialchars(stringify($_smarty_tpl->tpl_vars['column']->value), ENT_QUOTES, 'UTF-8');?>
:</td>
		<td><input <?php if ($_smarty_tpl->tpl_vars['column']->value=="password"||$_smarty_tpl->tpl_vars['column']->value=="verify_password") {?> type="password" <?php } else { ?> type="text" <?php }?> name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['column']->value, ENT_QUOTES, 'UTF-8');?>
" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['column']->value, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" /></td>
	</tr>
	<?php } ?>


		
<?php }} ?>
