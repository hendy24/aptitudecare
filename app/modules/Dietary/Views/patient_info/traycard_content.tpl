  <div class="traycard-column">
    <br><br>
    <table class="table traycard {if $k == 2} last-table{/if}">
      <tr>
        <td colspan="2" class="text-center text-20 text-strong">{$item->number} &mdash; {$item->patient_name}</td>
      </tr>
      <tr>
        <td colspan="2" class="text-strong text-18 text-center">{$item->meal_name}</td>
      </tr>
      {if $item->birthday}
      <tr>
        <td colspan="2" class="text-green text-center text-18">Happy Birthday!</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong">Diet Order:</td>
        <td>{$item->diet_orders}</td>
      </tr>
      {if ($item->portion_size != "Regular")}
      <tr>
        <td class="text-strong">Portion Size:</td>
        <td>{$item->portion_size}</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong">Texture:</td>
        <td>{$item->textures}</td>
      </tr>
      <tr>
        <td class="text-strong">Adaptive Equipment:</td>
        <td>{$item->adapt_equip}</td>
      </tr>
      {if !empty($item->special_reqs)}
      <tr>
        <td class="text-strong">Special Requests:</td>
        <td>{$item->special_reqs|default:"None"}</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong">Other:</td>
        <td>{$item->orders}</td>
      </tr>
      <tr>
        <td class="text-strong">Beverages:</td>
        <td>{$item->beverages|default:"None"}</td>
      </tr>
      <tr>
        <td class="text-strong">Do Not Serve:</td>
        <td>{$item->dislikes|capitalize}</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="text-center text-18">{$selectedDate|date_format}</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="text-center"><img src="{$IMAGES}/ahc_logo.png" width="200px" alt=""></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="text-red text-italics text-strong">Allergies:</td>
        <td class="text-red text-italics text-strong">{$item->allergies}</td>
      </tr>

      <tr>
        <td class="text-strong" width="150">Percent Consumed:</td>
        <td class="text-center text-10" style="padding-top:5px;">0-25 &nbsp;&nbsp; 26-50 &nbsp;&nbsp; 51-75 &nbsp;&nbsp; 76-100</td>
      </tr>
      {if ($location->id == 9)}
      <tr>
        <td class="text-strong" width="150">Cc Intake:</td>
        <td class="text-center text-10" style="padding-top:5px">120 cc. &nbsp;&nbsp; 240 cc. &nbsp;&nbsp; 360 cc. &nbsp;&nbsp; 480 cc.</td>
      </tr>
      {/if}
      {if ($location->id == 21)}
      <tr>
        <td>&nbsp;</td>
        <td class="text-center text-strong">Table: {$item->table_number}</td>
      </tr>
      {/if}
    </table>
  </div>
