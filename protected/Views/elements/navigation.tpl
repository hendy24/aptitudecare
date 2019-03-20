<ul class="navbar-nav mr-auto custom-nav">
	{if $session->getModule() == "HomeHealth"}
		{$this->loadElement("homeHealthNav")}
	{elseif $session->getModule() == "Dietary"}
		{$this->loadElement("dietaryNav")}
	{elseif $session->getModule() == "Activities"}
		{$this->loadElement("activitiesNav")}
	{elseif $session->getModule() == "Admission"}
		{$this->loadElement("admissionsNav")}
	{/if}
	{$this->loadElement("dataTab")}
</ul>
