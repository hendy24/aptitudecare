<!-- /modules/Dietary/Views/photos/upload_photos.tpl -->

<h1>Upload Photos</h1>

<form action="{$SITE_URL}" class="dropzone" id="photoUpload" enctype="multipart/form-data">
	<input type="hidden" name="page" value="photos" />
	<input type="hidden" name="action" value="submit_upload" />
	<input type="hidden" name="location" value="{$location->public_id}">
	<div class="fallback">
    	<input name="file" type="file" multiple />
    	<input type="submit" value="Save" />
  	</div>
</form>

<br>
<br>
<a href="{$SITE_URL}/?module=Dietary&amp;page=photos&amp;action=photo_info" class="button right">Next</a>
