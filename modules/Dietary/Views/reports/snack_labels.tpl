<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
      body {
      }
      #label-page {
          font-size: 12px;
      }
      .label5160{
          /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
          width: 32.3%;
          height: 95px;
          padding: 0.125in 0.08in;
          margin-right: .125in; /* the gutter */
          float: left;
          text-align: center;
          overflow: hidden;
          outline: white 1px dotted; /* outline doesn't occupy space like border does */
          page-break-after: auto;
		  page-break-inside: avoid;
		  color: black;
          }
	  .label5160:nth-of-type(3n) {
		margin-right: 0px;
	  }
	  .label5160:nth-of-type(9n) {
		margin-bottom: 3px;
	  }
      .page-break  {
          clear: left;
          display:block;
          page-break-after:always;
          }
      .allergy {
          color: red !important;
          font-style: italic;
		  font-size: 1.1em;
		  display: inline;
      }
	  .line1 {
		white-space: nowrap;
		overflow: hidden;
	  }
      .snack { font-weight: bold; }

    </style>
</head>
<body>

<div id="label-page">
  {foreach from=$snacks item=item key=time}
    {foreach from=$item item=snack}
      <div class="label5160">
        <strong class="line1">{$snack->number} - {$snack->patient_name} -  {$smarty.now|date_format:"%b %d"}</strong><br>
        <strong>Diet:</strong> {$snack->diet}<br>
        {if !empty($snack->allergy)}
        <p class="allergy">Allergies:</p> {$snack->allergy}<br>
        {/if}
        <strong>Snack:</strong> {$snack->name} <strong>[{$snack->time}]</strong>
      </div>
    {/foreach}
  {/foreach}
</div>
</body>
</html>
