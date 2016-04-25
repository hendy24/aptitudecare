<!-- /modules/Dietary/Views/photos/view_photos.tpl -->
<script>
	$(document).ready(function() {
		$(".fancybox").fancybox({
			openEffect: "none",
			closeEffect: "none"
		});

	});
</script>
<div id="page-header">
	<div id="action-left"></div>
	<div id="center-title">
		<h1>View Photos</h1>
	</div>
	<div id="action-right">
	</div>
</div>
<div id="image-container">
	{foreach from=$photos item=photo}
		<a class="fancybox image-item" rel="fancybox-thumb" href="{$SITE_URL}/files/dietary_photos/{$photo->filename}" title="{$photo->name}: {$photo->description}">
			<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" class="photo-image" alt="">
		</a>
	{/foreach}
</div>
<div class="clear"></div>
{$var = "{$SITE_URL}?module=Dietary&page=photos&action=view_photos"}
{include file="elements/pagination.tpl"}	

