$(function() {
        $("#divisionCode").change(function() {
                var divisionCode = $("option:selected", this).val();
                if (divisionCode != "") {
                        var dataString = "divisionCode=" + divisionCode;
                        $.ajax({
                                type: "POST",
                                url: baseUrl+"acara/loadMdByDivision",
                                data: dataString,
                                beforeSend: function() {
                                    //$("#imgLoading").removeClass("hide");
                                },
                                success: function(data) {
                                    //alert("Input berhasil: " + data);
                                    $("#firstSignature").html(data);
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
                        $("#firstSignature").html('<option value="">Pilih MD..</option>');
                }
        });
        
        FormValidation.init();
});

var FormValidation = function () {

        var handleValidation1 = function() {
                // for more info visit the official plugin documentation: 
                // http://docs.jquery.com/Plugins/Validation

                var form1 = $('#frmAcara');
                var error1 = $('.alert-danger', form1);
                
                form1.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-inline', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",
                        rules: {
                                divisionCode: {
                                    required: true
                                },
                                nasionalJateng: {
                                    required: true
                                }
                        },
                        messages: {
                                divisionCode: {
                                    required: "Divisi harus diisi."
                                },
                                nasionalJateng: {
                                    required: "Pilih Nasional atau Jateng."
                                }
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
                                form.submit();
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