<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>AHC Snack Labels</title>
    <style>
    body {
        width: 8.5in;
        margin: 0in .1875in;
        }
    #label-page {
        margin-top: .25in;
    }
    .label{
        /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
        width: 2.125in; /* plus .6 inches from padding */
        height: .9125in; /* plus .125 inches from padding .875 */
        padding: .125in .3in 0;
        margin-right: .125in; /* the gutter */
        float: left;
        text-align: center;
        overflow: hidden;
        outline: 1px dotted; /* outline doesn't occupy space like border does */
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
        <strong>{$snack->number} - {$snack->patient_name}</strong><br />
        <strong>Diet: {$snack->diet}</strong><br />
        <strong>Allergies:</strong> {$snack->allergy}<br />
        <strong>Snack</strong> {$snack->name} <strong>Time:</strong> {$snack->time}<br />
      </div>
    {/foreach}
  {/foreach}
</div>
</body>
</html>