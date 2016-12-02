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
    {foreach from=$snacks item=snack key=time}
    <div class="label">
        <strong>{$snack->number} &mdash; {$snack->patient_name}</strong><br />
        {$snack->diet}<br />
        <div class="allergy">{$snack->allergy}</div>
        <div class="texture">{$snack->texture}</div>
        <div class="snack">{$snack->name} {$snack->time}</div>
    </div>
    {/foreach}
</div>
</body>
</html>