<h1>{$headerTitle}</h1>

<div class="button left"><a href="{$siteUrl}/?page={$type}&amp;action=add">Add New</a></div>
<table class="form-table">
	<tr>
		{foreach array_keys($data[0]) as $key}
		{if $key != "public_id"}
		<th style="width: auto; padding: 6px 35px">{stringify($key)}</th>
		{/if}
		{/foreach}
		<td>&nbsp;</td>
	</tr>

	{foreach $data as $dataset}
		<tr>
			{foreach $dataset as $k => $d}
			{if $k != "public_id"}
			<td>{$d}</td>
			{/if}
			{/foreach}
			{if !empty ($data[0])}
			<td><a href="{$siteUrl}/?page={$type}&amp;action=edit&amp;id={$dataset['public_id']}"><img src="{$frameworkImg}/pencil.png" alt=""></a></td>
			{/if}
		</tr>
	{/foreach}
	

</table>
