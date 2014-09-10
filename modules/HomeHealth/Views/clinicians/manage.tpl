<script>
	$(document).ready(function() {
		$("#filter").change(function(e) {
			e.preventDefault();
			if ($(this).val() == 'all') {
				window.location.href = SiteUrl + "/?module=HomeHealth&page=clinicians&action=manage";
			} else {
				window.location.href = SiteUrl + "/?module=HomeHealth&page=clinicians&action=manage&filter=" + $("#filter option:selected").val();
			}
			
		});
	}); 
</script>


<div id="modules" class="button left"><a href="{$siteUrl}/?page=users&amp;action=add">Add New</a></div>
<div id="locations">
	<select name="location" id="location">
	{foreach $locations as $location}
		<option value="{$location->public_id}" {if isset($input->location)}{if $location->public_id == $input->location} selected{/if}{/if}><h1>{$location->name}</h1></option>
	{/foreach}
	</select>
	<h2>Manage Clinicians</h2>
</div>

<div id="areas">
	<select name="filter" id="filter">
		<option value="all">All</option>
		{foreach $clinicianOptions as $type}
		<option value="{$type->name}"{if $type->name == $filter} selected{/if}>{$type->description}</option>
		{/foreach}
	</select>
</div>

<br><br>


<table class="view">
	{foreach $clinicianTypes as $type}
	<tr>
		<th colspan="5" class="text-center"><h3>{$type->description}</h3></th>
	</tr>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
	</tr>
	{foreach $clinicians as $clinician}
	{if $type->name == $clinician->name}
	<tr>
		<td>{$clinician->fullName()}</td>
		<td>{$clinician->email}</td>
		<td>{$clinician->phone}</td>
		<td class="text-center">
			<a href="{$siteUrl}/?page=users&amp;action=edit&amp;id={$clinician->public_id}">
				<img src="{$frameworkImg}/pencil.png" alt="">
			</a>
		</td>
	</tr>
	{/if}
	{/foreach}
	<tr>
		<td style="border-bottom:none;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-bottom:none;">&nbsp;</td>
	</tr>
	{/foreach}
</table>
