<?php /* Smarty version Smarty-3.1.19, created on 2014-09-03 21:12:46
         compiled from "/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/discharges/schedule.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101184559253d8454cf3cb48-18557944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75eab21fb5bd34dca02e20d41d19a7ea613b20cb' => 
    array (
      0 => '/mnt/hgfs/Sites/aptitudecare_framework/sites/dev/modules/HomeHealth/Views/discharges/schedule.tpl',
      1 => 1409800365,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101184559253d8454cf3cb48-18557944',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_53d8454cf3e8d1_61939692',
  'variables' => 
  array (
    'frameworkJs' => 0,
    'current' => 0,
    'c' => 0,
    'siteUrl' => 0,
    'retreatWeekSeed' => 0,
    'frameworkImg' => 0,
    'week' => 0,
    'advanceWeekSeed' => 0,
    'discharged' => 0,
    'day' => 0,
    'discharge' => 0,
    'd' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53d8454cf3e8d1_61939692')) {function content_53d8454cf3e8d1_61939692($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mnt/hgfs/Sites/aptitudecare_framework/framework/protected/Vendors/Smarty-3.1.19/libs/plugins/modifier.date_format.php';
?><script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkJs']->value, ENT_QUOTES, 'UTF-8');?>
/discharges.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		var url = SiteUrl + "/?module=HomeHealth&page=discharges&action=schedule";

		$('#area').change(function() {
			window.location = url + "&location=" + $("#location").val() + "&area=" + $(this).val();
		});

		$("#location").change(function() {
			window.location = url + "&location=" + $(this).val();
		});
	});
</script>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['views']->value)."/elements/search_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<h2>Schedule Discharges</h2>

<div id="discharges">
	<div id="current-patients">
		<h2>Current Patients</h2>
	<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['current']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['c']->value->datetime_discharge=='') {?>
		<div class="current-patient" droppable="true" >
			<div class="select-patient" draggable="true"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->fullName(), ENT_QUOTES, 'UTF-8');?>

				<input type="hidden" name="public_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->schedule_pubid, ENT_QUOTES, 'UTF-8');?>
">
			</div>
		</div>
		<?php }?>
	<?php } ?>
		<div class="clear"></div>
	</div>
	

	<br><br>
	<div id="date-header">
		<div class="date-header-img">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=discharges&amp;action=schedule&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['retreatWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="left" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/icons/prev-icon.png" /></a>
		</div>
		<div class="date-header-text-center">
			<h2><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[0],"%a, %b %d, %Y"), ENT_QUOTES, 'UTF-8');?>
 &ndash; <?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['week']->value[6],"%a, %b %d, %Y"), ENT_QUOTES, 'UTF-8');?>
</h2>
		</div>
		<div class="date-header-img">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['siteUrl']->value, ENT_QUOTES, 'UTF-8');?>
/?module=HomeHealth&amp;page=discharges&amp;action=schedule&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['advanceWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img class="left" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['frameworkImg']->value, ENT_QUOTES, 'UTF-8');?>
/icons/next-icon.png" /></a>	
		</div>	
	</div>

	<div class="clear"></div>


	<div id="discharge-container">
		<?php  $_smarty_tpl->tpl_vars['discharge'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discharge']->_loop = false;
 $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['discharged']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['discharge']->key => $_smarty_tpl->tpl_vars['discharge']->value) {
$_smarty_tpl->tpl_vars['discharge']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->value = $_smarty_tpl->tpl_vars['discharge']->key;
?>
			<div class="discharge-day-wrapper" >
				<h3><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a, %b %e, %Y"), ENT_QUOTES, 'UTF-8');?>
</h3>
				<input type="hidden" name="date" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['day']->value, ENT_QUOTES, 'UTF-8');?>
">
				<div class="discharge-day">
					<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharge']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
					<?php if (isset($_smarty_tpl->tpl_vars['d']->value->id)) {?>
					<div class="discharge-info">
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->fullName(), ENT_QUOTES, 'UTF-8');?>

						<input type="hidden" name="public_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->schedule_pubid, ENT_QUOTES, 'UTF-8');?>
">
					</div>
					<?php }?>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
				
	</div>
</div><?php }} ?>
