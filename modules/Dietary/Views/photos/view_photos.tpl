<!-- /modules/Dietary/Views/photos/view_photos.tpl -->
<script>
	$(document).ready(function() {
		{literal}
	  	var options = {minMargin: 5, maxMargin: 15, itemSelector: ".item", firstItemClass: "first-item"};

		$(".fancybox").fancybox({
			prevEffect	: 'none',
			nextEffect	: 'none',
			helpers		: {
				title	: { type : 'inside' },
				buttons	: {}

			}
		});
		{/literal}

	  	$(".container").rowGrid(options);
	 	//nendless scrolling
		$(window).scroll(function() {
		    if($(window).scrollTop() + $(window).height() == $(document).height()) {
		    	$(".container").append("<div class='item'><img src='" + photos + "' width='140' height='100' /></div>");
		        $(".container").rowGrid("appended");
		    }
		});

	});
</script>
<h1>View Photos</h1>

<div class="grid">
{foreach from=$photos item=photo}
	<a class="fancybox" rel="fancybox-thumb" href="{$SITE_URL}/files/dietary_photos/{$photo->filename}" title="{$photo->name}: {$photo->description}">
		<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{$photo->filename}" class="photo-image" alt="">
	</a>
{/foreach}
</div>