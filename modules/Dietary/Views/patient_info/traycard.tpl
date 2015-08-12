<script>
	//window.onload = function () { window.print(); }
</script>

<h1>{$patient->fullName()}</h1>

<table class="form">
	<tr>
		<th colspan="4">General Information</th>
	</tr>
	<tr>
		<td class="text-strong">Admit Date:</td>
		<td>{$schedule->datetime_admit|date_format: "%D"}</td>
		<td class="text-strong">Birthdate:</td>
		<td>{$patient->date_of_birth|date_format: "%D"}</td>
	</tr>
	<tr>
		<td class="text-strong">Room #</td>
		<td>{$schedule->number}</td>
		<td class="text-strong">Age:</td>
		<td>{$age}</td>
	</tr>
	<tr>
		<th colspan="4">Diet Information</th>
	</tr>
	<tr>
		<td class="text-strong">Texture:</td>
		<td>{$diet->texture}</td>
		<td class="text-strong">Orders:</td>
		<td>{$diet->orders}</td>
	</tr>
	<tr>
		<td class="text-strong">AM Snack</td>
		<td>
			<ul>
			{foreach from=$am_snacks item=item}
				<li>{$item->name|default: "None"}</li>
			{/foreach}
			</ul>
		</td>
		<td class="text-strong">Portion Size:</td>
		<td>{$diet->portion_size}</td>
	</tr>
	<tr>
		<td class="text-strong">PM Snack</td>
		<td>
			<ul>
			{foreach from=$pm_snacks item=item}
				<li>{$item->name|default: "None"}</li>
			{/foreach}
			</ul>			
		</td>

		<td class="text-strong">Bedtime Snack</td>
		<td>
			<ul>
			{foreach from=$bedtime_snacks item=item}
				<li>{$item->name|default: "None"}</li>
			{/foreach}
			</ul>			
		</td>
	</tr>
	<tr>
		<td class="text-strong">Allergies</td>
		<td>
			<ul>
			{foreach from=$allergies item=item}
				<li>{$item->name|default: "None"}</li>
			{/foreach}
			</ul>			
		</td>

		<td class="text-strong">Dislikes</td>
		<td>
			<ul>
			{foreach from=$dislikes item=item}
				<li>{$item->name|default: "None"}</li>
			{/foreach}
			</ul>			
		</td>
	</tr>
	<tr>
		<td class="text-strong">Special Requests:</td>
		<td colspan="3">{$diet->special_requests}</td>
	</tr>
	<tr>
		<td class="text-strong">Date:</td>
		<td>{$smarty.now|date_format: "%D"}</td>
		<td class="text-strong">Date Changed:</td>
		<td>{if !empty($diet->datetime_modified)}{$diet->datetime_modified|date_format: "%D"}{else}{$diet->datetime_created|date_format: "%D"}{/if}</td>
	</tr>
</table>