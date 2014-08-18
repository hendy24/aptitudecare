<?php /* Smarty version Smarty-3.1.19, created on 2014-08-13 12:15:07
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/assign_clinicians.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136360745253eba87fdb6089-30623399%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2b2da213b7c56af510b8cf5ab4ab6fbd79697eae' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/assign_clinicians.tpl',
      1 => 1407953675,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '136360745253eba87fdb6089-30623399',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53eba87fdb8406_07949779',
  'variables' => 
  array (
    'patient' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53eba87fdb8406_07949779')) {function content_53eba87fdb8406_07949779($_smarty_tpl) {?>
<h1>Assign Clinicians<br>
<span class="text-14">for</span> <br><span class="text-20"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->last_name, ENT_QUOTES, 'UTF-8');?>
</span></h1><?php }} ?>
