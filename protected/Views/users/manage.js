$(".active").removeClass("active");
$(".show").removeClass("show");
$("#dataSection").addClass("show");
$("#users").addClass("active");


$(".delete").click(function(e) {
	e.preventDefault();
	var dataRow = $(this).parent().parent();
	var item = $(this);
	$("#dialog").dialog({
		buttons: {
			"Confirm": function() {
				var row = item.children().next($(".public-id"));
				var id = row.val();
					
				$.ajax({
					type: 'post',
					url: SITE_URL,
					data: {
						page: "users",
						action: 'delete_user',
						id: id,
					},
					success: function() {
						dataRow.fadeOut("slow");
					}
				});
				$(this).dialog("close");

			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});
});

$(".order").click(function(e) {
	e.preventDefault();
	var order = $(this).next("input").val();
	console.log
	window.location = SITE_URL + "/?page=data&action=manage&type=" + $("#page").val() + "&orderBy=" + order;
});


$("#locations").change(function() {
	window.location = SITE_URL + "/?page=users&action=manage&location=" + $("#locations option:selected").val();
});
