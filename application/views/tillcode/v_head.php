<!DOCTYPE html>
<html lang="en">
  <head>
    <title>EVENTO</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">

    <!--external css-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lineicons/style.css">    
    
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style-responsive.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>assets/js/chart-master/Chart.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.sparkline.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/gritter-conf.js"></script>

    <!--datatables-->
    <link href="<?php echo base_url(); ?>assets/datatables/jquery.dataTables.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatables/jquery.dataTables.js"></script>

    <!--script for this page-->
    <script src="<?php echo base_url(); ?>assets/js/sparkline-chart.js"></script>    
    <script src="<?php echo base_url(); ?>assets/js/zabuto_calendar.js"></script>   
    
    <!-- jquery validate -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>

    <script>
       
        // jika form disubmit
        $.validator.setDefaults({
            submitHandler: function() {
                
                kode = $("#txt_kode").val();
                $.ajax({
                    type    : "POST",
                    url     : "<?php echo base_url(); ?>brand/cek_kode/",
                    data    : "p_kode="+kode,
                    cache   : false,
                    success : function(msg){
                        data = msg;
                       
                        if (data=='1'){
                             $("#txt_kode-error").html("kode sudah ada");
                        }else {
                            $("#txt_kode-error").html("");
                            document.frm.submit();
                        } 
                    }

                });//end ajax

            }
        });

        $(document).ready(function() {

            // validate form on keyup and submit
            $("#frm").validate({
                rules: {
                    txt_kode: "required",
                    txt_nama: "required"
                },
                messages: {
                    txt_kode: "kode harus di isi",
                    txt_nama: "nama harus di isi"
                }

            });

            

        });

       function cek_edit_form(){
            var nama = document.getElementById("txt_nama_show").value;
            if ((nama=="") || (nama=null)){
                alert("Nama harus di isi");
                document.getElementById("txt_nama_show").focus();
            }else document.frm_edit.submit();
        }

    </script>

    <script type="text/javascript">
        //show message flow
        function success_msg(msg){
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Notification',

                // (string | mandatory) the text inside the notification
                text: '<i class="fa fa-check"></i>'+msg,
               
                // (string | optional) the image to display on the left
                image: '',
               
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,//harus false jika ingin difade out
                
                // (int | optional) the time you want it to be alive for before fading out
                time: '3000',
               
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            return false;
        }

        $(function () {
            $("#datatable").dataTable({
                processing: true,
                serverSide: true,
                language: {
                    searchPlaceholder: "Tillcode"
                },
                ajax: {
                    "url": "<?php echo base_url(); ?>tillcode/datatable",
                    "type": "POST"
                },
                columns: [
                    {data: "tillcode"},
                    {data: "article_code"},
                    {data: "disc_label"},
                    {data: "brand_desc"},
                    {data: "disc1"},
                    {data: "disc2"},
                    {data: "disc3"},
                    {data: "is_sp"}
                ],
                createdRow: function (row, data, index) {
                    //set id to td
                    //row_id = $(row).eq(0).attr('id');
                    
                    var tillcode = $(row).eq(0).attr('id'); 
                    var disc1 = $('td', row).eq(4).text();
                    var disc2 = $('td', row).eq(5).text();
                    var disc3 = $('td', row).eq(6).text();
                    var issp = $('td', row).eq(7).text();

                    // alert($('td', row).eq(1).text());
                    
                    var html = "<td>";
                        html += "<a id='a-"+tillcode+"' data-id='"+tillcode+"' "; 
                        html +=     "data-disc1='"+disc1+"' ";
                        html +=     "data-disc2='"+disc2+"' ";
                        html +=     "data-disc3='"+disc3+"' ";
                        html +=     "data-issp='"+issp+"' ";
                        html +=     "data-toggle='modal' ";
                        html +=     "data-target='#editForm' "; 
                        html +=     "class='btn_update btn btn-xs editTrigger' title='Edit'>";
                        html +=     "<i class='fa fa-pencil'></i> ";
                        html +=  "</a></td>";
                        
                    // $("tr").find("td:last").after('<td>aaa</td>');
                    
                    $('td', row).eq(7).after(html)

                    if (issp == 1){
                        issp = 'Y';
                    }else if (issp == 0){
                        issp = 'N';
                    }else {
                        issp = 'undefined';
                    }

                    $('td', row).eq(7).text(issp);
                    
            }
            }).dataTableSearch(500);
        });

        (function ($) {
            $.fn.dataTableSearch = function (delay) {
                var dt = this;
                
                $("input[type=search]").on('keyup', function (event) {
                    getInput = function () {
                        return $(event.target);
                    };

                    dt.DataTable()
                            .columns(0)
                            .search(getInput().val().toUpperCase())
                            .draw();       
                });
                return this;
            };


            function delay() {
                var timer = 0;
                return function (ms, callback) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            }
        })(jQuery);

        //get and show modal data tobe edited
        $(document).on("click", ".show_modal", function () {
            var kode_show = $(this).data('id');
            //var rb_active = document.getElementsByName('rb_active_show');

            $.ajax({
                type    : "POST",
                url     : "<?php echo base_url(); ?>brand/show_modal/",
                data    : "p_kode="+kode_show,
                cache   : false,
                success : function(msg){
                    data = msg;
                    content = data.split("|");
                    $(".modal-body #txt_kode_show").val(content[0]);
                    $(".modal-body #txt_nama_show").val(content[1]);
                
                    if ($(".modal-body #rb_active_show_yes").val()==content[2]) {
                        $(".modal-body #rb_active_show_yes").prop("checked", true);
                        $(".modal-body #rb_active_show_no").prop("checked", false);
                        
                    } else {
                        $(".modal-body #rb_active_show_yes").prop("checked", false);
                        $(".modal-body #rb_active_show_no").prop("checked", true);
                    }


                }

            });//end ajax

        });
    </script>
    
    
    
    

  </head>