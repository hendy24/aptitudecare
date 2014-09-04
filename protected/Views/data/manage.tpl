<script>
	$(document).ready(function() {
		$(".delete").click(function(e) {
			e.preventDefault();
			var dataRow = $(this).parent().parent();
			console.log(dataRow);
			var item = $(this);
			$("#dialog").dialog({
				buttons: {
					"Confirm": function() {
						var row = item.children().next($(".public-id"));
						var id = row.val();
							
						$.ajax({
							type: 'post',
							url: SiteUrl,
							data: {
								page: $("#page").val(),
								action: 'deleteId',
								id: id,
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

		$(".order").click(function(e) {
			e.preventDefault();
			var order = $(this).next("input").val();
			console.log
			window.location = SiteUrl + "/?page=data&action=manage&type=" + $("#page").val() + "&orderBy=" + order;
		});


		$("#locations").change(function() {
			window.location = SiteUrl + "/?page=data&action=manage&type=" + $("#page").val() + "&location=" + $("#locations option:selected").val();
		});

	});
</script>

<h1>{$headerTitle}</h1>
<br>

<div id="modules" class="button left"><a href="{$siteUrl}/?page={$type}&amp;action=add">Add New</a></div>
<div id="locations">
	Location: <select name="locations" id="locations">
		<option value="">Select a location...</option>
		{foreach $locations as $location}
		<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}>{$location->name}</option>
		{/foreach}
	</select>
</div>


<input type="hidden" id="page" name="page" value="{$page}" />
<table class="view">
	<tr>
		{foreach array_keys($data[0]) as $key}
		{if $key != "public_id"}
		<th style="width: auto; padding: 6px 35px">
			<a href="" class="order">{stringify($key)}</a>
			<input type="hidden" name="order" id="order-value" value="{$key}" />
		</th>
		{/if}
		{/foreach}
		{if !empty ($data[0])}
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Delete</span></th>
		{/if}
	</tr>

	{foreach $data as $dataset}
		<tr class="data-row">
			{foreach $dataset as $k => $d}
			{if $k != "public_id"}
			<td>
				{if $k == "email"}
					<a href="mailto:{$d}" target="_top">{$d}</a>
				{else}
					{$d}
				{/if}
			</td>
			{/if}
			{/foreach}
			{if !empty ($data[0])}
			<td class="text-center">
				<a href="{$siteUrl}/?page={$type}&amp;action=edit&amp;id={$dataset['public_id']}">
					<img src="{$frameworkImg}/pencil.png" alt="">
				</a>
			</td>
			<td class="text-center">
				<a href="" value="{$dataset['public_id']}" class="delete">
					<img src="{$frameworkImg}/delete.png" alt="">
					<input type="hidden" name="public_id" class="public-id" value="{$dataset['public_id']}" />
				</a>
			</td>
			{/if}
		</tr>
	{/foreach}
</table>


{if isset ($pagination)}
	<div id="pagination">
		{if $pagination->current_page != 1}
			<a href="{$siteUrl}?page=data&amp;action=manage&amp;type={$page}&amp;page_count={$pagination->current_page - 1}">&laquo;&nbsp; Previous</a>
		{/if}
		{for $i=1; $i<=$pagination->num_pages; $i++}
			{if $i == $pagination->current_page}
				<strong><a href="{$siteUrl}?page=data&amp;action=manage&amp;type={$page}&amp;page_count={$i}" class="page-numbers">{$i}</a></strong>
			{else}
				<a href="{$siteUrl}?page=data&amp;action=manage&amp;type={$page}&amp;page_count={$i}" class="page-numbers">{$i}</a>
			{/if}
		{/for}
		{if $pagination->current_page != $pagination->num_pages}
			<a href="{$siteUrl}?page=data&amp;action=manage&amp;type={$page}&amp;page_count={$pagination->current_page + 1}">Next &nbsp;&raquo;</a>
		{/if}
	</div>
{/if}

<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>