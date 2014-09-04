<?php /* Smarty version Smarty-3.1.19, created on 2014-08-25 21:15:38
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:98051478753efd68eda12e8-55303446%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c20f70f07f0476d61cc74c7519ce7167125ba3d' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/edit.tpl',
      1 => 1409021227,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98051478753efd68eda12e8-55303446',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53efd68eda2fc8_94681873',
  'variables' => 
  array (
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'siteUrl' => 0,
    'page' => 0,
    'id' => 0,
    'current_url' => 0,
    'dataArray' => 0,
    'key' => 0,
    'column' => 0,
    'data' => 0,
    'public_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53efd68eda2fc8_94681873')) {function content_53efd68eda2fc8_94681873($_smarty_tpl) {?><script>
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

	});
</script>

<form name="edit" id="edit" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="page" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['id']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />

	<table class="form">
	<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['dataArray']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
	<tr>
		<td ><?php echo htmlspecialchars(stringify($_smarty_tpl->tpl_vars['key']->value), ENT_QUOTES, 'UTF-8');?>
:</td>
		<td><input <?php if ($_smarty_tpl->tpl_vars['key']->value=="password") {?> type="password" <?php } else { ?> type="text" <?php }?> name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8');?>
" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['column']->value, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value, ENT_QUOTES, 'UTF-8');?>
" style="width:200px" /></td>
	
		<?php if ($_smarty_tpl->tpl_vars['key']->value=="password") {?>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?page=users&amp;action=reset_password&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['public_id']->value, ENT_QUOTES, 'UTF-8');?>
" class="button">Reset Password</a></td>
		<?php }?>
	</tr>
	<?php } ?>
<?php }} ?>
