<?php /* Smarty version Smarty-3.1.13, created on 2015-04-16 15:12:17
         compiled from "/home/aptitude/dev/protected/tpl/patient/viewNotesFile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1742763577553025b1b5c247-37931887%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b3136527a7178075862f9b5692da037656056b66' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/viewNotesFile.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1742763577553025b1b5c247-37931887',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'patient' => 0,
    'idx' => 0,
    'relpaths' => 0,
    'ENGINE_URL' => 0,
    'SITE_URL' => 0,
    'schedule' => 0,
    'relpath' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_553025b1b96142_44976770',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553025b1b96142_44976770')) {function content_553025b1b96142_44976770($_smarty_tpl) {?><?php echo smarty_set_title(array('title'=>"Notes File Viewer"),$_smarty_tpl);?>

<h1>Viewing Notes file for <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->fullname(), ENT_QUOTES, 'UTF-8');?>
</h1>
<br />
<h2>File: <i><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->{"notes_name".((string)$_smarty_tpl->tpl_vars['idx']->value)}, ENT_QUOTES, 'UTF-8');?>
</i></h2>
<br />
<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

$(".notes-preview").click(function(e) {
	e.preventDefault();
	$("#container").attr("src", $(this).attr("href"));
	var parts = $(this).attr("id").split("-");
	var idx = parseInt(parts[2]);
	if (idx == 0) {
		$("#notes-preview-previous").hide();
	} else {
		$("#notes-preview-previous").show();	
	}
	if (idx == <?php echo htmlspecialchars(count($_smarty_tpl->tpl_vars['relpaths']->value)-1, ENT_QUOTES, 'UTF-8');?>
) {
		$("#notes-preview-next").hide();	
	} else {
		$("#notes-preview-next").show();	
	}
	
	$("#notes-preview-previous a").attr("rel", parseInt(idx) - 1);
	$("#notes-preview-next a").attr("rel", parseInt(idx) + 1);
});
$("#notes-preview-0").trigger("click");

$("#notes-preview-previous a").click(function(e) {
	e.preventDefault();
	$("#notes-preview-" + $(this).attr("rel")).trigger("click");
});

$("#notes-preview-next a").click(function(e) {
	e.preventDefault();
	console.log($(this).attr("rel"));
	$("#notes-preview-" + $(this).attr("rel")).trigger("click");
});


<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ENGINE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/icons/printer.png" alt="Printer" /> <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=downloadNotesFile&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['idx']->value, ENT_QUOTES, 'UTF-8');?>
">Open this file for printing</a>
<br />
<br />
<table width="100%" border=1>
	<tr>
		<td width="125" valign="top">
			
			<?php  $_smarty_tpl->tpl_vars['relpath'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['relpath']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['relpaths']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['relpath']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['relpath']->iteration=0;
 $_smarty_tpl->tpl_vars['relpath']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['relpath']->key => $_smarty_tpl->tpl_vars['relpath']->value){
$_smarty_tpl->tpl_vars['relpath']->_loop = true;
 $_smarty_tpl->tpl_vars['relpath']->iteration++;
 $_smarty_tpl->tpl_vars['relpath']->index++;
?>
			Page <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['relpath']->iteration, ENT_QUOTES, 'UTF-8');?>
 of <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['relpath']->total, ENT_QUOTES, 'UTF-8');?>

			<br />
			<a class="notes-preview" id="notes-preview-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['relpath']->index, ENT_QUOTES, 'UTF-8');?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=notesImage&_image=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['relpath']->value, ENT_QUOTES, 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=notesImage&_image=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['relpath']->value, ENT_QUOTES, 'UTF-8');?>
&amp;max_width=100" style="border: 1px solid #000; margin: 10px;" /></a>
			<?php } ?>
		</td>
		<td valign="top">
			<span id="notes-preview-previous">&laquo; <a href="#" rel="">PREVIOUS PAGE</a></span>
			<span id="notes-preview-next"><a href="#" rel="1">NEXT PAGE</a> &raquo;</span>
			<br /><br />
			
			<img id="container" width="600" />
		</td>
	</tr>
</table><?php }} ?>