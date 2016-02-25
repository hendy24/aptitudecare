{foreach from=$menuItems item=meal}
  <div class="traycard-column">
    <table>
      {if $birthday}
      <tr>
        <td colspan="2" class="text-green text-center">Happy Birthday!</td>
      </tr>
      {/if}

      <tr>
        <td>{$meal->meal_name}</td>
        <td>{mysql_date()|date_format}</td>
      </tr>
      <tr>
        <td>Diet Order:</td>
      </tr>
      <tr>
        <td>Textures:</td>
        <td>{$texture->names}</td>
      </tr>
      <tr>
        <td>Portion Size:</td>
      </tr>
      <tr>
        <td class="text-red">Allergies:</td>
        <td class="text-red">{$allergies->list}</td>
      </tr>
      <tr>
        <td>Orders:</td>
      </tr>
      <tr>
        <td>Special Requests:</td>
      </tr>
      <tr>
        <td>Beverages:</td>
      </tr>
      <tr>
        <td>Do Not Serve:</td>
        <td>{$dislikes->list}</td>
      </tr>
      
      <tr>
        <th colspan="2">Meal</th>
      </tr>
      <tr>
        <td colspan="2">
          {$meal->content}
        </td>
      </tr>
    </table>
  </div>
  {/foreach}

