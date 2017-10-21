{setTitle title="Manage Healthcare Locations"}
{jQueryReady}

$("#hospital-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				obj.pubid = val.pubid;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#hospital").val(ui.item.value);
		e.target.value = ui.item.label;		
		window.location = SITE_URL + '/?page=hospital&action=edit&hospital=' + ui.item.pubid;
	
	}
});

$("#state").change(function() {
	window.location = SITE_URL + '/?page=hospital&action=manage&state=' + $(this).val();
});



{/jQueryReady}

<h1 class="text-center">Manage Healthcare Facilities</h1>

<div class="right-top">
	<a href="{$SITE_URL}/?page=hospital&action=add" class="button">New Facility</a>
</div>
<br />
<br />

<div class="left">
	<select name="state" id="state">
		<option value="">Select a state...</option>
		{foreach $states as $s}
			<option value="{$s->state}" {if $state == $s->state} selected{/if}>{$s->state}</option>
			{if $s->add_state != ""}<option value="{$s->add_state}" {if $state == $s->add_state} selected{/if}>{$s->add_state}</option>{/if}
		{/foreach}
	</select>	
</div>


<div class="right">
	Search: <input type="text" name="hospital_search" id="hospital-search" size="30" />
</div>


<br />
<br />
<br />

<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Name</th>
		<th>Type</th>
		<th>Location</th>
		<th>Phone</th>
	</tr>
	{foreach $hospitals as $hospital}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td><a href="{$SITE_URL}/?page=hospital&amp;action=edit&amp;hospital={$hospital->pubid}">{$hospital->name}</a></td>
			<td>{$hospital->type}
			<td>{$hospital->address}<br />
				{$hospital->city}, {$hospital->state} {$hospital->zip}</td>
			<td>{$cm->phone}</td>
		</tr>
	{/foreach}
</table>



 <div id="pagination">
 	{$getter->paginationSetMaxLinks(30)}
 	<div class="pagination-link">
		<!-- Shows the page numbers -->
		{$sliceLinks = $getter->paginationGetSliceLinks(2, 2)}
		{if count($sliceLinks) > 0}
			{foreach $sliceLinks as $chunk}
				{foreach $chunk as $slice}
					{if $slice == $getter->paginationGetSlice()}
						<td class="current">
							{$slice}&nbsp;&nbsp;|&nbsp;
						</td>
					{else}
						<td>
							<a href="{$getter->paginationGetURL($slice)}">{$slice}</a>&nbsp;&nbsp;|&nbsp;
						</td>
					{/if}
					{if $slice@last == true && $chunk@last != true}<td class="ellipsis"> ...</td>{/if}
				{/foreach}
			{/foreach}
		{/if}
	</div>

 	<div class="pagination-link">
		 <!-- Shows the next and previous links -->
		 <a href="{$getter->paginationGetURL($getter->paginationPrevSlice())}" class="floatleft pagination-link" rel="previous">Previous</a>
		 &nbsp;&nbsp;
		<a href="{$getter->paginationGetURL($getter->paginationNextSlice())}" rel="next">Next</a>
	</div>

 	<div class="pagination-link">		
		 <!-- prints X of Y, where X is current page and Y is number of pages -->
		 Page {$getter->paginationGetSlice()} of {$getter->paginationNumSlices()}
	</div>
 </div>
