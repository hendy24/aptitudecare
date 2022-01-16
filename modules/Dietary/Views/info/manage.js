$(".active").removeClass("active");
$(".show").removeClass("show");
$("#dietarySection").addClass("show");
$("#infoSection").addClass("show");
$("#manage-menus").addClass("active");


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
						page: "info",
						action: 'delete_menu',
						menu: id,
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

