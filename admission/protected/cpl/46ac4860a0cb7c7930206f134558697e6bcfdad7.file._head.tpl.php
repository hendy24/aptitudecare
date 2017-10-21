<?php /* Smarty version Smarty-3.1.13, created on 2017-10-21 16:20:31
         compiled from "/Users/kemish/Sites/aptitudecare/admission/cms2/protected/tpl/_head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:122574217859ebc82fa052b5-97194479%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46ac4860a0cb7c7930206f134558697e6bcfdad7' => 
    array (
      0 => '/Users/kemish/Sites/aptitudecare/admission/cms2/protected/tpl/_head.tpl',
      1 => 1508618683,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122574217859ebc82fa052b5-97194479',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'JQUERY_URL' => 0,
    'ENGINE_URL' => 0,
    'SITE_URL' => 0,
    'CDN_URL' => 0,
    'SECURE_URL' => 0,
    'CDN_ENGINE_URL' => 0,
    'metatags' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59ebc82fa1e8b8_31633180',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59ebc82fa1e8b8_31633180')) {function content_59ebc82fa1e8b8_31633180($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['JQUERY_URL']->value!=''){?>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['JQUERY_URL']->value, ENT_QUOTES, 'UTF-8');?>
"></script>
<?php }?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('javascript', array()); $_block_repeat=true; echo smarty_javascript(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

var ENGINE_URL = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
';
var SITE_URL = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
';
var CDN_URL = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['CDN_URL']->value, ENT_QUOTES, 'UTF-8');?>
';
var SECURE_URL = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SECURE_URL']->value, ENT_QUOTES, 'UTF-8');?>
';
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_javascript(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['CDN_ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/helpers.js"></script>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['cms_template_dir']->value)."/_functions.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<_TITLE_>
<meta name="keywords" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['metatags']->value->meta_keywords, ENT_QUOTES, 'UTF-8');?>
" />
<meta name="description" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['metatags']->value->meta_description, ENT_QUOTES, 'UTF-8');?>
" />
<meta name="robots" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['metatags']->value->meta_robots, ENT_QUOTES, 'UTF-8');?>
" />
<_HEAD_><?php }} ?>