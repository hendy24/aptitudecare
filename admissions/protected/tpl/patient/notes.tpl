{setTitle title="Manage Patient Medical Records"}
	<h1 class="text-center">Upload Medical Records Document(s)<br /><span class="text-18">for {$patient->fullName()}</span></h1>
	<br />			
	<input type="button" value="Return to Previous Page" onclick="history.go(-1)" style="margin-left: 50px;"> 	
	{if $auth->getRecord()->isAdmissionsCoordinator() == 1}
		<div class="right" style="margin-right: 50px;">
		<form method="post" action="{$SITE_URL}" enctype="multipart/form-data">
			<input type="hidden" name="page" value="patient" />
			<input type="hidden" name="action" value="setFinalOrders" />
			<input type="hidden" name="patient" value="{$patient->pubid}" />
			<input type="hidden" name="_path" value="{currentURL()}" />
			
			<input type="checkbox" value="1" name="final_orders"{if $patient->final_orders == 1} checked{/if} /> Yes, final orders have been received 
			<input type="submit" value="Save" />
			
		</form>
		</div>
	<br />
	<br />
	<br />
	<table id="notes-docs" cellpadding="0" cellspacing="0">
	<form method="post" action="{$SITE_URL}" enctype="multipart/form-data">
		<input type="hidden" name="page" value="patient" />
		<input type="hidden" name="action" value="addNotes" />
		<input type="hidden" name="patient" value="{$patient->pubid}" />
		<input type="hidden" name="schedule" value="{$schedule->pubid}" />
		<input type="hidden" name="_path" value="{currentURL()}" />
		<tr>
			<th>Select file</th>
			<th>Briefly describe</th>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload1" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name1" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload2" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name2" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload3" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name3" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload4" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name4" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload5" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name5" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload6" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name6" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload7" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name7" size="25" /> 					
			</td>
		</tr>
		<tr>
			<td>
				<input type="file" name="notes_file_upload8" style="margin-top: 6px;" />
				<br />
				<i>PDF files only</i>					
			</td>
			<td>
				<input type="text" name="notes_name8" size="25" /> 					
			</td>
		</tr>
				
	</table>
	<input type="submit" value="Submit" class="right" style="margin: 20px 50px 0px 0px;" />	
	{/if}
	{$notes = $patient->getNotes()}
	<br />
	<br />
	<br />
	<br />
	<table id="notes-docs" cellpadding="0" cellspacing="0">
		<tr>
			<th><strong>File Description</strong></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	{foreach $notes['notes'] as $i => $file}
		{$name = $notes['names'][$i]}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td><a href="{$SITE_URL}/?page=patient&amp;action=previewNotesFile&amp;schedule={$schedule->pubid}&amp;idx={$i}&amp;b={urlencode(currentURL())}" title="View {$name}">{$name}</a></td>
			<td width="20"><a href="{$SITE_URL}/?page=patient&amp;action=downloadNotesFile&amp;schedule={$schedule->pubid}&amp;idx={$i}" title="Print File"><img src="{$ENGINE_URL}/images/icons/printer.png" alt="Print File" /></a></td>
			<td width="20"><a href="{$SITE_URL}/?page=patient&amp;action=removeNotes&amp;patient={$patient->pubid}&amp;idx={$i}&amp;_path={urlencode(currentURL())}" title="Delete this file"><img src="{$ENGINE_URL}/images/icons/delete.png" alt="Delete this file" /></a></td>
		</tr>
	{foreachelse}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td>There are no files to display.</td>
			<td width="20"></td>
			<td width="20"></td>
		</tr>
	{/foreach}
	
	</table>
	
</form>
