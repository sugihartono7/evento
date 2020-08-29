$(document).ready(function() {
    
    $(".confirmOk").click(function() {
		var id = $(".modal-body #idToDelete").val();
		var dataString = "id=" + id;
        
        $.ajax({
            type: "POST",
            url: baseUrl+"acara/delete",
            data: dataString,
            beforeSend: function() {
                //$("#imgLoading").removeClass("hide");
            },
            success: function(data) {
                if (data == "success") {
                    //reloadTable();
                    location.reload();
                }
                else {
                    alert(data);    
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            },
            complete: function(xhr, textStatus) {
                //$("#imgLoading").addClass("hide");
                $("#deleteConfirm").modal('hide');
            }
        });
        
	});

    $(".confirmOkCancel").click(function() {
        var id = $(".modal-body #idToCancel").val();
        var dataString = "id=" + id;
        
        $.ajax({
            type: "POST",
            url: baseUrl+"acara/cancel2",
            data: dataString,
            beforeSend: function() {
                //$("#imgLoading").removeClass("hide");
            },
            success: function(data) {
                if (data == "success") {
                    //reloadTable();
                    location.href = baseUrl+"acara/list/1";
                }
                else {
                    alert(data);    
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            },
            complete: function(xhr, textStatus) {
                //$("#imgLoading").addClass("hide");
                $("#cancelConfirm").modal('hide');
            }
        });
        
    });
    
});

$(document).on("click", ".deleteTrigger", function () {
    var id = $(this).data('id');
	var letterNumber= $(this).data('letter_number');
	$(".modal-body #idToDelete").val(id);
	$(".modal-body #letterNumber").html(letterNumber);
});

$(document).on("click", ".cancelTrigger", function () {
    var id = $(this).data('id');
    var letterNumber= $(this).data('letter_number');
    $(".modal-body #idToCancel").val(id);
    $(".modal-body #letterNumber").html(letterNumber);
});

function reloadTable() {
    // seems not working for non ajax or server side call
    oTable.fnDraw();
    //oTable.draw();
}