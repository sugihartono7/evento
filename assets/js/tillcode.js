
$(function() {
    var opts = {vMin: '0.00', vMax: '100.00'};
    
    $("#disc1").autoNumeric('init', opts);
    $("#disc2").autoNumeric('init', opts);
    $("#disc3").autoNumeric('init', opts);
    
    $(".confirmOk").click(function() {
		
        var rowIndex = $("#idToUpdate").val();
        rowIndex = new Number(rowIndex)+1;
        var tillcode = $("#tillcode").val();
        var disc1 = $("#disc1").autoNumeric("get");
        var disc2 = $("#disc2").autoNumeric("get");
        var disc3 = $("#disc3").autoNumeric("get");
        var issp = $("#issp option:selected").val();
        var isspLbl = issp == "1" ? "Y" : "N";
        
        disc1 = disc1 == "" ? 0 : disc1;
        disc2 = disc2 == "" ? 0 : disc2;
        disc3 = disc3 == "" ? 0 : disc3;
        
        var dataString = "tillcode=" + tillcode + "&disc1=" + disc1 + "&disc2=" + disc2 + "&disc3=" + disc3 + "&issp=" + issp;
        $.ajax({
            type: "POST",
            url:baseUrl+"tillcode/update",
            data: dataString,
            beforeSend: function() {
                $("#imgLoading").removeClass("hide");
            },
            success: function(data) {
                //alert("Input berhasil: " + data);
                if (data == "success") {
                    
                    var t = document.getElementById('datatable');
                    $(t.rows[rowIndex].cells[4]).html(disc1);
                    $(t.rows[rowIndex].cells[5]).html(disc2);
                    $(t.rows[rowIndex].cells[6]).html(disc3);
                    $(t.rows[rowIndex].cells[7]).html(isspLbl);
                    
                    $("#a-"+tillcode).data("disc1", disc1);
                    $("#a-"+tillcode).data("disc2", disc2);
                    $("#a-"+tillcode).data("disc3", disc3);
                    $("#a-"+tillcode).data("issp", issp);
                    
                    $("#editForm").modal('hide');
                }
                else {
                    alert(data);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            },
            complete: function(xhr, textStatus) {
                $("#imgLoading").addClass("hide");
            }
        });
	});
    
});

$(document).on("click", ".editTrigger", function () {
    var tillcode = $(this).data('id');
    var disc1 = $(this).data('disc1');
    var disc2 = $(this).data('disc2');
    var disc3 = $(this).data('disc3');
    var issp = $(this).data('issp');
    var rowIndex = $(this).parent().parent().index();
    
	$(".modal-body #idToUpdate").val(rowIndex);
    $(".modal-body #tillcode").val(tillcode);
    $(".modal-body #disc1").val(disc1);
	$(".modal-body #disc2").val(disc2);
    $(".modal-body #disc3").val(disc3);
    $(".modal-body #issp").val(issp);
    
});

