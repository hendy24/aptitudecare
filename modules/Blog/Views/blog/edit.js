$(".active").removeClass("active");
$("#new-post").addClass("active");

{literal}
$('#blog-tags').selectize({
    create: function(input, callback) {
        $.post(SITE_URL, {
            page: 'blogTag',
            action: 'createTag',
            name: input
        },
        function(response) {
            callback({value: response.id, text: response.name});
        }, 'json');
    }
});


$('#summernote').summernote({
    height: 350
});


$('#deletePost').click(function(e) {
    var id = $('#public-id').val();
    $.ajax({
        type: 'post',
        url: SITE_URL + '/?page=blog&action=delete_post&id=' + id,
        success: function(response) {
            window.location.href = SITE_URL + '/?page=blog&action=manage';
        }
    });
});

{/literal}
