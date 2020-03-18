
<div class="container" id="sidebar-nav">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block bg-dark sidebar">
			<div class="sidebar-sticky">
				<ul class="nav flex-column">
					{if $session->getModule() == "HomeHealth"}
						<!-- Home health nav links -->
						{$this->loadElement("homeHealthNav")}
						<!-- /home health nav links -->
					{elseif $session->getModule() == "Admission"}
						{$this->loadElement("admissionsNav")}
					{/if}
					{$this->loadElement("dietaryNav")}
					{$this->loadElement("activitiesNav")}
					<li class="nav-item">
						<a href="#blogSection" class="nav-link dropdown-toggle" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="blogSection">Blog</a>
						<div class="collapse" id="blogSection" data-parent="#sidebar-nav">
							<ul class="nav flex-column ml-4">
								<li class="nav-item"><a href="{$SITE_URL}/?page=blog&amp;action=manage" class="nav-link">Manage Posts</a></li>
								<li class="nav-item"><a href="{$SITE_URL}/?page=blog&amp;action=edit" class="nav-link">New Post</a></li>
							</ul>
						</div>
					</li>
					{$this->loadElement("dataTab")}
				</ul>
			</div>
		</nav>
	</div>
</div>
