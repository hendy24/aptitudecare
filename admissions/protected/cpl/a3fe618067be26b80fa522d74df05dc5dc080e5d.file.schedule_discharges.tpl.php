<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:14:39
         compiled from "/home/aptitude/dev/protected/tpl/facility/schedule_discharges.tpl" */ ?>
<?php /*%%SmartyHeaderCode:987143488563ad7afae20e0-49319298%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3fe618067be26b80fa522d74df05dc5dc080e5d' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/facility/schedule_discharges.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '987143488563ad7afae20e0-49319298',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'facilities' => 0,
    'f' => 0,
    'facility' => 0,
    'current' => 0,
    'c' => 0,
    'occupant' => 0,
    'room' => 0,
    'occupantSchedule' => 0,
    'ptName' => 0,
    'prevWeekSeed' => 0,
    'nextWeekSeed' => 0,
    'week' => 0,
    'day' => 0,
    'discharged' => 0,
    'discharges' => 0,
    'd' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad7afb48426_96123594',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad7afb48426_96123594')) {function content_563ad7afb48426_96123594($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>"Schedule Discharges"),$_smarty_tpl);?>

<script src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/js/discharges.js" type="text/javascript"></script>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

$("#facility").change(function(e) {
	window.location.href = SITE_URL + '/?page=facility&action=schedule_discharges&facility=' + $("option:selected", this).val();
})
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<h1 class="text-center">Schedule Discharges</h1>

<div id="census-options">
	<select id="facility">
		<option value="">Please Select a facility&nbsp;&nbsp;</option>
		<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['f']->value->id==$_smarty_tpl->tpl_vars['facility']->value->id){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
	</select>
</div>

<div id="current-patients">
	<table cellpadding="5" cellspacing="5" id="current-patient-table">
		<tr>
	<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['current']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['c']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
 $_smarty_tpl->tpl_vars['c']->iteration++;
?>
		<?php if ($_smarty_tpl->tpl_vars['c']->value->datetime_discharge==''){?>
			<?php $_smarty_tpl->tpl_vars['occupant'] = new Smarty_variable(CMS_Patient_Admit::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupant']->value->load($_smarty_tpl->tpl_vars['c']->value->patient_admit_pubid), ENT_QUOTES, 'UTF-8');?>

			<?php $_smarty_tpl->tpl_vars['occupantSchedule'] = new Smarty_variable(CMS_Schedule::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['occupantSchedule']->value->load($_smarty_tpl->tpl_vars['room']->value->schedule_pubid), ENT_QUOTES, 'UTF-8');?>

			<?php $_smarty_tpl->tpl_vars['ptName'] = new Smarty_variable((substr($_smarty_tpl->tpl_vars['occupant']->value->fullName(),0,20)).("..."), null, 0);?>
			<td class="current-patient" droppable="true" ><div class="select-patient" draggable="true"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->number, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ptName']->value, ENT_QUOTES, 'UTF-8');?>
<input type="hidden" name="pubid" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['c']->value->schedule_pubid, ENT_QUOTES, 'UTF-8');?>
"></div></td>
			<?php if (!($_smarty_tpl->tpl_vars['c']->iteration % 5)){?>
			</tr>
			<tr>
			<?php }?>				
		<?php }?>
	<?php } ?>
		</tr>
	</table>
</div>

<div id="week-nav">

	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=schedule_discharges&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['prevWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/prev-icon.png" /> Previous Week</a> 
	
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=schedule_discharges&amp;weekSeed=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nextWeekSeed']->value, ENT_QUOTES, 'UTF-8');?>
">Next Week <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/next-icon.png" /></a>
	
</div>

<div id="discharge-calendar">
	<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['week']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
?>
		<div class="discharge-day-text <?php if ($_smarty_tpl->tpl_vars['day']->last){?>discharge-day-text-last<?php }?>" >
			<h3 class="select-day"><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a, %b %e, %Y"), ENT_QUOTES, 'UTF-8');?>
</h3>
			<input type="hidden" name="date" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['day']->value, ENT_QUOTES, 'UTF-8');?>
">
			<div class="discharge-day <?php echo smarty_function_cycle(array('name'=>"admitDayColumn",'values'=>"facility-day-box-blue, "),$_smarty_tpl);?>
">
				<?php $_smarty_tpl->tpl_vars['discharges'] = new Smarty_variable($_smarty_tpl->tpl_vars['discharged']->value[$_smarty_tpl->tpl_vars['day']->value], null, 0);?>
				<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discharges']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
					<div class="discharge-info">
						<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->number, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->first_name, ENT_QUOTES, 'UTF-8');?>

						<input type="hidden" name="pubid" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['d']->value->schedule_pubid, ENT_QUOTES, 'UTF-8');?>
">
					</div>
				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
			
</div><?php }} ?>