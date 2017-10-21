<?php /* Smarty version Smarty-3.1.13, created on 2015-06-04 06:42:46
         compiled from "/home/aptitude/dev/protected/tpl/caseManager/manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:208460368557047c64d71c4-16138627%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24d0f4ef198baabda3f0b17769e12e5039583ea5' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/caseManager/manage.tpl',
      1 => 1404433524,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208460368557047c64d71c4-16138627',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'state' => 0,
    'states' => 0,
    's' => 0,
    'caseManagers' => 0,
    'cm' => 0,
    'getter' => 0,
    'sliceLinks' => 0,
    'chunk' => 0,
    'slice' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_557047c653c843_47672336',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_557047c653c843_47672336')) {function content_557047c653c843_47672336($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/function.cycle.php';
?><?php echo smarty_set_title(array('title'=>"Manage Case Managers"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


$("#cm-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'caseManager', action: 'searchCaseManagers', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name;
				obj.pubid = val.pubid;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#case-manager").val(ui.item.value);
		e.target.value = ui.item.label;		
		window.location = SITE_URL + '/?page=caseManager&action=edit&case_manager=' + ui.item.pubid;
	}
	
});

$("#state").change(function() {
	window.location = SITE_URL + '/?page=caseManager&action=manage&state=' + $(this).val();
});



<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<h1 class="text-center">Manage Case Managers</h1>

<div class="right-top">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&action=add&state=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
" class="button">New Case Manager</a>
</div>

<br />
<br />

<div class="left">
	<select name="state" id="state">
		<option value="">Select a state...</option>
		<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['states']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
?>
			<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value->state, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['state']->value==$_smarty_tpl->tpl_vars['s']->value->state){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value->state, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php if ($_smarty_tpl->tpl_vars['s']->value->add_state!=''){?><option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value->add_state, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['state']->value==$_smarty_tpl->tpl_vars['s']->value->add_state){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value->add_state, ENT_QUOTES, 'UTF-8');?>
</option><?php }?>
		<?php } ?>
	</select>	
</div>


<div class="right">
	Search: <input type="text" name="cm_search" id="cm-search" size="30" />
</div>

<br />
<br />
<br />
<br />

<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Case Manager Name</th>
		<th>Phone</th>
		<th>Hospital</th>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['cm'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cm']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['caseManagers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cm']->key => $_smarty_tpl->tpl_vars['cm']->value){
$_smarty_tpl->tpl_vars['cm']->_loop = true;
?>
		<tr bgcolor="<?php echo smarty_function_cycle(array('values'=>"#d0e2f0,#ffffff"),$_smarty_tpl);?>
">
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&amp;action=edit&amp;case_manager=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->pubid, ENT_QUOTES, 'UTF-8');?>
"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['cm']->value->last_name)===null||$tmp==='' ? '<span class="text-grey text-10">no last name</span>' : $tmp);?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->first_name, ENT_QUOTES, 'UTF-8');?>
</a></td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->phone, ENT_QUOTES, 'UTF-8');?>
</td>
			<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->name, ENT_QUOTES, 'UTF-8');?>
</td>
		</tr>
	<?php } ?>
</table>



 <div id="pagination">
 	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationSetMaxLinks(30), ENT_QUOTES, 'UTF-8');?>

 	<div class="pagination-link">
		<!-- Shows the page numbers -->
		<?php $_smarty_tpl->tpl_vars['sliceLinks'] = new Smarty_variable($_smarty_tpl->tpl_vars['getter']->value->paginationGetSliceLinks(2,2), null, 0);?>
		<?php if (count($_smarty_tpl->tpl_vars['sliceLinks']->value)>0){?>
			<?php  $_smarty_tpl->tpl_vars['chunk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['chunk']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sliceLinks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['chunk']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['chunk']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['chunk']->key => $_smarty_tpl->tpl_vars['chunk']->value){
$_smarty_tpl->tpl_vars['chunk']->_loop = true;
 $_smarty_tpl->tpl_vars['chunk']->iteration++;
 $_smarty_tpl->tpl_vars['chunk']->last = $_smarty_tpl->tpl_vars['chunk']->iteration === $_smarty_tpl->tpl_vars['chunk']->total;
?>
				<?php  $_smarty_tpl->tpl_vars['slice'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slice']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['chunk']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['slice']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['slice']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['slice']->key => $_smarty_tpl->tpl_vars['slice']->value){
$_smarty_tpl->tpl_vars['slice']->_loop = true;
 $_smarty_tpl->tpl_vars['slice']->iteration++;
 $_smarty_tpl->tpl_vars['slice']->last = $_smarty_tpl->tpl_vars['slice']->iteration === $_smarty_tpl->tpl_vars['slice']->total;
?>
					<?php if ($_smarty_tpl->tpl_vars['slice']->value==$_smarty_tpl->tpl_vars['getter']->value->paginationGetSlice()){?>
						<td class="current">
							<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slice']->value, ENT_QUOTES, 'UTF-8');?>
&nbsp;&nbsp;|&nbsp;
						</td>
					<?php }else{ ?>
						<td>
							<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationGetURL($_smarty_tpl->tpl_vars['slice']->value), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slice']->value, ENT_QUOTES, 'UTF-8');?>
</a>&nbsp;&nbsp;|&nbsp;
						</td>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['slice']->last==true&&$_smarty_tpl->tpl_vars['chunk']->last!=true){?><td class="ellipsis"> ...</td><?php }?>
				<?php } ?>
			<?php } ?>
		<?php }?>
	</div>

 	<div class="pagination-link">
		 <!-- Shows the next and previous links -->
		 <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationGetURL($_smarty_tpl->tpl_vars['getter']->value->paginationPrevSlice()), ENT_QUOTES, 'UTF-8');?>
" class="floatleft pagination-link" rel="previous">Previous</a>
		 &nbsp;&nbsp;
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationGetURL($_smarty_tpl->tpl_vars['getter']->value->paginationNextSlice()), ENT_QUOTES, 'UTF-8');?>
" rel="next">Next</a>
	</div>

 	<div class="pagination-link">		
		 <!-- prints X of Y, where X is current page and Y is number of pages -->
		 Page <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationGetSlice(), ENT_QUOTES, 'UTF-8');?>
 of <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['getter']->value->paginationNumSlices(), ENT_QUOTES, 'UTF-8');?>

	</div>
 </div><?php }} ?>