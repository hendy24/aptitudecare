<?php /* Smarty version Smarty-3.1.13, created on 2015-07-24 10:45:38
         compiled from "/home/aptitude/dev/protected/tpl/patient/notes.tpl" */ ?>
<?php /*%%SmartyHeaderCode:98681295855b26bb2d97296-24186900%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '061bb0a36dcde900f3d40627622b754a3ce8e296' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/notes.tpl',
      1 => 1430169361,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98681295855b26bb2d97296-24186900',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'patient' => 0,
    'auth' => 0,
    'SITE_URL' => 0,
    'schedule' => 0,
    'notes' => 0,
    'i' => 0,
    'name' => 0,
    'ENGINE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55b26bb2dda953_45750400',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b26bb2dda953_45750400')) {function content_55b26bb2dda953_45750400($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>"Manage Patient Medical Records"),$_smarty_tpl);?>

	<h1 class="text-center">Upload Medical Records Document(s)<br /><span class="text-18">for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->fullName(), ENT_QUOTES, 'UTF-8');?>
</span></h1>
	<br />			
	<input type="button" value="Return to Previous Page" onclick="history.go(-1)" style="margin-left: 50px;"> 	
	<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->isAdmissionsCoordinator()==1){?>
		<div class="right" style="margin-right: 50px;">
		<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" enctype="multipart/form-data">
			<input type="hidden" name="page" value="patient" />
			<input type="hidden" name="action" value="setFinalOrders" />
			<input type="hidden" name="patient" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
			<input type="hidden" name="_path" value="<?php echo htmlspecialchars(currentURL(), ENT_QUOTES, 'UTF-8');?>
" />
			
			<input type="checkbox" value="1" name="final_orders"<?php if ($_smarty_tpl->tpl_vars['patient']->value->final_orders==1){?> checked<?php }?> /> Yes, final orders have been received 
			<input type="submit" value="Save" />
			
		</form>
		</div>
	<br />
	<br />
	<br />
	<table id="notes-docs" cellpadding="0" cellspacing="0">
	<form method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" enctype="multipart/form-data">
		<input type="hidden" name="page" value="patient" />
		<input type="hidden" name="action" value="addNotes" />
		<input type="hidden" name="patient" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
		<input type="hidden" name="schedule" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
		<input type="hidden" name="_path" value="<?php echo htmlspecialchars(currentURL(), ENT_QUOTES, 'UTF-8');?>
" />
		<tr>
			<th>Select file</th>
			<th>Briefly describe</th>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload1" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name1" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload2" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name2" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload3" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name3" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload4" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name4" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload5" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name5" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload6" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name6" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload7" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name7" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload8" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name8" size="25" /> 					
			</td>
		</tr>
				
	</table>
	<input type="submit" value="Submit" class="right" style="margin: 20px 50px 0px 0px;" />	
	<?php }?>
	<?php $_smarty_tpl->tpl_vars['notes'] = new Smarty_variable($_smarty_tpl->tpl_vars['patient']->value->getNotes(), null, 0);?>
	<br />
	<br />
	<br />
	<br />
	<table id="notes-docs" cellpadding="0" cellspacing="0">
		<tr>
			<th><strong>File Description</strong></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	<?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['notes']->value['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value){
$_smarty_tpl->tpl_vars['file']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['file']->key;
?>
		<?php $_smarty_tpl->tpl_vars['name'] = new Smarty_variable($_smarty_tpl->tpl_vars['notes']->value['names'][$_smarty_tpl->tpl_vars['i']->value], null, 0);?>
		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=previewNotesFile&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value, ENT_QUOTES, 'UTF-8');?>
&amp;b=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" title="View <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8');?>
</a></td>
			<td width="20"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=downloadNotesFile&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value, ENT_QUOTES, 'UTF-8');?>
" title="Print File"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/printer.png" alt="Print File" /></a></td>
			<td width="20"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=removeNotes&amp;patient=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value, ENT_QUOTES, 'UTF-8');?>
&amp;_path=<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" title="Delete this file"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/delete.png" alt="Delete this file" /></a></td>
		</tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['file']->_loop) {
?>
		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
			<td>There are no files to display.</td>
			<td width="20"></td>
			<td width="20"></td>
		</tr>
	<?php } ?>
	
	</table>
	
</form><?php }} ?>