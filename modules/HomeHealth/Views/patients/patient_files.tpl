<script>
	$(document).ready(function () {
		$(".delete").click(function(e) {
			e.preventDefault();
			var dataRow = $(this).parent().parent();
			var item = $(this);
			var publicId = $("#public-id").val();
			var filename = item.children().next($(".filename")).val();
			$("#dialog").dialog({
				buttons: {
					"Confirm": function() {							
						$.ajax({
							type: 'post',
							url: SITE_URL,
							data: {
								page: "patients",
								action: 'deleteFile',
								filename: filename,
								public_id: publicId
							},
							success: function() {
								dataRow.fadeOut("slow");
							}
						});
						$(this).dialog("close");

					},
					"Cancel": function() {
						$(this).dialog("close");
					}
				}
			});
		});


		$("#patientFileUpload").on('success', function(file, responseText) {

		});

	});
</script>
<h1>Patient Files<br>
	<span class="text-16">for</span><br><span class="text-20">{$patient->fullName()}</span></h1>

<form action="{$SITE_URL}" class="dropzone" id="patientFileUpload">
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="fileUpload" />
	<input type="hidden" name="patient" id="public-id" value="{$patient->public_id}" />
	<div class="fallback">
    	<input name="file" type="file" multiple />
    	<input type="submit" value="Save" />
  	</div>
</form>


{if !empty($patientFiles)}
	<div id="current-files">
		<h2>Current Files</h2>
		{foreach from=$patientFiles item=file}
			<a href="{$SITE_URL}/?module=HomeHealth&amp;page=Patients&amp;action=previewNotesFile&amp;patient={$patient->public_id}&amp;file={$file->file}&amp;offset=0&amp;b=&amp;width=">
				<div class="note-thumbnail">
					<div class="filename">
						{$file->name}
					</div>
					<div class="trashcan">
						<a href="#" class="delete">
							<img src="{$FRAMEWORK_IMAGES}/trash_can.png" alt="">
							<input type="hidden" name="filename" class="filename" value="{$file->file}" />
						</a>
					</div>
				</div>	
			</a>
		{/foreach}
	</div>
	
{/if}



<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>