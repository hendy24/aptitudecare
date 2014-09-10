<?php /* Smarty version Smarty-3.1.19, created on 2014-09-09 15:40:29
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/assign_clinicians.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136360745253eba87fdb6089-30623399%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2b2da213b7c56af510b8cf5ab4ab6fbd79697eae' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/patients/assign_clinicians.tpl',
      1 => 1410298828,
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
    'siteUrl' => 0,
    'currentUrl' => 0,
    'clinicianTypes' => 0,
    'clinicianByType' => 0,
    'type' => 0,
    'key' => 0,
    'clinician' => 0,
    'c' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53eba87fdb8406_07949779')) {function content_53eba87fdb8406_07949779($_smarty_tpl) {?>
<h1>Assign Clinicians<br>
<span class="text-14">for</span> <br><span class="text-20"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->last_name, ENT_QUOTES, 'UTF-8');?>
</span></h1>
<br>

<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
" method="post" id="assign-clinicians">
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="assign_clinicians" />
	<input type="hidden" name="patient" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="currentUrl", value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['currentUrl']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<table class="form">
		<tr>
			<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinicianTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
			<?php  $_smarty_tpl->tpl_vars['clinician'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['clinician']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['clinicianByType']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['count']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['clinician']->key => $_smarty_tpl->tpl_vars['clinician']->value) {
$_smarty_tpl->tpl_vars['clinician']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['clinician']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['count']['iteration']++;
?> 
			<?php if ($_smarty_tpl->tpl_vars['type']->value->name==$_smarty_tpl->tpl_vars['key']->value) {?>
			<td><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->description, ENT_QUOTES, 'UTF-8');?>
:</strong></td>
			<td>	
				<select name="clinician_id[<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8');?>
]" id="">
					<option value="">Select...</option>
				<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinician']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->user_id, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['type']->value->name==$_smarty_tpl->tpl_vars['c']->value->name) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
				</select>
			</td>
			<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['count']['iteration']%2==0) {?>
				</tr>
				<tr>
			<?php }?>
			<?php }?>
			<?php } ?>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="4" class="text-right">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" class="text-right">
				<input type="submit" name="submit" value="Save" />
			</td>
		</tr>
	</table>
</form>
<?php }} ?>
