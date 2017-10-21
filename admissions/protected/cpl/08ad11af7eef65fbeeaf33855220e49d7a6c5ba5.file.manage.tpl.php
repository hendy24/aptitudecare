<?php /* Smarty version Smarty-3.1.13, created on 2014-06-10 12:15:02
         compiled from "/home/aptitude/dev/protected/tpl/siteUser/manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19425216053974b263d33e9-69481336%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '08ad11af7eef65fbeeaf33855220e49d7a6c5ba5' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/siteUser/manage.tpl',
      1 => 1401406972,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19425216053974b263d33e9-69481336',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'facilities' => 0,
    'f' => 0,
    'facility' => 0,
    'users' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_53974b2641e1f0_10052287',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53974b2641e1f0_10052287')) {function content_53974b2641e1f0_10052287($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>"Manage Users"),$_smarty_tpl);?>

<h1 class="text-center">Manage Users</h1>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$('#selectFacility').change(function() {
		document.location = SITE_URL + '/?page=siteUser&action=manage&facility=' + $('#selectFacility option:selected').val();
	});
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<div class="left">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=siteUser&action=add" class="button">New User</a>
</div>
<div class="right">
	<select id="selectFacility" name="facilities">
		<option value="">Select a facility...</option>
		<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['facility']->value->name==$_smarty_tpl->tpl_vars['f']->value->name){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
	</select>
</div>
<br />
<br />
<br />
<br />
<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Name</th>
		<th>Username (Email Address)</th>
		<th>Phone</th>
		<th>Role</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
?>
		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=siteUser&action=edit&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&user=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->last, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->first, ENT_QUOTES, 'UTF-8');?>
</a></td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->email, ENT_QUOTES, 'UTF-8');?>
</td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->phone, ENT_QUOTES, 'UTF-8');?>
</td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->description, ENT_QUOTES, 'UTF-8');?>
</td>
		</tr>
	<?php } ?>
</table><?php }} ?>