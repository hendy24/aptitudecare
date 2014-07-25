<?php /* Smarty version Smarty-3.1.19, created on 2014-07-22 16:04:06
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:187468342353cedfd69de4b0-26990333%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef0215baeceb0e0c18d35d2bd68c2a33582fea52' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/nav.tpl',
      1 => 1405981054,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187468342353cedfd69de4b0-26990333',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'siteUrl' => 0,
    'module' => 0,
    'locations' => 0,
    'currentUrl' => 0,
    'l' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53cedfd69fc6a4_13195558',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53cedfd69fc6a4_13195558')) {function content_53cedfd69fc6a4_13195558($_smarty_tpl) {?><nav>
	<ul>
		<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
?module=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module']->value, ENT_QUOTES, 'UTF-8');?>
">Home</a></li>
		<li>Locations
			<ul>
				<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['currentUrl']->value, ENT_QUOTES, 'UTF-8');?>
&location=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['l']->value->public_id, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['l']->value->name, ENT_QUOTES, 'UTF-8');?>
</a></li>
				<?php } ?>
			</ul>
		</li>
		<li>Admissions
			<ul>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
?module=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module']->value, ENT_QUOTES, 'UTF-8');?>
&page=admission&action=new_admit">New Admission</a></li>	
			</ul>
		</li>
		<li>Data
			<ul>
				<li>Case Managers</li>
				<li>Clinicians</li>
				<li>Healthcare Facilities</li>
				<li>Users</li>
			</ul>
		</li>
	</ul>
</nav>
<?php }} ?>
