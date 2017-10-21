<?php /* Smarty version Smarty-3.1.13, created on 2017-10-21 16:20:31
         compiled from "/Users/kemish/Sites/aptitudecare/admission/protected/tpl/_feedback.tpl" */ ?>
<?php /*%%SmartyHeaderCode:119998320759ebc82fa7d672-76331011%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '95dc5f72deefba1f57e35e61e3e596643cee7238' => 
    array (
      0 => '/Users/kemish/Sites/aptitudecare/admission/protected/tpl/_feedback.tpl',
      1 => 1454956029,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119998320759ebc82fa7d672-76331011',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'feedback' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59ebc82faa2369_24469235',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59ebc82faa2369_24469235')) {function content_59ebc82faa2369_24469235($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

var why = "";
<?php if ($_smarty_tpl->tpl_vars['feedback']->value->wasWarning()){?>
<?php  $_smarty_tpl->tpl_vars["msg"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["msg"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['feedback']->value->getVals("warning"); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["msg"]->key => $_smarty_tpl->tpl_vars["msg"]->value){
$_smarty_tpl->tpl_vars["msg"]->_loop = true;
?>
	why += "- <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
\n";
<?php } ?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['feedback']->value->wasError()){?>
<?php  $_smarty_tpl->tpl_vars["msg"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["msg"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['feedback']->value->getVals("error"); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["msg"]->key => $_smarty_tpl->tpl_vars["msg"]->value){
$_smarty_tpl->tpl_vars["msg"]->_loop = true;
?>
	why += "- <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
\n";
<?php } ?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['feedback']->value->wasConf()){?>
<?php  $_smarty_tpl->tpl_vars["msg"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["msg"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['feedback']->value->getVals("conf"); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["msg"]->key => $_smarty_tpl->tpl_vars["msg"]->value){
$_smarty_tpl->tpl_vars["msg"]->_loop = true;
?>
	why += "- <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
\n";
<?php } ?>
<?php }?>
if (why != "") {
	jAlert(why, "Attention Required");
}
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['feedback']->value->clear(), ENT_QUOTES, 'UTF-8');?>
<?php }} ?>