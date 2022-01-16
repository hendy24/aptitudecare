{if count($modules) > 1}
	<div class="dropdown">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="moduleDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			{$this->module}
		</button>
		<div class="dropdown-menu" aria-labelledby="moduleDropdownButton">
			{foreach $modules as $m}
			<a class="dropdown-item {if $module == $m->name} selected{/if}" href="{$SITE_URL}/?module={$m->name}" >{$m->name}</a>
			{/foreach}
		</div>
	</div>

{/if}