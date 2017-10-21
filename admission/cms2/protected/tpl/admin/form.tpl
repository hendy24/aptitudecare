{jQueryReady}
	{$obj->renderJqueryReady()}	
{/jQueryReady}
{if count($obj->getMetaByType('textarea_html')) > 0}
	{head}
	<script type="text/javascript" src="{$CDN_ENGINE_URL}/js/jquery/tiny_mce/jquery.tinymce.js"></script>
	{/head}
{/if}

</script>
{if $enableNew == true}<img src="{$ENGINE_URL}/images/icons/table_add.png" /> <a href="{$SITE_URL}/?page=admin&amp;action=form&amp;m={$model}">New</a>{/if}
&nbsp;&nbsp;
<img src="{$ENGINE_URL}/images/icons/arrow_left.png" /> <a href="{$SITE_URL}/?page=admin&amp;action=record_index&amp;slice={$from_slice}&amp;m={$model}{if $filterParamStr != ''}&{base64_decode($filterParamStr)}{/if}">Back to List of Records</a>
<br /><br />
<hr noshade />
{if $adminInstructions != ''}
	<p><strong>{$adminInstructions}</strong></p>
{/if}
{foreach $obj->getActions() as $a => $aName}
&raquo; <a href="{$SITE_URL}/?page=admin&amp;action=executeAction&amp;m={$model}&amp;a={$a}&amp;id={$obj->pk()}">{$aName}</a><br />
{/foreach}
<hr noshade />
{if ($method == "create" && $enableNew == true) || ($method == "update" && $obj->adminCanEdit())}
<br />
<form method="post" action="{$SITE_URL}" enctype="multipart/form-data" accept-charset="UTF-8">
	<input type="hidden" name="page" value="admin" />
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="m" value="{$model}" />
	<input type="hidden" name="from_slice" value="{$from_slice}" />
	<input type="hidden" name="filterParamStr" value="{$filterParamStr}" />
{/if}
	<table cellspacing="0">
		<tbody>
	{foreach from=$flags item="f"}
		<tr>
			<td class="form-label">{$f->description}</td>
			<td><input type="checkbox" name="flags[{$f->id}]"{if $f->hasThisFlag($obj)} checked{/if} />
		</tr>
	{/foreach}

	{foreach from=$metadata key="field" item="fieldmeta"}
		{if $fieldmeta.widget == "hidden" || $fieldmeta.widget == "primary_key"}
		{$obj->renderFieldWidget($field)}
		{else}
			{if $fieldmeta.widget != "off" && $fieldmeta.widget != "file" && $fieldmeta.widget != "priority"}
			<tr class="form-row-{cycle values="0,1"}">
				<td valign="top" class="form-label" nowrap>{$obj->getLabel($field)}</td>
				<td valign="top" class="form-widget">{$obj->renderFieldWidget($field)}
				{if $fieldmeta.instructions != ''}<sup>{$fieldmeta.instructions}</sup>{/if}
				</td>
			</tr>
			{/if}
		{/if}
	{/foreach}
	{if ($method == "create" && $enableNew == true) || ($method == "update" && $obj->adminCanEdit())}
		<tr>
			<td colspan="2" align="right">
			<input type="submit" value="Save &raquo;" />
			&nbsp;&nbsp;
			<input type="submit" name="save_and_new" value="Save and Create New &raquo;" />
			&nbsp;&nbsp;
			<input type="submit" name="save_and_advance" value="Save and Advance &raquo;" />
			</td>
		</tr>
</form>
	{/if}
		</tbody>
	</table>

</form>

{$files = $obj->getMetaByType("file")}
{if $files|@count > 0}
	<h2>Images, Videos, and Documents</h2>
	{if $method== "update"}
		{foreach $files as $field => $fieldmeta}
		<br />
			<form method="post" action="{$SITE_URL}" enctype="multipart/form-data">
			<input type="hidden" name="page" value="admin" />
			<input type="hidden" name="action" value="addFile" />
			<input type="hidden" name="m" value="{$model}" />
			<input type="hidden" name="file_field" value="{$field}" />
			<input type="hidden" name="from_slice" value="{$from_slice}" />
			<strong>{$fieldmeta.label}</strong>:<br />
			<div class="file-frame">
				{$obj->renderFieldWidget($obj->getPrimaryKeyField())}
				{$obj->renderFieldWidget($field)}
				<br />
				<input type="submit" value="Upload File &raquo;" id="file-submit-button-{$field}" style="display: none;" />
			</div>
			</form>
		{/foreach}
	{else}
		(After you hit Save, you will be able to attach files to this record.)
	{/if}
{/if}
