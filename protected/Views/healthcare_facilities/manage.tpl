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
							url: SITE_URL,
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
			window.location = SITE_URL + "/?page=data&action=manage&type=" + $("#page").val() + "&orderBy=" + order;
		});


		$("#locations").change(function() {
			window.location = SITE_URL + "/?page=data&action=manage&type=" + $("#page").val() + "&location=" + $("#locations option:selected").val();
		});

	});
</script>


<div id="action-left"><a href="{$SITE_URL}/?page={$type}&amp;action=add&amp;location={$location_id}" class="button">Add New</a></div>
<div id="center-title">
	<div id="locations">
		<select name="location" id="location">
		{foreach $locations as $location}
			<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}><h1>{$location->name}</h1></option>
		{/foreach}
		</select>
	</div>
</div>
<br>
<br>
<h2>Manage Healthcare Facilities</h2>

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
				<a href="{$SITE_URL}/?page={$type}&amp;action=edit&amp;id={$dataset['public_id']}">
					<img src="{$FRAMEWORK_IMAGES}/pencil.png" alt="">
				</a>
			</td>
			<td class="text-center">
				<a href="" value="{$dataset['public_id']}" class="delete">
					<img src="{$FRAMEWORK_IMAGES}/delete.png" alt="">
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
			<a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count={$pagination->current_page - 1}">&laquo;&nbsp; Previous</a>
		{/if}
		{for $i=max($pagination->current_page-5, 1); $i<=max(1, min($pagination->num_pages,$pagination->current_page+5)); $i++}
			{if $i == $pagination->current_page}
				<strong><a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count={$i}" class="page-numbers">{$i}</a></strong>
			{else}
				<a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count={$i}" class="page-numbers">{$i}</a>
			{/if}
		{/for}
		{if $pagination->current_page != $pagination->num_pages}
			<a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count={$pagination->current_page + 1}">Next &nbsp;&raquo;</a>
		{/if}
	</div>
	{if $pagination->num_pages > 20}
	<div id="beginning-end">
		<a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count=1" class="page-numbers">First Page</a>
		<a href="{$SITE_URL}?page=data&amp;action=manage&amp;type={$page}&amp;location={$location_id}&amp;page_count={floor($pagination->num_pages)}" class="page-numbers">Last Page</a>
	</div>
	{/if}
{/if}

<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>