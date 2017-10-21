<?php /* Smarty version Smarty-3.1.13, created on 2017-10-21 16:20:31
         compiled from "/Users/kemish/Sites/aptitudecare/admission/cms2/protected/tpl/_functions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12697412859ebc82fa260b8-65040030%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5a96c4fadd631f8d9ca7cc25a66987535d5c6e5' => 
    array (
      0 => '/Users/kemish/Sites/aptitudecare/admission/cms2/protected/tpl/_functions.tpl',
      1 => 1508618683,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12697412859ebc82fa260b8-65040030',
  'function' => 
  array (
    'html_options_cms' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    '_name' => 0,
    'id' => 0,
    'class' => 0,
    'style' => 0,
    'multiple' => 0,
    'set' => 0,
    'keyfield' => 0,
    'obj' => 0,
    'selected' => 0,
    'valfield' => 0,
    'other' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59ebc82fa64484_42319787',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59ebc82fa64484_42319787')) {function content_59ebc82fa64484_42319787($_smarty_tpl) {?><?php if (!function_exists('smarty_template_function_html_options_cms')) {
    function smarty_template_function_html_options_cms($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['html_options_cms']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<select name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8');?>
" id="<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['id']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_name']->value : $tmp), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['class']->value!=''){?> class="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['class']->value, ENT_QUOTES, 'UTF-8');?>
"<?php }?><?php if ($_smarty_tpl->tpl_vars['style']->value!=''){?> style="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['style']->value, ENT_QUOTES, 'UTF-8');?>
"<?php }?><?php if ($_smarty_tpl->tpl_vars['multiple']->value==true){?> multiple<?php }?>>
	<option value=''>Select...</option>
	<?php  $_smarty_tpl->tpl_vars["obj"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["obj"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['set']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["obj"]->key => $_smarty_tpl->tpl_vars["obj"]->value){
$_smarty_tpl->tpl_vars["obj"]->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['keyfield']->value!=''){?>
	<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['obj']->value->{$_smarty_tpl->tpl_vars['keyfield']->value}, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['obj']->value->{$_smarty_tpl->tpl_vars['keyfield']->value}==$_smarty_tpl->tpl_vars['selected']->value){?> selected<?php }?>>
	<?php }else{ ?>
	<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['obj']->value->pk(), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['obj']->value->pk()==$_smarty_tpl->tpl_vars['selected']->value){?> selected<?php }?>>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['valfield']->value!=''){?>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['obj']->value->{$_smarty_tpl->tpl_vars['valfield']->value}, ENT_QUOTES, 'UTF-8');?>

	<?php }else{ ?>
	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['obj']->value->getTitle(), ENT_QUOTES, 'UTF-8');?>

	<?php }?>
	<?php } ?>
	<?php if ($_smarty_tpl->tpl_vars['other']->value==true){?>
	<option value="_other">Other...</option>
	<?php }?>
</select>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php }} ?>