<?php /* Smarty version Smarty-3.1.13, created on 2015-07-24 10:45:43
         compiled from "/home/aptitude/dev/protected/tpl/patient/previewNotesFile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:126507839255b26bb77225f4-74297560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6546b7afb98545ad428c6f6a9b6b0e9aee31176' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/previewNotesFile.tpl',
      1 => 1430169361,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '126507839255b26bb77225f4-74297560',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'width' => 0,
    'totalPages' => 0,
    'numPages' => 0,
    'SITE_URL' => 0,
    'i' => 0,
    'offset' => 0,
    'schedule' => 0,
    'idx' => 0,
    'b' => 0,
    'thisChunkNumPages' => 0,
    'page' => 0,
    'pageI' => 0,
    'totalpages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55b26bb77b3e62_34102059',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b26bb77b3e62_34102059')) {function content_55b26bb77b3e62_34102059($_smarty_tpl) {?><?php echo smarty_set_title(array('title'=>"Preview Notes"),$_smarty_tpl);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


	var animating = false;
	
	$("#preview-nav-next").click(function(e) {
		e.preventDefault();
		if (animating == false) {
			var leftCurrent = $("#preview-img").position().left ;
			var leftNew = leftCurrent - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
;
			if (! (leftNew <= -1 * <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
 * <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totalPages']->value, ENT_QUOTES, 'UTF-8');?>
 )) {
				//animating = true;
				$("#preview-img").animate( {
					"left": leftNew + "px"
				}, "slow", function() {
					animating = false;
				});
			}
		}
	});
	$("#preview-nav-prev").click(function(e) {
		e.preventDefault();
		if (animating == false) {
			var leftCurrent = $("#preview-img").position().left ;
			var leftNew = leftCurrent + <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
;
			if (leftCurrent < 0) {
				//animating = true;
				$("#preview-img").animate( {
					"left": leftNew + "px"
				}, "slow", function() {
					animating = false;
				});
			}
		}
	});
	
	$(".preview-nav-bypage").click(function(e) {
		e.preventDefault();
		if ( animating == false) {
			var page = $(this).attr("rel");
			
			var leftCurrent = $("#preview-img").position().left ;
			var leftNew = -1 * page * <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
;
			console.log(leftCurrent);
			console.log(leftNew);
			if (leftCurrent < 0 || !(leftNew <= -1 * <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
 * <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['numPages']->value, ENT_QUOTES, 'UTF-8');?>
 )) {
				console.log(2);
				//animating = true;
				$("#preview-img").animate( {
					"left": leftNew + "px"
				}, "slow", function() {
					console.log(3);
					animating = false;
				});
			}
		}
	});
	
	// hide the "no JS" straight IMG element. its download will continue.
	$("#preview-img-noscript").hide();
	
	// show the IMG element that currently contains our spinner graphic
	$("#preview-img").show();
	
	// asynchronoiusly load the image into an Image object. the browser *should* make use of the
	// resource already grabbed, or currently being grabbed, by the "NO JS" element
	var img = $("<img />").attr("src", $("#preview-img-noscript").attr("src")).load(function() {
		if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
			$("#preview-img").attr("src", "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/preview-not-available.png");
		} else {
			// show the navigation buttons
			$("#preview-buttons").show();
			
			// show the navigation buttons for the chunks
			$("#preview-chunks").show();
	
			// when the Image has finished downloading, replace the spinner with the image
			$("#preview-img").attr("src", $(this).attr("src"));
		}
	});

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<div id="preview-chunks" style="display: none;">
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['numPages']->value;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['totalPages']->value+1 - (0) : 0-($_smarty_tpl->tpl_vars['totalPages']->value)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
	<a href="<?php if ($_smarty_tpl->tpl_vars['i']->value==$_smarty_tpl->tpl_vars['offset']->value){?>#<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=previewNotesFile&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['idx']->value, ENT_QUOTES, 'UTF-8');?>
&amp;b=<?php echo htmlspecialchars(urlencode($_smarty_tpl->tpl_vars['b']->value), ENT_QUOTES, 'UTF-8');?>
&amp;offset=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value, ENT_QUOTES, 'UTF-8');?>
<?php }?>" class="<?php if ($_smarty_tpl->tpl_vars['i']->value==$_smarty_tpl->tpl_vars['offset']->value){?>button-disabled<?php }else{ ?>button<?php }?>" style="margin-right: 5px;">Pages <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value+1, ENT_QUOTES, 'UTF-8');?>
 to <?php if ($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['numPages']->value<$_smarty_tpl->tpl_vars['totalPages']->value){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['numPages']->value, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totalPages']->value, ENT_QUOTES, 'UTF-8');?>
<?php }?></a>	
	<?php }} ?>
	<a class="button" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=downloadNotesFile&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['idx']->value, ENT_QUOTES, 'UTF-8');?>
" title="Print File">Print File</a>

</div>
<br />
<br />
<div id="preview-buttons" style="display: none;">

	<?php $_smarty_tpl->tpl_vars['pageI'] = new Smarty_variable(0, null, 0);?>
	<?php $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['page']->step = 1;$_smarty_tpl->tpl_vars['page']->total = (int)ceil(($_smarty_tpl->tpl_vars['page']->step > 0 ? ($_smarty_tpl->tpl_vars['offset']->value+$_smarty_tpl->tpl_vars['thisChunkNumPages']->value-1)+1 - ($_smarty_tpl->tpl_vars['offset']->value) : $_smarty_tpl->tpl_vars['offset']->value-(($_smarty_tpl->tpl_vars['offset']->value+$_smarty_tpl->tpl_vars['thisChunkNumPages']->value-1))+1)/abs($_smarty_tpl->tpl_vars['page']->step));
if ($_smarty_tpl->tpl_vars['page']->total > 0){
for ($_smarty_tpl->tpl_vars['page']->value = $_smarty_tpl->tpl_vars['offset']->value, $_smarty_tpl->tpl_vars['page']->iteration = 1;$_smarty_tpl->tpl_vars['page']->iteration <= $_smarty_tpl->tpl_vars['page']->total;$_smarty_tpl->tpl_vars['page']->value += $_smarty_tpl->tpl_vars['page']->step, $_smarty_tpl->tpl_vars['page']->iteration++){
$_smarty_tpl->tpl_vars['page']->first = $_smarty_tpl->tpl_vars['page']->iteration == 1;$_smarty_tpl->tpl_vars['page']->last = $_smarty_tpl->tpl_vars['page']->iteration == $_smarty_tpl->tpl_vars['page']->total;?>
	<a href="#" class="button preview-nav-bypage" id="preview-page-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value, ENT_QUOTES, 'UTF-8');?>
" rel="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pageI']->value, ENT_QUOTES, 'UTF-8');?>
" style="margin-right: 5px;"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value+1, ENT_QUOTES, 'UTF-8');?>
</a>
		<?php $_smarty_tpl->tpl_vars['pageI'] = new Smarty_variable($_smarty_tpl->tpl_vars['pageI']->value+1, null, 0);?>
	<?php }} ?>
	
</div>
<br />
<br />
<input type="hidden" id="preview-pages" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totalpages']->value, ENT_QUOTES, 'UTF-8');?>
" />
<div id="image-viewport" style="position: relative; overflow: hidden; width: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value+2, ENT_QUOTES, 'UTF-8');?>
px; border: 1px solid;">
	<div id="image-inner">
		<img id="preview-img-noscript" data-previewPage="0" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&amp;action=previewNotesFileImage&amp;schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&amp;idx=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['idx']->value, ENT_QUOTES, 'UTF-8');?>
&amp;offset=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['offset']->value, ENT_QUOTES, 'UTF-8');?>
&amp;numPages=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['numPages']->value, ENT_QUOTES, 'UTF-8');?>
&amp;totalPages=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['totalPages']->value, ENT_QUOTES, 'UTF-8');?>
&amp;width=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['width']->value, ENT_QUOTES, 'UTF-8');?>
" style="position: relative; left: 0;" />
		<img id="preview-img" data-previewPage="0" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/ajax-loader.gif" style="position: relative; left: 0; display: none;" />
	</div>
</div><?php }} ?>