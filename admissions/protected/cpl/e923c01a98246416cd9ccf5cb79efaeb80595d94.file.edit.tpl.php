<?php /* Smarty version Smarty-3.1.13, created on 2015-06-04 06:42:52
         compiled from "/home/aptitude/dev/protected/tpl/caseManager/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1842235467557047cc838725-48104705%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e923c01a98246416cd9ccf5cb79efaeb80595d94' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/caseManager/edit.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1842235467557047cc838725-48104705',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE_URL' => 0,
    'isMicro' => 0,
    'cm' => 0,
    'hospital' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_557047cc85f1a9_81140898',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_557047cc85f1a9_81140898')) {function content_557047cc85f1a9_81140898($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$("#hospital-search").autocomplete({
		minLength: 4,
		source: function(req, add) {
			$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
				var suggestions = [];
				$.each (json, function(i, val) {
					var obj = new Object;
					obj.value = val.id;
					obj.label = val.name + " (" + val.state + ")";
					suggestions.push(obj);
				});
				add(suggestions);
			});
		}
		,select: function (e, ui) {
			e.preventDefault();
			$("#hospital").val(ui.item.value);
			e.target.value = ui.item.label;		
		}
	});
	
	$(".phone").mask("(999) 999-9999");

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<h1 class="text-center">Edit Case Manager</h1>

<form id="edit-case-manager" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" method="post">
	<input type="hidden" name="page" value="caseManager" />
	<?php if ($_smarty_tpl->tpl_vars['isMicro']->value){?>
		<input type="hidden" name="action" value="submitShadowboxEdit" />
	<?php }else{ ?>
		<input type="hidden" name="action" value="submitEdit" />
	<?php }?>
	<input type="hidden" name="case_manager" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>First Name:</strong></td>
			<td><strong>Last Name:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->first_name, ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->last_name, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Healthcare Facility</strong></td>
		</tr>
		<tr>
			<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->load($_smarty_tpl->tpl_vars['cm']->value->hospital_id), ENT_QUOTES, 'UTF-8');?>

			<td><input type="text" id="hospital-search" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->name, ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" size="30" /></td>
			<input type="hidden" name="hospital" id="hospital" />
		</tr>
		<tr>
			<td><strong>Phone</strong></td>
			<td><strong>Fax</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="phone" class="phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->phone, ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><input type="text" name="fax" class="phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->fax, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td><strong>Email</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="email" size="50" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->email, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&action=delete&case_manager=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cm']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" id="deleteCM" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form><?php }} ?>