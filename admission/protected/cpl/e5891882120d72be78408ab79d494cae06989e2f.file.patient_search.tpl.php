<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:15:19
         compiled from "/home/aptitude/dev/protected/tpl/patient/patient_search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:634128597563ad7d7c78656-34011160%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5891882120d72be78408ab79d494cae06989e2f' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/patient_search.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '634128597563ad7d7c78656-34011160',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7d7c7b4e5_16656337',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7d7c7b4e5_16656337')) {function content_563ad7d7c7b4e5_16656337($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$("#submit-button").click(function(e) {
		e.preventDefault();
		window.location = SITE_URL + "/?page=patient&action=search_results&patient_name=" + $("#patient-search").val();
	});
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<form name="patient-search" accept="post">
	<div id="report-search-box">
		<input type="text" name="search_patient" id="patient-search" placeholder="Enter the patients' name" size="30" /> <input type="submit" value="Search" id="submit-button" />
	</div>
</form><?php }} ?>