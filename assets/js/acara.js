var tmpSp;
var tmpSp_e;
var tillcodeToHold = "";

$(function() {
    var opts = {vMin: '0.00', vMax: '100.00'};
    
    $("#supplierResponsibility").autoNumeric('init', opts);
    $("#ydsResponsibility").autoNumeric('init', opts);
    $("#margin").autoNumeric('init', opts);
    //$("#sp").autoNumeric('init');
   
    /*$("#sp").keyup(function() {
        a = $(this).val();
        tmp = a.replace(/[^0-9\.]/g,'');
        $(this).val(tmp);
    });*/
    
    $("#sp").keypress(function(e){
        if (e.which != 46 && e.which != 45 && e.which != 46 
            && !(e.which >= 48 && e.which <= 57) && (e.which !=8) 
            && !(e.which >= 37 && e.which <= 40) ) {
            return false;
        }
    });

    $("#sp_e").keypress(function(e){
        if (e.which != 46 && e.which != 45 && e.which != 46 && !(e.which >= 48 && e.which <= 57) && (e.which !=8) && !(e.which >= 37 && e.which <= 40) ) {
            return false;
        }
    });

    
    $("#supplierResponsibility_e").autoNumeric('init', opts);
    $("#ydsResponsibility_e").autoNumeric('init', opts);
    $("#margin_e").autoNumeric('init', opts);
    //$("#sp_e").autoNumeric('init');
    $("#eventHargaFaktur_e").autoNumeric('init');
    $("#eventHargaJual_e").autoNumeric('init');
    
    $("#kindOfResponsibility").change(function() {
        if ($(this).val() == "0") {
            $("#responsibilityHolder").show("slow");
        }
        else {
            $("#responsibilityHolder").hide("slow");
        }
    });
    
    $("#kindOfResponsibility_e").change(function() {
        if ($(this).val() == "0") {
            $("#responsibilityHolder_e").show();
        }
        else {
            $("#responsibilityHolder_e").hide();
        }
    });
    
    $('#eventStartDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        minDate: new Date()
    });
    
    $('#eventEndDate').datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        minDate: new Date()
    });
    
    $('#eventStartDate_e').datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        minDate: new Date()
    });
    
    $('#eventEndDate_e').datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        minDate: new Date()
    });
    
    $("#cbSp").click(function() {
        if ($(this).prop("checked")) {
            $("#sp").prop("disabled", false);
            $("#sp").val(tmpSp);
            $("#sp").focus();
        }
        else {
            tmpSp = $("#sp").val();
            $("#sp").prop("disabled", true);
            $("#sp").val("");
        }
    });
    
    $("#cbSp_e").click(function() {
        if ($(this).prop("checked")) {
            $("#sp_e").prop("disabled", false);
            $("#sp_e").val(tmpSp_e);
            $("#sp_e").focus();
        }
        else {
            tmpSp_e = $("#sp_e").val();
            $("#sp_e").prop("disabled", true);
            $("#sp_e").val("");
        }
    });
    
    $(".confirmOk").click(function() {
		poolEditTillcode();
	});
    
    $(".confirmOk2").click(function() {
        editLocation();
    });

    /*$("#btn_add_cabang").click(function() {
        var cabang = $("#storeCode").val();
        var init = cabang.substr(cabang.indexOf("(")+1, 3);
        
        //nanti
        ret =  "<div class='col-sm-2'>&nbsp;&nbsp;&nbsp;";    
        ret += "<label><input type='checkbox' name='check_store' id='check_store_' class='check_store' checked value='"+cabang+"' > "+init+"</input></label>";    
        ret += "</div>";
        
        if (cabang == "") {
            alert("Silakan isi cabang dahulu")
        }
        else {
           $("#div_store").append(ret); 
           $('#modal_add_cabang').modal('toggle');
        }
        
    
    

	});*/

    ////////////////////////////////////////////////////////////////////////////////////-------------------------

$("#btn_add_cabang").click(function() {
    var todo = $("#todo").val();
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    //var sameLocation = $("#sameLocation").prop("checked");
    var isSameLocation = $("#isSameLocation").val();
    var locationCode = $("#locationCodeAdd option:selected").val();
    //var storeCode = $("#storeCode").val();
    
    //cek
    
        var storeCode = $("#storeCode").val();
        var storeCodeId = this.id;

        if (isSameLocation == "1") {
            if (locationCode == "" || storeCode == "") {
                alert("Silakan mengisi lokasi terlebih dahulu.");
                return;
            }    
        }
        else {
            if (tillcode == "" || locationCode == "" || storeCode == "") {
                alert("Silakan mengisi tillcode dan lokasi terlebih dahulu.");
                return;
            }   
        }
        
        //if (isSameLocation == "1") tillcode = "";
        
        if (!locationExist(tillcode, isSameLocation, locationCode, storeCode)) {
            
            if (isSameLocation == "1") {
                
                var cntZ = new Number($("#cntZ").val());
            
                if (todo == "edit") {
                    var idx = cntZ;
                    if (idx < 0) {
                        idx = 0;
                    }
                    
                    if (isSameLocation == "1") {
                        var row =   "<tr>" + 
                                        "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" + 
                                        "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a id=\"edit2-" + idx + "\"" + 
                                                "data-id=\"" + idx + "\"" + 
                                                "data-tillcode=\"" + tillcode + "\"" + 
                                                "data-loc_desc=\"" + locationCode + "\"" + 
                                                "data-store_desc=\"" + storeCode + "\"" + 
                                                "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" + 
                                                "<i class='fa fa-pencil'></i> edit" + 
                                            "</a>" + 
                                        "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                "<i class='fa fa-trash-o'></i> del" + 
                                            "</a>" + 
                                        "</td>" + 
                                    "</tr>";    
                    }
                    else {
                        var row =   "<tr>" + 
                                        "<td class='locationTillcode' id='locationTillcode-" + idx + "'>" + tillcode + "</td>" + 
                                        "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" + 
                                        "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a id=\"edit2-" + idx + "\"" + 
                                                "data-id=\"" + idx + "\"" + 
                                                "data-tillcode=\"" + tillcode + "\"" + 
                                                "data-loc_desc=\"" + locationCode + "\"" + 
                                                "data-store_desc=\"" + storeCode + "\"" + 
                                                "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" + 
                                                "<i class='fa fa-pencil'></i> edit" + 
                                            "</a>" + 
                                        "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                "<i class='fa fa-trash-o'></i> del" + 
                                            "</a>" + 
                                        "</td>" + 
                                    "</tr>";
                    }
                }
                else {
                    if (isSameLocation == "1") {
                        var row =   "<tr>" + 
                                        "<td class='locationLocationCode'>" + locationCode + "</td>" + 
                                        "<td class='locationStoreCode'>" + storeCode + "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                "<i class='fa fa-trash-o'></i> del" + 
                                            "</a>" + 
                                        "</td>" + 
                                    "</tr>";    
                    }
                    else {
                        var row =   "<tr>" + 
                                        "<td class='locationTillcode'>" + tillcode + "</td>" + 
                                        "<td class='locationLocationCode'>" + locationCode + "</td>" + 
                                        "<td class='locationStoreCode'>" + storeCode + "</td>" + 
                                        "<td class='al-center'>" + 
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                "<i class='fa fa-trash-o'></i> del" + 
                                            "</a>" + 
                                        "</td>" + 
                                    "</tr>";
                    }    
                }
                
                if ($("#datatableZ tr#dummyRowZ").length) {
                    $("#datatableZ tr#dummyRowZ").remove();
                }
                
                $("#datatableZ > tbody:last").append(row);
                $("#cntZ").val(cntZ+1);
                
                addDeleteRowEvent("datatableZ", storeCodeId);
                $("#locationCodeAdd").val("");
                $("#storeCode").val("");
                $('#modal_add_cabang').modal('toggle');
            }
            else {
                
                var dataString = "tillcode=" + tillcode;
                $.ajax({
                    type: "POST",
                    url: baseUrl+"acara/isArticleHasDiscount",
                    data: dataString,
                    beforeSend: function() {
                        //$("#imgLoading").removeClass("hide");
                    },
                    success: function(data) {
                        //alert("Input berhasil: " + data);
                        if (data == "hasnot") {
                            alert("Tillcode belum dilengkapi nilai diskonnya di data master.");
                        }
                        else {
                            
                            var cntZ = new Number($("#cntZ").val());
            
                            if (todo == "edit") {
                                var idx = cntZ;
                                if (idx < 0) {
                                    idx = 0;
                                }
                                
                                if (isSameLocation == "1") {
                                    var row =   "<tr>" + 
                                                    "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" + 
                                                    "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a id=\"edit2-" + idx + "\"" + 
                                                            "data-id=\"" + idx + "\"" + 
                                                            "data-tillcode=\"" + tillcode + "\"" + 
                                                            "data-loc_desc=\"" + locationCode + "\"" + 
                                                            "data-store_desc=\"" + storeCode + "\"" + 
                                                            "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" + 
                                                            "<i class='fa fa-pencil'></i> edit" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                            "<i class='fa fa-trash-o'></i> del" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                "</tr>";    
                                }
                                else {
                                    var row =   "<tr>" + 
                                                    "<td class='locationTillcode' id='locationTillcode-" + idx + "'>" + tillcode + "</td>" + 
                                                    "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" + 
                                                    "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a id=\"edit2-" + idx + "\"" + 
                                                            "data-id=\"" + idx + "\"" + 
                                                            "data-tillcode=\"" + tillcode + "\"" + 
                                                            "data-loc_desc=\"" + locationCode + "\"" + 
                                                            "data-store_desc=\"" + storeCode + "\"" + 
                                                            "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" + 
                                                            "<i class='fa fa-pencil'></i> edit" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                            "<i class='fa fa-trash-o'></i> del" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                "</tr>";
                                }
                            }
                            else {
                                if (isSameLocation == "1") {
                                    var row =   "<tr>" + 
                                                    "<td class='locationLocationCode'>" + locationCode + "</td>" + 
                                                    "<td class='locationStoreCode'>" + storeCode + "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                            "<i class='fa fa-trash-o'></i> del" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                "</tr>";    
                                }
                                else {
                                    var row =   "<tr>" + 
                                                    "<td class='locationTillcode'>" + tillcode + "</td>" + 
                                                    "<td class='locationLocationCode'>" + locationCode + "</td>" + 
                                                    "<td class='locationStoreCode'>" + storeCode + "</td>" + 
                                                    "<td class='al-center'>" + 
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
                                                            "<i class='fa fa-trash-o'></i> del" + 
                                                        "</a>" + 
                                                    "</td>" + 
                                                "</tr>";
                                }    
                            }
                            
                            if ($("#datatableZ tr#dummyRowZ").length) {
                                $("#datatableZ tr#dummyRowZ").remove();
                            }
                            
                            $("#datatableZ > tbody:last").append(row);
                            $("#cntZ").val(cntZ+1);
                            
                            addDeleteRowEvent("datatableZ", storeCodeId);
                            $("#locationCodeAdd").val("");
                            $("#storeCode").val("");
                            $('#modal_add_cabang').modal('toggle');

                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        //alert("Error: " + errorThrown);
                    },
                    complete: function(xhr, textStatus) {
                        //$("#imgLoading").addClass("hide");
                    }
                });
                
            }
        
        }
        else {
            alert("Data lokasi sudah ada.");
        }

     

});
    
    $(".confirmOk3").click(function() {
		editDate();
	});
    
    $("#supplierCode_e").focus(function() {
        $(this).select();
    });
    /*
    $("#tillcode_e").focus(function() {
        $(this).select();
    });
    */
    $("#storeCode_e").focus(function() {
        $(this).select();
    });
    
    $("#supplierCode").focus(function() {
        $(this).select();
    });
    $("#brandCode").focus(function() {
        $(this).select();
    });
    $("#tillcode").focus(function() {
        $(this).select();
    });
    $("#notes").focus(function() {
        $(this).select();
    });
	
    $("#btnBack").click(function(event) {
        if (needBackConfirmation()) {
            event.preventDefault();
            //alert('aye');
            $("#backConfirmation").modal('show');    
        }
    });
    
    $(".backConfirmOk").click(function() {
        var todo = $("#todo").val();
        var id = $("#id").val();
        
        $("#backConfirmation").modal('hide');
        
        if (todo == "edit") {
            location.href=baseUrl+"acara/edit/"+id;
        }
        else {
            location.href=baseUrl+"acara/add";    
        }
        
    });
        
    autocompleteSuppliers();
    autocompleteBrands();
    autocompleteTillcodes(); 
    autocompleteStores();
    
    FormValidation.init();
    
    addDeleteRowEvent("datatableY", null);
    addDeleteRowEvent("datatableZ", null);
    addDeleteRowEvent("datatableX", null);

    $("#editForm3").on("shown.bs.modal", function () {
        autocompleteTillcodes_e(); 
    });
    
    $("#editForm2").on("shown.bs.modal", function () {
        autocompleteTillcodes_e(); 
    });
    
    $("#tillcode2_e").on("click", function() {
        $(this).select();
    });
    
    $("#tillcode3_e").on("click", function() {
        $(this).select();
    });

});

function needBackConfirmation() {
    var dateLengthDummy = $("#datatableY tr#dummyRowY").length;
    var locationLengthDummy = $("#datatableZ tr#dummyRowZ").length;
    var tillcodeLengthDummy = $("#datatableX tr#dummyRowX").length;
    
    var dateLength = $("#datatableY tr").length;
    var locationLength = $("#datatableZ tr").length;
    var tillcodeLength = $("#datatableX tr").length;
    
    if (dateLengthDummy == 0 && dateLength > 0) {
        return true;
    }
    else if (locationLengthDummy == 0 && locationLength > 0) {
        return true;
    }
    else if (tillcodeLengthDummy == 0 && tillcodeLength > 0) {
        return true;
    }
    else {
        return false;
    }
    
}

function getMissingTillcode() {
    var isSameDate = $("#isSameDate").val();
    var isSameLocation = $("#isSameLocation").val();
    
    var dateNotPulled = "";
    var locationNotPulled = "";
    var missDate = "";
    var missLocation = "";
    
    var arrDateTillcode = [];
    var arrLocationTillcode = [];
    var arrTillcode = [];
    
    var found = false;
    
    $("#datatableX .eventTillcode").each(function() {
        arrTillcode.push($(this).html());
    });
    
    if (isSameDate == "0") {
        $("#datatableY .dateTillcode").each(function() {
            arrDateTillcode.push($(this).html());
        });
    }
    
    if (isSameLocation == "0") {
        $("#datatableZ .locationTillcode").each(function() {
            arrLocationTillcode.push($(this).html());
        });
    }
    
    // date not pool yet
    if (arrDateTillcode.length > 0) {
        var tmpArr = [];
		var tmpIdx = -1;
		for (i = 0; i < arrDateTillcode.length; i++) {
            found = false;
            for (j = 0; j < arrTillcode.length; j++) {
                if (arrDateTillcode[i] == arrTillcode[j]) {
                    found = true;
                    break;
                }
            }
            if (!found) {
				tmpIdx = tmpArr.indexOf(arrDateTillcode[i]);
				if (tmpIdx == -1) {
					dateNotPulled += arrDateTillcode[i] + ", ";
					tmpArr.push(arrDateTillcode[i]);	
				}
            }
        }
        dateNotPulled = dateNotPulled.substr(0, dateNotPulled.length-2);
    }
    
    // location not pool yet
    if (arrLocationTillcode.length > 0) {
		var tmpArr = [];
		var tmpIdx = -1;
        for (i = 0; i < arrLocationTillcode.length; i++) {
            found = false;
            for (j = 0; j < arrTillcode.length; j++) {
                if (arrLocationTillcode[i] == arrTillcode[j]) {
                    found = true;
                    break;
                }
            }
            if (!found) {
				tmpIdx = tmpArr.indexOf(arrLocationTillcode[i]);
				if (tmpIdx == -1) {
					locationNotPulled += arrLocationTillcode[i] + ", ";
					tmpArr.push(arrLocationTillcode[i]);	
				}
            }
        }
        locationNotPulled = locationNotPulled.substr(0, locationNotPulled.length-2);
    }
    
    // pooled tillcode not exist in date/location
    if (arrTillcode.length > 0) {
		var tmpArr = [];
		var tmpArr2 = [];
		var tmpIdx = -1;
		var tmpIdx2 = -1;
		
        for (i = 0; i < arrTillcode.length; i++) {
            
            if (arrDateTillcode.length > 0) {
                found = false;
                for (j = 0; j < arrDateTillcode.length; j++) {
                    if (arrTillcode[i] == arrDateTillcode[j]) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
					tmpIdx = tmpArr.indexOf(arrTillcode[i]);
					if (tmpIdx == -1) {
						missDate += arrTillcode[i] + ", "; 
						tmpArr.push(arrTillcode[i]);	
					}
                }    
            }
            
            if (arrLocationTillcode.length > 0) {
                found = false;
                for (j = 0; j < arrLocationTillcode.length; j++) {
                    if (arrTillcode[i] == arrLocationTillcode[j]) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
					tmpIdx2 = tmpArr2.indexOf(arrTillcode[i]);
					if (tmpIdx2 == -1) {
						missLocation += arrTillcode[i] + ", "; 
						tmpArr2.push(arrTillcode[i]);	
					}
                }    
            }
            
        }
        missDate = missDate.substr(0, missDate.length-2);
        missLocation = missLocation.substr(0, missLocation.length-2);
    }
    
    return {"dateNotPulled": dateNotPulled, "locationNotPulled": locationNotPulled, "missDate": missDate, "missLocation": missLocation};
}

function editDate() {
    var isExc = $("#isExc").val();
    var isSameDate = $("#isSameDate").val();
    var id = $(".modal-body #idToUpdate3").val();
    
    var tillcode = $("#tillcode3_e").val();
    tillcode = tillcode.substr(0, 8);
    
    var eventStartDate = $("#eventStartDate_e").val();
    var eventEndDate = $("#eventEndDate_e").val();
    var eventHargaFaktur = 0;
    var eventHargaJual = 0;
    if (isExc == "Y") {
        eventHargaFaktur = $("#eventHargaFaktur_e").val();
        eventHargaJual = $("#eventHargaJual_e").val();    
    }
    
    if (isSameDate == "1") {
        if (eventStartDate == "" && eventEndDate == "") {
            alert("Silakan mengisi tanggal terlebih dahulu.");
            return;
        }    
    }
    else {
        if (tillcode == "" || (eventStartDate == "" && eventEndDate == "")) {
            alert("Silakan mengisi tillcode dan tanggal terlebih dahulu.");
            return;
        }    
    }
    
    if (eventStartDate == eventEndDate)  eventEndDate = "";
    if (eventStartDate == "" && eventEndDate != "") {
        eventStartDate = eventEndDate;
        eventEndDate = "";
    }
    
    if (!(dateEditExist(id, tillcode, isSameDate, eventStartDate, eventEndDate))) {
        if (!dateEditInRange(id, tillcode, isSameDate, eventStartDate, eventEndDate)) {
            if (isValidDateRange(eventStartDate, eventEndDate)) {
                if (isSameDate == "1") {
                    $("#dateEventStartDate-"+id).html(eventStartDate);
                    $("#dateEventEndDate-"+id).html(eventEndDate);
                    
                    // data attr
                    $("#edit3-"+id).data("date_start", eventStartDate);
                    $("#edit3-"+id).data("date_end", eventEndDate);
                    
                    if (isExc == "Y") {
                        $("#dateHargaFaktur-"+id).html(eventHargaFaktur);
                        $("#dateHargaJual-"+id).html(eventHargaJual);
                    
                        $("#edit3-"+id).data("harga_faktur", eventHargaFaktur);
                        $("#edit3-"+id).data("harga_jual", eventHargaJual);
                    }
                }
                else {
                    $("#dateTillcode-"+id).html(tillcode);
                    $("#dateEventStartDate-"+id).html(eventStartDate);
                    $("#dateEventEndDate-"+id).html(eventEndDate);
                    
                    // tambahkan disini utk ganti tillcode berjamaah
                    var cek = "";
                    $(".dateTillcode").each(function() {
                        cek = parseInt($(this).html());
                        if (cek >= 1 && cek <= 7) {
                            if (cek == tillcodeToHold) {
                                $(this).html(tillcode);
                            }
                        }
                    });
                    cek = "";
                    $(".locationTillcode").each(function() {
                        cek = parseInt($(this).html());
                        if (cek >= 1 && cek <= 7) {
                            if (cek == tillcodeToHold) {
                                $(this).html(tillcode);    
                            }
                        }
                    });
                    // end tambahkan
                    
                    // data attr
                    $("#edit3-"+id).data("tillcode", tillcode);
                    $("#edit3-"+id).data("date_start", eventStartDate);
                    $("#edit3-"+id).data("date_end", eventEndDate);
                    
                    if (isExc == "Y") {
                        $("#dateHargaFaktur-"+id).html(eventHargaFaktur);
                        $("#dateHargaJual-"+id).html(eventHargaJual);
                    
                        $("#edit3-"+id).data("harga_faktur", eventHargaFaktur);
                        $("#edit3-"+id).data("harga_jual", eventHargaJual);
                    }
                    
                    // tambahkan disini utk ganti tillcode berjamaah
                    cek = "";
                    $(".link_edit3").each(function() {
                        cek = parseInt($(this).data("tillcode"));
                        if (cek >= 1 && cek <= 7) {
                            if (cek == tillcodeToHold) {
                                $(this).data("tillcode", tillcode);    
                            }
                        }
                    });
                    cek = "";
                    $(".link_edit2").each(function() {
                        cek = parseInt($(this).data("tillcode"));
                        if (cek >= 1 && cek <= 7) {
                            if (cek == tillcodeToHold) {
                                $(this).data("tillcode", tillcode);    
                            }
                        }
                    });
                    // end tambahkan
                    
                }
                
                $("#editForm3").modal('hide');
            }
            else {
                alert("Interval tanggal tidak valid.");
            }
        }
        else {
            alert("Data tanggal sudah ada dalam interval.");
        }
    }
    else {
        alert("Data tanggal sudah ada.");
    }
}

function editLocation() {
    var isSameLocation = $("#isSameLocation").val();
    var id = $(".modal-body #idToUpdate2").val();
    
    var tillcode = $("#tillcode2_e").val();
    tillcode = tillcode.substr(0, 8);
    
    var locationCode = $("#locationCode_e option:selected").val();
    var storeCode = $("#storeCode_e").val();
    
    if (isSameLocation == "1") {
        if (locationCode == "" || storeCode == "") {
            alert("Silakan mengisi lokasi terlebih dahulu.");
            return;
        }    
    }
    else {
        if (tillcode == "" || locationCode == "" || storeCode == "") {
            alert("Silakan mengisi tillcode dan lokasi terlebih dahulu.");
            return;
        }   
    }
    
    if (!locationEditExist(id, tillcode, isSameLocation, locationCode, storeCode)) {
        if (isSameLocation == "1") {
            $("#locationLocationCode-"+id).html(locationCode);
            $("#locationStoreCode-"+id).html(storeCode);
            
            // data attr
            $("#edit2-"+id).data("loc_desc", locationCode);
            $("#edit2-"+id).data("store_desc", storeCode);
        }
        else {
            $("#locationTillcode-"+id).html(tillcode);
            $("#locationLocationCode-"+id).html(locationCode);
            $("#locationStoreCode-"+id).html(storeCode);
            
            // tambahkan disini utk ganti tillcode berjamaah
            var cek = "";
            $(".dateTillcode").each(function() {
                cek = parseInt($(this).html());
                if (cek >= 1 && cek <= 7) {
                    if (cek == tillcodeToHold) {
                        $(this).html(tillcode);    
                    }
                }
            });
            cek = "";
            $(".locationTillcode").each(function() {
                cek = parseInt($(this).html());
                if (cek >= 1 && cek <= 7) {
                    if (cek == tillcodeToHold) {
                        $(this).html(tillcode);    
                    }
                }
            });
            // end tambahkan        
                    
            // data attr
            $("#edit2-"+id).data("tillcode", tillcode);
            $("#edit2-"+id).data("loc_desc", locationCode);
            $("#edit2-"+id).data("store_desc", storeCode);
            
            // tambahkan disini utk ganti tillcode berjamaah
            cek = "";
            $(".link_edit3").each(function() {
                cek = parseInt($(this).data("tillcode"));
                if (cek >= 1 && cek <= 7) {
                    if (cek == tillcodeToHold) {
                        $(this).data("tillcode", tillcode);    
                    }
                }
            });
            cek = "";
            $(".link_edit2").each(function() {
                cek = parseInt($(this).data("tillcode"));
                if (cek >= 1 && cek <= 7) {
                    if (cek == tillcodeToHold) {
                        $(this).data("tillcode", tillcode);    
                    }
                }
            });
            // end tambahkan
        }
        
        $("#editForm2").modal('hide');
    }
    else {
        alert("Data lokasi sudah ada.");
    }
}

function poolEditTillcode() {
    var id = $(".modal-body #idToUpdate").val();	
    
    var tillcode = $("#tillcode_e").val();
    var notes = $("#notes_e").val();
    var supplierCode = $("#supplierCode_e").val();
    if (supplierCode.length > 4) {
        supplierCode = supplierCode.slice(-5).substr(0, 4);    
    }
    var categoryCode = $("#categoryCode_e option:selected").val();
    var specialPrice = $("#sp_e").val();
    var specialPriceF = $("#sp_e").val();
    var kota = $("#cbKota_e").val();
    var isSp = $("#cbSp_e").prop("checked");
    
    var kindOfResponsibility = $("#kindOfResponsibility_e option:selected").val();
    if (kindOfResponsibility == "0") {
        var ydsResponsibility = $("#ydsResponsibility_e").autoNumeric("get");
        var supplierResponsibility = $("#supplierResponsibility_e").autoNumeric("get");
    }
    else if (kindOfResponsibility == "-1") {
        var ydsResponsibility = "0";
        var supplierResponsibility = "0";
    }
    else {
        var ydsResponsibility = kindOfResponsibility.substr(0, 2);
        var supplierResponsibility = kindOfResponsibility.substr(2, 2);    
    }
    var isPkp = $("#isPkp_e option:selected").val();
    var margin = $("#margin_e").autoNumeric("get");

    isPkpOri = isPkp;
    isPkp = isPkp == "1" ? "PKP" : "NPKP";
   
    if (notes == "" || tillcode == "" || supplierCode == "" || categoryCode == "" || supplierResponsibility == "" || ydsResponsibility == "" || isPkp == "" || margin == "") {
        alert("Silakan lengkapi isian terlebih dahulu.");
        return;
    }
    
    if (kota==null){
        kota = "";
    }
    
    if (isSp && specialPrice == "") {
        alert("Silakan mengisi SP.");
        $("#sp_e").focus();
        return;
    }
    
    var check = new Number(ydsResponsibility) + new Number(supplierResponsibility);
    if (kindOfResponsibility != "-1" && check != 100) {
        alert("Jumlah pertanggungan tidak sama dengan 100.");
        return;
    }
    
    if (!tillcodeEditExist(id, tillcode, supplierCode, categoryCode, supplierResponsibility, ydsResponsibility, isPkp, margin, notes)) {
        
        if (isSp) {
            var dataString = "tillcode=" + tillcode;
            $.ajax({
                type: "POST",
                url: baseUrl+"acara/isValidSpArticle",
                data: dataString,
                beforeSend: function() {
                    //$("#imgLoading").removeClass("hide");
                },
                success: function(data) {
                    if (data == "validsp") {
                        
                        $("#eventNotes-"+id).html(notes);
                        $("#eventTillcode-"+id).html(tillcode);
                        $("#eventSupplierCode-"+id).html(supplierCode);
                        $("#eventKota-"+id).html(kota);
                        $("#eventCategoryCode-"+id).html(categoryCode);
                        $("#eventSupplierResponsibility-"+id).html(supplierResponsibility);
                        $("#eventYdsResponsibility-"+id).html(ydsResponsibility);
                        $("#eventIsPkp-"+id).html(isPkp);
                        $("#eventMargin-"+id).html(margin);
                        $("#eventSp-"+id).html(specialPriceF);
                        
                        // data attr
                        $("#edit-"+id).data("notes", notes);
                        $("#edit-"+id).data("tillcode", tillcode);
                        $("#edit-"+id).data("supp_code", supplierCode);
                        $("#edit-"+id).data("kota", kota);
                        $("#edit-"+id).data("category_desc", categoryCode);
                        $("#edit-"+id).data("supp_responsibility", supplierResponsibility);
                        $("#edit-"+id).data("yds_responsibility", ydsResponsibility);
                        $("#edit-"+id).data("is_pkp", isPkpOri);
                        $("#edit-"+id).data("tax", margin);
                        $("#edit-"+id).data("is_sp", "1");
                        $("#edit-"+id).data("special_price", specialPriceF);
                        
                        tmpSp_e = "";
                        $("#editForm").modal('hide');
                    }
                    else {
                        alert("Article bukan special price.");
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("Error: " + errorThrown);
                },
                complete: function(xhr, textStatus) {
                    //$("#imgLoading").addClass("hide");
                }
            });
        }
        else {
            
            var dataString = "tillcode=" + tillcode;
            $.ajax({
                type: "POST",
                url: baseUrl+"acara/isValidSpArticle",
                data: dataString,
                beforeSend: function() {
                    //$("#imgLoading").removeClass("hide");
                },
                success: function(data) {
                    if (data == "validsp" && !isSp) {
                        alert("Article special price, silahkan mengisi SP.");
                    }
                    else {
                        $("#eventNotes-"+id).html(notes);
                        $("#eventTillcode-"+id).html(tillcode);
                        $("#eventSupplierCode-"+id).html(supplierCode);
                        $("#eventKota-"+id).html(kota);
                        $("#eventCategoryCode-"+id).html(categoryCode);
                        $("#eventSupplierResponsibility-"+id).html(supplierResponsibility);
                        $("#eventYdsResponsibility-"+id).html(ydsResponsibility);
                        $("#eventIsPkp-"+id).html(isPkp);
                        $("#eventMargin-"+id).html(margin);
                        $("#eventSp-"+id).html(specialPriceF);
                        
                        // data attr
                        $("#edit-"+id).data("notes", notes);
                        $("#edit-"+id).data("tillcode", tillcode);
                        $("#edit-"+id).data("supp_code", supplierCode);
                        $("#edit-"+id).data("kota", kota);
                        $("#edit-"+id).data("category_desc", categoryCode);
                        $("#edit-"+id).data("supp_responsibility", supplierResponsibility);
                        $("#edit-"+id).data("yds_responsibility", ydsResponsibility);
                        $("#edit-"+id).data("is_pkp", isPkpOri);
                        $("#edit-"+id).data("tax", margin);
                        $("#edit-"+id).data("is_sp", "0");
                        $("#edit-"+id).data("special_price", specialPriceF);
                        
                        tmpSp_e = "";
                        $("#editForm").modal('hide');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("Error: " + errorThrown);
                },
                complete: function(xhr, textStatus) {
                    //$("#imgLoading").addClass("hide");
                }
            });
           
        }
        
    }
    else {
        alert("Data tillcode sudah ada.");
    }
}

            

       

$(document).on("click", ".editTrigger3", function () {
    var isSameDate = $("#isSameDate").val();
    var id = $(this).data('id');
    var tillcode = $(this).data('tillcode');
    var dateStart = $(this).data('date_start');
    var dateEnd = $(this).data('date_end');
    
    if (isSameDate == "1") {
        $("#tillcode3_e_holder").addClass("hide");
    }
    else {
        $("#tillcode3_e_holder").removeClass("hide");
    }
    
	$(".modal-body #idToUpdate3").val(id);
    $(".modal-body #tillcode3_e").val(tillcode);
	$(".modal-body #eventStartDate_e").val(dateStart);
    $(".modal-body #eventEndDate_e").val(dateEnd);
    
    var isExc = $("#isExc").val();
    if (isExc == "Y") {
        var hargaFaktur = $(this).data('harga_faktur');
        var hargaJual = $(this).data('harga_jual');
        $(".modal-body #eventHargaFaktur_e").val($.trim(hargaFaktur));
        $(".modal-body #eventHargaJual_e").val($.trim(hargaJual));    
    }
    
    // utk yg dr venditore
    tillcodeToHold = tillcode;
});

$(document).on("click", ".editTrigger2", function () {
    var isSameLocation = $("#isSameLocation").val();
    var id = $(this).data('id');
    var tillcode = $(this).data('tillcode');
    var locDesc = $(this).data('loc_desc');
    var storeDesc = $(this).data('store_desc');
    
    if (isSameLocation == "1") {
        $("#tillcode2_e_holder").addClass("hide");
    }
    else {
        $("#tillcode2_e_holder").removeClass("hide");
    }
    
	$(".modal-body #idToUpdate2").val(id);
    $(".modal-body #tillcode2_e").val(tillcode);
	$(".modal-body #locationCode_e").val(locDesc);
    $(".modal-body #storeCode_e").val(storeDesc);
    
    // utk yg dr venditore
    tillcodeToHold = tillcode;
});

$(document).on("click", ".editTrigger", function () {
    var id = $(this).data('id');
    var notes = $(this).data('notes');
    var tillcode = $(this).data('tillcode');
    var suppCode = $(this).data('supp_code');
    var catDesc = $(this).data('category_desc');
    var suppRes = $(this).data('supp_responsibility');
    var ydsRes = $(this).data('yds_responsibility');
    var isPkp = $(this).data('is_pkp');
    var tax = $(this).data('tax');
    var isSp = $(this).data('is_sp');
    var specialPrice = $(this).data('special_price');
    var kota = $(this).data('kota');
    var joinRes = ydsRes + "" + suppRes;
    
    $(".modal-body #idToUpdate").val(id);
	$(".modal-body #notes_e").val(notes);
    $(".modal-body #tillcode_e").val(tillcode);
    $(".modal-body #supplierCode_e").val(suppCode);
    $(".modal-body #categoryCode_e option").each(function() {
        if ($(this).text() == catDesc) {
            $(this).prop("selected", "selected");            
        }                        
    });
    
    var dataString = "supplierCode="+suppCode;
    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterKotaBySupplierEdit/"+suppCode,
        data: dataString,
        success: function(data) {
            $("#cbKota_e").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            $(".modal-body #cbKota_e option").each(function() {
                if ($(this).text() == kota) {
                    $(this).prop("selected", "selected");            
                }                        
            });
        }
    });

    if (joinRes == "5050" || joinRes == "4060") {
        $(".modal-body #kindOfResponsibility_e").val(joinRes);
        $("#responsibilityHolder_e").hide();
    }
    else if (joinRes == "00") {
        $(".modal-body #kindOfResponsibility_e").val("-1");
        $("#responsibilityHolder_e").hide();
    }
    else {
        $(".modal-body #kindOfResponsibility_e").val("0");
        $("#responsibilityHolder_e").show();
    }
    
    $(".modal-body #supplierResponsibility_e").val(suppRes);
    $(".modal-body #ydsResponsibility_e").val(ydsRes);
    
    $(".modal-body #isPkp_e").val(isPkp);
    $(".modal-body #margin_e").val(tax);
    if (isSp == "1") {
        tmpSp_e = specialPrice;
        $("#cbSp_e").prop("checked", true);
        $(".modal-body #sp_e").val(specialPrice);
        $("#sp_e").prop("disabled", false);
        $("#sp_e").focus();
    }
    else {
        tmpSp_e = "";
        $("#cbSp_e").prop("checked", false);
        $(".modal-body #sp_e").val("");
        $("#sp_e").prop("disabled", true);    
    }
    
});

function autocompleteStores() {
    var stores = loadStores();
    $("#storeCode").autocomplete({
         source: stores,
         minLength: 1
    });
    $("#storeCode_e").autocomplete({
         source: stores,
         minLength: 1
    });
}

function loadStores() {
    var storeList = "";
    
    $.ajax({
        url: baseUrl+'acara/loadStores',
        type: "POST",
        async: false,
        data: { sto: null }
    }).done(function(store) {
        storeList = store.split('|');
    });
    
    return storeList;
}

function autocompleteSuppliers() {
    var suppliers = loadSuppliers();
    $("#supplierCode").autocomplete({
         source: suppliers,
         minLength: 1,
		 change: function() {
		    autocompleteBrands();
		    autocompleteTillcodes();
            load_pic();
		 }
    });
    $("#supplierCode_e").autocomplete({
         source: suppliers,
         minLength: 1,
         change: function() {
            filterKotaBySupplierEdit();
         }
    });
}



function loadSuppliers() {
    var supplierList = "";
    
    $.ajax({
        url: baseUrl+'acara/loadSuppliers',
        type: "POST",
        async: false,
        data: { supp: null }
    }).done(function(supplier) {
        supplierList = supplier.split('|');
    });
    
    return supplierList;
}

function autocompleteBrands() {
    var brands = loadBrands();
    
    $("#brandCode").autocomplete({
	source: brands,
	minLength: 1,
		change: function() {
		       autocompleteTillcodes();
		}
    });	
    
    if (brands.length == 1) {
	$("#brandCode").val(brands[0]);
    }
    
}

function loadBrands() {
    var supplier = $("#supplierCode").val();
    supplierCode = supplier.slice(-5).substr(0, 4);
	
    var brandList = "";
    
    if (supplierCode == "") {
	$.ajax({
	    url: baseUrl+'acara/loadBrands',
	    type: "POST",
	    async: false,
	    data: { brand: null }
	}).done(function(brand) {
	    brandList = brand.split('|');
	});	
    }
    else {
	$.ajax({
	    url: baseUrl+'acara/loadBrandsBySupplier/'+supplierCode,
	    type: "POST",
	    async: false,
	    data: { brand: null }
	}).done(function(brand) {
	    brandList = brand.split('|');
	});	
    }
    
    return brandList;
}

function autocompleteTillcodes_e() {
    var tillcodes = loadTillcodes();
    $("#tillcode2_e").autocomplete({
         source: tillcodes,
         minLength: 2
    });
    $("#tillcode3_e").autocomplete({
         source: tillcodes,
         minLength: 2
    });
    /*
    $("#tillcode_e").autocomplete({
         source: tillcodes,
         minLength: 1
    });
    */
}

function autocompleteTillcodes() {
    var tillcodes = loadTillcodes();
    $("#tillcode").autocomplete({
         source: tillcodes,
         minLength: 2,
	 change: function() {
		setMarginPkp();
        filterStoreByTillcode();
        filterSupplierByTillcode();
        filterBrandByTillcode();
        
        //$('#btn_check').show();
       // $('#btn_uncheck').show();
        //$('#btn_add_cabang').show();
	 }
    });
    /*
    $("#tillcode_e").autocomplete({
         source: tillcodes,
         minLength: 1
    });
    */
}

function loadTillcodes() {
    var division = $("#division").val();
	var supplier = $("#supplierCode").val();
	supplierCode = supplier.slice(-5).substr(0, 4);
	var brand = $("#brandCode").val();
	brandCode = brand.slice(-5).substr(0, 4);
	
	// brandCode set to brand description
	/*
	var n = brand.indexOf("(");
	if (n == -1) {
		brandCode = brand;
	}
	else {
		brandCode = brand.substr(0, n-1);
	}
	*/
	
    var tillcodeList = "";
    
	if (supplierCode == "" && brandCode == "") {
		$.ajax({
			url: baseUrl+'acara/loadTillcodes/'+division,
			type: "POST",
			async: false,
			data: { supp: null }
		}).done(function(tillcode) {
			tillcodeList = tillcode.split('|');
		});	
	}
	else if (supplierCode != "" && brandCode == "") {
		$.ajax({
			url: baseUrl+'acara/loadTillcodesBySupplier/'+division+'/'+supplierCode,
			type: "POST",
			async: false,
			data: { supp: null }
		}).done(function(tillcode) {
			tillcodeList = tillcode.split('|');
		});	
	}
	else if (supplierCode == "" && brandCode != "") {
		brandCode = brandCode.replace(/ /g, "~");
		$.ajax({
			url: baseUrl+'acara/loadTillcodesByBrand/'+division+'/'+brandCode,
			type: "POST",
			async: false,
			data: { supp: null }
		}).done(function(tillcode) {
			tillcodeList = tillcode.split('|');
		});	
	}
	else {
		brandCode = brandCode.replace(/ /g, "~");
		$.ajax({
			url: baseUrl+'acara/loadTillcodesBySupplierAndBrand/'+division+'/'+supplierCode+'/'+brandCode,
			type: "POST",
			async: false,
			data: { supp: null }
		}).done(function(tillcode) {
			tillcodeList = tillcode.split('|');
		});	
	}
	
    return tillcodeList;
}

function setMarginPkp() {
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    var dataString = "tillcode=" + tillcode;
    $.ajax({
	type: "POST",
	url: baseUrl+"acara/setMarginPkp/"+tillcode,
	data: dataString,
	beforeSend: function() {
	    //$("#imgLoading").removeClass("hide");
	},
	success: function(data) {
	    var aData = data.split("|");
	    var margin = aData[0];
	    var pkp = aData[1];
	    $("#margin").autoNumeric("set", margin);
	    $("#isPkp").val(pkp);
	},
	error: function(xhr, textStatus, errorThrown) {
	    alert("Error: " + errorThrown);
	},
	complete: function(xhr, textStatus) {
	    //$("#imgLoading").addClass("hide");
	}
    });
}

//function by gie
function filterStoreByTillcode() {
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    var dataString = "tillcode=" + tillcode;
    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterStoreByTillcode/"+tillcode,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            $("#div_store").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            //$("#imgLoading").addClass("hide");
        }
    });
}

function filterSupplierByTillcode() {
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    var dataString = "tillcode=" + tillcode;
    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterSupplierByTillcode/"+tillcode,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            $("#supplierCode").val(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            filterKotaBySupplier();
        }
    });
}

function filterBrandByTillcode() {
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    var dataString = "tillcode=" + tillcode;
    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterBrandByTillcode/"+tillcode,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            $("#brandCode").val(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            //$("#imgLoading").addClass("hide");
        }
    });
}

function filterKotaBySupplier() {
    var supplierCode = $("#supplierCode").val(); 
    var supplierCode = supplierCode.substr(supplierCode.indexOf("(")+1, 4);
    var dataString = "supplierCode=" + supplierCode;

    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterKotaBySupplier/"+supplierCode,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            $("#cbKota").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            //$("#imgLoading").addClass("hide");
        }
    });
}

function filterKotaBySupplierEdit() {
    var supplierCode = $("#supplierCode_e").val(); 
    var supplierCode = supplierCode.substr(supplierCode.indexOf("(")+1, 4);
    var dataString = "supplierCode=" + supplierCode;

    $.ajax({
        type: "POST",
        url: baseUrl+"acara/filterKotaBySupplierEdit/"+supplierCode,
        data: dataString,
        success: function(data) {
            $("#cbKota_e").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        }
    });
}

function load_pic() {
    var supplierCode = $("#supplierCode").val(); 
    var supplierCode = supplierCode.substr(supplierCode.indexOf("(")+1, 4);
    var dataString = "supplierCode=" + supplierCode;
    
    $.ajax({
        type: "POST",
        url: baseUrl+"acara/load_pic/"+supplierCode,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            $("#pic_label").html('Pic : '+data);
            $("#txt_pic").val(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            //$("#imgLoading").addClass("hide");
        }
    });
}
//end-
function submitEvent(todo) {
    var dateTillcode = "";
    var dateEventStartDate = "";
    var dateEventEndDate = "";
    var dateEventHargaFaktur = "";
    var dateEventHargaJual = "";
    
    var locationTillcode = "";
    var locationLocationCode = "";
    var locationStoreCode = "";
    
    var eventTillcode = "";
    var eventSupplierCode = "";
    var eventCategoryCode = "";
    var eventSupplierResponsibility = "";
    var eventYdsResponsibility = "";
    var eventIsPkp = "";
    var eventMargin = "";
    var eventSp = "";
    var eventNotes = "";
    var eventKota = "";
    
    var id = $("#id").val(); // for edit
    //var sameDate = $("#sameDate").prop("checked") ? 1 : 0;
    //var sameLocation = $("#sameLocation").prop("checked") ? 1 : 0;
    var isSameDate = $("#isSameDate").val();
    var isSameLocation = $("#isSameLocation").val();
    var isExc = $("#isExc").val();
    
    var pkp = "";
    var loc = "";
    var sto = "";
    
    $("#datatableY .dateTillcode").each(function() {
        dateTillcode += $(this).html() + "#";
    });
    dateTillcode = dateTillcode.substr(0, dateTillcode.length-1);
    
    $("#datatableY .dateEventStartDate").each(function() {
        dateEventStartDate += $(this).html() + "#";
    });
    dateEventStartDate = dateEventStartDate.substr(0, dateEventStartDate.length-1);
    
    $("#datatableY .dateEventEndDate").each(function() {
        dateEventEndDate += $(this).html() + "#";
    });
    dateEventEndDate = dateEventEndDate.substr(0, dateEventEndDate.length-1);
    
    if (isExc) {
        $("#datatableY .dateHargaFaktur").each(function() {
            dateEventHargaFaktur += $(this).html() + "#";
        });
        dateEventHargaFaktur = dateEventHargaFaktur.substr(0, dateEventHargaFaktur.length-1);
        
        $("#datatableY .dateHargaJual").each(function() {
            dateEventHargaJual += $(this).html() + "#";
        });
        dateEventHargaJual = dateEventHargaJual.substr(0, dateEventHargaJual.length-1);    
    }
    
    $("#datatableZ .locationTillcode").each(function() {
        locationTillcode += $(this).html() + "#";
    });
    locationTillcode = locationTillcode.substr(0, locationTillcode.length-1);
     
    $("#datatableZ .locationLocationCode").each(function() {
        loc = arrLocation[$(this).html()];
        locationLocationCode += loc + "#";
    });
    locationLocationCode = locationLocationCode.substr(0, locationLocationCode.length-1);
    
	/*$("#datatableZ .locationStoreCode").each(function() {
		var tmpStore = $(this).html();
		var n = tmpStore.indexOf("(");
		if (n == -1) {
			sto = arrStore[tmpStore];
		}
		else {
			var tmpStore2 = tmpStore.substr(0, n-1);
			sto = arrStore[tmpStore2];
		}
        //sto = arrStore[$(this).html()];
		locationStoreCode += sto + "#";
    });
    locationStoreCode = locationStoreCode.substr(0, locationStoreCode.length-1);
    */
   
   $("#datatableZ .locationStoreCode").each(function() {
        var tmpStore = $(this).html();
        var sto = tmpStore.substr(tmpStore.indexOf("(")+1, 3);
        locationStoreCode += sto + "#";
    });
    locationStoreCode = locationStoreCode.substr(0, locationStoreCode.length-1);
    
    /*$("#datatableZ .locationStoreCode").each(function() {
        locationStoreCode += $(this).html() + "#";
    });
    locationStoreCode = locationStoreCode.substr(0, locationStoreCode.length-1);
    */
    
    $("#datatableX .eventTillcode").each(function() {
        eventTillcode += $(this).html() + "#";
    });
    eventTillcode = eventTillcode.substr(0, eventTillcode.length-1);
    
    $("#datatableX .eventSupplierCode").each(function() {
        eventSupplierCode += $(this).html() + "#";
    });
    eventSupplierCode = eventSupplierCode.substr(0, eventSupplierCode.length-1);
    
    $("#datatableX .eventCategoryCode").each(function() {
        cat = arrCategory[$(this).html().replace(/&amp;/gi, "&")];
        eventCategoryCode += cat + "#";
    });
    eventCategoryCode = eventCategoryCode.substr(0, eventCategoryCode.length-1);
    
    $("#datatableX .eventSupplierResponsibility").each(function() {
        eventSupplierResponsibility += $(this).html() + "#";
    });
    eventSupplierResponsibility = eventSupplierResponsibility.substr(0, eventSupplierResponsibility.length-1);
    
    $("#datatableX .eventYdsResponsibility").each(function() {
        eventYdsResponsibility += $(this).html() + "#";
    });
    eventYdsResponsibility = eventYdsResponsibility.substr(0, eventYdsResponsibility.length-1);
    
    $("#datatableX .eventIsPkp").each(function() {
        pkp = $(this).html() == "PKP" ? "1" : "0";
        eventIsPkp += pkp + "#";
    });
    eventIsPkp = eventIsPkp.substr(0, eventIsPkp.length-1);
    
    $("#datatableX .eventMargin").each(function() {
        eventMargin += $(this).html() + "#";
    });
    eventMargin = eventMargin.substr(0, eventMargin.length-1);
    
    $("#datatableX .eventSp").each(function() {
        eventSp += $(this).html().replace(/&nbsp;/gi, '').replace(/,/g, "" ) + "#";
    });
    eventSp = eventSp.substr(0, eventSp.length-1);
    //alert(eventSp);
    
    $("#datatableX .eventNotes").each(function() {
        eventNotes += $(this).html() + "#";
    });
    eventNotes = eventNotes.substr(0, eventNotes.length-1);

    $("#datatableX .eventKota").each(function() {
        eventKota += $(this).html() + "#";
    });
    eventKota = eventKota.substr(0, eventKota.length-1);
    
    var dataString = "dateTillcode=" + dateTillcode + "&dateEventStartDate=" + dateEventStartDate + "&dateEventEndDate=" + dateEventEndDate + "&dateEventHargaFaktur=" + dateEventHargaFaktur + "&dateEventHargaJual=" + dateEventHargaJual + 
                    "&locationTillcode=" + locationTillcode + "&locationLocationCode=" + locationLocationCode + "&locationStoreCode=" + locationStoreCode +
                    "&eventTillcode=" + eventTillcode + "&eventSupplierCode=" + eventSupplierCode + "&eventCategoryCode=" + eventCategoryCode +
                    "&eventSupplierResponsibility=" + eventSupplierResponsibility + "&eventYdsResponsibility=" + eventYdsResponsibility + "&eventIsPkp=" + eventIsPkp +
                    "&eventMargin=" + eventMargin + "&eventSp=" + eventSp + "&eventNotes=" + encodeURIComponent(eventNotes) + "&isSameLocation=" + isSameLocation + "&isSameDate=" + isSameDate + "&isExc=" + isExc + "&eventKota=" + eventKota;
    
    var sUrl = baseUrl+"acara/save";
    if (todo == "edit") {
        sUrl = baseUrl+"acara/save/"+id;
    }
    
    $.ajax({
        type: "POST",
        url: sUrl,
        data: dataString,
        beforeSend: function() {
            //$("#imgLoading").removeClass("hide");
        },
        success: function(data) {
            //alert("Input berhasil: " + data);
            location.href=baseUrl+"acara/preview/"+data;
        },
        error: function(xhr, textStatus, errorThrown) {
            //alert("Error: " + errorThrown);
        },
        complete: function(xhr, textStatus) {
            //$("#imgLoading").addClass("hide");
        }
    });
    
}



$("#frmAcaraNext").on("reset", function() {
    resetDetailTables();
});

function resetDetailTables() {
    var isSameDate = $("#isSameDate").val();
    var isSameLocation = $("#isSameLocation").val();
    
    if (isSameDate == "1") {
        var row =   "<tr id='dummyRowY'>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                    "</tr>";    
    }
    else {
        var row =   "<tr id='dummyRowY'>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                    "</tr>";
    }
    $("#datatableY tr:gt(0)").remove();
    $("#datatableY > tbody:last").append(row);
    
    if (isSameLocation == "1") {
        var row =   "<tr id='dummyRowZ'>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                    "</tr>";    
    }
    else {
        var row =   "<tr id='dummyRowZ'>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                        "<td>&nbsp;</td>" + 
                    "</tr>";
    }
    $("#datatableZ tr:gt(0)").remove();
    $("#datatableZ > tbody:last").append(row);
    
    var row =   "<tr id='dummyRowX'>" +
                    "<td>&nbsp;</td>" +
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" +
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                    "<td>&nbsp;</td>" + 
                "</tr>";
    $("#datatableX tr:gt(0)").remove();
    $("#datatableX > tbody:last").append(row);
}

function addDeleteRowEvent(id, checkbox_id) {
    var isSameDate = $("#isSameDate").val();
    var isSameLocation = $("#isSameLocation").val();
    
    $(".btnRowDelete").click(function() {
        $(this).closest("tr").remove();
        
        var id = $(this).data('id');
        $(id).prop('checked', false);//gie
        
        
        

        var rowCount = $("#" + id + " tr").length;
        
        if (rowCount == 1) {
            if (id == "datatableY") {
                if (isSameDate == "1") {
                    var row =   "<tr id='dummyRowY'>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                "</tr>";
                }
                else {
                    var row =   "<tr id='dummyRowY'>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                "</tr>";
                }
                $("#datatableY > tbody:last").append(row);
            }
            else if (id == "datatableZ") {
                if (isSameLocation == "1") {
                    var row =   "<tr id='dummyRowZ'>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                "</tr>";
                }
                else {
                    var row =   "<tr id='dummyRowZ'>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                    "<td>&nbsp;</td>" + 
                                "</tr>";
                }
                $("#datatableZ > tbody:last").append(row);
            }
            else if (id == "datatableX") {
                var row =   "<tr id='dummyRowX'>" + 
                                "<td>&nbsp;</td>" +
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" +
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                                "<td>&nbsp;</td>" + 
                            "</tr>";
                
                $("#datatableX > tbody:last").append(row);
            }
        }
    });
}

function locationsWithEmptyTillcode() {
    var check = "";
    var isSameLocation = $("#isSameLocation").val();
    var ret = false;
    
    if (isSameLocation != "1") {
        $("#datatableZ > tbody  > tr").each(function() { 
            $("td", this).each(function (index) {     
                if (index == 0) {
                    check = $(this).html().replace("&nbsp;", "");
                    if (check == "") {
                        ret = true;
                        return false;
                    }
                }
                return true;
            });
        });
    }
    
    return ret;
}

function datesWithEmptyTillcode() {
    var check = "";
    var isSameDate = $("#isSameDate").val();
    var ret = false;
    
    if (isSameDate != "1") {
        $("#datatableY > tbody  > tr").each(function() { 
            $("td", this).each(function (index) {          
                if (index == 0) {
                    check = $(this).html().replace("&nbsp;", "");
                    if (check == "") {
                        ret = true;
                        return false;
                    }
                }
                return true;
            });
        });
    }
    
    return ret;
}

function emptyLocations() {
    var check = "";
    var isSameLocation = $("#isSameLocation").val();
    
    $("#datatableZ > tbody  > tr").each(function() { 
        $("td", this).each(function (index) {
            if (isSameLocation == "1") {
                if (index < 2) {
                    check += $(this).html().replace("&nbsp;", "");
                }    
            }
            else {
                if (index < 3) {
                    check += $(this).html().replace("&nbsp;", "");
                }    
            }
            
        });
    });
    
    if (check == "") {
        return true;
    }
    return false;
}

function emptyDates() {
    var check = "";
    var isSameDate = $("#isSameDate").val();
    
    $("#datatableY > tbody  > tr").each(function() { 
        $("td", this).each(function (index) {
            if (isSameDate == "1") {
                if (index < 2) {
                    check += $(this).html().replace("&nbsp;", "");
                }    
            }
            else {
                if (index < 3) {
                    check += $(this).html().replace("&nbsp;", "");
                }   
            }
        });
    });
    
    if (check == "") {
        return true;
    }
    return false;
}

function emptyTillcodes() {
    var check = "";
    
    $("#datatableX > tbody  > tr").each(function() { 
        $("td", this).each(function (index) {
            if (index < 9) {
                check += $(this).html().replace("&nbsp;", "");
            }
        });
    });
    
    if (check == "") {
        return true;
    }
    return false;
}

function locationEditExist(idx, tillcode, sameLocation, locationCode, storeCode) {
    var check = "";
    var ret = false;
    
    if (sameLocation == "1") {
        var row = locationCode + storeCode;
    }
    else {
        var row = tillcode + locationCode + storeCode;
    }
    
    $("#datatableZ > tbody  > tr").each(function(index) {
        
        if (index != idx) {
            check = "";    
            $("td", this).each(function (index) {
                if (sameLocation == "1") {
                    if (index < 2) {
                        check += $(this).html().trim();
                    }
                }
                else {
                    if (index < 3) {
                        check += $(this).html().trim();
                    }
                }
            });
            
            if (check == row) {
                ret = true;
                return false;
            }
        }
        
        return true;
    });
    
    return ret;
}

function locationExist(tillcode, sameLocation, locationCode, storeCode) {
    var check = "";
    var ret = false;
    
    if (sameLocation == "1") {
        var row = locationCode + storeCode;
    }
    else {
        var row = tillcode + locationCode + storeCode;
    }
    
    $("#datatableZ > tbody  > tr").each(function() {
    
        check = "";    
        $("td", this).each(function (index) {
            if (sameLocation == "1") {
                if (index < 2) {
                    check += $(this).html().trim();
                }
            }
            else {
                if (index < 3) {
                    check += $(this).html().trim();
                }
            }
        });
        
        if (check == row) {
            ret = true;
            return false;
        }
        
        return true;
    });
    
    return ret;
}

function dateEditInRange(idx, tillcode, isSameDate, eventStartDate, eventEndDate) {
    var ret = false;
    
    var existingStartDate = "";
    var existingEndDate = "";
    var existingTillcode = "";
    var existingStartDateEn = "";
    var existingEndDateEn = "";
    var objExistingStartDate = null;
    var objExistingEndDate = null;
    
    var eventStartDateEn = "";
    var eventEndDateEn = "";
    var objEventStartDate = null;
    var objEventEndDate = null;
    
    var time1 = 0;
    var time2 = 0;
    var time3 = 0;
    
    // format: dd-mm-yyyy -> yyyy-mm-dd
    if (eventStartDate != "") {
        eventStartDateEn = eventStartDate.substr(6, 4) + "-" + eventStartDate.substr(3, 2) + "-" + eventStartDate.substr(0, 2);
    }
    if (eventEndDate) {
        eventEndDateEn = eventEndDate.substr(6, 4) + "-" + eventEndDate.substr(3, 2) + "-" + eventEndDate.substr(0, 2);
    }
    
    $("#datatableY > tbody  > tr").each(function(index) {
        
        if (index != idx) {
            $("td", this).each(function (index) {
                if (isSameDate == "1") {
                    existingTillcode = "";    
                    if (index == 0) {
                        existingStartDate = $(this).html().trim();
                        existingStartDateEn = existingStartDate.substr(6, 4) + "-" + existingStartDate.substr(3, 2) + "-" + existingStartDate.substr(0, 2);
                    }
                    else if (index == 1) {
                        existingEndDate = $(this).html().trim();
                        existingEndDateEn = existingEndDate.substr(6, 4) + "-" + existingEndDate.substr(3, 2) + "-" + existingEndDate.substr(0, 2);
                    }    
                }
                else {
                    if (index == 0) {
                        existingTillcode = $(this).html().trim();    
                    }
                    else if (index == 1) {
                        existingStartDate = $(this).html().trim();
                        existingStartDateEn = existingStartDate.substr(6, 4) + "-" + existingStartDate.substr(3, 2) + "-" + existingStartDate.substr(0, 2);
                    }
                    else if (index == 2) {
                        existingEndDate = $(this).html().trim();
                        existingEndDateEn = existingEndDate.substr(6, 4) + "-" + existingEndDate.substr(3, 2) + "-" + existingEndDate.substr(0, 2);
                    }
                }
            });
            
            if (existingStartDate != "" && existingEndDate != "") {    
                objExistingStartDate = new Date(existingStartDateEn);
                objExistingEndDate = new Date(existingEndDateEn);
                objEventStartDate = new Date(eventStartDateEn);
                if (isSameDate == "1") {
                    time1 = objExistingStartDate.getTime();
                    time2 = objExistingEndDate.getTime();
                    time3 = objEventStartDate.getTime();
                    if (time3 >= time1 && time3 <= time2) {
                        ret = true;
                        return false;
                    }
                }
                else {
                    if (existingTillcode == tillcode) {
                        time1 = objExistingStartDate.getTime();
                        time2 = objExistingEndDate.getTime();
                        time3 = objEventStartDate.getTime();
                        if (time3 >= time1 && time3 <= time2) {
                            ret = true;
                            return false;
                        }
                    }    
                } 
            }
            else if (eventStartDate != "" && eventEndDate != "") {
                objEventStartDate = new Date(eventStartDateEn);
                objEventEndDate = new Date(eventEndDateEn);
                objExistingStartDate = new Date(existingStartDateEn);
                if (isSameDate == "1") {
                    time1 = objEventStartDate.getTime();
                    time2 = objEventEndDate.getTime();
                    time3 = objExistingStartDate.getTime();
                    if (time3 >= time1 && time3 <= time2) {
                        ret = true;
                        return false;
                    }
                }
                else {
                    if (existingTillcode == tillcode) {
                        time1 = objEventStartDate.getTime();
                        time2 = objEventEndDate.getTime();
                        time3 = objExistingStartDate.getTime();
                        if (time3 >= time1 && time3 <= time2) {
                            ret = true;
                            return false;
                        }
                    }    
                } 
            }    
        }
        
        return true;
    });
    
    return ret;
}

function dateInRange(tillcode, isSameDate, eventStartDate, eventEndDate) {
    var ret = false;
    
    var existingStartDate = "";
    var existingEndDate = "";
    var existingTillcode = "";
    var existingStartDateEn = "";
    var existingEndDateEn = "";
    var objExistingStartDate = null;
    var objExistingEndDate = null;
    
    var eventStartDateEn = "";
    var eventEndDateEn = "";
    var objEventStartDate = null;
    var objEventEndDate = null;
    
    var time1 = 0;
    var time2 = 0;
    var time3 = 0;
    
    // format: dd-mm-yyyy -> yyyy-mm-dd
    if (eventStartDate != "") {
        eventStartDateEn = eventStartDate.substr(6, 4) + "-" + eventStartDate.substr(3, 2) + "-" + eventStartDate.substr(0, 2);
    }
    if (eventEndDate) {
        eventEndDateEn = eventEndDate.substr(6, 4) + "-" + eventEndDate.substr(3, 2) + "-" + eventEndDate.substr(0, 2);
    }
    
    $("#datatableY > tbody  > tr").each(function() {
    
        $("td", this).each(function (index) {
            if (isSameDate == "1") {
                existingTillcode = "";    
                if (index == 0) {
                    existingStartDate = $(this).html().trim();
                    existingStartDateEn = existingStartDate.substr(6, 4) + "-" + existingStartDate.substr(3, 2) + "-" + existingStartDate.substr(0, 2);
                }
                else if (index == 1) {
                    existingEndDate = $(this).html().trim();
                    existingEndDateEn = existingEndDate.substr(6, 4) + "-" + existingEndDate.substr(3, 2) + "-" + existingEndDate.substr(0, 2);
                }    
            }
            else {
                if (index == 0) {
                    existingTillcode = $(this).html().trim();    
                }
                else if (index == 1) {
                    existingStartDate = $(this).html().trim();
                    existingStartDateEn = existingStartDate.substr(6, 4) + "-" + existingStartDate.substr(3, 2) + "-" + existingStartDate.substr(0, 2);
                }
                else if (index == 2) {
                    existingEndDate = $(this).html().trim();
                    existingEndDateEn = existingEndDate.substr(6, 4) + "-" + existingEndDate.substr(3, 2) + "-" + existingEndDate.substr(0, 2);
                }
            }
        });
        
        if (existingStartDate != "" && existingEndDate != "") {    
            objExistingStartDate = new Date(existingStartDateEn);
            objExistingEndDate = new Date(existingEndDateEn);
            objEventStartDate = new Date(eventStartDateEn);
            if (isSameDate == "1") {
                time1 = objExistingStartDate.getTime();
                time2 = objExistingEndDate.getTime();
                time3 = objEventStartDate.getTime();
                if (time3 >= time1 && time3 <= time2) {
                    ret = true;
                    return false;
                }
            }
            else {
                if (existingTillcode == tillcode) {
                    time1 = objExistingStartDate.getTime();
                    time2 = objExistingEndDate.getTime();
                    time3 = objEventStartDate.getTime();
                    if (time3 >= time1 && time3 <= time2) {
                        ret = true;
                        return false;
                    }
                }    
            } 
        }
        else if (eventStartDate != "" && eventEndDate != "") {
            objEventStartDate = new Date(eventStartDateEn);
            objEventEndDate = new Date(eventEndDateEn);
            objExistingStartDate = new Date(existingStartDateEn);
            if (isSameDate == "1") {
                time1 = objEventStartDate.getTime();
                time2 = objEventEndDate.getTime();
                time3 = objExistingStartDate.getTime();
                if (time3 >= time1 && time3 <= time2) {
                    ret = true;
                    return false;
                }
            }
            else {
                if (existingTillcode == tillcode) {
                    time1 = objEventStartDate.getTime();
                    time2 = objEventEndDate.getTime();
                    time3 = objExistingStartDate.getTime();
                    if (time3 >= time1 && time3 <= time2) {
                        ret = true;
                        return false;
                    }
                }    
            } 
        }
        
        return true;
    });
    
    return ret;
}

function dateEditExist(idx, tillcode, sameDate, eventStartDate, eventEndDate) {
    var check = "";
    var ret = false;
    
    if (sameDate == "1") {
        var row = eventStartDate + "" + eventEndDate;
    }
    else {
        var row = tillcode + "" + eventStartDate + "" + eventEndDate;
    }
    
    $("#datatableY > tbody  > tr").each(function(index) {
        
        if (index != idx) {
            check = "";    
            $("td", this).each(function (index) {
                if (sameDate == "1") {
                    if (index < 2) {
                        check += $(this).html().trim();
                    }
                }
                else {
                    if (index < 3) {
                        check += $(this).html().trim();
                    }
                }
            });
            
            if (check == row) {
                ret = true;
                return false;
            }   
        }
        
        return true;
    });
    
    return ret;
}

function dateExist(tillcode, sameDate, eventStartDate, eventEndDate) {
    var check = "";
    var ret = false;
    
    if (sameDate == "1") {
        var row = eventStartDate + "" + eventEndDate;
    }
    else {
        var row = tillcode + "" + eventStartDate + "" + eventEndDate;
    }
    
    $("#datatableY > tbody  > tr").each(function() {
    
        check = "";    
        $("td", this).each(function (index) {
            if (sameDate == "1") {
                if (index < 2) {
                    check += $(this).html().trim();
                }
            }
            else {
                if (index < 3) {
                    check += $(this).html().trim();
                }
            }
        });
        
        if (check == row) {
            ret = true;
            return false;
        }
        
        return true;
    });
    
    return ret;
}

function isValidDateRange(startDate, endDate) {
    // format: dd-mm-yyyy -> yyyy-mm-dd
    if (startDate != "" && endDate != "") {
        startDateEn = startDate.substr(6, 4) + "-" + startDate.substr(3, 2) + "-" + startDate.substr(0, 2);
        endDateEn = endDate.substr(6, 4) + "-" + endDate.substr(3, 2) + "-" + endDate.substr(0, 2);
        
        return Date.parse(startDateEn) <= Date.parse(endDateEn);
    }
    return true;
}

function tillcodeEditExist(idx, tillcode, supplierCode, categoryCode, supplierResponsibility, ydsResponsibility, isPkp, margin, notes) {
    var check = "";
    var ret = false;
    var row = notes + tillcode + supplierCode + categoryCode + supplierResponsibility + ydsResponsibility + isPkp + margin;
    
    $("#datatableX > tbody  > tr").each(function(index) {
        if (index != idx) {
            check = "";
            $("td", this).each(function (index) {
                if (index < 9) {
                    check += $(this).html().trim();
                }
            });
            
            if (check == row) {
                ret = true;
                return false;
            }    
        }
        return true;
    });
    
    // tambahan
    if (!ret) {
        var eventTillcode = "";
        var isSameDate = $("#isSameDate").val();
        if (isSameDate == "1") {
            $("#datatableX .eventTillcode").each(function(index) {
                if (index != idx) {
                    eventTillcode = $(this).html().trim();
                    if (tillcode == eventTillcode) {
                        ret = true;
                        return false;
                    }
                }
                return true;
            });
        }
        else {
            // check tillcode & date
            
        }
    }
    // end tambahan
    
    return ret;
}

function tillcodeExist(tillcode, supplierCode, categoryCode, supplierResponsibility, ydsResponsibility, isPkp, margin, notes) {
    var check = "";
    var ret = false;
    var row = notes + tillcode + supplierCode + categoryCode + supplierResponsibility + ydsResponsibility + isPkp + margin;
    
    $("#datatableX > tbody  > tr").each(function() {
        check = "";
        $("td", this).each(function (index) {
            if (index < 9) {
                check += $(this).html().trim();
            }
        });
        
        if (check == row) {
            ret = true;
            return false;
        }
        
        return true;
    });
    
    // tambahan
    //if (!ret) {
    //    var eventTillcode = "";
    //    var isSameDate = $("#isSameDate").val();
    //    if (isSameDate == "1") {
    //        $("#datatableX .eventTillcode").each(function() {
    //            eventTillcode = $(this).html().trim();
    //            if (tillcode == eventTillcode) {
    //                ret = true;
    //                return false;
    //            }
    //            return true;
    //        });
    //    }
    //    else {
    //        // check tillcode & date
    //        
    //    }
    //}
    // end tambahan
    
    return ret;
}

$("#btnAddDate").click(function() {
    var todo = $("#todo").val();
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    //var sameDate = $("#sameDate").prop("checked");
    var isSameDate = $("#isSameDate").val();
    var eventStartDate = $("#eventStartDate").val();
    var eventEndDate = $("#eventEndDate").val();
    var isExc = $("#isExc").val();
    
    if (isSameDate == "1") {
        if (eventStartDate == "" && eventEndDate == "") {
            alert("Silakan mengisi tanggal terlebih dahulu.");
            return;
        }    
    }
    else {
        if (tillcode == "" || (eventStartDate == "" && eventEndDate == "")) {
            alert("Silakan mengisi tillcode dan tanggal terlebih dahulu.");
            return;
        }    
    }
    
    //if (isSameDate == "1") tillcode = "";
    if (eventStartDate == eventEndDate)  eventEndDate = "";
    if (eventStartDate == "" && eventEndDate != "") {
        eventStartDate = eventEndDate;
        eventEndDate = "";
    }
    
    if (!(dateExist(tillcode, isSameDate, eventStartDate, eventEndDate))) {
        if (!dateInRange(tillcode, isSameDate, eventStartDate, eventEndDate)) {
            if (isValidDateRange(eventStartDate, eventEndDate)) {
                
				if (isSameDate == "1") {
					
					var cntY = new Number($("#cntY").val());
                
					if (todo == "edit") {
						var idx = cntY;
						if (idx < 0) {
							idx = 0;
						}
						
                        var tdHarga = "";
                        var dataHarga = "";
                        if (isExc == "Y") {
                            tdHarga = "<td class='dateHargaFaktur al-right' id='dateHargaFaktur-" + idx + "'>0</td>" ;
                            tdHarga += "<td class='dateHargaJual al-right' id='dateHargaJual-" + idx + "'>0</td>" ;
                            dataHarga = " data-harga_faktur=\"0\" ";
                            dataHarga += " data-harga_jual=\"0\" ";
                        }
                        
						if (isSameDate == "1") {
							var row =   "<tr>" + 
											"<td class='dateEventStartDate' id='dateEventStartDate-" + idx + "'>" + eventStartDate + "</td>" + 
											"<td class='dateEventEndDate' id='dateEventEndDate-" + idx + "'>" + eventEndDate + "</td>" +
                                            tdHarga + 
											"<td class='al-center'>" + 
												"<a id=\"edit3-" + idx + "\"" +  
													"data-id=\"" + idx + "\"" + 
													"data-tillcode=\"\"" + 
													"data-date_start=\"" + eventStartDate + "\"" + 
													"data-date_end=\"" + eventEndDate + "\"" +
                                                    dataHarga + 
													"data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3'>" + 
													"<i class='fa fa-pencil'></i> edit" + 
												"</a>" + 
											"</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";    
						}
						else {
							var row =   "<tr>" + 
											"<td class='dateTillcode' id='dateTillcode-" + idx + "'>" + tillcode + "</td>" + 
											"<td class='dateEventStartDate' id='dateEventStartDate-" + idx + "'>" + eventStartDate + "</td>" + 
											"<td class='dateEventEndDate' id='dateEventEndDate-" + idx + "'>" + eventEndDate + "</td>" +
                                            tdHarga + 
											"<td class='al-center'>" + 
												"<a id=\"edit3-" + idx + "\"" +  
													"data-id=\"" + idx + "\"" + 
													"data-tillcode=\"" + tillcode + "\"" + 
													"data-date_start=\"" + eventStartDate + "\"" + 
													"data-date_end=\"" + eventEndDate + "\"" +
                                                    dataHarga + 
													"data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3'>" + 
													"<i class='fa fa-pencil'></i> edit" + 
												"</a>" + 
											"</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";
						}    
					}
					else {
						if (isSameDate == "1") {
							var row =   "<tr>" + 
											"<td class='dateEventStartDate'>" + eventStartDate + "</td>" + 
											"<td class='dateEventEndDate'>" + eventEndDate + "</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";    
						}
						else {
							var row =   "<tr>" + 
											"<td class='dateTillcode'>" + tillcode + "</td>" + 
											"<td class='dateEventStartDate'>" + eventStartDate + "</td>" + 
											"<td class='dateEventEndDate'>" + eventEndDate + "</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";
						}    
					}
					
					if ($("#datatableY tr#dummyRowY").length) {
						$("#datatableY tr#dummyRowY").remove();
					}
					
					$("#datatableY > tbody:last").append(row);
					$("#cntY").val(cntY+1);
					
					addDeleteRowEvent("datatableY");
					$("#eventStartDate").val("");
					$("#eventEndDate").val("");
		
				}
				else {
					var dataString = "tillcode=" + tillcode;
					$.ajax({
						type: "POST",
						url: baseUrl+"acara/isArticleHasDiscount",
						data: dataString,
						beforeSend: function() {
							//$("#imgLoading").removeClass("hide");
						},
						success: function(data) {
							//alert("Input berhasil: " + data);
							if (data == "hasnot") {
								alert("Tillcode belum dilengkapi nilai diskonnya di data master.");
							}
							else {
								
								var cntY = new Number($("#cntY").val());
                
								if (todo == "edit") {
									var idx = cntY;
									if (idx < 0) {
										idx = 0;
									}
									
                                    var tdHarga = "";
                                    var dataHarga = "";
                                    if (isExc == "Y") {
                                        tdHarga = "<td class='dateHargaFaktur al-right' id='dateHargaFaktur-" + idx + "'>0</td>" ;
                                        tdHarga += "<td class='dateHargaJual al-right' id='dateHargaJual-" + idx + "'>0</td>" ;
                                        dataHarga = " data-harga_faktur=\"0\" ";
                                        dataHarga += " data-harga_jual=\"0\" ";
                                    }
                                    
									if (isSameDate == "1") {
										var row =   "<tr>" + 
														"<td class='dateEventStartDate' id='dateEventStartDate-" + idx + "'>" + eventStartDate + "</td>" + 
														"<td class='dateEventEndDate' id='dateEventEndDate-" + idx + "'>" + eventEndDate + "</td>" +
                                                        tdHarga + 
														"<td class='al-center'>" + 
															"<a id=\"edit3-" + idx + "\"" +  
																"data-id=\"" + idx + "\"" + 
																"data-tillcode=\"\"" + 
																"data-date_start=\"" + eventStartDate + "\"" + 
																"data-date_end=\"" + eventEndDate + "\"" +
                                                                dataHarga + 
																"data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3'>" + 
																"<i class='fa fa-pencil'></i> edit" + 
															"</a>" + 
														"</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";    
									}
									else {
										var row =   "<tr>" + 
														"<td class='dateTillcode' id='dateTillcode-" + idx + "'>" + tillcode + "</td>" + 
														"<td class='dateEventStartDate' id='dateEventStartDate-" + idx + "'>" + eventStartDate + "</td>" + 
														"<td class='dateEventEndDate' id='dateEventEndDate-" + idx + "'>" + eventEndDate + "</td>" +
                                                        tdHarga + 
														"<td class='al-center'>" + 
															"<a id=\"edit3-" + idx + "\"" +  
																"data-id=\"" + idx + "\"" + 
																"data-tillcode=\"" + tillcode + "\"" + 
																"data-date_start=\"" + eventStartDate + "\"" + 
																"data-date_end=\"" + eventEndDate + "\"" +
                                                                dataHarga + 
																"data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3'>" + 
																"<i class='fa fa-pencil'></i> edit" + 
															"</a>" + 
														"</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";
									}    
								}
								else {
									if (isSameDate == "1") {
										var row =   "<tr>" + 
														"<td class='dateEventStartDate'>" + eventStartDate + "</td>" + 
														"<td class='dateEventEndDate'>" + eventEndDate + "</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";    
									}
									else {
										var row =   "<tr>" + 
														"<td class='dateTillcode'>" + tillcode + "</td>" + 
														"<td class='dateEventStartDate'>" + eventStartDate + "</td>" + 
														"<td class='dateEventEndDate'>" + eventEndDate + "</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";
									}    
								}
								
								if ($("#datatableY tr#dummyRowY").length) {
									$("#datatableY tr#dummyRowY").remove();
								}
								
								$("#datatableY > tbody:last").append(row);
								$("#cntY").val(cntY+1);
								
								addDeleteRowEvent("datatableY");
								$("#eventStartDate").val("");
								$("#eventEndDate").val("");
								
							}
						},
						error: function(xhr, textStatus, errorThrown) {
							//alert("Error: " + errorThrown);
						},
						complete: function(xhr, textStatus) {
							//$("#imgLoading").addClass("hide");
						}
					});
				
				}
				 
            }
            else {
                alert("Interval tanggal tidak valid.");
            }
        }
        else {
            alert("Data tanggal sudah ada dalam interval.");
        }
    }
    else {
        alert("Data tanggal sudah ada.");
    }
});

function tillcodeExistInLocation(tillcode, sameLocation) {
    var ret = false;
    
    if (sameLocation != "1") {
		$("#datatableZ > tbody  > tr").each(function() {
    
			$("td", this).each(function (index) {
				if (index == 0) {
					if (tillcode == $(this).html().trim()) {
						ret = true;
						return false;
					}
				}
				return true;
			});
			
			return true;
		});    
    }
    
    return ret;
}

function copyLocations(isSameLocation) {
	var locationCode = "";
    var storeCode = "";
	var arrLocation = [];
	var loc = null;
	
	if (isSameLocation != "1") {
		$("#datatableZ > tbody  > tr").each(function() {
			$("td", this).each(function (index) {
				if (index == 1) {
					locationCode = $(this).html().trim();
				}
				if (index == 2) {
					storeCode = $(this).html().trim();
				}
			});
			loc = {"locationCode": locationCode, "storeCode": storeCode};
			arrLocation.push(loc);
		});
	}
	
	return arrLocation;
}

$("#btnCopyLocation").click(function() {
	var todo = $("#todo").val();
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    //var sameLocation = $("#sameLocation").prop("checked");
    var isSameLocation = $("#isSameLocation").val();
    var locationCode = "";
    var storeCode = "";
	var arrLocation = [];
	var loc = null;
	
	if (isSameLocation != "1") {
        if (tillcode == "") {
            alert("Silakan mengisi tillcode terlebih dahulu.");
        }
		else {
			if (!tillcodeExistInLocation(tillcode, isSameLocation)) {
				//alert('aye');
				
				arrLocation = copyLocations(isSameLocation);
				for	(i = 0; i < arrLocation.length; i++) {
					loc = arrLocation[i];
					locationCode = loc.locationCode;
					storeCode = loc.storeCode;
					
					// ini dia
					if (!locationExist(tillcode, isSameLocation, locationCode, storeCode)) {
        
						var cntZ = new Number($("#cntZ").val());
						
						if (todo == "edit") {
							var idx = cntZ;
							if (idx < 0) {
								idx = 0;
							}
							
							var row =   "<tr>" + 
											"<td class='locationTillcode' id='locationTillcode-" + idx + "'>" + tillcode + "</td>" + 
											"<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" + 
											"<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" + 
											"<td class='al-center'>" + 
												"<a id=\"edit2-" + idx + "\"" + 
													"data-id=\"" + idx + "\"" + 
													"data-tillcode=\"" + tillcode + "\"" + 
													"data-loc_desc=\"" + locationCode + "\"" + 
													"data-store_desc=\"" + storeCode + "\"" + 
													"data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" + 
													"<i class='fa fa-pencil'></i> edit" + 
												"</a>" + 
											"</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";
							
						}
						else {
							
							var row =   "<tr>" + 
											"<td class='locationTillcode'>" + tillcode + "</td>" + 
											"<td class='locationLocationCode'>" + locationCode + "</td>" + 
											"<td class='locationStoreCode'>" + storeCode + "</td>" + 
											"<td class='al-center'>" + 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
													"<i class='fa fa-trash-o'></i> del" + 
												"</a>" + 
											"</td>" + 
										"</tr>";
							
						}
						
						if ($("#datatableZ tr#dummyRowZ").length) {
							$("#datatableZ tr#dummyRowZ").remove();
						}
						
						$("#datatableZ > tbody:last").append(row);
						$("#cntZ").val(cntZ+1);
						
						addDeleteRowEvent("datatableZ");
						
					}
					else {
						//alert("Data lokasi sudah ada.");
					}
					
					// eo ini dia	
				} 
				
			}
			else {
				alert("Tillcode sudah ada.");
			}
		}
    }
	
});

$("#btnAddLocation").click(function() {
    var todo = $("#todo").val();
    var tillcode = $("#tillcode").val();
    tillcode = tillcode.substr(0, 8);
    //var sameLocation = $("#sameLocation").prop("checked");
    var isSameLocation = $("#isSameLocation").val();
    var locationCode = $("#locationCode option:selected").val();
    //var storeCode = $("#storeCode").val();

    var ada = 0;
    $("input.check_store:checkbox:checked").each(function() {
        ada = 1;
        return false;
    });

    if (isSameLocation == "1") {
        if (locationCode == "" || ada == 0) {
            alert("Silakan mengisi lokasi terlebih dahulu.");
            return;
        }
    }
    else {
        if (tillcode == "" || locationCode == "" || ada == 0) {
            alert("Silakan mengisi tillcode dan lokasi terlebih dahulu.");
            return;
        }
    }

    //cek
    $("input.check_store:checkbox:checked").each(function() {
        var storeCode = $(this).val();
        var storeCodeId = this.id;

        //if (isSameLocation == "1") tillcode = "";

        if (!locationExist(tillcode, isSameLocation, locationCode, storeCode)) {

            if (isSameLocation == "1") {

                var cntZ = new Number($("#cntZ").val());

                if (todo == "edit") {
                    var idx = cntZ;
                    if (idx < 0) {
                        idx = 0;
                    }

                    if (isSameLocation == "1") {
                        var row =   "<tr>" +
                                        "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" +
                                        "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" +
                                        "<td class='al-center'>" +
                                            "<a id=\"edit2-" + idx + "\"" +
                                                "data-id=\"" + idx + "\"" +
                                                "data-tillcode=\"" + tillcode + "\"" +
                                                "data-loc_desc=\"" + locationCode + "\"" +
                                                "data-store_desc=\"" + storeCode + "\"" +
                                                "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" +
                                                "<i class='fa fa-pencil'></i> edit" +
                                            "</a>" +
                                        "</td>" +
                                        "<td class='al-center'>" +
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                "<i class='fa fa-trash-o'></i> del" +
                                            "</a>" +
                                        "</td>" +
                                    "</tr>";
                    }
                    else {
                        var row =   "<tr>" +
                                        "<td class='locationTillcode' id='locationTillcode-" + idx + "'>" + tillcode + "</td>" +
                                        "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" +
                                        "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" +
                                        "<td class='al-center'>" +
                                            "<a id=\"edit2-" + idx + "\"" +
                                                "data-id=\"" + idx + "\"" +
                                                "data-tillcode=\"" + tillcode + "\"" +
                                                "data-loc_desc=\"" + locationCode + "\"" +
                                                "data-store_desc=\"" + storeCode + "\"" +
                                                "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" +
                                                "<i class='fa fa-pencil'></i> edit" +
                                            "</a>" +
                                        "</td>" +
                                        "<td class='al-center'>" +
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                "<i class='fa fa-trash-o'></i> del" +
                                            "</a>" +
                                        "</td>" +
                                    "</tr>";
                    }
                }
                else {
                    if (isSameLocation == "1") {
                        var row =   "<tr>" +
                                        "<td class='locationLocationCode'>" + locationCode + "</td>" +
                                        "<td class='locationStoreCode'>" + storeCode + "</td>" +
                                        "<td class='al-center'>" +
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                "<i class='fa fa-trash-o'></i> del" +
                                            "</a>" +
                                        "</td>" +
                                    "</tr>";
                    }
                    else {
                        var row =   "<tr>" +
                                        "<td class='locationTillcode'>" + tillcode + "</td>" +
                                        "<td class='locationLocationCode'>" + locationCode + "</td>" +
                                        "<td class='locationStoreCode'>" + storeCode + "</td>" +
                                        "<td class='al-center'>" +
                                            "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                "<i class='fa fa-trash-o'></i> del" +
                                            "</a>" +
                                        "</td>" +
                                    "</tr>";
                    }
                }

                if ($("#datatableZ tr#dummyRowZ").length) {
                    $("#datatableZ tr#dummyRowZ").remove();
                }

                $("#datatableZ > tbody:last").append(row);
                $("#cntZ").val(cntZ+1);

                addDeleteRowEvent("datatableZ", storeCodeId);
                //$("#locationCode").val("");
                //$(this.value).val("");//edit ugie

            }
            else {

                var dataString = "tillcode=" + tillcode;
                $.ajax({
                    type: "POST",
                    url: baseUrl+"acara/isArticleHasDiscount",
                    data: dataString,
                    beforeSend: function() {
                        //$("#imgLoading").removeClass("hide");
                    },
                    success: function(data) {
                        //alert("Input berhasil: " + data);
                        if (data == "hasnot") {
                            alert("Tillcode belum dilengkapi nilai diskonnya di data master.");
                        }
                        else {

                            var cntZ = new Number($("#cntZ").val());

                            if (todo == "edit") {
                                var idx = cntZ;
                                if (idx < 0) {
                                    idx = 0;
                                }

                                if (isSameLocation == "1") {
                                    var row =   "<tr>" +
                                                    "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" +
                                                    "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a id=\"edit2-" + idx + "\"" +
                                                            "data-id=\"" + idx + "\"" +
                                                            "data-tillcode=\"" + tillcode + "\"" +
                                                            "data-loc_desc=\"" + locationCode + "\"" +
                                                            "data-store_desc=\"" + storeCode + "\"" +
                                                            "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" +
                                                            "<i class='fa fa-pencil'></i> edit" +
                                                        "</a>" +
                                                    "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                            "<i class='fa fa-trash-o'></i> del" +
                                                        "</a>" +
                                                    "</td>" +
                                                "</tr>";
                                }
                                else {
                                    var row =   "<tr>" +
                                                    "<td class='locationTillcode' id='locationTillcode-" + idx + "'>" + tillcode + "</td>" +
                                                    "<td class='locationLocationCode' id='locationLocationCode-" + idx + "'>" + locationCode + "</td>" +
                                                    "<td class='locationStoreCode' id='locationStoreCode-" + idx + "'>" + storeCode + "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a id=\"edit2-" + idx + "\"" +
                                                            "data-id=\"" + idx + "\"" +
                                                            "data-tillcode=\"" + tillcode + "\"" +
                                                            "data-loc_desc=\"" + locationCode + "\"" +
                                                            "data-store_desc=\"" + storeCode + "\"" +
                                                            "data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2'>" +
                                                            "<i class='fa fa-pencil'></i> edit" +
                                                        "</a>" +
                                                    "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                            "<i class='fa fa-trash-o'></i> del" +
                                                        "</a>" +
                                                    "</td>" +
                                                "</tr>";
                                }
                            }
                            else {
                                if (isSameLocation == "1") {
                                    var row =   "<tr>" +
                                                    "<td class='locationLocationCode'>" + locationCode + "</td>" +
                                                    "<td class='locationStoreCode'>" + storeCode + "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                            "<i class='fa fa-trash-o'></i> del" +
                                                        "</a>" +
                                                    "</td>" +
                                                "</tr>";
                                }
                                else {
                                    var row =   "<tr>" +
                                                    "<td class='locationTillcode'>" + tillcode + "</td>" +
                                                    "<td class='locationLocationCode'>" + locationCode + "</td>" +
                                                    "<td class='locationStoreCode'>" + storeCode + "</td>" +
                                                    "<td class='al-center'>" +
                                                        "<a data-id='"+storeCodeId+"' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" +
                                                            "<i class='fa fa-trash-o'></i> del" +
                                                        "</a>" +
                                                    "</td>" +
                                                "</tr>";
                                }
                            }

                            if ($("#datatableZ tr#dummyRowZ").length) {
                                $("#datatableZ tr#dummyRowZ").remove();
                            }

                            $("#datatableZ > tbody:last").append(row);
                            $("#cntZ").val(cntZ+1);

                            addDeleteRowEvent("datatableZ", storeCodeId);
                            //$("#locationCode").val("");
                            //$(this.value).val("");edit by gie

                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        //alert("Error: " + errorThrown);
                    },
                    complete: function(xhr, textStatus) {
                        //$("#imgLoading").addClass("hide");
                    }
                });

            }

        }
        else {
            alert("Data lokasi sudah ada.");
            return false;
        }

    });

});

$("#btnPoolTillcode").click(function() {
    var todo = $("#todo").val();
    var tillcode = $("#tillcode").val(); 
    tillcode = tillcode.substr(0, 8);
    var notes = $("#notes").val();
    var supplierCode = $("#supplierCode").val();
    supplierCode = supplierCode.slice(-5).substr(0, 4);
    var categoryCode = $("#categoryCode option:selected").val();
    var specialPrice = $("#sp").val();
    var specialPriceF = $("#sp").val();
    var kota = $("#cbKota").val();
    var isSp = $("#cbSp").prop("checked");
    
    var kindOfResponsibility = $("#kindOfResponsibility option:selected").val();
    if (kindOfResponsibility == "0") {
        var ydsResponsibility = $("#ydsResponsibility").autoNumeric("get");
        var supplierResponsibility = $("#supplierResponsibility").autoNumeric("get");
    }
    else if (kindOfResponsibility == "-1") {
        var ydsResponsibility = "0";
        var supplierResponsibility = "0";
    }
    else {
        var ydsResponsibility = kindOfResponsibility.substr(0, 2);
        var supplierResponsibility = kindOfResponsibility.substr(2, 2);    
    }
    var isPkp = $("#isPkp option:selected").val();
    var margin = $("#margin").autoNumeric("get");
    
    isPkp = isPkp == "1" ? "PKP" : "NPKP";
   
    if (notes == "" || tillcode == "" || supplierCode == "" || categoryCode == "" || supplierResponsibility == "" || ydsResponsibility == "" || isPkp == "" || margin == "") {
        alert("Silakan lengkapi isian terlebih dahulu.");
        return;
    }
    
    if (kota==null){
        kota = "";
    }

    if (isSp && specialPrice == "") {
        alert("Silakan mengisi SP.");
        $("#sp").focus();
        return;
    }
    
    var check = new Number(ydsResponsibility) + new Number(supplierResponsibility);
    if (kindOfResponsibility != "-1" && check != 100) {
        alert("Jumlah pertanggungan tidak sama dengan 100.");
        return;
    }
    
    if (!tillcodeExist(tillcode, supplierCode, categoryCode, supplierResponsibility, ydsResponsibility, isPkp, margin, notes)) {
        
		var dataString = "tillcode=" + tillcode;
		$.ajax({
			type: "POST",
			url: baseUrl+"acara/isArticleHasDiscount",
			data: dataString,
			beforeSend: function() {
				//$("#imgLoading").removeClass("hide");
			},
			success: function(data) {
				//alert("Input berhasil: " + data);
				if (data == "hasnot") {
					alert("Tillcode belum dilengkapi nilai diskonnya di data master.");
				}
				else {
					
					var cntX = new Number($("#cntX").val());
        
					if (isSp) {
						var dataString = "tillcode=" + tillcode;
						$.ajax({
							type: "POST",
							url: baseUrl+"acara/isValidSpArticle",
							data: dataString,
							beforeSend: function() {
								//$("#imgLoading").removeClass("hide");
							},
							success: function(data) {
								if (data == "validsp") {
									
									if (todo == "edit") {
										var idx = cntX;
										if (idx < 0) {
											idx = 0;
										}
										
										var row =   "<tr>" +
														"<td class='eventNotes' id='eventNotes-" + idx + "'>" + notes + "</td>" + 
														"<td class='eventTillcode' id='eventTillcode-" + idx + "'>" + tillcode + "</td>" +
                                                        "<td class='eventSupplierCode' id='eventSupplierCode-" + idx + "'>" + supplierCode + "</td>" +
														"<td class='eventKota' id='eventKota-" + idx + "'>" + kota + "</td>" +
														"<td class='eventCategoryCode' id='eventCategoryCode-" + idx + "'>" + categoryCode + "</td>" +
														"<td class='eventSupplierResponsibility al-right' id='eventSupplierResponsibility-" + idx + "'>" + supplierResponsibility + "</td>" +
														"<td class='eventYdsResponsibility al-right' id='eventYdsResponsibility-" + idx + "'>" + ydsResponsibility + "</td>" +
														"<td class='eventIsPkp' id='eventIsPkp-" + idx + "'>" + isPkp + "</td>" + 
														"<td class='eventMargin al-right' id='eventMargin-" + idx + "'>" + margin + "</td>" +
														"<td class='eventSp al-right' id='eventSp-" + idx + "'>" + specialPriceF + "</td>" + 
														"<td class='al-center'>" + 
															"<a id=\"edit-" + idx + "\"" + 
																"data-id=\"" + idx + "\"" + 
																"data-notes=\"" + notes + "\"" + 
																"data-tillcode=\"" + tillcode + "\"" + 
                                                                "data-supp_code=\"" + supplierCode + "\"" + 
																"data-kota=\"" + kota + "\"" + 
																"data-category_desc=\"" + categoryCode + "\"" + 
																"data-supp_responsibility=\"" + supplierResponsibility + "\"" + 
																"data-yds_responsibility=\"" + ydsResponsibility + "\"" + 
																"data-is_pkp=\"" + isPkp + "\"" + 
																"data-tax=\"" + margin + "\"" + 
																"data-is_sp=\"1\"" + 
																"data-special_price=\"" + specialPriceF + "\"" + 
																"data-toggle='modal' data-target='#editForm' class='btn_update btn btn-xs editTrigger'>" + 
																"<i class='fa fa-pencil'></i> edit" + 
															"</a>" + 
														"</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";
									}
									else {
										var row =   "<tr>" +
														"<td class='eventNotes'>" + notes + "</td>" + 
														"<td class='eventTillcode'>" + tillcode + "</td>" +
                                                        "<td class='eventSupplierCode'>" + supplierCode + "</td>" +
														"<td class='eventKota'>" + kota + "</td>" +
														"<td class='eventCategoryCode'>" + categoryCode + "</td>" +
														"<td class='eventSupplierResponsibility al-right'>" + supplierResponsibility + "</td>" +
														"<td class='eventYdsResponsibility al-right'>" + ydsResponsibility + "</td>" +
														"<td class='eventIsPkp'>" + isPkp + "</td>" + 
														"<td class='eventMargin al-right'>" + margin + "</td>" +
														"<td class='eventSp al-right'>" + specialPriceF + "</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";    
									}
									
									if ($("#datatableX tr#dummyRowX").length) {
										$("#datatableX tr#dummyRowX").remove();
									}
									
									$("#datatableX > tbody:last").append(row);
									$("#cntX").val(cntX+1);
									
									addDeleteRowEvent("datatableX");
									//$("#notes").val("");
									$("#tillcode").val("");
									$("#cbSp").prop("checked", false);
									$("#sp").val("");
									$("#sp").prop("disabled", true);
									tmpSp = "";
								}
								else {
									alert("Article bukan special price.");
								}
							},
							error: function(xhr, textStatus, errorThrown) {
								alert("Error: " + errorThrown);
							},
							complete: function(xhr, textStatus) {
								//$("#imgLoading").addClass("hide");
							}
						});
					}
					else {
						
						var dataString = "tillcode=" + tillcode;
						$.ajax({
							type: "POST",
							url: baseUrl+"acara/isValidSpArticle",
							data: dataString,
							beforeSend: function() {
								//$("#imgLoading").removeClass("hide");
							},
							success: function(data) {
								if (data == "validsp" && !isSp) {
									alert("Article special price, silahkan mengisi SP.");
								}
								else {
									if (todo == "edit") {
										var idx = cntX;
										if (idx < 0) {
											idx = 0;
										}
										
										var row =   "<tr>" +
														"<td class='eventNotes' id='eventNotes-" + idx + "'>" + notes + "</td>" + 
														"<td class='eventTillcode' id='eventTillcode-" + idx + "'>" + tillcode + "</td>" +
                                                        "<td class='eventSupplierCode' id='eventSupplierCode-" + idx + "'>" + supplierCode + "</td>" +
														"<td class='eventKota' id='eventKota-" + idx + "'>" + kota + "</td>" +
														"<td class='eventCategoryCode' id='eventCategoryCode-" + idx + "'>" + categoryCode + "</td>" +
														"<td class='eventSupplierResponsibility al-right' id='eventSupplierResponsibility-" + idx + "'>" + supplierResponsibility + "</td>" +
														"<td class='eventYdsResponsibility al-right' id='eventYdsResponsibility-" + idx + "'>" + ydsResponsibility + "</td>" +
														"<td class='eventIsPkp' id='eventIsPkp-" + idx + "'>" + isPkp + "</td>" + 
														"<td class='eventMargin al-right' id='eventMargin-" + idx + "'>" + margin + "</td>" +
														"<td class='eventSp al-right' id='eventSp-" + idx + "'>" + specialPriceF + "</td>" + 
														"<td class='al-center'>" + 
															"<a id=\"edit-" + idx + "\"" + 
																"data-id=\"" + idx + "\"" + 
																"data-notes=\"" + notes + "\"" + 
																"data-tillcode=\"" + tillcode + "\"" + 
                                                                "data-supp_code=\"" + supplierCode + "\"" + 
																"data-kota=\"" + kota + "\"" + 
																"data-category_desc=\"" + categoryCode + "\"" + 
																"data-supp_responsibility=\"" + supplierResponsibility + "\"" + 
																"data-yds_responsibility=\"" + ydsResponsibility + "\"" + 
																"data-is_pkp=\"" + isPkp + "\"" + 
																"data-tax=\"" + margin + "\"" + 
																"data-is_sp=\"0\"" + 
																"data-special_price=\"" + specialPriceF + "\"" + 
																"data-toggle='modal' data-target='#editForm' class='btn_update btn btn-xs editTrigger'>" + 
																"<i class='fa fa-pencil'></i> edit" + 
															"</a>" + 
														"</td>" + 
														"<td class='al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";
									}
									else {
										var row =   "<tr>" +
														"<td class='eventNotes'>" + notes + "</td>" + 
														"<td class='eventTillcode'>" + tillcode + "</td>" +
                                                        "<td class='eventSupplierCode'>" + supplierCode + "</td>" +
														"<td class='eventKota'>" + kota + "</td>" +
														"<td class='eventCategoryCode'>" + categoryCode + "</td>" +
														"<td class='eventSupplierResponsibility al-right'>" + supplierResponsibility + "</td>" +
														"<td class='eventYdsResponsibility al-right'>" + ydsResponsibility + "</td>" +
														"<td class='eventIsPkp'>" + isPkp + "</td>" + 
														"<td class='eventMargin al-right'>" + margin + "</td>" +
														"<td class='eventSp al-right'>" + specialPriceF + "</td>" + 
														"<td class=' al-center'>" + 
															"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" + 
																"<i class='fa fa-trash-o'></i> del" + 
															"</a>" + 
														"</td>" + 
													"</tr>";
									}
									
									if ($("#datatableX tr#dummyRowX").length) {
										$("#datatableX tr#dummyRowX").remove();
									}
									
									$("#datatableX > tbody:last").append(row);
									$("#cntX").val(cntX+1);
									
									addDeleteRowEvent("datatableX");
									//$("#notes").val("");
									$("#tillcode").val("");
									$("#cbSp").prop("checked", false);
									$("#sp").val("");
									$("#sp").prop("disabled", true);
									tmpSp = "";   
								}
							},
							error: function(xhr, textStatus, errorThrown) {
								alert("Error: " + errorThrown);
							},
							complete: function(xhr, textStatus) {
								//$("#imgLoading").addClass("hide");
							}
						});
					   
					}
					
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				//alert("Error: " + errorThrown);
			},
			complete: function(xhr, textStatus) {
				//$("#imgLoading").addClass("hide");
			}
		});
		
    }
    else {
        alert("Data tillcode sudah ada.");
    }
});

$("#btn_uncheck").click(function() {
    $('#btn_check').show();
    $('#btn_uncheck').hide();

    $("input.check_store:checkbox").each(function() {
        $(this).prop('checked', false);
        
    })

})

$("#btn_check").click(function() {
    $('#btn_check').hide();
    $('#btn_uncheck').show();
    
    $("input.check_store:checkbox").each(function() {
        $(this).prop('checked', true);
        
    })
    
})
/*
$(document).on("click", ".editTrigger2", function () {
    var isSameLocation = $("#isSameLocation").val();
    var id = $(this).data('id');
    var tillcode = $(this).data('tillcode');
    var locDesc = $(this).data('loc_desc');
    var storeDesc = $(this).data('store_desc');
    
    if (isSameLocation == "1") {
        $("#tillcode2_e_holder").addClass("hide");
    }
    else {
        $("#tillcode2_e_holder").removeClass("hide");
    }
    
    $(".modal-body #idToUpdate2").val(id);
    $(".modal-body #tillcode2_e").val(tillcode);
    $(".modal-body #locationCode_e").val(locDesc);
    $(".modal-body #storeCode_e").val(storeDesc);
});*/

//$(document).on("click", ".editTrigger2", function () {

/*$("#btn_add_cabang").click(function() {
    var cabang = $("#storeCode").val();
    var init = cabang.substr(cabang.indexOf("(")+1, 3);
    
    ret =  "<div class='col-sm-2'>&nbsp;&nbsp;&nbsp;";    
    ret += "<label><input type='checkbox' name='check_store' id='check_store' checked value='"+cabang+"' > "+init+"</input></label>";    
    ret += "</div>";
    
    if (cabang == "") {
        alert("Silakan isi cabang dahulu")
    }
    else {
       $("#div_store").append(ret); 
    }
    
    
    
});*/

var FormValidation = function () {

        var handleValidation1 = function() {
                // for more info visit the official plugin documentation: 
                // http://docs.jquery.com/Plugins/Validation
                
                var todo = $("#todo").val();
                var form1 = $('#frmAcaraNext');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-inline', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",
                        rules: {
                                
                        },
                        messages: {
                            
                        },
    
                        invalidHandler: function (event, validator) { //display error alert on form submit              
                                $("#alertMessage").html("Tidak dapat melanjutkan. Silakan periksa isian anda.");
                                error1.removeClass("hide");
                                FormValidation.scrollTo(error1, -200);
                        },
    
                        highlight: function (element) { // hightlight error inputs
                                $(element).closest('.required').removeClass('has-success').addClass('has-error'); // set error class to the control group
                        },
    
                        unhighlight: function (element) { // revert the change done by hightlight
                                $(element).closest('.required').removeClass('has-success').removeClass('has-error'); // set error class to the control group
                        },
    
                        success: function (label) {
                                label.closest('.required').removeClass('has-error').addClass('has-success'); // set success class to the control group
                        },
        
                        submitHandler: function (form) {
                                //form.submit();
                                
                                if (emptyDates()) {
                                    alert("Tabel tanggal masih kosong.");    
                                }
                                else if (emptyLocations()) {
                                    alert("Tabel lokasi masih kosong.");    
                                }
                                else if (emptyTillcodes()) {
                                    alert("Tabel tillcode masih kosong.");    
                                }
                                else if (datesWithEmptyTillcode()) {
                                    alert("Ada tillcode yang kosong pada tabel tanggal."); 
                                }
                                else if (locationsWithEmptyTillcode()) {
                                    alert("Ada tillcode yang kosong pada tabel lokasi."); 
                                }
                                else {
                                    //alert("aye");
                                    
                                    //{"dateNotPulled": dateNotPulled, "locationNotPulled": locationNotPulled, "missDate": missDate, "missLocation": missLocation};
                                    var obj = getMissingTillcode();
                                    var dateNotPulled = obj.dateNotPulled;
                                    var locationNotPulled = obj.locationNotPulled;
                                    var missDate = obj.missDate;
                                    var missLocation = obj.missLocation;
                                    
                                    if (dateNotPulled != "") {
                                        alert("Tillcode (tanggal) berikut belum di-pool.\n" + dateNotPulled);
                                    }
                                    else if (locationNotPulled != "") {
                                        alert("Tillcode (lokasi) berikut belum di-pool.\n" + locationNotPulled);
                                    }
                                    else if (missDate != "") {
                                        alert("Tillcode berikut belum ada di tabel tanggal.\n" + missDate);
                                    }
                                    else if (missLocation != "") {
                                        alert("Tillcode berikut belum ada di tabel lokasi.\n" + missLocation);
                                    }
                                    else {
                                        submitEvent(todo);    
                                    }
                                }
                        }
                    
                });
            
        }

        return {
                //main function to initiate the module
                init: function () {
                        handleValidation1();
                },
        
                // wrapper function to scroll to an element
                scrollTo: function (el, offeset) {
                        pos = el ? el.offset().top : 0;
                        jQuery('html,body').animate({
                                scrollTop: pos + (offeset ? offeset : 0)
                        }, 'slow');
                }

        };

}();