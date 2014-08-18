<h1>{$headerTitle}</h1>

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
			<td><a href="{$siteUrl}/?module=HomeHealth&amp;page=data&amp;action=edit&amp;type=case_managers&amp;id={$dataset['public_id']}"><img src="{$frameworkImg}/pencil.png" alt=""></a></td>
			{/if}
		</tr>
	{/foreach}
	

</table>
