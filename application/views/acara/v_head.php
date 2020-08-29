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

    <!-- read more -->
    <script src="<?php echo base_url(); ?>assets/js/readmore.min.js"></script>   

    <script type="text/javascript">
        var baseUrl = "<?php echo base_url(); ?>"; 
        var oTable;
        
        $(function() {
            var str = window.location.href;
            var res = str.split("/");
            if(res[5]=="1"){
                alert('data berhasil di upload');
            }
            
            <?php
                if (isset($is_printed)) {
            ?>

            table = $('#datatable').DataTable({ 
                "processing": true, 
                "serverSide": true, 
                <?php

                    if ($is_printed==1){
                        $order = "DESC";
                    } 
                    else if ($is_printed==0){
                        $order = "ASC";
                    } 

                ?>
               "order": [[ 0, "<?php //echo $order; ?>"]], 
                "ajax": {
                    <?php
                        // cek login
                        if ($this->session->userdata['event_logged_in']['role']==1){
                            if ($this->session->userdata['event_logged_in']['division_code']=='X')
                                $is_md = 0;// administrator
                            else 
                                $is_md = 1;// md
                            
                            $urls = base_url('acara/ajax_list')."/".$is_printed."/".$is_md;
                        }
                        else if ($this->session->userdata['event_logged_in']['role']==2){
                            // dipisah if soalnya bisi ada tambahan
                            $is_md = 0;
                            if ($dept != null && $div != null)
                                $urls = base_url('acara/ajax_list')."/".$is_printed."/".$is_md."/".$dept."/".$div;
                            else 
                                $urls = base_url('acara/ajax_list')."/".$is_printed."/".$is_md;
                        }
                    ?>
                    "url": "<?php echo $urls; ?>",
                    "type": "POST",
                },
                // "fnDrawCallback": function(oSettings, json) {
                //     alert("<?php //echo $this->db->last_query();?>");
                // },
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('td', nRow).closest('tr').css('background', aData[10]);
                    
                    var t = (aData[5]==null?0:aData[5]);
                    var maxLength = 50;
                    var ellipsestext = "<div class='more'> ...</div>";
                    var moretext = "more";
                    var lesstext = "less";

                    if (t!=0 || t!=null){
                        if(parseInt(t.length) > parseInt(maxLength)) {
                            var start = t.substr(0, maxLength);
                            var end = t.substr(maxLength, t.length - maxLength);
                            $("td:eq(4)", nRow).html(start+ellipsestext);
                            $("td:eq(4)", nRow).prop("title", aData[5]);
                        }    
                    }
                    
                    
                    
                    // alert(t.length);

                },
                "columnDefs": [
                    {
                        "targets": [0],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [1],
                        "width": "2%",
                        "sortable": false
                    },
                    {
                        "targets": [2],
                        "width": "18%"
                    },
                    {
                        "targets": [3],
                        "width": "5%"
                    },
                    {
                        "targets": [4],
                        "width": "10%"
                    },
                    {
                        "targets": [5],
                        "width": "20%"
                    },
                    {
                        "targets": [6],
                        "width": "25%"
                    }, 
                    {
                        "targets": [7],
                        "width": "6%"
                    },
                    {
                        "targets": [8],
                        "width": "6%"
                    },
                    {
                        "targets": [9],
                        "width": "10%",
                        "sortable" : false,
                        "searchable": false
                    },
                    
                ],

            });

            <?php

                }

            ?>
            
            $('#btn_refresh').click(function(){
                $('#refresh_icon').attr('class', 'fa fa-spinner fa-spin');
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url();?>"+"Acara_Controller/refresh_minified", 
                    success: function(msg) {
                        $("#datas").fadeOut(100, function(){
                            $("#datas").html(msg).fadeIn().delay(0);
                            $('#refresh_icon').attr('class', 'fa fa-refresh');
                        });

                    }
                }); 

            });

            $('#div_toward').hide();
            $('#btn_check').hide();

        });
        
    </script>
    

</head>
