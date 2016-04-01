<!-- /modules/Dietary/Views/photos/manage_photos.tpl -->
{literal}
<script>
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
{/literal}
<h1>Manage Photos</h1>

{if !empty ($photos)}
<form action="{$SITE_URL}" method="post" id="photo-form">
	<input type="hidden" name="page" value="photos">
	<input type="hidden" name="action" value="approve_photos">
	<input type="hidden" name="current_url" value="{$current_url}">

	<table class="form">
		{foreach from=$photos item=photo name=count}
		<tr>
			<td rowspan="2">
				<a class="fancybox" rel="fancybox-thumb" href="{$SITE_URL}/files/dietary_photos/{$photo->filename}" title="{$photo->name}: {$photo->description}">
					<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:100px" alt=""></td>
				</a>
			<td>
				<strong>Name:</strong><br>
				{$photo->name}
			</td>
			<td>
				<strong>Description:</strong><br>
				{$photo->description}
			</td>
		</tr>
		<tr>
			<td>
				<strong>User Created:</strong><br>
				{$photo->username}
			</td>
			<td>
				<strong>Facilty:</strong><br>
				{$photo->location_name}
			</td>
		</tr>
		<tr>
			<td colspan="3">Tags: <input type="text" class="tags" name="tags" style="width:300px"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
				<input type="radio" name="photo[{$photo->public_id}]" value="1">Approve<br>
				<input type="radio" name="photo[{$photo->public_id}]" value="0">Reject
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		{if not $smarty.foreach.count.last}
		<tr>
			<td colspan="3" style="border-top: 1px solid #ccc;">&nbsp;</td>
		</tr>
		{else}
		<tr>
			<td colspan="3" class="text-right"><input type="submit" value="Save"></td>
		</tr>
		{/if}
		{/foreach}
	</table>
</form>
{else}
	<h2>No photos to approve at this time.</h2>
{/if}

<div id="dialog">Are you sure you want to reject these photos? The photos will be deleted and will not be recoverable.</div>