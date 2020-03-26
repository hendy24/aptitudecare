
<div class="container" id="sidebar-nav">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block bg-dark sidebar">
			<div class="sidebar-sticky">
				<ul class="nav flex-column">
					{if $session->getModule() == 'HomeHealth'}
						<!-- Home health nav links -->
						{$this->loadElement('homeHealthNav')}
						<!-- /home health nav links -->
					{elseif $session->getModule() == 'Admission'}
						{$this->loadElement('admissionsNav')}
					{/if}

					<!-- dietary nav section -->
					{if $auth->hasPermission('manage_menu')}
						{$this->loadElement('dietaryNav')}
					{/if}
					<!-- /dietary nav section -->

					<!-- activities nav section -->
					{if $auth->hasPermission('manage_activities')}
						{$this->loadElement('activitiesNav')}
					{/if}
					<!-- activities nav section -->

					<!-- blog nav section -->
					{if $auth->hasPermission('manage_blog')}
						{$this->loadElement('blogNav')}
					{/if}
					<!-- /blog nav section -->


					{$this->loadElement('dataTab')}
				</ul>
			</div>
		</nav>
	</div>
</div>
