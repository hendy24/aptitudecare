<script>
	$(document).ready(function() {
		$('#phone').mask("(999) 999-9999");

	});
	
</script>


<h1>Add a new {$headerTitle}</h1>
<br>
<form name="add_user" id="add-user" method="post" action="{$siteUrl}">
	<input type="hidden" name="page" value="healthcare_facilities" />
	<input type="hidden" name="action" value="submitAdd" />
	<input type="hidden" name="submit" value="true" />
	<input type="hidden" name="path" value="{$current_url}" />

	<table class="form-table">
		

	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><input class="right" type="submit" value="Save" /></td>
	</tr>
		
	</table>
</form>
