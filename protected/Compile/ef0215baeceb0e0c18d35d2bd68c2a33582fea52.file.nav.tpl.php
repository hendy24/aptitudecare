<?php /* Smarty version Smarty-3.1.19, created on 2014-08-16 16:10:21
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:196237096053d29cca6c4164-29691226%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef0215baeceb0e0c18d35d2bd68c2a33582fea52' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/elements/nav.tpl',
      1 => 1408227020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196237096053d29cca6c4164-29691226',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d29cca6cc8f5_37744421',
  'variables' => 
  array (
    'siteUrl' => 0,
    'module' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d29cca6cc8f5_37744421')) {function content_53d29cca6cc8f5_37744421($_smarty_tpl) {?><nav>
	<ul>
		<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
">Home</a></li>
		<li>Admissions
			<ul>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
?module=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module']->value, ENT_QUOTES, 'UTF-8');?>
&amp;page=admissions&amp;action=new_admit">New Admission</a></li>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
?module=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module']->value, ENT_QUOTES, 'UTF-8');?>
&amp;page=admissions&amp;action=pending_admits">Pending Admissions</a></li>
			</ul>
		</li>
		<li>Discharges
			<ul>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=discharges&amp;action=manage">Manage Discharges</a></li>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=discharges&amp;action=schedule">Schedule Discharges</a></li>			
			</ul>
		</li>
		<li>Data
			<ul>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=data&amp;action=manage&amp;type=case_managers">Case Managers</a></li>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=data&amp;action=manage&amp;type=home_health_clinicians">Clinicians</a></li>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=data&amp;action=manage&amp;type=healthcare_facilities">Healthcare Facilities</a></li>
				<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=data&amp;action=manage&amp;type=site_users">Users</a></li>
			</ul>
		</li>
	</ul>
</nav>
<?php }} ?>
