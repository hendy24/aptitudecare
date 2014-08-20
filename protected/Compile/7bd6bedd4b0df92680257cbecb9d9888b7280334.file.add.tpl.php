<?php /* Smarty version Smarty-3.1.19, created on 2014-08-19 22:41:59
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:70796179553d83999a2e8d6-78784358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7bd6bedd4b0df92680257cbecb9d9888b7280334' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/healthcare_facilities/add.tpl',
      1 => 1408509651,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70796179553d83999a2e8d6-78784358',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d83999a303d1_35490988',
  'variables' => 
  array (
    'headerTitle' => 0,
    'siteUrl' => 0,
    'current_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d83999a303d1_35490988')) {function content_53d83999a303d1_35490988($_smarty_tpl) {?><script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");

	});
	
</script>


<h1>Add a new <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8');?>
</h1>
<br>
<form name="add_user" id="add-user" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">
	<input type="hidden" name="page" value="healthcare_facilities" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['current_url']->value, ENT_QUOTES, 'UTF-8');?>
" />

	<table class="form-table">
		

	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
		
	</table>
</form>
<?php }} ?>
