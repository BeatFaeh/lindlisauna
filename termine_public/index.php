<?php


require("assets/functions/functions.php");
require 'assets/functions/CSRF_Protect.php';
$csrf = new CSRF_Protect();

error_reporting(E_ALL);


?>
<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Event Calendar">
    <meta name="author" content="EZCode.de">
	<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" >

    <title><?php echo $whbs; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">	
    
	<!-- DateTimePicker CSS -->
	<link href="assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">	
	
	<!-- DataTables CSS -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">	
    
	<!-- FullCalendar CSS -->
	<link href="assets/css/fullcalendar.css" rel="stylesheet" />
	<link href="assets/css/fullcalendar.print.css" rel="stylesheet" media="print" />	
	
	<!-- jQuery -->
    <script src="assets/js/jquery.js"></script>	
    
	<!-- SweetAlert CSS -->
	<script src="assets/js/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css">
	
    <!-- Custom Fonts -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
	<!-- ColorPicker CSS -->
	<link href="assets/css/bootstrap-colorpicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

	<body>


		<!-- Header -->

		<!-- /.intro-header -->

		<!-- Page Content -->

		<div id="eventcalendar"></div>
		<div class="content-section-a">
			
			<!--BEGIN PLUGIN -->
			<div class="container">
				<div class="row">
				   <div class="col-lg-12">
				<div class="panel panel-default dash">
					<h
						
					</h4>
					<div class="panel panel-default">
						<?php if ($administrator) {  ?>
						<div class="panel-heading">
						
							<!-- Button trigger New Event modal -->
							

							
							<!-- New Event Creation Modal -->
							
							<div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>

										</div>
										<div class="modal-body">
										
											 <!-- New Event Creation Form -->
											 
											<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
												<fieldset>
													<!-- CSRF PROTECTION -->
													<?php $csrf->echoInputField(); ?>
													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="title">Räume</label>
														<div class="col-md-6">
															<select name='title' class="form-control input-md" required>
																<?php 
																foreach($typesArray AS $element) {
																	echo "<option value='".$element."'>".$element."</option>";
																}
																?>
															</select>
														</div>
													</div>
													
													<!-- Text input-->
													
													<!--
													<div class="form-group">
														<label class="col-md-3 control-label" for="color">Color</label>
														<div class="col-md-4">
															<div id="cp1" class="input-group colorpicker-component">
																<input id="cp1" type="text" class="form-control" name="color" value="#5367ce" required/>
																<span class="input-group-addon"><i></i></span>
															</div>
														</div>
													</div>
													-->

													<div class="form-group">
<label class="col-md-3 control-label" for="start">Start-Datum</label>
<div class="input-group date form_date col-md-6" data-date="" data-date-format="dd.mm.yyyy hh:ii" data-link-field="start" data-link-format="yyyy-mm-dd hh:ii">
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
														</div>
														<input id="start" name="start" type="hidden" value="" required>

													</div>

													<div class="form-group">
<label class="col-md-3 control-label" for="end">End-Datum</label>
<div class="input-group date form_date col-md-6" data-date="" data-date-format="dd.mm.yyyy hh:ii" data-link-field="end" data-link-format="yyyy-mm-dd hh:ii">

<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
														</div>
														<input id="end" name="end" type="hidden" value="" required>

													</div>
	
													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="description">Mieter</label>
														<div class="col-md-9">
															<!-- <textarea class="form-control" rows="5" name="description" id="description"></textarea> -->
															<input id="description" name="description" type="text" class="form-control input-md" required>
															
														</div>
													</div>


													<!-- Button -->
													<div class="form-group">
														<label class="col-md-12 control-label" for="singlebutton"></label>
														<div class="col-md-4">
															<input type="submit" name="novoevento" class="btn btn-success" value="Reservation speichern" />
														</div>
													</div>

												</fieldset>
											</form>  
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">schliessen</button>
										</div>
									</div>
								</div>
							</div>
							
							<!-- Button trigger New Type modal -->
							
							<!-- 
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#type">
								<i class="fa fa-globe" aria-hidden="true"></i> New Type
							</button>
							-->
							
							 <!-- New Type Creation Form -->

							<div class="modal fade" id="type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
												New Type
											</h4>
										</div>
										<div class="modal-body">                               
											<!-- New Event Creation Form -->
											<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novotipo">
												<fieldset>
													<!-- CSRF PROTECTION -->
													<?php $csrf->echoInputField(); ?>
													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="title">Title</label>
														<div class="col-md-9">
															<input id="title" name="title" type="text" class="form-control input-md" required>

														</div>
													</div>
													
													<!-- Button -->
													<div class="form-group">
														<label class="col-md-12 control-label" for="singlebutton"></label>
														<div class="col-md-4">
															<input type="submit" name="novotipo" class="btn btn-success" value="New Type" />
														</div>
													</div>

												</fieldset>
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">schliessen</button>
										</div>
									</div>
								</div>
							</div>
	

							<!-- Modal -->
							<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
												Reservation editieren
											</h4>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsEdit(); ?>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">schliessen</button>
										</div>
									</div>
								</div>
							</div>


							<!-- Modal -->
							<div class="modal fade" id="delevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>

										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsDelete(); ?>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">schliessen</button>
										</div>
									</div>
								</div>
							</div>
							
                            <!--
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deltype">
								<i class="fa fa-close" aria-hidden="true"></i> Delete Types
							</button>
							-->

							<!-- Modal -->
							
							
							
							<div class="modal fade" id="deltype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel3"> <i class="fa fa-calendar" aria-hidden="true"></i>
												Delete Types
											</h4>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all types saved on database -->
											<?php echo listAllTypes(); ?>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">schliessen</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>					
						
						
						
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">

								<div class="col-lg-12">
									<div id="events"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

				if (!empty($_POST['novoevento']))
				{
					
					// Variables from form
					$title       = $_POST['title'];
					$image       = "_";
					$description = $_POST['description'];			
					$url         = "_";
					$start       = $_POST['start'];
					$end         = $_POST['end'];
					$color       = "_";


				if($title == "Hof")
				{
					$title = "Hof und Arkade";
				}
				
				switch ($title)
				{					
					case "Cafeteria":
						$color = "#F44336";
						break;
					case "Hof und Arkade":
						$color = "#9C27B0";
						break;
					case "Kartäusersaal":
						$color = "#3F51B5";
						break;
					case "Kirche":
						$color = "#26A69A";
						break;
					case "Kreuzgang":
						$color = "#FFEB3B";
						break;
					case "Laienrefektorium":
						$color = "#FFA726";
						break;
					case "Refektorium":
						$color = "#2196F3";
						break;
					case "Zscheckenbürlin":
						$color = "#795548";
						break;
					default:
						$color = "#B0BEC5";
						break;
				}					
					
					
					if ($end < $start)
					{
						echo "<script type='text/javascript'>swal('Ooops...!', 'Das Ende ".$end." muss grösser sein als der Start ".$start."!', 'error');</script>";	
						echo '<meta http-equiv="refresh" content="10; index.php">'; 
						return false;
					}

							
					if (empty($start) || empty($end))
					{
						echo "<script type='text/javascript'>swal('Ooops...!', 'Bitte alle Angaben erfassen!', 'error');</script>";	
						echo '<meta http-equiv="refresh" content="1; index.php">'; 
						return false;
					}
							
					if (!empty($start) || !empty($end) || !empty($image)) {
				 
						// If photos has been slected
						if (!empty($image["name"]) && isset($_FILES['image']))
						{
					 
							// Max width (px)
							$largura = 10000;
							// Max high (px)
							$altura = 10000;
							// Max size (pixels)
							$tamanho = 5000000000;
					 
							// Verifies if this is an image format
							if(!preg_match("/image\/(pjpeg|jpeg|png|gif|bmp)/", $image["type"]))
							{
							   $error[1] = "Sorry but this is not an image.";
							} 
					 
							// Select image size
							$dimensoes = getimagesize($image["tmp_name"]);
					 
							// check if the width size is allowed
							if($dimensoes[0] > $largura)
							{
								$error[2] = "Image width should be max ".$largura." pixels";
							}
					 
							// check if the height size is allowed
							if($dimensoes[1] > $altura)
							{
								$error[3] = "Image height should be max ".$altura." pixels";
							}
					 
							// check if the total size is allowed
							if($image["size"] > $tamanho)
							{
								$error[4] = "Image Should have max ".$tamanho." bytes";
							}
							
								// Get image extension
								preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $image["name"], $ext);
					 
								// Creates unique name (md5)
								$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
					 
								// Path for uploading the image
								$caminho_imagem = "assets/uploads/" . $nome_imagem;
					 
								// upload the image to the folder
								move_uploaded_file($image["tmp_name"], $caminho_imagem);

// Saves informationon the database
								$sql = mysqli_query($conection, "INSERT INTO events  VALUES (NULL, '".$title."', '_','".str_replace( "'", "´", $description)."','".$start."','".$end."','".$url."', '".$color."')");

								
								// If information is correctly saved		
								if (!$sql)
								{
									echo ("Can't insert into database (1): " . mysqli_error());
									return false;
								} 
								else
								{
										echo "<script type='text/javascript'>swal('Erfolgreich gespeichert!', 'Neue Reservation erstellt!', 'success');</script>";
										echo '<meta http-equiv="refresh" content="1; index.php">'; 
										die();
								}		
								return true;
							
							// Displays any error on database saving
							if (count($error) != 0) {
								foreach ($error as $erro) {
									echo $erro . "<br />";
								}
							}
						}
					} 
					
					if (empty($image["name"]))
					{
					
// Saves informationon the database
					$reservation_id = 0;
					$sql = mysqli_query($conection, "INSERT INTO events VALUES (NULL,'".$reservation_id."', '".$title."', '','".str_replace( "'", "´", $description)."','".$start."','".$end."','".$url."', '".$color."')");
							
							// If information is correctly saved		
							if (!$sql) {
							echo ("Can't insert into database (2): " . mysqli_error());
							return false;
							} else {
									echo "<script type='text/javascript'>swal('Erfolgreich gespeichert!', 'Neue Reservation erstellt!', 'success');</script>";
									echo '<meta http-equiv="refresh" content="1; index.php">'; 
									die();
							}		
							return true;
					}

				}


				// If user clicked on the new event button
				if (!empty($_POST['novotipo']))
				{
				 
					// Variables from form
					$title = $_POST['title'];
					
					// Saves informationon the database
					
					$sql = mysqli_query($conection, "INSERT INTO type VALUES ('', '".$title."')");
		 
					// If information is correctly saved			
					if (!$sql)
					{
						echo ("Can't insert into database (3): " . mysqli_error());
						return false;
					} 
					else
					{
							echo "<script type='text/javascript'>swal('Erfolgreich gespeichert!', 'Neue Reservation erstellt!', 'success');</script>";
							echo '<meta http-equiv="refresh" content="1; index.php">'; 
							die();
					}		
					return true;
				}

			?>
			<!-- Modal with events description -->
			<?php echo modalEvents(); ?>
				</div>

			</div>
			<!-- /.container -->

		
		<!-- Footer -->
		<!--
			<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">                   
						<p class="copyright text-muted small"><?php // echo $whbs; ?> - <?php // echo date("Y"); ?>
						<br>Vermietung von Räumen
						</p>
					</div>
				</div>
			</div>
		</footer>
		-->

		<!-- Bootstrap Core JavaScript -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- DataTables JavaScript -->
		<script src="assets/js/jquery.dataTables.js"></script>
		<script src="assets/js/dataTables.bootstrap.js"></script>
		<!-- Listings JavaScript delete options-->
		<script src="assets/js/listings.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="assets/js/metisMenu.min.js"></script>
		<!-- Moment JavaScript -->
		<script src="assets/js/moment.min.js"></script>
		<!-- FullCalendar JavaScript -->
		<script src="assets/js/fullcalendar.js"></script>
		<!-- FullCalendar Language JavaScript Selector -->
		<script src='assets/lang/de-ch.js'></script>
		<!-- DateTimePicker JavaScript -->
		<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<!-- Datetime picker initialization -->
		<script type="text/javascript">
			$('.form_date').datetimepicker({
				language:  'de',
				weekStart: 1,
				todayBtn:  0,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				forceParse: 0
			});
		</script>	
		<!-- ColorPicker JavaScript -->
		<script src="assets/js/bootstrap-colorpicker.js"></script>
		<!-- Plugin Script Initialization for DataTables -->
		<script>
			$(document).ready(function() {
				$('#dataTables-example').dataTable();
			});
		</script>
		<!-- ColorPicker Initialization -->
		<script>
			$(function() {
				$('#cp1').colorpicker();
			});
		
		</script>
		<!-- JS array created from database -->
		<?php echo listEvents($preis_id); ?>
		
		
	</body>

</html>