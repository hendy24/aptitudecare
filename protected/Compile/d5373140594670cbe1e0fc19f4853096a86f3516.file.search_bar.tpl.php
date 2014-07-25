<?php /* Smarty version Smarty-3.1.19, created on 2014-07-22 16:04:06
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/search_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:75154039353cedfd6a0a909-91180286%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5373140594670cbe1e0fc19f4853096a86f3516' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/search_bar.tpl',
      1 => 1405980379,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '75154039353cedfd6a0a909-91180286',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'modules' => 0,
    'm' => 0,
    'module' => 0,
    'headerTitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53cedfd6a1f775_88324635',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53cedfd6a1f775_88324635')) {function content_53cedfd6a1f775_88324635($_smarty_tpl) {?><div id="search-header">
	<div id="modules">
		Module: <select name="module" id="module">
			<?php  $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['m']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['modules']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['m']->key => $_smarty_tpl->tpl_vars['m']->value) {
$_smarty_tpl->tpl_vars['m']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['m']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['module']->value==$_smarty_tpl->tpl_vars['m']->value->name) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['m']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php } ?>
		</select>
	</div>
	<h1><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8');?>
</h1>
	<div id="patient-search">
		Search: <input id="patient-search-box" type="text" name="patient_search" value="" placeholder="Patient Name"/>
		<input  type="submit" id="submit-patient-name" value="Go">
	</div>
</div><?php }} ?>
