<div class="container">
	<div class="row">
		{foreach from=$folders item=folder}
		<div class="col-md-4 col-lg-3 col-sm-6">
			<img src="{$IMAGES}/folder-icon.png" alt="">
			{$folder->name}
		</div>	
		{/foreach}
	</div>
</div>