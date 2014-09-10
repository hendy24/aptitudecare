<?php /* Smarty version Smarty-3.1.19, created on 2014-09-04 15:35:32
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/home_health/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:82607754953d83ac7a0b8c8-28535554%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73358ec0c80f7548761bce904b4cd15c5e5391e5' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/home_health/index.tpl',
      1 => 1409866530,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '82607754953d83ac7a0b8c8-28535554',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d83ac7a6fc87_70423136',
  'variables' => 
  array (
    'frameworkJs' => 0,
    'siteUrl' => 0,
    'area' => 0,
    'retreatWeekSeed' => 0,
    'frameworkImg' => 0,
    'week' => 0,
    'advanceWeekSeed' => 0,
    'admitsByDate' => 0,
    'day' => 0,
    'admits' => 0,
    'admit' => 0,
    'patientTools' => 0,
    'dischargesByDate' => 0,
    'discharges' => 0,
    'discharge' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d83ac7a6fc87_70423136')) {function content_53d83ac7a6fc87_70423136($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/function.cycle.php';
?><script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/admissions.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		$('#area').change(function() {
			window.location = "?module=HomeHealth&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#location").change(function() {
			window.location = "?module=HomeHealth&location=" + $(this).val();
		});
	});
</script>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div id="date-header">
	<div class="date-header-img">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;area=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['area']->value->public_id, ENT_QUOTES, 'UTF-8');?>
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
/?module=HomeHealth&amp;area=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['area']->value->public_id, ENT_QUOTES, 'UTF-8');?>
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
" droppable="true">
			<input type="hidden" class="date" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['day']->value, ENT_QUOTES, 'UTF-8');?>
" />
			<div class="box-title">Admit</div>
			<?php  $_smarty_tpl->tpl_vars['admit'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['admit']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admits']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['admit']->key => $_smarty_tpl->tpl_vars['admit']->value) {
$_smarty_tpl->tpl_vars['admit']->_loop = true;
?>
			<?php if (isset($_smarty_tpl->tpl_vars['admit']->value->id)) {?>
			<div class="<?php if ($_smarty_tpl->tpl_vars['admit']->value->status=='Pending') {?> location-admit-pending<?php } else { ?>location-admit<?php }?>" draggable="true">
				<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patientTools']->value->menu($_smarty_tpl->tpl_vars['admit']->value), ENT_QUOTES, 'UTF-8');?>
<br>
				<input type="hidden" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->public_id, ENT_QUOTES, 'UTF-8');?>
" />

				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admit']->value->healthcare_facility_name, ENT_QUOTES, 'UTF-8');?>

			</div>
			<?php }?>
			<?php } ?>

			
		</div>
	</div>
	<?php } ?>

	<div class="clear"></div>
	<div class="horizontal-break"></div>

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
			<div class="box-title">Discharge</div>
			<?php  $_smarty_tpl->tpl_vars['discharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharge']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharge']->key => $_smarty_tpl->tpl_vars['discharge']->value) {
$_smarty_tpl->tpl_vars['discharge']->_loop = true;
?>
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
