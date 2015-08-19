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
	<tr>
		<td><img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:100px" alt=""></td>
		<td><input type="text" class="name" name="name" placeholder="Photo name"></td>
		<td><textarea name="description" class="description" placeholder="Photo description" cols="30" rows="4"></textarea></td>
		<td><input type="button" data-photo-id="{$photo->public_id}" class="save" value="Save"></td>
	</tr>
	{/foreach}
</table>

<div class="text-right">
	<a href="{$SITE_URL}/?module=Dietary" class="button">Done</a>
</div>