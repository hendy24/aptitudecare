<!-- /modules/Dietary/Views/photos/manage_photos.tpl -->
<div id="page-header">
	<div id="action-left">&nbsp;</div>
	<div id="center-title">
		{$this->loadElement("selectLocation")}
	</div>
	<div id="action-right">{* Search: <input type="text" id="search-pictures" size="30"> *}</div>
</div>

<h1>Manage Photos</h1>

<br><br>
{if !empty ($photos)}
	{foreach from=$photos item=photo name=count key=key}
	<div id="manage-photos" class="row">
		<form id="photo-info-{$key}" method="post" action="{$SITE_URL}">
			<input type="hidden" id="form-key-{$key}" value="{$key}">
			<div class="col-md-4">
				<a href="{$SITE_URL}/files/dietary_photos/{$photo->filename}" rel="fancybox-thumb" class="fancybox" title="{$photo->name}: {$photo->description}">
					<img class="float-left" src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:250px;margin-top: 25px;" alt="">
				</a>
			</div>
			<div class="col-md-8">
				<div class="form-group col-xs-6">
					<input type="text" name="name" value="{$photo->name}" placeholder="Name" size="20">
				</div>
				<div class="form-group col-xs-6"></div>
					<ul class="photo-tag">
						{foreach from=$photo->tag item=tag}
						<li>{$tag->name}</li>
						{/foreach}
					</ul>
				</div>
				<div class="form-group">
					<textarea name="description" class="description float-left" placeholder="Photo description" cols="80" rows="4" >{$photo->description}</textarea>
				</div>
			</div>
		</form>
	</div>
	<br><br><hr><br><br>
	{/foreach}




<br><br><br><br>

			<table class="form">
				<input type="hidden" class="photo-id" value="{$photo->public_id}">
				<tr>
					<td rowspan="2">
						<a class="fancybox" rel="fancybox-thumb" href="{$SITE_URL}/files/dietary_photos/{$photo->filename}" title="{$photo->name}: {$photo->description}">
							<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" style="width:100px" alt=""></td>
						</a>
					<td>
						<strong>Name:</strong><br>
						<input type="text" name="name" value="{$photo->name}" size="69">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong>Description:</strong><br>
						<textarea name="description" class="description" placeholder="Photo description" cols="80" rows="4" >{$photo->description}</textarea>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><strong>Tags:</strong><br>
						<ul class="photo-tag">
							{foreach from=$photo->tag item=tag}
							<li>{$tag->name}</li>
							{/foreach}
						</ul>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>
						<strong>User Created:</strong><br>
						{$photo->username}
					</td>
					<td>
						<strong>Facilty:</strong><br>
						{$photo->location_name}
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<input type="radio" name="approved" value="1" {if $photo->approved} checked="checked"{/if}>Approve<br>
						<input type="radio" name="approved" value="0" {if $photo->approved == 0} checked="checked"{/if}>Reject
					</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3" class="text-right"><input type="submit" id="save-photo" value="Save"></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>
		</form>

	<div class="clear"></div>
	<div id="page-links">
		{$var = "{$SITE_URL}?module=Dietary&page=photos&action=manage_photos&facility={$facility->public_id}"}
		{include file="elements/pagination.tpl"}
	</div>


{else}
	<h2>The selected location has not yet uploaded any photos.</h2>
{/if}

<div id="dialog">Are you sure you want to reject these photos? The photos will be deleted and will not be recoverable.</div>

<script type="text/javascript" src="{$JS}/managePhotos.js"></script>
