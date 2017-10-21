<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:15:19
         compiled from "/home/aptitude/dev/protected/tpl/elements/detail_options.tpl" */ ?>
<?php /*%%SmartyHeaderCode:953334835563ad7d7ce52c4-18995008%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7337d4afb6f13a0f810a0a00dc8970215e0a02ca' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/elements/detail_options.tpl',
      1 => 1400775304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '953334835563ad7d7ce52c4-18995008',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'filterByOpts' => 0,
    'k' => 0,
    'filterby' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7d7cf41d0_30344416',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7d7cf41d0_30344416')) {function content_563ad7d7cf41d0_30344416($_smarty_tpl) {?><div id="admission-bar">
	<div class="left">
		<strong>View Details for:</strong>
		<select id="filterby">
			<option value="">Select an option...</option>
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['filterByOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['filterby']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php } ?>
		</select>
	</div><?php }} ?>