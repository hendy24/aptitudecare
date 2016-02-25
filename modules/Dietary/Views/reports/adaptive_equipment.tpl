<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Adaptive Equipment Report</h1>
  </div>
  <div id="action-right">
  </div>
</div>

<table class="form">
	<tr>
		<th>Room</th>	
		<th>Patient</th>
		<th>Adaptive Equipment</th>
	</tr>
	<tr>
		{foreach from=$patients item=patient}
		<td>{$patient->number}</td>
		<td>{$patient->fullName()}</td>
		<td></td>
		{/foreach}
	</tr>
</table>