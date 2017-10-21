{jQueryReady}
	$(".delete-trigger").click(function(e) {
		e.preventDefault();
		$.alerts.href = $(this).attr("href");
		jConfirm("Are you sure you want to delete this record? This action cannot be undone.", "Confirm Delete", function (r) {
			if (r == true) {
				location.href = $.alerts.href;
			} else {
				return false;
			}
		});
		return false;
	});
	$(".export-csv").click(function(e) {
		e.preventDefault();
		$.alerts.href = $(this).attr("href");
		jConfirm("Are you sure you want to run the export? This could take a long time.", "Confirm Export", function (r) {
			if (r == true) {
				location.href = $.alerts.href;
			} else {
				return false;
			}
		});
		return false;
	});


	{if $model_obj->isSortable()}
	$(".record-list").sortable({
		cursor: 'pointer',
		update: function(event, ui) {
			$.ajax({
				url: '{{$SITE_URL}}/?page=admin&action=setPriorities&m={{$model}}&' + $(this).sortable("serialize")
			});
		}
	});
	{/if}
	
	$("#show-filters").click(function(e) {
		e.preventDefault();
		$("#filters-show").hide();
		$("#filters-hide").show();
		$("#record-index-filters").show();	
	});

	$("#hide-filters").click(function(e) {
		e.preventDefault();
		$("#filters-show").show();
		$("#filters-hide").hide();
		$("#record-index-filters").hide();	
	});
	
	$("#clear-filters").click(function(e) {
		e.preventDefault();
		$("#filter-form select").each(function(i, elem) {
			$("option:eq(0)", elem).attr('selected', 'selected');
		});
		$("#filter-form input[type=text]").val('');
		$("#filter-form").submit();
	});
	
	{if $filter != ''}
		{* Filters are engaged. Hide the "show filters" link, show the "hide filters" link *}
		$("#filters-show").hide();
		$("#filters-hide").show();
		$("#record-index-filters").show();			
	{else}
		$("#filters-show").show();
		$("#filters-hide").hide();	
		$("#record-index-filters").hide();	
	{/if}
{/jQueryReady}

<h2>{$modelTitle}</h2>
<a href="{$SITE_URL}/?page=admin&amp;action=exportCSV&amp;m={$model}" class="export-csv">Export all records to CSV</a>
<br />
Records per page:
{foreach ['10','25','50','75','100','9999999999'] as $step}
	{if $step == '9999999999'}
		{$stepName = 'All Records'}
		{$url = setURLVar(currentURL(), 'slice', 1)}
	{else}
		{$stepName = $step}
		{$url = currentURL()}
	{/if}
	{if $sliceSize == $step}
		[ <b>{$stepName}</b> ]&nbsp;&nbsp;
	{else}
		[ <a href="{setURLVar($url, 'sliceSize', $step)}">{$stepName}</a> ]&nbsp;&nbsp;
	{/if}
{/foreach}
<br />
<br />
{if $enableSearch == true}
	<form method="get" action="{$SITE_URL}">
		<input type="hidden" name="page" value="admin" />
		<input type="hidden" name="action" value="record_index" />
		<input type="hidden" name="m" value="{$model}" />
	Search for records: <input type="text" name="query" value="{$query}" size="20" /> <input type="submit" value="Submit" />
	</form>
	<br /><br />
{/if}
{if $enableNew == true || ($admin_auth->is_root == 1 && $enableNew == "root")}
	<img src="{$ENGINE_URL}/images/icons/table_add.png" /> <a href="{$SITE_URL}/?page=admin&amp;action=form&amp;m={$model}">New</a>
	<br /><br />
{/if}
{if $isSortable && count($records) > 0}
	<i>You may click the title of any record and drag to change its position in the sort order.</i>
	<br /><br />
{/if}
{if count($records) == 0}
	<i>There are no records to display. <a href="{$SITE_URL}/?page=admin&amp;action=form&amp;m={$model}">Click here to add a new record.
{/if}

<span id="filters-show"><a href="#" id="show-filters">+ Show Filters</a></span>
<span id="filters-hide"><a href="#" id="hide-filters">- Hide Filters</a></span>
<div id="record-index-filters">
<form method="get" action="{$SITE_URL}" id="filter-form">
	<input type="hidden" name="page" value="admin" />
	<input type="hidden" name="action" value="record_index" />
	<input type="hidden" name="m" value="{$model}" />
	<input type="hidden" name="filter" value="1" />
	{if count($filterFields) > 0}
		<strong>Filter on:</strong>
		<br />
		{if $filter != ''}
		<img src="{$ENGINE_URL}/images/icons/cancel.png" /> <a href="#" id="clear-filters">Clear Filters</a>
		<br />
		{/if}
		<table>
		{if count($filterFields.related_single) > 0}
			{foreach $filterFields.related_single as $field}
			<tr>
				{$widget = $model_obj->getFieldWidget($field.field)}
				{$widget->setOption("forceFirstEmpty", true)}
				{$widget->setProperty("value", $filter[$field.field])}
				<td>{$widget->getForeignModelTitle()}:</td>
				<td>{$widget->render()}</td>
			</tr>
			{/foreach}
		</table>
		{/if}
		<input type="submit" value="Apply Filters &raquo;" />
	{/if}	
</form>
</div>
<br />
<br />

{foreach $modelActions as $a => $aName}
&raquo; <a href="{$SITE_URL}/?page=admin&amp;action=executeModelAction&amp;m={$model}&amp;a={$a}">{$aName}</a><br />
{/foreach}
<br />
<ul class="record-list">
{foreach $records as $idx => $r}
	<li id="{$pk_col}_{$r->pk()}">
	{if $r->adminCanEdit()}
	<img src="{$ENGINE_URL}/images/icons/table_edit.png" />
	<a href="{$SITE_URL}/?page=admin&amp;action=form&amp;m={$model}&amp;{$pk_col}={$r->pk()}&amp;from_slice={$model_obj->paginationGetSlice()}">{if $r->adminCanEdit() == false}View{else}Edit{/if}</a>
	{/if}
	&nbsp;&nbsp;
	{if $r->adminCanDelete()}<img src="{$ENGINE_URL}/images/icons/table_delete.png" />{/if}
	{if $r->adminCanDelete()}<a class="delete-trigger" id="delete-trigger-{$r->pk()}" href="{$SITE_URL}/?page=admin&amp;action=delete&amp;m={$model}&amp;{$pk_col}={$r->pk()}">Delete</a>{/if}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<span class="record-title">
		<a href="{$SITE_URL}/?page=admin&amp;action=form&amp;m={$model}&amp;{$pk_col}={$r->pk()}&amp;from_slice={$model_obj->paginationGetSlice()}&filterParamStr={base64_encode($filterParamStr)}">{$r->getTitle()}</a>{if $r->adminCanEdit() == false} <i>read-only</i>{/if}</span>	
		{foreach from=$flags item="flag"}
			<span class="record-flag">{if $flag->hasThisFlag($r)}<img src="{$ENGINE_URL}/images/icons/asterisk_yellow.png" /> {$flag->description}{/if}</span>
		{/foreach}
	</li>

{/foreach}

</ul>
{if $enablePagination == true}
	{assign var="paginationNumSlices" value=$model_obj->paginationNumSlices()}
	{if $paginationNumSlices > 1}
		{if $model_obj->paginationGetSlice() > 1}
			<a href="{$model_obj->paginationGetURL($model_obj->paginationPrevSlice())}">&laquo;</a>
		{/if}
		{section name=slices start=0 loop=$paginationNumSlices}
			{if $model_obj->paginationGetSlice() == $smarty.section.slices.iteration}
				{$smarty.section.slices.iteration}
			{else}
				<a href="{$model_obj->paginationGetURL($smarty.section.slices.iteration)}">				
				{$smarty.section.slices.iteration}</a>
			{/if}
			&nbsp;&nbsp;
		{/section}
		{if $model_obj->paginationLastSlice() != $model_obj->paginationGetSlice()}
			<a href="{$model_obj->paginationGetURL($model_obj->paginationNextSlice())}">&raquo;</a>
		{/if}
	{/if}
{/if}