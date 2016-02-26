{foreach from=$menuItems item=item}
  <div class="traycard-column">
    <table class="form">
      <tr>
        <td colspan="2" class="text-strong text-18 text-center">{$item->meal_name}</td>
      </tr>
      {if $birthday}
      <tr>
        <td colspan="2" class="text-green text-center text-18">Happy Birthday!</td>
      </tr>
      {/if}
      <tr>
        <td>Diet Order:</td>
        <td>{$patientDietInfo->list}</td>
      </tr>
      <tr>
        <td>Textures:</td>
        <td>{$texture->names}</td>
      </tr>
      <tr>
        <td>Portion Size:</td>
        <td>{$diet->portion_size}</td>
      </tr>
      <tr>
        <td class="text-red text-italics text-strong">Allergies:</td>
        <td class="text-red text-italics text-strong">{$allergies->list}</td>
      </tr>
      <tr>
        <td>Orders:</td>
        <td>{$orders->list}</td>
      </tr>
      <tr>
        <td>Special Requests:</td>
        <td>{$item->spec_req|default:"None"}</td>
      </tr>
      <tr>
        <td>Beverages:</td>
        <td>{$item->beverages}</td>
      </tr>
      <tr>
        <td>Do Not Serve:</td>
        <td>{$dislikes->list}</td>
      </tr>
      <tr>
        <td width="150">Percent Consumed:</td>
        <td class="text-center text-10 bottom">0-25 &nbsp;&nbsp; 26-50 &nbsp;&nbsp; 51-75 &nbsp;&nbsp; 76-100</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="text-center text-18">{mysql_date()|date_format}</td>
      </tr>
      <tr>
        <td colspan="2" class="text-center text-20 text-strong">{$patient->number} &mdash; {$patient->last_name}, {$patient->first_name}</td>
      </tr>

    </table>
  </div>
  {/foreach}

