  <!-- Bootstrap scripts -->
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
  
<!-- Older JS scripts -->
  <!-- <script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  {if $this->module == "HomeHealth"}
  <script type="text/javascript" src="{$FRAMEWORK_JS}/jQuery-Autocomplete-master/dist/jquery.autocomplete.min.js"></script>
  {/if}
  <script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-validation-1.13.0/dist/jquery.validate.min.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.maskedinput.min.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/datepicker.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/jquery.row-grid.min.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/dropzone/dropzone.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/fancybox/jquery.fancybox.pack.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/gridify/gridify-min.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/gridify/require.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/shadowbox-3.0.3/shadowbox.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/fancybox/helpers/jquery.fancybox-buttons.js"></script>
  <script type="text/javascript" src="{$FRAMEWORK_JS}/tagit/js/tag-it.min.js"></script>

  <script>
    var SITE_URL = '{$SITE_URL}';
    Shadowbox.init({
      height: 425,
      width: 450,
      handleOversize: "resize",
      overlayColor: "#666",
      overlayOpacity: "0.25"
    });
  </script>

  <script type="text/javascript" src="{$JS}/general.js"></script> -->

  <script type="text/javascript" src="{$SITE_URL}/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="{$SITE_URL}/bootstrap/js/bootstrap.min.js"></script>

  {if $auth->valid() && $auth->getRecord()->timeout}
    <script type="text/javascript" src="{$JS}/timeout.js"></script>

    <script>
      $(document).ready(function() {
        startTimer();
      });
    </script>
  {/if}
