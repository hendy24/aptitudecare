<!-- /modules/Dietary/Views/photos/photo_info.tpl -->
<script>
	$(document).ready(function() {
		$(".save").click(function() {
			var tableRow = $(this).parent().parent();
			var photoId = $(this).attr("data-photo-id");
			var name = tableRow.find(".name").val();
			var description = tableRow.find(".description").val();
			var currentUrl = $("#current-url").val();
			
			$.post(SITE_URL, { 
					page: "photos", 
					action: "save_photo_info", 
					photo_id: photoId,
					name: name,
					description: description,
					current_url: currentUrl
				}, function(e) {
					tableRow.fadeOut("slow");
				}
			);
		});
	});
</script>
<h1>Add Photo Info</h1>
<input type="hidden" id="current-url" name="current_url" value="{$current_url}">

<table class="form">
	{foreach from=$photos item=photo}
	<form method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="photos">
		<input type="hidden" name="action" value="save_photo_info">
		<input type="hidden" name="photo_id" value="{$photo->public_id}">
		<input type="hidden" name="current_url" value="{$current_url}">
		<tr>
			<td><img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:100px" alt=""></td>
			<td><input type="text" class="name" name="name" placeholder="Photo name"></td>
			<td><textarea name="description" class="description" placeholder="Photo description" cols="30" rows="4"></textarea></td>
			<td>
{* 				<input type="submit" value="Submit"> *}
 				<input type="button" data-photo-id="{$photo->public_id}" class="save" value="Save">
 			</td>
		</tr>
	</form>
	{/foreach}
	<tr>
		<td colspan="4" class="text-right">
			<a href="{$SITE_URL}/?module=Dietary" class="button">Done</a>
		</td>
	</tr>
</table>

