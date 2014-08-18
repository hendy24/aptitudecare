<?php /* Smarty version Smarty-3.1.19, created on 2014-08-16 15:36:32
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135694955753efcc47d2d9b0-65728843%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6256cad53cac4feef7abab13b2973dc4bdf7d04a' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/case_managers/manage.tpl',
      1 => 1408224991,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135694955753efcc47d2d9b0-65728843',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53efcc47d50b48_85198781',
  'variables' => 
  array (
    'data' => 0,
    'key' => 0,
    'dataset' => 0,
    'k' => 0,
    'd' => 0,
    'siteUrl' => 0,
    'frameworkImg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53efcc47d50b48_85198781')) {function content_53efcc47d50b48_85198781($_smarty_tpl) {?><h1>Case Managers</h1>

<table class="form-table">
	<tr>
		<?php  $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['key']->_loop = false;
 $_from = array_keys($_smarty_tpl->tpl_vars['data']->value[0]); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['key']->key => $_smarty_tpl->tpl_vars['key']->value) {
$_smarty_tpl->tpl_vars['key']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['key']->value!="public_id") {?>
		<th style="width: auto; padding: 6px 35px"><?php echo htmlspecialchars(stringify($_smarty_tpl->tpl_vars['key']->value), ENT_QUOTES, 'UTF-8');?>
</th>
		<?php }?>
		<?php } ?>
		<td>&nbsp;</td>
	</tr>

	<?php  $_smarty_tpl->tpl_vars['dataset'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dataset']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dataset']->key => $_smarty_tpl->tpl_vars['dataset']->value) {
$_smarty_tpl->tpl_vars['dataset']->_loop = true;
?>
		<tr>
			<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['dataset']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['d']->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['k']->value!="public_id") {?>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value, ENT_QUOTES, 'UTF-8');?>
</td>
			<?php }?>
			<?php } ?>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=case_managers&amp;action=edit&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dataset']->value['public_id'], ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/pencil.png" alt=""></a></td>
		</tr>
	<?php } ?>
	

</table>
<?php }} ?>
