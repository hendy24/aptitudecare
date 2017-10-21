<?php /* Smarty version Smarty-3.1.13, created on 2015-06-04 06:43:20
         compiled from "/home/aptitude/dev/protected/tpl/physician/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:746551646557047e8cdcbd7-83603103%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '324a5676f4b7248ed04fe7dcbfd97cd69e9f00b4' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/physician/edit.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '746551646557047e8cdcbd7-83603103',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'SITE_URL' => 0,
    'isMicro' => 0,
    'p' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_557047e8d10388_98579998',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_557047e8d10388_98579998')) {function content_557047e8d10388_98579998($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

<?php $_smarty_tpl->tpl_vars['states'] = new Smarty_variable(getUSAStates(), null, 0);?>
var states = [
<?php  $_smarty_tpl->tpl_vars['state'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['state']->_loop = false;
 $_smarty_tpl->tpl_vars['abbr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['states']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['state']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['state']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['state']->key => $_smarty_tpl->tpl_vars['state']->value){
$_smarty_tpl->tpl_vars['state']->_loop = true;
 $_smarty_tpl->tpl_vars['abbr']->value = $_smarty_tpl->tpl_vars['state']->key;
 $_smarty_tpl->tpl_vars['state']->iteration++;
 $_smarty_tpl->tpl_vars['state']->last = $_smarty_tpl->tpl_vars['state']->iteration === $_smarty_tpl->tpl_vars['state']->total;
?>
<?php if ($_smarty_tpl->tpl_vars['state']->value!=''){?>
	{
		value: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
",
		label: "(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
) <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
"
	}
	<?php if ($_smarty_tpl->tpl_vars['state']->last!=true){?>,<?php }?>
<?php }?>
<?php } ?>
];

$("#search-states").autocomplete(
	{
		minLength: 0,
		source: states,
		focus: function( event, ui ) {
			$( "#search-states" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#search-states" ).val( ui.item.label );
			$( "#state_id" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};
	
$(".phone").mask("(999) 999-9999");
$(".fax").mask("(999)-999-9999");

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<h1 class="text-center">Edit Physician/Surgeon</h1>

<form id="edit-physician" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" method="post">
	<input type="hidden" name="page" value="physician" />
	<?php if ($_smarty_tpl->tpl_vars['isMicro']->value){?>
		<input type="hidden" name="action" value="submitShadowboxEdit" />
	<?php }else{ ?>
		<input type="hidden" name="action" value="submitEdit" />
	<?php }?>
	<input type="hidden" name="physician" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	
	<table id="edit-data" cellpadding="5" cellspacing="5">
		<tr>
			<td><strong>First Name:</strong></td>
			<td><strong>Last Name:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->first_name, ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->last_name, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td colspan="2"><strong>Address</strong></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" size="50" name="address" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->address, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td><strong>City</strong></td>
			<td><strong>State</strong></td>
		<tr>
			<td><input type="text" name="city" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->city, ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><input type="text" name="state" id="search-states" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->state, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td><strong>Zip</strong></td>
			<td><strong>Phone</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="zip" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->zip, ENT_QUOTES, 'UTF-8');?>
" /></td>
			<td><input type="text" name="phone" class="phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->phone, ENT_QUOTES, 'UTF-8');?>
" /></td>
		</tr>
		<tr>
			<td><strong>Fax</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="fax" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->fax, ENT_QUOTES, 'UTF-8');?>
" class="phone" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=delete&physician=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" id="deleteCM" class="button">Delete</a></td>
			<td><input type="submit" value="Save" class="right" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&amp;action=manage" style="margin-right: 5px;">Cancel</a></td>
		</tr>
	</table>
</form><?php }} ?>