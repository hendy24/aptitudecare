<div class="container">
	<div class="row">
		{foreach from=$folders item=folder}
		<div class="col-md-3 col-lg-2 col-sm-4 col-6">
			<div class="photo-folder">
				<a href="{$SITE_URL}/?module=Dietary&amp;page=photos&amp;action=subfolder&amp;folder_id={$folder->id}">
					<img src="{$IMAGES}/folder-icon.png" alt="">
					<div class="folder-name">{$folder->name}</div>							
				</a>
			</div>
		</div>	
		{/foreach}
	</div>
</div>