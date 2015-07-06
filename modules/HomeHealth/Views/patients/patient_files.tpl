<h1>Patient Files<br>
	<span class="text-16">for</span><br><span class="text-20">{$patient->fullName()}</span></h1>

<form action="{$SITE_URL}" class="dropzone" id="patientFileUpload">
	<input type="hidden" name="page" value="patients" />
	<input type="hidden" name="action" value="fileUpload" />
	<input type="hidden" name="patient" value="{$patient->public_id}" />
	<div class="fallback">
    	<input name="file" type="file" multiple />
  	</div>
</form>


{if !empty($patientFiles)}
	<div id="current-files">
		<h2>Current Files</h2>
		{foreach from=$patientFiles item=file}
			<a href="{$SITE_URL}/?module=HomeHealth&amp;page=Patients&amp;action=previewNotesFile&amp;patient={$patient->public_id}&amp;file={$file->file}&amp;offset=0&amp;b=&amp;width=">
				<div class="note-thumbnail">
					{$file->name}
				</div>	
			</a>
		{/foreach}
	</div>
	
{/if}


<script>
// 	Dropzone.options.patientFileUpload = {
// 		acceptedFiles: 
// 			"application/pdf",
// 			"application/vnd.openxmlformats-officedocument.wordpressingml.template",
// 			"application/vnd.ms-excel",
// 			"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
// 	};
// </script>