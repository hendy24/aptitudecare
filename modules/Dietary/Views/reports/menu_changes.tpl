<script>
	$(document).ready(function() {
		$("#num-days").change(function() {
			window.location.href = SITE_URL + "/?module={$this->module}&page={$this->page}&action={$this->action}&days=" + $("option:selected", this).val();
		});
	});
</script>


<div id="page-header">
	<div id="action-left">
		&nbsp;
	</div>
	<div id="center-title">
		<h1>Menu Changes</h1>
	</div>
	<div id="action-right">
		<select name="num_days" id="num-days">
			{foreach from=$numberOfDays item=days key=key name=day}
				<option value="{$key}" {if $key == $numDays} selected{/if}>{$days}</option>
			{/foreach}
		</select>	
	</div>
</div>

<div class="clear"></div>

<table class="view" style="width: 35%">
	<tr>
		<th>Location</th>
		<th>Number of Changes</th>
	</tr>
	{foreach from=$menuChanges item=change key=key name=change}
	<tr>
		<td class="bold-link"><a href="{$url}&amp;location={$change->public_id}">{$change->name}</a></td>
		<td class="text-right">{$change->count}</td>
	</tr>
	{/foreach}
</table>


