<?php
require("assets/functions/functions.php");

// CSRF Protection
require 'assets/functions/CSRF_Protect.php';
$csrf = new CSRF_Protect();

# Error Reporting Active
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="de">

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Event Calendar">
		<meta name="author" content="EZCode.pt">
		<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" >

		<title>Event Calendar</title>

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

		<script src="assets/js/isotope.pkgd.min.js"></script> 


		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
 <![endif]-->

	</head>

	<body>


		<!-- Header -->
		<div id="home"></div>

		<!-- /.intro-header -->

		<!-- Page Content -->
		<div id="eventcalendar"></div>
		<div class="content-section-a">

			<!--BEGIN PLUGIN -->
			<div class="container">

	

				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default dash">

							<div class="panel panel-default">
								<div class="panel-heading">
									<!-- Button trigger New Event modal -->
									<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#event">
										<i class="fa fa-calendar" aria-hidden="true"></i> Neuen Termin
									</button>
									<!-- New Event Creation Modal -->
									<div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Neuen Termin</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class="modal-body">
													<!-- New Event Creation Form -->
													<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
														<fieldset>
															<!-- CSRF PROTECTION -->
															<?php $csrf->echoInputField(); ?>
															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="title">Title</label>
																<div class="col-md-4">
																	<select name='title' class="form-control form-select input-md">

																		<?php 

$query = mysqli_query($conection, "select * from type ORDER BY title");

echo "<option value='No type Selected' required>Select Termin-Typ</option>";

while ($row = mysqli_fetch_assoc($query)) {
echo "

																	<option value='".$row['title']."'>".$row['title']."</option>

																	";

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
																		<input id="cp1" type="text" class="form-control form-control-color" name="color" value="#5367ce" required/>
																		<span class="input-group-addon"><i></i></span>
																	</div>
																</div>
															</div>
-->
															<div class="form-group col-md-6">
																<label class="col-md-3 control-label" for="start">Startdatum</label>
																<div class="input-group date form_date col-md-6" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="start" data-link-format="yyyy-mm-dd hh:ii">
																	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
																</div>
																<input id="start" name="start" type="hidden" value="" required>

															</div>

															<div class="form-group col-md-6">
																<label class="col-md-3 control-label" for="end" "no-wrap">Enddatum</label>
																<div class="input-group date form_date col-md-6" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="end" data-link-format="yyyy-mm-dd hh:ii">
																	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
																</div>
																<input id="end" name="end" type="hidden" value="" required>

															</div>
			   <!-- Image input-->
<!--															<div class="form-group">
																<label class="col-md-3 control-label" for="image">Upload Image</label>
																<div class="col-md-12">
																	<input type="file" name="image" id="image">
																</div>
															</div>
-->
															<!-- Text input-->
<!--															
															<div class="form-group">
																<label class="col-md-3 control-label" for="url">Link</label>
																<div class="col-md-12">
																	<input id="url" name="url" type="text" class="form-control input-md" required>

																</div>
															</div>

-->															<!-- Text input-->
<!--
															<div class="form-group">
																<label class="col-md-3 control-label" for="description">Beschreibung</label>
																<div class="col-md-12">
																	<textarea class="form-control" rows="5" name="description" id="description"></textarea>
																</div>
															</div>
-->
															<!-- Text input-->
<!--
															
															<div class="form-group">
																<label class="col-md-3 control-label" for="location">Ort</label>
																<div class="col-md-12">
																	<textarea class="form-control" rows="1" name="location" id="location"></textarea>
																</div>
															</div>

-->
															<!-- Button -->
															<div class="form-group">
																<label class="col-md-12 control-label" for="singlebutton"></label>
																<div class="col-md-4">
																	<input type="submit" name="novoevento" class="btn btn-success" value="Termin speichern" />
																</div>
															</div>

														</fieldset>
													</form>  
												</div>
												<div class="modal-footer">
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>							

									<!-- New Event Creation Modal for the selectable date -->
									<div class="modal fade" id="event1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Neuer Termin</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class="modal-body">
													<!-- New Event Creation Form -->
													<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
														<fieldset>
															<!-- CSRF PROTECTION -->
															<?php $csrf->echoInputField(); ?>
															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="title">Title</label>
																<div class="col-md-4">
																	<select name='title' class="form-control form-select input-md">

																		<?php 

																		$query = mysqli_query($conection, "select * from type ORDER BY id DESC");

																		echo "<option value='No type Selected' required>Select Type</option>";

																		while ($row = mysqli_fetch_assoc($query)) {

																			echo "

																	<option value='".$row['title']."'>".$row['title']."</option>

																	";

																		}

																		?>
																	</select>
																</div>
															</div>

															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="color">Farbe</label>
																<div class="col-md-4">
																	<div id="cp2" class="input-group colorpicker-component">
																		<input id="cp2" type="text" class="form-control" name="color" value="#5367ce" required/>
																		<span class="input-group-addon"><i></i></span>
																	</div>
																</div>
															</div>


															<input id="start" class="form-control" name="start" type="hidden" value="">
															<input id="end" class="form-control" name="end" type="hidden" value="">


															<!-- Image input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="image">Upload Image</label>
																<div class="col-md-12">
																	<input type="file" name="image" id="image">
																</div>
															</div>

															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="url">Link</label>
																<div class="col-md-12">
																	<input id="url" name="url" type="text" class="form-control input-md" required>

																</div>
															</div>

															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="description">Beschreibung</label>
																<div class="col-md-12">
																	<textarea class="form-control" rows="5" name="description" id="description"></textarea>
																</div>
															</div>

															<!-- Text input-->
															<div class="form-group">
																<label class="col-md-3 control-label" for="location">Ort</label>
																<div class="col-md-12">
																	<textarea class="form-control" rows="1" name="location" id="location"></textarea>
																</div>
															</div>


															<!-- Button -->
															<div class="form-group">
																<label class="col-md-12 control-label" for="singlebutton"></label>
																<div class="col-md-4">
																	<input type="submit" name="novoevento" class="btn btn-success" value="New Event" />
																</div>
															</div>

														</fieldset>
													</form>  
												</div>
												<div class="modal-footer">
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>														

									<!-- Button trigger New Type modal -->
									<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#type">
										<i class="fa fa-globe" aria-hidden="true"></i> Neuen Typ
									</button>
									<!-- New Type Creation Form -->
									<div class="modal fade" id="type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Neuen Typ</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
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
																<div class="col-md-12">
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
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>

									<!-- Button trigger Delete Event modal -->
									<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editevent">
										<i class="fa fa-edit" aria-hidden="true"></i> Edit Termin
									</button>

									<!-- Modal -->
									<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Edit Termin</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class="modal-body">
													<!-- Modal featuring all events saved on database -->
													<?php echo listAllEventsEdit(); ?>

												</div>
												<div class="modal-footer">
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>

									<!-- Button trigger Delete Event modal -->
									<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#delevent">
										<i class="fa fa-close" aria-hidden="true"></i> Delete Termin
									</button>

									<!-- Modal -->
									<div class="modal fade" id="delevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Delete Termin</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class="modal-body">
													<!-- Modal featuring all events saved on database -->
													<?php echo listAllEventsDelete(); ?>

												</div>
												<div class="modal-footer">
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>

									<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deltype">
										<i class="fa fa-close" aria-hidden="true"></i> Delete Types
									</button>

									<!-- Modal -->
									<div class="modal fade" id="deltype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class='modal-header'>
													<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Delete Typ</h5>
													<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
												</div>
												<div class="modal-body">
													<!-- Modal featuring all types saved on database -->
													<?php echo listAllTypes(); ?>

												</div>
												<div class="modal-footer">
													<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Schliessen</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="col-lg-12">
										<div id="events"></div>
									</div>				
								</div>
							</div>
						</div>
					</div>
					<?php

					// If user clicked on the new event button
					if (!empty($_POST['novoevento'])) {

						// Variables from form
						$title = htmlspecialchars($_POST['title'], ENT_QUOTES);
						// $image = $_FILES['image'];
						$image = '_';
						$description = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['description']))));
						$location = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['location']))));							
						// $url = antiSQLInjection($_POST['url']);
						$url = '_';
						$start = $_POST['start'];
						$end = $_POST['end'];
						// $color = $_POST['color'];
						// blau
						$color = '#5367ce';

						if (empty($start) || empty($end)) {
							echo "<script type='text/javascript'>swal('Ooops...!', 'You need to fill in the date options!', 'error');</script>";	
							echo '<meta http-equiv="refresh" content="1; ./">'; 
							return false;
						}

						if (!empty($start) || !empty($end) || !empty($image)) {

							// If photos has been slected
							if (!empty($image["name"]) && isset($_FILES['image'])) {

								// Max width (px)
								$largura = 10000;
								// Max high (px)
								$altura = 10000;
								// Max size (pixels)
								$tamanho = 5000000000;

								// Verifies if this is an image format
								if(!preg_match("/image\/(pjpeg|jpeg|png|gif|bmp)/", $image["type"])){
									$error[1] = "Sorry but this is not an image.";
								} 

								// Select image size
								$dimensoes = getimagesize($image["tmp_name"]);

								// check if the width size is allowed
								if($dimensoes[0] > $largura) {
									$error[2] = "Image width should be max ".$largura." pixels";
								}

								// check if the height size is allowed
								if($dimensoes[1] > $altura) {
									$error[3] = "Image height should be max ".$altura." pixels";
								}

								// check if the total size is allowed
								if($image["size"] > $tamanho) {
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
								$sql = mysqli_query($conection, "INSERT INTO events VALUES ('', '".$title."', '".$nome_imagem."','".str_replace( "'", "´", $description)."','".str_replace( "'", "´", $location)."','".$start."','".$end."','".$url."', '".$color."')");

								// If information is correctly saved		
								if (!$sql) {
									echo ("Can't insert into database: " . mysqli_error());
									return false;
								} else {
									echo "<script type='text/javascript'>swal('Good job!', 'New Event Created!', 'success');</script>";
									echo '<meta http-equiv="refresh" content="1; ./">'; 
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
						} if (empty($image["name"])) {
							// Saves informationon the database
							// $sql = mysqli_query($conection, "INSERT INTO events VALUES ('', '".$title."', '','".str_replace( "'", "´", $description)."','".str_replace( "'", "´", $location)."','".$start."','".$end."','".$url."', '".$color."')");

							$sql = mysqli_query($conection, "
    INSERT INTO events 
    (title, image, description, location, start, end, url, color)
    VALUES (
        '$title',
        '$nome_imagem',
        '".str_replace("'", "´", $description)."',
        '".str_replace("'", "´", $location)."',
        '$start',
        '$end',
        '$url',
        '$color'
    )
");						


							// If information is correctly saved		
							if (!$sql) {
								echo ("Can't insert into database: " . mysqli_error());
								return false;
							} else {
								echo "<script type='text/javascript'>swal('Good job!', 'New Event Created!', 'success');</script>";
								echo '<meta http-equiv="refresh" content="1; ./">'; 
								die();
							}		
							return true;
						}

					}


					// If user clicked on the new event button
					if (!empty($_POST['novotipo'])) {

						// Variables from form
						$title = htmlspecialchars($_POST['title'], ENT_QUOTES);

						// Saves informationon the database
						// $sql = mysqli_query($conection, "INSERT INTO type VALUES ('', '".$title."')");
						$sql = mysqli_query($conection, "INSERT INTO type (title) VALUES ('".$title."')");

						// If information is correctly saved			
						if (!$sql) {
							echo ("Can't insert into database: " . mysqli_error());
							return false;
						} else {
							echo "<script type='text/javascript'>swal('Good job!', 'New Type Created!', 'success');</script>";
							echo '<meta http-equiv="refresh" content="1; ./">'; 
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

		</div>

		<!-- Footer -->
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">                   
<p class="copyright text-muted"></p>
					</div>
				</div>
			</div>
		</footer>

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
		<!-- Option 1: Bootstrap Bundle with Popper -->
		<script src="assets/js/bootstrap.bundle.min.js" ></script>  
		<!-- Datetime picker initialization -->
		<script type="text/javascript">	
			"use strict";
			$('.form_date').datetimepicker({
				language:  'de',
				weekStart: 1,
				todayBtn:  0,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				forceParse: 0,
				format: 'yyyy-mm-dd hh:ii'
			});
		</script>	
		<!-- ColorPicker JavaScript -->
		<script src="assets/js/bootstrap-colorpicker.js"></script>
		<!-- Plugin Script Initialization for DataTables -->
		<script>
			"use strict";
			$(document).ready(function() {				
				$('.dataTables-example').dataTable();
			});
		</script>
		<!-- ColorPicker Initialization -->
		<script>
			"use strict";
			$(function() {
				"use strict";
				$('#cp1').colorpicker();
				$('#cp2').colorpicker();
			});

		</script>
		<!-- JS array created from database -->
		<?php echo listEvents(); ?>

		<script>
			"use strict";
			// init Isotope
			var $grid = $('.grid').isotope({
				itemSelector: '.element-item',
				layoutMode: 'fitRows'
			});
			// filter functions
			var filterFns = {
				// show if number is greater than 50
				numberGreaterThan50: function() {
					var number = $(this).find('.number').text();
					return parseInt( number, 10 ) > 50;
				},
				// show if name ends with -ium
				ium: function() {
					var name = $(this).find('.name').text();
					return name.match( /ium$/ );
				}
			};
			// bind filter on select change
			$('.filters-select').on( 'change', function() {
				// get filter value from option value
				var filterValue = this.value;
				// use filterFn if matches value
				filterValue = filterFns[ filterValue ] || filterValue;
				$grid.isotope({ filter: filterValue });
			});

		</script>

	</body>

</html>