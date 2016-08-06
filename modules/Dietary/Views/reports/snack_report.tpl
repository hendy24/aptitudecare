<style>
  .container{
    width: 75%;
    margin: 20px auto;
    text-align: left;
    font-weight: normal;
    border-collapse: collapse;
  }
</style>

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>

</div>

<h1>Snack Report</h1>
<div class="container">
  <form action="{$SITE_URL}" method="POST">
    <input type="hidden" name="module" value="Dietary">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="snack_report">
    <input type="hidden" name="location" value="{$location->public_id}">
    <table class="center">
      <tr>
        <th>Room</th>
        <th>Patient Name</th>
        <th>Diet</th>
        <th>Allergies</th>
        <th>Snack</th>
        <th>Time</th>
      </tr>
      {foreach from=$snacks item=snack key=time}
      <tr class="{cycle values="row,rowalt"}">
        <td>{$snack->number}</td>
        <td>{$snack->patient_name}</td>
        <td>{$snack->diet}</td>
        <td>{$snack->allergy}</td>
        <td>{$snack->snack}</td>
        <td>{$snack->time}</td>
      </tr>
      {/foreach}
    </table>
  </form>
</div>