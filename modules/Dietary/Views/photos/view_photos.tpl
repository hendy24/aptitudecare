<!-- /modules/Dietary/Views/photos/view_photos.tpl -->
<style>
	.photoFilter.form-control{
		width:150px;
		padding: 0px 12px;
	}
	#page-header.mb-45{
		margin-bottom: 45px;
	}
	.imageBox{
		height:250px;
	}
	dir-pagination-controls .pagination{
		margin:0px;
		margin-top: 20px;
	}
	#center-title{
		margin-top: -30px;
	}
	div#action-right{
		float:right;
	}
</style>
<link href="{$CSS}/plugins/bootstrap_columns.css" rel="stylesheet" >

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
<script src="{$JS}/plugins/dirPagination.js" type="text/javascript"></script>
<script src="{$JS}/angular_js/photo_app.js" type="text/javascript"></script>



<div class="grid" ng-app="app" ng-controller="homeController">
	<div id="page-header" class="mb-45">
		<div id="action-left"></div>
		<div id="center-title">
			<h1>View Photos</h1>
		</div>
		<div id="action-right">
			<input ng-model="filter" placeholder="Filter photos" class="photoFilter form-control"/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2 imageBox" dir-paginate="photo in filteredPhotos = (photos | filter:filter)| itemsPerPage: 15 | filter:filter" >
			<a class="fancybox" rel="fancybox-thumb" href="{$SITE_URL}/files/dietary_photos/{literal}{{photo.filename}}{/literal}" title="{literal}{{photo.name}}{/literal}: {literal}{{photo.description}}{/literal}">
				<img src="{$SITE_URL}/files/dietary_photos/thumbnails/{literal}{{photo.filename}}{/literal}" class="photo-image" alt="">
			</a>
			<br>
			<span ng-repeat="tag in photo.tags" ng-if="$index + 1  < photo.tags.length"> {literal}{{tag}}{/literal}, </span>
			<span ng-repeat="tag in photo.tags" ng-if="$index + 1 == photo.tags.length"> {literal}{{tag}}{/literal} </span>
		</div>
	</div>

	<dir-pagination-controls></dir-pagination-controls>
</div>