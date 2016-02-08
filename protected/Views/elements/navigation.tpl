<nav>
	<ul>
		{if $this->getModule() == "HomeHealth"}
			{$this->loadElement("homeHealthNav")}
		{elseif $this->getModule() == "Dietary"}
			{$this->loadElement("dietaryNav")}
		{elseif $this->getModule() == "Activities"}
			{$this->loadElement("activitiesNav")}
		{elseif $this->getModule() == "Admission"}
			{$this->loadElement("admissionsNav")}
		{/if}
		{$this->loadElement("dataTab")}
	</ul>
</nav>

