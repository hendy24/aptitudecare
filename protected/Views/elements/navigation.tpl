<nav>
	<ul>
		{if $this->getModule() == "HomeHealth"}
			{$this->loadElement("homeHealthNav")}
		{elseif $this->getModule() == "Dietary"}
			{$this->loadElement("dietaryNav")}
		{/if}
		{$this->loadElement("dataTab")}
	</ul>
</nav>
