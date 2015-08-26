<nav>
	<ul>
		{if $this->getModule() == "HomeHealth"}
			{$this->loadElement("homeHealthNav")}
		{elseif $this->getModule() == "Dietary"}
			{$this->loadElement("dietaryNav")}
		{elseif $this->getModule() == "Activities"}
			{$this->loadElement("activitiesNav")}
		{/if}
		{$this->loadElement("dataTab")}
	</ul>
</nav>
