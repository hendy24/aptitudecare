{if !$isPDF}
<style>
	.container{
		width: 75%;
		margin: 20px auto;
		text-align: left;
		font-weight: normal;
		border-collapse: collapse;
	}

	.tooltiptext {
		visibility: hidden;
		width: 85px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;
		margin-left: 40px;
		margin-top: 10px;

		/* Position the tooltip */
		position: absolute;
		z-index: 1;
	}

	.tool-tip:hover .tooltiptext {
		visibility: visible;
	}
</style>

<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Snack Report</h1>
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
	<a class="" href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}&amp;pdf2=true" target="_blank" alt="Table">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="Table">
    </a>
	<a class="tool-tip" href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_labels&amp;location={$location->public_id}&amp;pdf2=true" target="_blank" alt="Labels">
	  <span class="tooltiptext">5160 Labels</span>
	  <img src="{$FRAMEWORK_IMAGES}/print.png" alt="Labels">
	</a>
    {/if}
  </div>
</div>
<h2 class="report_date">{$smarty.now|date_format}</h2>

<div class="container">
  <form action="{$SITE_URL}" method="POST">
    <input type="hidden" name="module" value="Dietary">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="snack_report">
    <input type="hidden" name="location" value="{$location->public_id}">
    <table class="table">
      <tr>
        <th>Room</th>
        <th>Patient Name</th>
        <th>Diet</th>
        <th>Allergies</th>
        <th>Texture</th>
        <th>Snack</th>
        <th>Time</th>
      </tr>
      {foreach from=$snacks item=item key=time}
        {foreach from=$item item=snack}
        <tr>
          <td>{$snack->number}</td>
          <td>{$snack->patient_name}</td>
          <td>{$snack->diet}</td>
          <td>{$snack->allergy}</td>
          <td>{$snack->texture}</td>
          <td>{$snack->name}</td>
          <td>{$snack->time}</td>
        </tr>

        {/foreach}
      {/foreach}
    </table>
  </form>
</div>
{else}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="{$CSS}/labels.css">
</head>
<body>
  {foreach from=$snacks item=item key=time}
    {foreach from=$item item=snack}
      <div class="snack-label">
        <strong>{$snack->number} - {$snack->patient_name}</strong><br />
        <strong>Diet: {$snack->diet}</strong><br />
        <strong>Allergies:</strong> {$snack->allergy}<br />
        <strong>Snack</strong> {$snack->name} <strong>Time:</strong> {$snack->time}<br />
      </div>
    {/foreach}
  {/foreach}
</body>
</html>
{/if}
