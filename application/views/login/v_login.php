<!DOCTYPE html>
<html lang="en">
  <head>
    <title>EVENTO</title>
	
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <!-- jquery validate -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
	
	<!-- global variable : warning, message etc -->
    <script src="<?php echo base_url(); ?>assets/js/global.js"></script>
	
    <script>
		$.validator.setDefaults({
			submitHandler: function() {
				$("#frm").submit();
			}
		});
		
		$(document).ready(function() {
			
			// validate signup form on keyup and submit
			$("#frm").validate({
				rules: {
					txt_username: "required",
					txt_pass: "required"
				},
				messages: {
					// set_warning() from global.js
					txt_username: set_warning("username"),
					txt_pass: set_warning("password")
				}

			});

			
		});
	</script>

  </head>

  <body>
	  <div id="login-page">
	  	<div class="container">
		    <form class="form-login" action="<?php echo base_url(); ?>login/do_login" id="frm" name="frm" method="post">
		        <h2 class="form-login-heading"><i class="fa fa-sign-in"></i> Login</h2>
		        <div class="login-wrap">
		            <input type="text" class="form-control" placeholder="&#xf007; username" style="font-family:FontAwesome" autofocus name="txt_username" id="txt_username">
		            <br>
		            
		            <input type="password" class="form-control" placeholder="&#xf13e; password" name="txt_pass" style="font-family:FontAwesome" id="txt_pass">
		            
		            <br>
		            <button class="btn btn-theme btn-block" style="background-color:#424a5d;" type="submit">LOGIN</button>
		            
		        </div>

		        <hr>

		  		<div class="login-social-link centered">
	            <p>untuk hasil yang optimal silakan download browser terbaru</p>
	                <a href="<?php echo base_url(); ?>browser/Firefox.exe" class="btn btn-warning"><i class="fa fa-firefox"></i> Firefox</a>
	                <a href="<?php echo base_url(); ?>browser/Chrome.exe" class="btn btn-success"><i class="fa fa-chrome"></i> Chrome</a>
	            </div>
				<br>
				
		    </form>	  	
	  	
	  	</div>
	  </div>

  </body>
</html>
