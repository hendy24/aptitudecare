{if $allTrayCards}
  {foreach from=$trayCardCols item=traycards name=count}
    {foreach from=$traycards key=k item=item name=count}
      {include file="$MODULES_DIR/Dietary/Views/patient_info/traycard_content.tpl"}
      {if $smarty.foreach.count.iteration is div by 3}
        <div class="page-break"></div>
      {/if}
    {/foreach}
  {/foreach}
{else}
  {foreach from=$trayCardCols key=k item=item name=count}
    {include file="$MODULES_DIR/Dietary/Views/patient_info/traycard_content.tpl"}
  {/foreach}
{/if}
