<nav>
	<ul>
		{if $this->module == "HomeHealth"}
			{$this->loadElement("homeHealthNav")}
		{elseif $this->module == "Dietary"}
			{$this->loadElement("dietaryNav")}
		{/if}

		{$this->loadElement("dataTab")}
	</ul>
</nav>
