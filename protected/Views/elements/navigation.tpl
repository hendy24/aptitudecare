
<div class="container" id="sidebar-nav">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block sidebar">

			<div class="sidebar-sticky">
				<ul class="nav flex-column">
					{if $auth->hasModuleAccess('HomeHealth')}
						<!-- Home health nav links -->
						{$this->loadElement('homeHealthNav')}
						<!-- /home health nav links -->
					{elseif $auth->hasModuleAccess('Admissions')}
						{$this->loadElement('admissionsNav')}
					{/if}

					<!-- dietary nav section -->
					{if $auth->hasModuleAccess('Dietary')}
						{$this->loadElement('dietaryNav')}
					{/if}
					<!-- /dietary nav section -->

					<!-- activities nav section -->
					{if $auth->hasModuleAccess('Activities')}
						{$this->loadElement('activitiesNav')}
					{/if}
					<!-- activities nav section -->

					<!-- blog nav section -->
					{if $auth->hasModuleAccess('Blog')}
						{$this->loadElement('blogNav')}
					{/if}
					<!-- /blog nav section -->


					{$this->loadElement('dataTab')}
				</ul>
			</div>
		</nav>
	</div>
</div>
