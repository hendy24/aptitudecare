<?php /* Smarty version Smarty-3.1.19, created on 2014-08-19 22:15:27
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:150095780053efd24962d827-12486009%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19a7441c8c26734fe1e59dce44494a9ebebde715' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/data/manage.tpl',
      1 => 1408508126,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '150095780053efd24962d827-12486009',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53efd24965b899_13776906',
  'variables' => 
  array (
    'headerTitle' => 0,
    'siteUrl' => 0,
    'type' => 0,
    'data' => 0,
    'key' => 0,
    'dataset' => 0,
    'k' => 0,
    'd' => 0,
    'frameworkImg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53efd24965b899_13776906')) {function content_53efd24965b899_13776906($_smarty_tpl) {?><h1><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8');?>
</h1>

<div class="button left"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?page=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8');?>
&amp;action=add">Add New</a></div>
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
			<?php if (!empty($_smarty_tpl->tpl_vars['data']->value[0])) {?>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?page=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8');?>
&amp;action=edit&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['dataset']->value['public_id'], ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/pencil.png" alt=""></a></td>
			<?php }?>
		</tr>
	<?php } ?>
	

</table>
<?php }} ?>
