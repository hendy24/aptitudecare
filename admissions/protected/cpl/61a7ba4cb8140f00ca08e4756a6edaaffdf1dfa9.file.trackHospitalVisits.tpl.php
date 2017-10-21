<?php /* Smarty version Smarty-3.1.13, created on 2014-05-07 18:22:49
         compiled from "/home/aptitude/dev/protected/tpl/coord/trackHospitalVisits.tpl" */ ?>
<?php /*%%SmartyHeaderCode:83286440536ace59086290-12010035%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61a7ba4cb8140f00ca08e4756a6edaaffdf1dfa9' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/coord/trackHospitalVisits.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '83286440536ace59086290-12010035',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'facility' => 0,
    'facilities' => 0,
    'f' => 0,
    'atHospitalRecords' => 0,
    'orderByOpts' => 0,
    'k' => 0,
    'orderby' => 0,
    'v' => 0,
    'ahr' => 0,
    'schedule' => 0,
    'SITE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_536ace592297e0_76450057',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536ace592297e0_76450057')) {function content_536ace592297e0_76450057($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>"Track Hospital Visits for ".((string)$_smarty_tpl->tpl_vars['facility']->value->name)),$_smarty_tpl);?>

<h1 style="text-align: center;">Return to Hospital</h1>
<h2 class="text-center"><?php if ($_smarty_tpl->tpl_vars['facility']->value!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }?></h2>

<br />
<br />

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

$("#facility").change(function(e) {
    window.location.href = SITE_URL + '/?page=coord&action=trackHospitalVisits&facility=' + $("option:selected", this).val();
});


$(".stop-tracking").click(function(e) {
	var tableRow = $(this).parent().parent();
	e.preventDefault();
	var anchor = this;
			
	jConfirm ("Are you sure you want to stop tracking this hospital visit?  This cannot be undone.", 'Confirmation Required', function(r) {
		if (r == true) {
			$.getJSON(SITE_URL , { page: "coord", action: "stopTrackingHospitalVisit", schedule_hospital: $(anchor).attr("rel") }, function(json) {
				$(tableRow).fadeOut();
			}, "json");
		} else {
			return false;
		}
	});
	
	return false;
	
});


<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<div style="float: left;">
	Track Visits for:
	<select id="facility">
	<option value="">Select a facility...</option>
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
<?php if ($_smarty_tpl->tpl_vars['atHospitalRecords']->value!==false){?>

<?php if (count($_smarty_tpl->tpl_vars['atHospitalRecords']->value)>0){?>
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$("#orderby").change(function(e) {
		window.location.href = SITE_URL + '/?page=coord&action=trackHospitalVisits&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&orderby=' + $("option:selected", this).val();
	});
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<?php $_smarty_tpl->tpl_vars['orderByOpts'] = new Smarty_variable(array('datetime_updated_DESC'=>'Date (last updated - newest first)','datetime_updated_ASC'=>'Date (last updated - oldest first)','datetime_created_DESC'=>'Date (initiated - newest first)','datetime_created_ASC'=>'Date (initiated - oldest first)','datetime_sent_DESC'=>'Date (sent to hospital - newest first)','datetime_sent_ASC'=>'Date (sent to hospital - oldest first)','hospital_name'=>'Hospital name (A &rarr; Z)','facility'=>'Facility name (A &rarr; Z)','discharge_nurse'=>'Discharge nurse name (A &rarr; Z)'), null, 0);?>
	<div style="float: right;">
		<strong>Order by:</strong>
		<select id="orderby">
		<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['orderByOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['orderby']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
		<?php } ?>
		</select>
	</div>
	<div style="float: left; clear: both; margin: 25px 50px; font-size: 14px;">
		There are <strong><?php echo htmlspecialchars(count($_smarty_tpl->tpl_vars['atHospitalRecords']->value), ENT_QUOTES, 'UTF-8');?>
</strong> hospital visits currently being tracked.
	</div>
<?php }?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<?php  $_smarty_tpl->tpl_vars['ahr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ahr']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['atHospitalRecords']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ahr']->key => $_smarty_tpl->tpl_vars['ahr']->value){
$_smarty_tpl->tpl_vars['ahr']->_loop = true;
?>
	
		<?php $_smarty_tpl->tpl_vars['schedule'] = new Smarty_variable(CMS_Schedule::generate(), null, 0);?>
		<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->load($_smarty_tpl->tpl_vars['ahr']->value->pubid), ENT_QUOTES, 'UTF-8');?>

		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#eeeeee,#ffffff"),$_smarty_tpl);?>
">
	
			<td valign="top" nowrap class="track-visits-patient" width="20px">
				<div class="trackVisitsName
					<?php if ($_smarty_tpl->tpl_vars['ahr']->value->datetime_discharge!=''&&$_smarty_tpl->tpl_vars['ahr']->value->datetime_discharge_bedhold_end!=''){?>
						background-yellow
					<?php }elseif($_smarty_tpl->tpl_vars['ahr']->value->datetime_discharge!=''&&$_smarty_tpl->tpl_vars['ahr']->value->datetime_discharge_bedhold_end==''){?>
						background-red
					<?php }else{ ?>
						background-purple
					<?php }?>
				"></div>
			</td>
			<td width="25px">&nbsp;</td>
			<td>
				<strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</strong><br />
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->name, ENT_QUOTES, 'UTF-8');?>

			</td>
			<td width="100px"><?php echo scheduleMenu(array('schedule'=>$_smarty_tpl->tpl_vars['schedule']->value),$_smarty_tpl);?>
</td>
			<td>
				<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=facility&amp;action=sendToHospital&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;path=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" class="button">Hospital Visit Info</a>
			</td>
			<?php if ($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge!=''){?>
				<td>
					<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=coord&amp;action=readmit&facility=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" class="button">Re-Admit this Patient</a>
				</td>
					<?php }?>
			<td valign="top" valign="right" style="padding: 25px;">
				<?php if ($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge!=''&&($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge_bedhold_end==''||strtotime($_smarty_tpl->tpl_vars['schedule']->value->datetime_discharge_bedhold_end)<strtotime("now"))){?>
					<a class="stop-tracking" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->schedule_hospital_pubid, ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/trash.png" alt="Delete Hospital Visit" /></a>
				<?php }else{ ?>
					&nbsp;
				<?php }?>
			</td>
	
		</tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['ahr']->_loop) {
?>
	
		<br />
		<br />
		<br />
		<div class="text-center">
			<?php if ($_smarty_tpl->tpl_vars['facility']->value){?>
				<strong> There are currently no hospital visits being tracked for this facility</strong>
			<?php }else{ ?>
				<strong>Please select a facility to view hospital visits.</strong>
			<?php }?>
		</div>
	<?php } ?>
</table>
<?php }?><?php }} ?>