<?php /* Smarty version Smarty-3.1.19, created on 2014-09-03 18:05:35
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/search_bar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:174941554353d29cd575b286-35416349%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5373140594670cbe1e0fc19f4853096a86f3516' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/search_bar.tpl',
      1 => 1409789134,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '174941554353d29cd575b286-35416349',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d29cd5774699_62360065',
  'variables' => 
  array (
    'modules' => 0,
    'm' => 0,
    'module' => 0,
    'locations' => 0,
    'location' => 0,
    'input' => 0,
    'areas' => 0,
    'area' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d29cd5774699_62360065')) {function content_53d29cd5774699_62360065($_smarty_tpl) {?><div id="search-header">
	
	<?php if (count($_smarty_tpl->tpl_vars['modules']->value)>1) {?>
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
	<?php }?>
	
	<div id="locations">
		<select name="location" id="location">
		<?php  $_smarty_tpl->tpl_vars['location'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['location']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['location']->key => $_smarty_tpl->tpl_vars['location']->value) {
$_smarty_tpl->tpl_vars['location']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['input']->value->location)) {?><?php if ($_smarty_tpl->tpl_vars['location']->value->public_id==$_smarty_tpl->tpl_vars['input']->value->location) {?> selected<?php }?><?php }?>><h1><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->name, ENT_QUOTES, 'UTF-8');?>
</h1></option>
		<?php } ?>
		</select>
	</div>
	
	
	<div id="areas">
		Area: <select name="areas" id="area">
			<?php  $_smarty_tpl->tpl_vars['area'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['area']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['areas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['area']->key => $_smarty_tpl->tpl_vars['area']->value) {
$_smarty_tpl->tpl_vars['area']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['area']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['input']->value->area)) {?><?php if ($_smarty_tpl->tpl_vars['area']->value->public_id==$_smarty_tpl->tpl_vars['input']->value->area) {?> selected<?php }?><?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['area']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php } ?>
		</select>
	</div>
</div>
<?php }} ?>
