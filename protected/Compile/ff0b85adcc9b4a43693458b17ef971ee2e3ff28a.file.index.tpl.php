<?php /* Smarty version Smarty-3.1.19, created on 2014-07-28 12:49:00
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/homehealth/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184870552153d29cd56f3dd6-55884422%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff0b85adcc9b4a43693458b17ef971ee2e3ff28a' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/homehealth/index.tpl',
      1 => 1406573339,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184870552153d29cd56f3dd6-55884422',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d29cd574dc94_75408506',
  'variables' => 
  array (
    'siteUrl' => 0,
    'location' => 0,
    'retreatWeekSeed' => 0,
    'frameworkImg' => 0,
    'week' => 0,
    'advanceWeekSeed' => 0,
    'admitsByDate' => 0,
    'day' => 0,
    'admits' => 0,
    'admit' => 0,
    'dischargesByDate' => 0,
    'discharges' => 0,
    'discharge' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d29cd574dc94_75408506')) {function content_53d29cd574dc94_75408506($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/function.cycle.php';
?>
<script>
	$(document).ready(function() {
		$('#locations').change(function() {
			window.location = "?module=HomeHealth&location=" + $(this).val();
		});
	});
</script>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div id="date-header">
	<div class="date-header-img">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;location=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['retreatWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="left" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/icons/prev-icon.png" /></a>
	</div>
	<div class="date-header-text-center">
		<h2><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[0],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
 &ndash; <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[6],"%a, %B %d, %Y"), ENT_QUOTES, 'UTF-8');?>
</h2>
	</div>
	<div class="date-header-img">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;location=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['location']->value->public_id, ENT_QUOTES, 'UTF-8');?>
&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['advanceWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="left" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/icons/next-icon.png" /></a>	
	</div>	
</div>

<div class="clear"></div>

<div id="location-wrapper">
	<?php  $_smarty_tpl->tpl_vars['admits'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admits']->_loop = false;
 $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['admitsByDate']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admits']->key => $_smarty_tpl->tpl_vars['admits']->value) {
$_smarty_tpl->tpl_vars['admits']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->value = $_smarty_tpl->tpl_vars['admits']->key;
?>
	<div class="location-container">
		<h3 class="day-title"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a, %b %e"), ENT_QUOTES, 'UTF-8');?>
</h3>
		<div class="location-day-box location-day-box-admit <?php echo smarty_function_cycle(array('name'=>"admitDayColumn",'values'=>"location-day-box-color, "),$_smarty_tpl);?>
">
			<?php  $_smarty_tpl->tpl_vars['admit'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admit']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admit']->key => $_smarty_tpl->tpl_vars['admit']->value) {
$_smarty_tpl->tpl_vars['admit']->_loop = true;
?>
			<div class="box-title">Admit</div>
			<?php if (isset($_smarty_tpl->tpl_vars['admit']->value->id)) {?>
			<div class="<?php if ($_smarty_tpl->tpl_vars['admit']->value->status=='Pending') {?> location-admit-pending<?php } else { ?>location-admit<?php }?>">
				<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</strong><br>
				Aspen Transitional Rehab
			</div>
			<?php }?>
			<?php } ?>

			
		</div>
	</div>
	<?php } ?>

	<div class="clear"></div>
	<br>
	<hr>
	<br>

	<?php  $_smarty_tpl->tpl_vars['discharges'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharges']->_loop = false;
 $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['dischargesByDate']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharges']->key => $_smarty_tpl->tpl_vars['discharges']->value) {
$_smarty_tpl->tpl_vars['discharges']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->value = $_smarty_tpl->tpl_vars['discharges']->key;
?>
	<div class="location-container">
		<h3 class="day-title"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a, %b %e"), ENT_QUOTES, 'UTF-8');?>
</h3>
		<div class="location-day-box location-day-box-discharge <?php echo smarty_function_cycle(array('name'=>"dischargeDayColumn",'values'=>"location-day-box-color, "),$_smarty_tpl);?>
">
			<?php  $_smarty_tpl->tpl_vars['discharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharge']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharge']->key => $_smarty_tpl->tpl_vars['discharge']->value) {
$_smarty_tpl->tpl_vars['discharge']->_loop = true;
?>
			<div class="box-title">Discharge</div>
			<?php if (isset($_smarty_tpl->tpl_vars['discharge']->value->id)) {?>
				<div class="location-discharge">
					<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['discharge']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</strong><br>
				</div>
			<?php }?>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

</div>
<div class="clear"></div>



	<?php }} ?>
