{setTitle title="Notes File Viewer"}
<h1>Viewing Notes file for {$patient->fullname()}</h1>
<br />
<h2>File: <i>{$patient->{"notes_name{$idx}"}}</i></h2>
<br />
{jQueryReady}
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
	if (idx == {count($relpaths) - 1}) {
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


{/jQueryReady}
<img src="{$ENGINE_URL}/images/icons/printer.png" alt="Printer" /> <a href="{$SITE_URL}/?page=patient&amp;action=downloadNotesFile&amp;schedule={$schedule->pubid}&amp;idx={$idx}">Open this file for printing</a>
<br />
<br />
<table width="100%" border=1>
	<tr>
		<td width="125" valign="top">
			{*thumbnanils on the left*}
			{foreach $relpaths as $relpath}
			Page {$relpath@iteration} of {$relpath@total}
			<br />
			<a class="notes-preview" id="notes-preview-{$relpath@index}" href="{$SITE_URL}/?page=patient&amp;action=notesImage&_image={$relpath}"><img src="{$SITE_URL}/?page=patient&amp;action=notesImage&_image={$relpath}&amp;max_width=100" style="border: 1px solid #000; margin: 10px;" /></a>
			{/foreach}
		</td>
		<td valign="top">
			<span id="notes-preview-previous">&laquo; <a href="#" rel="">PREVIOUS PAGE</a></span>
			<span id="notes-preview-next"><a href="#" rel="1">NEXT PAGE</a> &raquo;</span>
			<br /><br />
			{* biggish file on the right *}
			<img id="container" width="600" />
		</td>
	</tr>
</table>