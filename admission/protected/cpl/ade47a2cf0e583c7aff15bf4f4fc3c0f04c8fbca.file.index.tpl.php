<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:24:58
         compiled from "/home/aptitude/dev/protected/tpl/report/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:359091579563ada1a71ee68-88994266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ade47a2cf0e583c7aff15bf4f4fc3c0f04c8fbca' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/report/index.tpl',
      1 => 1400775304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '359091579563ada1a71ee68-88994266',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    'orderby' => 0,
    'viewby' => 0,
    'summary' => 0,
    'filterby' => 0,
    'auth' => 0,
    'facilities' => 0,
    'f' => 0,
    'facility' => 0,
    'reportTypes' => 0,
    'k' => 0,
    'v' => 0,
    'viewOpts' => 0,
    'view' => 0,
    'dateStart' => 0,
    'yearOpts' => 0,
    'year' => 0,
    'dateEnd' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ada1a774854_59678586',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ada1a774854_59678586')) {function content_563ada1a774854_59678586($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><div id="reports">
	<?php if ($_smarty_tpl->tpl_vars['type']->value==''){?>
	<h1 class="text-center">AHC Reports</h1>
	<?php }?>
	<br />
		
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

				
		if ($("#start-date").val() == '' && $("#end-date").val() == '' && $('#view option:selected').val() == '' && $('#year option:selected').val() == '') {
			$('#input1').hide();
			$('#input2').hide();
		} else if ($("#start-date").val() != '' || $("#end-date").val() != '' && $('#view option:selected').val() == '' && $('#year option:selected').val() == '') {
			$('#input1').show();
			$('#input2').show();
			$('other-types').show();
			$('.length-of-stay').hide();
		} else if ($("#start-date").val() == '' && $("#end-date").val() == '' && $('#view option:selected').val() != '' || $('#year option:selected').val() != '') {
			$('#input1').show();
			$('#input2').show();
			$('.length-of-stay').show();
			$('.other-types').hide();
		}
		
		
		var reportType = $('#report-type');	
		var redirectURL = function() {
			if (reportType.val() == "discharge_history") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&week_start=<?php echo htmlspecialchars(smarty_modifier_date_format("last Sunday - 1 week","Y-m-d"), ENT_QUOTES, 'UTF-8');?>
';
			}
			if (reportType.val() == "length_of_stay" || reportType.val() == "discharge_type" || reportType.val() == "discharge_service" || reportType.val() == "adc") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&view=' + $('#view option:selected').val() + '&year=' + $('#year option:selected').val() + '&orderby=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['orderby']->value, ENT_QUOTES, 'UTF-8');?>
';
			} if (reportType.val() == "discharge_calls") {
				return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val();
			} else {
			return SITE_URL + '/?page=report&action=' + $("#report-type option:selected").val() + '&facility=' + $("#facility option:selected").val() + '&start_date=' + $("#start-date").val() + '&end_date=' + $("#end-date").val() + '&orderby=' + $("#orderby").val() + '&filterby=' + $("#filterby").val() + '&viewby=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['viewby']->value, ENT_QUOTES, 'UTF-8');?>
&summary=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['summary']->value, ENT_QUOTES, 'UTF-8');?>
';
			}
		}
				
	
		$("#report-search").click(function(e) {
			window.location.href = redirectURL();		
		});
	
		$("#facility").change(function(e) {	
			if ($("#report-type").val() != '' && ($("#start-date").val() != '' && $("#end-date").val() != '') || ($('#view option:selected').val() != '' && $('#year option:selected').val() != '')) {
				window.location.href = redirectURL();
			}	
		});
		
		reportType.change(function(e) {
			if (reportType.val() != "") {
				if (reportType.val() == "discharge_history") {
					$(".length-of-stay").hide();
					$(".other-types").hide();
					window.location.href = redirectURL();
				}
				else if (reportType.val() == "length_of_stay" || reportType.val() == "discharge_type" || reportType.val() == "discharge_service" || reportType.val() == "adc") {
					$(".length-of-stay").show();
					$(".other-types").hide();
					if ($('#facility').val() != '' && $('#view option:selected').val() != '' && $('#year option:selected').val() != '') {
						window.location.href = redirectURL();	
					}	
				} 
				else if (reportType.val() == "discharge_calls") {
					$(".length-of-stay").hide();
					$(".other-types").hide();
					if ($('#facility').val() != '') {
						window.location.href = redirectURL();
					}
				
				} else {
					$(".other-types").show();
					$(".length-of-stay").hide();
					if ($("#facility").val() != '' && $("#start-date").val() != '' && $("#end-date").val() != '') {
						window.location.href = redirectURL();	
					}	
				}
				
				$("#input1").show();
				$("#input2").show();
			}
		
			
		});
		
		$("#normal-view").click(function(e) {
			window.location.href = redirectURL() + '&filterby=&summary=0';
		});
		
		$("#start-date").change(function(e) {
			if ($("#facility").val() != '' && $("#report-type").val() != '' && $("#end-date").val() != '') {
				window.location.href = redirectURL();	
			}	
		});
		
		$("#end-date").change(function(e) {
			if ($("#facility").val() != '' && $("#report-type").val() != '' && $("#start-date").val() != '') {
				window.location.href = redirectURL();	
			}	
		});
		
		
		$("#orderby").change(function(e) {
			window.location.href = redirectURL();
		});
		
		$("#filterby").change(function(e) {
			window.location.href = redirectURL() + "&filterby=" + $("#filterby option:selected").val() + '&viewby=' + '&summary=1';
		});
		
		$("#view-by").hide();
		
		$("#viewby").change(function(e) {
			window.location.href = redirectURL() + "&viewby=" + $("#viewby option:selected").val();
		});
				
		$('#view').change(function(e) {
			if ($('#year option:selected').val() != '') {
				window.location.href = redirectURL();
			}
		});
		
		$('#year').change(function(e) {
			if ($('#view option:selected').val() != '') {
				window.location.href = redirectURL();
			}
		});
		
		$('#readmit-type').change(function(e) {
			window.location.href = redirectURL() + '&readmit_type=' + $('#readmit-type option:selected').val();
		});
		
		
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	
	<?php if ($_smarty_tpl->tpl_vars['filterby']->value!=''){?>
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

			$("#view-by").show();
		<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<?php }?>
	
	<?php $_smarty_tpl->tpl_vars['facilities'] = new Smarty_variable($_smarty_tpl->tpl_vars['auth']->value->getRecord()->getFacilities(), null, 0);?>
	
	<table id="select-report-info" cellpadding="5">
		<tr>
			<td align="top">
				<strong>Run report for</strong><br />
					<select id="facility">
						<option value="">Select a facility...&nbsp;&nbsp;</option>
						<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['facilities']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
?>
	    					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['f']->value->pubid==$_smarty_tpl->tpl_vars['facility']->value->pubid){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['f']->value->name, ENT_QUOTES, 'UTF-8');?>
</option>
						<?php } ?>
					</select>
			</td>
			<td>
				<strong>Type of Report</span><br />
				<select id="report-type">
					<option value="">Select the type of report...</option>
					<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['reportTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
					<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['type']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
					<?php } ?>
				</select>
			</td>
			<td width="150px" valign="top">
				<div id="input1">
					<div class="length-of-stay">
						<strong>View:</strong><br />
						<select id="view">
							<option value="">Select an option...</option>
							<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['viewOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
								<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['view']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
							<?php } ?>
						</select>
					</div>
					<div class="other-types"><strong>Start Date:</strong><br /><input type="text" id="start-date" class="date-picker" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['dateStart']->value,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
" /></div>
						
				</div>
			</td>
			<td width="150px" valign="top">
				<div id="input2">
					<div class="length-of-stay">
						<strong>Year:</strong><br />
						<select id="year">
							<option value="">Select year...</option>
							<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['yearOpts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
								<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['year']->value==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['v']->value, ENT_QUOTES, 'UTF-8');?>
</option>
							<?php } ?>
						</select>
					</div>
					<div class="other-types"><strong>End Date: </strong><br /><input type="text" id="end-date" class="date-picker" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['dateEnd']->value,"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
" /></div>
					
				</div>
			</td>
		</tr>
	<!--
		<tr>
			<td colspan="4" align="right"><input type="button" value="Search" id="report-search" /></td>
		</tr>
	-->
	</table>
</div><?php }} ?>