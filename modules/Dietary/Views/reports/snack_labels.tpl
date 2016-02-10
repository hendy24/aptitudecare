<style>
  .container{
    width: 75%;
    margin: 20px auto;
    text-align: left;
    font-weight: normal;
    border-collapse: collapse;
  }
  tr{
    height: 30px;
  }
</style>
<script type="text/javascript">
//  $('radio').change(function{
//    $(this).closest('.container').find('td').css('background-color', 'white');
//    $(this).closest('td').css('background-color', 'grey')
//  });
</script>

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
</div>

<div class="container">
  <div>Snack Labels</div>
  <form action="?module=Dietary&page=reports&action=snack_labels_pdf&location={$location->public_id}" method="POST">
    <label>Choose Day:</label>
    <input class="datepicker" name="date" />
    <br>
    <br>
    <tabel>Select Label Start Position</tabel>
    <table border="1" width="300">
      <tr>
        <td><input type="radio" name="start_posit" value="1" checked="checked">1</td>
        <td><input type="radio" name="start_posit" value="2">2</td>
        <td><input type="radio" name="start_posit" value="3">3</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="4">4</td>
        <td><input type="radio" name="start_posit" value="5">5</td>
        <td><input type="radio" name="start_posit" value="6">6</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="7">7</td>
        <td><input type="radio" name="start_posit" value="8">8</td>
        <td><input type="radio" name="start_posit" value="9">9</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="10">10</td>
        <td><input type="radio" name="start_posit" value="11">11</td>
        <td><input type="radio" name="start_posit" value="12">12</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="13">13</td>
        <td><input type="radio" name="start_posit" value="14">14</td>
        <td><input type="radio" name="start_posit" value="15">15</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="16">16</td>
        <td><input type="radio" name="start_posit" value="17">17</td>
        <td><input type="radio" name="start_posit" value="18">18</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="19">19</td>
        <td><input type="radio" name="start_posit" value="20">20</td>
        <td><input type="radio" name="start_posit" value="21">21</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="22">22</td>
        <td><input type="radio" name="start_posit" value="23">23</td>
        <td><input type="radio" name="start_posit" value="24">24</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="25">25</td>
        <td><input type="radio" name="start_posit" value="26">26</td>
        <td><input type="radio" name="start_posit" value="27">27</td>
      </tr>
      <tr>
        <td><input type="radio" name="start_posit" value="28">28</td>
        <td><input type="radio" name="start_posit" value="29">29</td>
        <td><input type="radio" name="start_posit" value="30">30</td>
      </tr>
    </table>
    <br>
    <input type="submit" value="Submit" />

  </form>
</div>