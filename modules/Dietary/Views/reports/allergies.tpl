<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Allergies & Dislikes Report</h1>
    {if $isPDF}
      <h2>{$smarty.now|date_format}</h2>
    {/if}
  </div>
  <div id="action-right">
  	{if $auth->isLoggedIn()}
  	<a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  	{/if}
  </div>
</div>

<h2>Allergies</h2>
<table class="table">
  <tr>
    <th width="75">Room</th>
    <th width="250">Patient</th>
    <th width="500">Allergy</th>
  </tr>
  {foreach from=$patients item=patient}
  <tr class="form-row">
    <td>{$patient->number}</td>
    <td>{$patient->last_name}, {$patient->first_name}</td>
    <td>{$patient->allergy_name|default:"None"}</td>
  </tr>
  {/foreach}
</table>
<h2>Dislikes</h2>
<table class="table">
  <tr>
    <th width="75">Room</th>
    <th width="250">Patient</th>
    <th width="500">Dislike</th>
  </tr>
  {foreach from=$patientsdislikes item=patientd}
  <tr class="form-row">
    <td>{$patientd->number}</td>
    <td>{$patientd->last_name}, {$patientd->first_name}</td>
    <td>{$patientd->dislike_name|default:"None"}</td>
  </tr>
  {/foreach}
</table>