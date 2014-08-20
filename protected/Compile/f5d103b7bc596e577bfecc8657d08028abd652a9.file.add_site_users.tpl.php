<?php /* Smarty version Smarty-3.1.19, created on 2014-08-19 20:16:48
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/add_site_users.tpl" */ ?>
<?php /*%%SmartyHeaderCode:92442006553f3db348fa887-45551066%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f5d103b7bc596e577bfecc8657d08028abd652a9' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/add_site_users.tpl',
      1 => 1408501005,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92442006553f3db348fa887-45551066',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53f3db3492e8f5_21913933',
  'variables' => 
  array (
    'headerTitle' => 0,
    'siteUrl' => 0,
    'current_url' => 0,
    'columns' => 0,
    'column' => 0,
    'available_locations' => 0,
    'loc' => 0,
    'k' => 0,
    'groups' => 0,
    'group' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f3db3492e8f5_21913933')) {function content_53f3db3492e8f5_21913933($_smarty_tpl) {?><script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");
		$("#additional-locations").hide();

		$("#add-user").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				email: {
					required: true,
					email: true
				},
				password: "required",
				verify_password: {
					equalTo: "#password",
					required: true
				},
				group: "required",
				default_location: "required"
			}
		});

		$("#default_location").change(function() {
			$("#additional-locations").show();
			var location = $(this).val();
			$("#" + location).prop("checked", true);

		});

	});
	
</script>


<h1>Add a new <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8');?>
</h1>
<br>
<form name="add_user" id="add-user" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="page" value="Users" />
	<input type="hidden" name="action" value="submitAddUser" />
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />

	<table class="form-table">
		
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
	
	<tr>
		<td>Default Location:</td>
		<td>
			<select name="default_location" id="default_location">
				<option value="">Select a location...</option>
				<?php  $_smarty_tpl->tpl_vars['loc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['available_locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['loc']->key => $_smarty_tpl->tpl_vars['loc']->value) {
$_smarty_tpl->tpl_vars['loc']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->public_id, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr id="additional-locations">
		<td style="vertical-align:top">Additional Locations:</td>
		<td>
		<?php  $_smarty_tpl->tpl_vars['loc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loc']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['available_locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['loc']->key => $_smarty_tpl->tpl_vars['loc']->value) {
$_smarty_tpl->tpl_vars['loc']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['loc']->key;
?>
			<input type="checkbox" name="user_location[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
]" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" /> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['loc']->value->name, ENT_QUOTES, 'UTF-8');?>
<br>
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td>Group:</td>
		<td>
			<select name="group" id="group">
				<option value="">Select a group role...</option>
				<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value->id, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['group']->value->description, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		</td>
		
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
		
	</table>
</form>
<?php }} ?>
