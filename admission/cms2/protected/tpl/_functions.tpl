{function name=html_options_cms}
<select name="{$_name}" id="{$id|default:$_name}"{if $class != ''} class="{$class}"{/if}{if $style != ''} style="{$style}"{/if}{if $multiple == true} multiple{/if}>
	<option value=''>Select...</option>
	{foreach from=$set item="obj"}
	{if $keyfield != ''}
	<option value="{$obj->$keyfield}"{if $obj->$keyfield == $selected} selected{/if}>
	{else}
	<option value="{$obj->pk()}"{if $obj->pk() == $selected} selected{/if}>
	{/if}
	{if $valfield != ''}
	{$obj->$valfield}
	{else}
	{$obj->getTitle()}
	{/if}
	{/foreach}
	{if $other == true}
	<option value="_other">Other...</option>
	{/if}
</select>
{/function}