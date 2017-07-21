<div class="row text-center">
  {$this->loadElement("selectLocation")}
</div>

<h1>{$location->name} Beverages</h1>
<br>
<!-- <form action="{$SITE_URL}" method="post"> -->
  <input type="hidden" name="page" value="info">
  <input type="hidden" name="action" value="save_beverages">
  <input type="hidden" name="location" class="locationId" value="{$location->public_id}">
  <input type="hidden" name="current_url" value="{$current_url}">

  <div class="row bev-items">
  {foreach $beverages as $key=>$bev}
      <div class="col-sm-4 col-xs-4 bev-item">
        {$bev->name}
        <a href="" value="{$menu->public_id}" class="delete">
          <img src="{$FRAMEWORK_IMAGES}/delete.png" class="pull-right" alt="">
          <input type="hidden" name="bev_id" class="bev-id" value="{$bev->public_id}" />
        </a>
      </div>
  {/foreach}
    <div class="col-sm-4 col-xs-4">
      <input type="text" name="beverage" class="new-bev" placeholder="Enter Name">
    </div>
  </div>
  <!-- <div class="row">
    <div class="col-sm-12 text-right">
      <input type="submit" class="btn btn-success" value="Save">
    </div>
  </div> -->
<!-- </form> -->

<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>

<script>
  $(document).ready(function() {

    var locationId = $('.locationId').val();
    var bevName = null;

    $(document).on('focusout', '.new-bev', function() {
      bevName = $(this).val();
      var itemRow = $(this).parent();
      if (bevName !== '') {
        $.post(
          SITE_URL,
          {
            page: 'MainPage',
            action: 'ajax_save',
            item: {
              1: {
                object: 'BeverageList',
                colName: 'name',
                value: bevName
              },
              2: {
                object: 'LocationBeverage',
                colName: 'location_id',
                join: 'beverage_id'
              }

            },
            location: locationId
          },
          function () {
            // replace the input with text only showing the new name
            itemRow.empty().html(bevName).after('<div class="col-sm-4 col-xs-4"><input type="text" name="beverage" class="new-bev" placeholder="Enter Name"></div>');
            $('.new-bev').focus();
          },
          'text'
        );
      }

    });

    $(document).on('click', '.delete', function(e) {
      e.preventDefault();
      var bevId = $(this).children("input.bev-id").val();
      var inputBox = $(this).parent();

      $.post(
        SITE_URL,
        {
          page: 'MainPage',
          action: 'ajax_delete',
          item: {
            object: 'LocationBeverage',
            colName: 'public_id',
            value: bevId
          }
        },
        function() {
          inputBox.fadeOut('slow');
        }
      );
    });

  });
</script>
