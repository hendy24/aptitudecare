$(document).ready(function() {
  var table = null;
  var key = null;
  var photoId = null;
  var form = null;
  var data = null;
  var formCount = $("table.form").length;

  $(".fancybox").fancybox();

    $(".photo-tag").tagit({
      // the photo-key is not being loaded because the field name is being set when the dom
      // loads, not when a tag is entered.
      //fieldName: "photo[" + $(this).next("input.photo-key").val() + "][photo_tag][]",
      beforeTagAdded: function() {
      },
      afterTagAdded: function() {
      },
      fieldName: "photo_tag[]",
      availableTags: fetchOptions("PhotoTag"),
      autocomplete: {delay:0, minLength: 2},
      showAutocompleteOnFocus: false,
      caseSensitive: false,
      allowSpaces: true,
      beforeTagRemoved: function(event, ui) {
          // if tag is removed
          var photoId = $(this).parent().parent().parent().parent().parent().children("input:hidden:first").val();
          var tagName = ui["tagLabel"];
          $.post(SITE_URL, {
            page: "photos",
            action: "delete_tag",
            photo_id: photoId,
            tag_name: tagName
            }, function (e) {

            }, "json"
          );
      }

    });


    function fetchOptions(type){
        var choices = "";
        var array = [];
        var runLog = function() {
          array.push(choices);
        };

        var options = $.get(SITE_URL, {
          page: "Photos",
          action: "fetchTags",
          type: type
          }, function(data) {
            $.each(data, function(key, value) {
              choices = value.name;
              runLog();
            });
          }, "json"
        );

        return array;
      }


  $("input#save-photo").on("click", function(e) {
    e.preventDefault();
    table = $(this).parent().parent().parent();
    key = table.parent().parent().children("input:hidden:first").val()
    photoId = table.parent().children("input:hidden:first").val();
    form = $("#photo-info-" + key);
    data = $("#photo-info-" + key).serialize();

    $.ajax({
      type: 'post',
      url: SITE_URL + "/?page=photos&action=save_photo_info&photo_id=" + photoId,
      data: data,
      success: function() {

      }
    });

  });


  var timeoutID = null;

  function findPhotos(str) {
    $.ajax({
      type: 'post',
      url: SITE_URL,
      data: {
        page: "photos",
        action: "search_photos",
        facility: $("#selected-facility").val(),
        term: str
      },
      success: function(data) {
        var $container = $("#image-container");
        $container.empty();
        $("#page-links").empty();
        $.each(data, function(key, value) {
          $container.append('<a class="fancybox image-item" rel="fancybox-thumb" href="' + SITE_URL + '/files/dietary_photos/' + value.filename + '" title="' + value.name + '": "' + value.description + '"> <img src="' + SITE_URL + '/files/dietary_photos/thumbnails/' + value.filename + '" class="photo-image" alt=""></a>');
        });
      },
      dataType: "json"
    });
  }

  $("#search-pictures").keyup(function() {
    clearTimeout(timeoutID);
    var $target = $(this);
    console.log($target.val());
    timeoutID = setTimeout(function() { findPhotos($target.val()); }, 500);
  });



});
