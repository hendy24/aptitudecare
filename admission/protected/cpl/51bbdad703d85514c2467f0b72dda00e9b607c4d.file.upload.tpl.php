<?php /* Smarty version Smarty-3.1.13, created on 2015-06-02 17:37:22
         compiled from "/home/aptitude/dev/protected/tpl/patient/upload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1654326961556e3e32384ee2-43771332%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51bbdad703d85514c2467f0b72dda00e9b607c4d' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/upload.tpl',
      1 => 1402335678,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1654326961556e3e32384ee2-43771332',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'facilities' => 0,
    'f' => 0,
    'facility' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_556e3e3239c7d9_94196917',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556e3e3239c7d9_94196917')) {function content_556e3e3239c7d9_94196917($_smarty_tpl) {?><?php echo smarty_set_title(array('title'=>"Upload Patient Data"),$_smarty_tpl);?>


<div id="upload">
	<h1 class="text-center">Bulk Upload Patient Data</h1>
	<br />
	<br />
	<p>This page can be used to perfom a bulk upload of patient data.  The uploaded file must be in a Windows CSV format with as columns outlined below.  You can download the CSV Template file by clicking on the CSV icon below for use in entering your data.  A few of the columns including paymethod and long term need to be entered as described below or they will not correctly save.</p>
	<br />
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/templates/patient_upload.csv"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/csv.png" /></a>
	<br />
	<br />
	<table>
		<tr>
			<th colspan="2" class="text-14">The following fields are captured in the CSV file:</th>
		</tr>
		<tr>
			<td width="45%" valign="top">
				<ul>
					<li>Room Number</li>
					<li>Last Name</li>
					<li>First Name</li>
					<li>Middle Name</li>
					<li>Address</li>
					<li>City</li>
					<li>State (two letter state abbreviation)</li>
					<li>Zip</li>
				</ul>
			</td>
			<td valign="top">
				<ul>
					<li>Phone</li>
					<li>Birth Date</li>
					<li>Sex (Male/Female)</li>
					<li>Social Security Number</li>
					<li>Paymethod (options are Medicare, HMO, Rugs, or Private Pay)</li>
					<li>Medicare Number</li>
					<li>Long Term (enter 0 for short-term patients and 1 for long-term)</li>
				</ul>
			</td>
		</tr>
	</table>
	
	<br />
	<br />
	<div class="text-center">
		<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" enctype="multipart/form-data">
			<input type="hidden" name="page" value="patient" />
			<input type="hidden" name="action" value="uploadData" />
			<input type="hidden" name="_path" value="<?php echo htmlspecialchars(currentURL(), ENT_QUOTES, 'UTF-8');?>
" />
			
			
			<select name="facility" id="facility">
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
			<br />
			<br />
			<input type="file" name="patient_data" id="file" />
			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
</div><?php }} ?>