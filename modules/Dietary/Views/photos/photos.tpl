<div class="container">
	{if !isset($photos)}
	<div class="row">
		{foreach from=$categories item=category}
		<div class="col-md-3 col-lg-2 col-sm-4 col-6">
			<div class="photo-folder">
				<a href="{$SITE_URL}/?module=Dietary&amp;page=photos&amp;action=photos&amp;{if $facility_selected}facility_id{elseif $subcat_selected}subcategory_id{else}category_id{/if}={$category->id}">
					<img src="{$IMAGES}/folder-icon.png" alt="">
					<div class="folder-name">{$category->name}</div>							
				</a>
			</div>
		</div>	
		{/foreach}
		{if !$subcat_selected}
		<div class="col-md-3 col-lg-2 col-6">
			<div class="photo-folder">
				<a href="{$SITE_URL}/?module=Dietary&amp;page=photos&amp;action=photos&amp;category_id=all_locations">
					<img src="{$IMAGES}/folder-icon.png" alt="">
					<div class="folder-name">Individual Locations</div>	
				</a>
			</div>
		</div>
		{/if}
	</div>
	{if $subcat_selected}
	<button class="btn btn-primary right" onclick="history.back()">Back</button>
	{/if}
	{else}
	<div class="row">
		{foreach from=$photos item=photo}
		<div class="col-md-3 col-lg-2">
			<div class="item-image">
				<a href="{$FILES}/dietary_photos/{$photo->filename}" rel="shadowbox">
					<img src="{$FILES}/dietary_photos/thumbnails/{$photo->filename}" class="img-thumbnail" alt="{$photo->filename}">
				</a>
			</div>
		</div>
		{/foreach}
	</div>
	<button class="btn btn-primary right" onclick="history.back()">Back</button>
	{/if}
	
</div>