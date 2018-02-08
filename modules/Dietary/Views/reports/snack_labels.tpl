<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
      body {
          width: 8.5in;
          height: 11.25in;
          margin: 0.5in 0.25in;
          font-size: 10px;
      }
      #label-page {
          border: 1px dotted black;
      }
      .label{
          /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
          width: 2.725in; /* plus .6 inches from padding */
          height: 0.9in; /* plus .125 inches from padding .875 */
          padding: .125in .1in;
          margin-right: .125in; /* the gutter */
          float: left;
          text-align: center;
          overflow: hidden;
          outline: 1px dotted; /* outline doesn't occupy space like border does */
          page-break-after: auto;
          }
      .page-break  {
          clear: left;
          display:block;
          page-break-after:always;
          }
      .allergy {
          color: red;
          font-style: italic;
      }
      .snack { font-weight: bold; }

    </style>

</head>
<body>

<div id="label-page">
  {foreach from=$snacks item=item key=time}
    {foreach from=$item item=snack}
      <div class="label">
        <strong>{$snack->number} - {$snack->patient_name}</strong><br>
        <strong>Date:</strong> {$smarty.now|date_format:"%D"}<strong> Time:</strong> {$snack->time}<br>
        <strong>Diet:</strong> {$snack->diet}<br>
        {if !empty($snack->allergy)}
        <strong>Allergies:</strong> {$snack->allergy}<br>
        {/if}
        <strong>Snack:</strong> {$snack->name}
      </div>
    {/foreach}
  {/foreach}
</div>
</body>
</html>
