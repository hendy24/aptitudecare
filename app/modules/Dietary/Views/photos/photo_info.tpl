<!-- /modules/Dietary/Views/photos/photo_info.tpl -->
<script src="{$JS}/plugins/jquery.tagsinput.js" type="text/javascript"></script>
<script>
	var table = null;
	var key = null;
	

	

	$(document).ready(function() {	
		var formCount = $("table.form").length;
		if (formCount === 0) {
			window.location = SITE_URL + "/?module=Dietary&page=photos&action=view_photos";
		}

		{literal}
	    $(".photo-tag").tagit({
	    	// the photo-key is not being loaded because the field name is being set when the dom
	    	// loads, not when a tag is entered.
	    	//fieldName: "photo[" + $(this).next("input.photo-key").val() + "][photo_tag][]",
	    	beforeTagAdded: function() {
	    	},
	    	afterTagAdded: function() {
	    	},
	    	fieldName: "photo_tag[]",
	    	availableTags: fetchOptions("PhotoTag"),
	    	autocomplete: {delay:0, minLength: 2},
	    	showAutocompleteOnFocus: false,
	    	caseSensitive: false,
	    	allowSpaces: true,
		    beforeTagRemoved: function(event, ui) {
		        // if tag is removed, need to delete from the dbg6znqf2r
		        var patientId = $("#patient-id").val();
		        var dislikeName = ui.tagLabel;
		        $.post(SITE_URL, {
		        	page: "",
		        	action: "",
		        	}, function (e) {
		        		
		        	}, "json"
		        );
		    }

	    }); 


	    function fetchOptions(type){
        	var choices = "";
        	var array = [];
        	var runLog = function() {
        		array.push(choices);
        	};

        	var options = $.get(SITE_URL, {
        		page: "Photos",
        		action: "fetchTags",
        		type: type
        		}, function(data) {
        			$.each(data, function(key, value) {
        				choices = value.name;
        				runLog();
        			});
        		}, "json"
        	);

        	return array;
        }
      
		{/literal}


		// Save the photo
		$("input#save-photo").on("click", function(e) {
			e.preventDefault();
			table = $(this).parent().parent().parent();
			key = table.parent().children("input:hidden:first").val();
			data = $("#photo-info-" + key).serialize();

			$.ajax({
				type: 'post',
				url: SITE_URL + "/?page=photos&action=save_photo_info",
				data: data,
				success: function() {
					table.parent().parent().fadeOut('slow');
					formCount = formCount - 1;

					if (formCount === 0) {
						window.location = SITE_URL + "/?module=Dietary&page=photos&action=view_photos";
					} 

				}
			});
		
		});

	});

</script>
<style>
	td textarea.description{
		height:150px;
	}
	td input.name{
		width:302px;
		margin-left:0px;
		margin-bottom: 20px;
	}


</style>
<link href="{$CSS}/plugins/jquery.tagsinput.css" rel="stylesheet" type="text/css">

<h1>Add Photo Info</h1>
<input type="hidden" name="current_url" value="{$current_url}">
	{foreach from=$photos item=photo key=key}
		<form id="photo-info-{$key}" method="post" action="{$SITE_URL}">
			<input type="hidden" name="photo_id" value="{$photo->public_id}">
			<table class="form">
				<input type="hidden" class="photo-key" value="{$key}">
				<tr>
					<td>
						<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:100px" alt="">
					</td>
					<td>
					<input type="text" class="name" name="name" placeholder="Photo name">
					<ul class="photo-tag">
						<li></li>
					</ul>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td ><textarea name="description" class="description" placeholder="Photo description" cols="80"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="text-right"><input type="submit" id="save-photo" value="Save"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
		</form>
	{/foreach}
