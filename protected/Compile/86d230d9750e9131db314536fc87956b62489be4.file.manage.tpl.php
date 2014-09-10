<?php /* Smarty version Smarty-3.1.19, created on 2014-09-09 17:51:50
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/clinicians/manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:171946654653d846287281c1-21603631%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86d230d9750e9131db314536fc87956b62489be4' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/clinicians/manage.tpl',
      1 => 1410306709,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '171946654653d846287281c1-21603631',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d84628729f46_68592116',
  'variables' => 
  array (
    'siteUrl' => 0,
    'locations' => 0,
    'location' => 0,
    'input' => 0,
    'clinicianOptions' => 0,
    'type' => 0,
    'filter' => 0,
    'clinicianTypes' => 0,
    'clinicians' => 0,
    'clinician' => 0,
    'frameworkImg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d84628729f46_68592116')) {function content_53d84628729f46_68592116($_smarty_tpl) {?><script>
	$(document).ready(function() {
		$("#filter").change(function(e) {
			e.preventDefault();
			if ($(this).val() == 'all') {
				window.location.href = SiteUrl + "/?module=HomeHealth&page=clinicians&action=manage";
			} else {
				window.location.href = SiteUrl + "/?module=HomeHealth&page=clinicians&action=manage&filter=" + $("#filter option:selected").val();
			}
			
		});
	}); 
</script>


<div id="modules" class="button left"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?page=users&amp;action=add">Add New</a></div>
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
	<h2>Manage Clinicians</h2>
</div>

<div id="areas">
	<select name="filter" id="filter">
		<option value="all">All</option>
		<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinicianOptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
		<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->name, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['type']->value->name==$_smarty_tpl->tpl_vars['filter']->value) {?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->description, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
	</select>
</div>

<br><br>


<table class="view">
	<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinicianTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
	<tr>
		<th colspan="5" class="text-center"><h3><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['type']->value->description, ENT_QUOTES, 'UTF-8');?>
</h3></th>
	</tr>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['clinician'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['clinician']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['clinicians']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['clinician']->key => $_smarty_tpl->tpl_vars['clinician']->value) {
$_smarty_tpl->tpl_vars['clinician']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['type']->value->name==$_smarty_tpl->tpl_vars['clinician']->value->name) {?>
	<tr>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clinician']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clinician']->value->email, ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clinician']->value->phone, ENT_QUOTES, 'UTF-8');?>
</td>
		<td class="text-center">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?page=users&amp;action=edit&amp;id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clinician']->value->public_id, ENT_QUOTES, 'UTF-8');?>
">
				<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/pencil.png" alt="">
			</a>
		</td>
	</tr>
	<?php }?>
	<?php } ?>
	<tr>
		<td style="border-bottom:none;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-bottom:none;">&nbsp;</td>
	</tr>
	<?php } ?>
</table>
<?php }} ?>
