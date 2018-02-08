<script type="text/javascript">
	$(document).ready(function() {
		$("#location").change(function() {
			window.location.href = SITE_URL + "/?module={$this->module}&page={$this->page}&action={$this->action}&location=" + $("option:selected", this).val();
		});
	});
</script>

<div id="page-header">
	<div id="action-left">
		<a class="button" href="{$SITE_URL}/?page=users&amp;action=add&amp;location={$location_id}">Add New</a>
	</div>
	<div id="center-title">
		<div id="locations">
			<select name="location" id="location">
			{foreach $locations as $location}
				<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}><h1>{$location->name}</h1></option>
			{/foreach}
			</select>
		</div>
	</div>
	<div id="action-right"></div>
</div>


<h2>Manage Clinicians</h2>

<table class="view">
	{foreach $clinicianTypes as $type}
	<tr>
		<td colspan="5"><h3 style="margin:2px 0">{$type->description}</h3></td>
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
			<a href="{$SITE_URL}/?page=users&amp;action=edit&amp;location={$loc->public_id}&amp;id={$clinician->public_id}">
				<img src="{$FRAMEWORK_IMAGES}/pencil.png" alt="">
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
