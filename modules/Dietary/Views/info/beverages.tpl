<div class="row text-center">
  {$this->loadElement("selectLocation")}
</div>

<h1>{$location->name} Beverages</h1>
<div class="row">

{foreach from=$beverages item=bev}
    <div class="col-sm-6">
      <input type="text" class="bev-input" value="{$bev->name}">
    </div>
    {if $bev@iteration is div by 2}
    <!--close row -->
    </div>
    {/if}
{/foreach}
</div>
