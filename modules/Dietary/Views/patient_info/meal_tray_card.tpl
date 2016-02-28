{if $allTrayCards}
  {foreach from=$trayCardCols item=traycards}
    {foreach from=$traycards key=k item=item name=count}
      {include file="$MODULES_DIR/Dietary/Views/patient_info/traycard_content.tpl"}
    {/foreach}
  {/foreach}
{else}
  {foreach from=$trayCardCols key=k item=item name=count}
    {include file="$MODULES_DIR/Dietary/Views/patient_info/traycard_content.tpl"}
  {/foreach}
{/if}


