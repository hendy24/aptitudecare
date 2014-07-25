<?php /* Smarty version Smarty-3.1.19, created on 2014-07-24 18:17:19
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/homehealth/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192219721053cede8c518496-10261958%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '531b047523cea0221dbfee75e8cb6f2b85c8f9d7' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/protected/Views/homehealth/index.tpl',
      1 => 1406247437,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192219721053cede8c518496-10261958',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53cede8c5214d8_89097113',
  'variables' => 
  array (
    'siteUrl' => 0,
    'location' => 0,
    'previousWeekSeed' => 0,
    'week' => 0,
    'nextWeekSeed' => 0,
    'day' => 0,
    'admitsByDate' => 0,
    'admits' => 0,
    'admit' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53cede8c5214d8_89097113')) {function content_53cede8c5214d8_89097113($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/Vendors/Smarty-3.1.19/libs/plugins/function.cycle.php';
?><div id="date-header">
	<div class="date-header-img">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;location=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['previousWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="left" src="/img/icons/prev-icon.png" /></a>
	</div>
	<div class="date-header-text-center">
		<h2><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[0],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
 &ndash; <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[6],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
</h2>
	</div>
	<div class="date-header-img">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;location=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nextWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="right" src="/img/icons/next-icon.png" /></a>		
	</div>	
</div>

<div class="clear"></div>

<div class="location-container">
	
	<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
	<div class="location-day-text <?php if ($_smarty_tpl->tpl_vars['day']->last) {?>location-day-text-last<?php }?>">

		<h3><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a, %b %e"), ENT_QUOTES, 'UTF-8');?>
</h3>

	</div>
	<?php } ?>

	<div class="location-admits">
		<input type="hidden" name="location" id="location-id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->id, ENT_QUOTES, 'UTF-8');?>
" />
		<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
			<div class="location-day-box location-day-box-admit <?php if ($_smarty_tpl->tpl_vars['day']->last) {?>location-day-box-last<?php }?> <?php echo smarty_function_cycle(array('name'=>"admitDayColumn",'values'=>"location-day-box-blue, "),$_smarty_tpl);?>
">
		
			<?php $_smarty_tpl->tpl_vars['admits'] = new Smarty_variable($_smarty_tpl->tpl_vars['admitsByDate']->value[$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
			<div class="regular-titles"><strong>Admit</strong></div>
			<?php  $_smarty_tpl->tpl_vars['admit'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admit']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admit']->key => $_smarty_tpl->tpl_vars['admit']->value) {
$_smarty_tpl->tpl_vars['admit']->_loop = true;
?>
				<div class="<?php if ($_smarty_tpl->tpl_vars['admit']->value->status=='Pending') {?> location-admit-pending<?php } else { ?> location-admit<?php }?>">
					<span class="admit-name"><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</strong></span>
					
				</div>
				
			<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>
	<?php }} ?>
