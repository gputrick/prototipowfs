<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<!-- JQuery -->
		<script src="js/jquery-2.1.1.js" type="text/javascript"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css">

		<!-- Custom styles for this template -->
		<link rel="stylesheet" href="css/dashboard.css" type="text/css">      

		<!-- Latest compiled and minified JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<!-- OpenLayers 3 -->
		<link rel="stylesheet" href="http://openlayers.org/en/v3.0.0/css/ol.css" type="text/css">
		<script src="http://openlayers.org/en/v3.0.0/build/ol-debug.js" type="text/javascript"></script>

		<!-- "Imports" -->
		<script src = "objects/Interface.js" type="text/javascript"></script>
		<script src = "objects/Styles.js" type="text/javascript"></script>
		<script src = "objects/Sources.js" type="text/javascript"></script>
		<script src = "objects/Layers.js" type="text/javascript"></script>
		<script src = "objects/Interactions.js" type="text/javascript"></script>
		<script src = "objects/Options.js" type="text/javascript"></script>

		<title>My Map</title>
		<link rel="shortcut icon" href="fonts/icon.png">
	</head>

	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="">My Map</a>
				</div>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Change Map To <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" id="changeMapTo">
							<li><a href="#" id="mapQuest">Map Quest</a></li>
							<li><a href="#" id="OSM">Open Street Maps</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<div class="container-fluid">
			<div class="sidebar">
				<div class="row">
					<div class="btn-group-vertical" data-toggle="buttons" id="toolsOptions">

						<label class="btn btn-default active" title="Info" id="selectOption">
							<input type="radio"> <span class="glyphicon glyphicon-info-sign"></span>
						</label>
						<label class="btn btn-default" title="Alternate Edit" id="alternateEditOption">
							<input type="checkbox"> <span class="glyphicon glyphicon-pencil"></span>
						</label>
						<label class="btn btn-default" title="Add Feature" id="drawOption" disabled>
							<input type="radio"> <span class="glyphicon glyphicon-plus"></span>
						</label>
						<label class="btn btn-default" title="Modify Feature" id="modifyOption" disabled>
							<input type="radio"> <span class="glyphicon glyphicon-edit"></span>
						</label>
						<label class="btn btn-default" title="Delete Feature" id="deleteOption" disabled>
							<input type="radio"> <span class="glyphicon glyphicon-remove"></span>
						</label>
					</div>
				</div>
				<br>
				<div class="row">
					<button type="button" class="btn btn-success" id="saveButton" title="Save" disabled>
						<span class="glyphicon glyphicon-floppy-save"></span>
					</button>
				</div>
			</div>
			<div class="col-xs-9 main">
				<?php
					session_start();
					if(@$_SESSION['Successful']){ 
						if($_SESSION['Successful'] == "yes"){
							echo '<div class="row">'.// print Success message
									'<div class="alert alert-success alert-dismissible" role="alert" id="IntertionMessage">'.
										'<span class="glyphicon glyphicon-ok"></span>'.
										'<a href="mostraLogServidor.php" class="alert-link">Success!</a>'.
										'<button type="button" class="close" data-dismiss="alert">'.
											'<span aria-hidden="true">&times;</span>'.
											'<span class="sr-only">Close</span>'.
										'</button>'.
									'</div>'.
								'</div>';

						}elseif ($_SESSION['Successful'] == "no"){    
							echo '<div class="row">'.// print Error message
								  '<div class="alert alert-danger alert-dismissible" role="alert" id="IntertionMessage">'.
											'<span class="glyphicon glyphicon-remove"></span>'.
											'<a href="mostraLogServidor.php" class="alert-link">Error!</a>'.
											'<button type="button" class="close" data-dismiss="alert">'.
												'<span aria-hidden="true">&times;</span>'.
												'<span class="sr-only">Close</span>'.
											'</button>'.
										'</div>'.
									'</div>';
						}
					}
					session_destroy();
				?>
				<div class="row">
					<div id="map" class="map"></div>
				</div>
			</div>
			<div class="col-xs-3 col-xs-offset-9 sidebar">
				<table class="table table-hover" id="layersTable">
					<thead>
						<tr>
							<th>Layer</th>
							<th>Visibility</th>
							<th class="editColumn">Edit</th>
						</tr>
					</thead>
					<tbody>
						<div class="btn-group" id="editLayerRadioOptions">
							<?php
								$url = 'http://localhost:8080/geoserver/rest/workspaces/sid1.gg/featuretypes.json';
								$ch = curl_init($url);
								// Optional settings for debugging
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string
								$passwordStr = "geoadmin:itaipu.123";
								curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

								if($json_str = curl_exec($ch)){ // Execute the curl request and return false if failed
									$json_obj = json_decode($json_str);
									$featureType = $json_obj->featureTypes->featureType;

									foreach ($featureType as $i) {
									echo  "<tr>".
											"<td>$i->name</td>".
												"<td><a  href='#' id='$i->name' class='glyphicon glyphicon-eye-close'></a></td>".
												"<td><input type='radio' layerName='$i->name' class='editColumn' name='editLayerRadioOption'></td>".
											"</tr>";
									}
								}else{
									echo "<p class='text-danger'><strong>The layers not could be loaded</strong></p>";                 
								}

								curl_close($ch); // free resources if curl handle will not be reused
							?>
						</div>
					</tbody>
				</table>
				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						Feature Infomation 
						<button href="#" class="pull-right" id="editButton" title="Edit Features" data-toggle="modal" data-target="#myModal" disabled>
							<span class="glyphicon glyphicon-pencil"></span>
						</button>
					</div>
					<table class="table table-hover" id="infoTable">
						<tbody id="infoTbody">
						</tbody>
					</table>
				</div>
			</div>
		</div>


		      <!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form class="form-inline" id="form" action="servidor.php" method="post">
						<div class="modal-body">
							<div class="panel panel-primary">
								<div class="panel-heading">Edit Feature Infomation</div>
								<table class="table table-hover" id="infoTable">
									<tbody id="editInfoTbody">
									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="textXML"/>
							<input type="button" class="btn btn-default" id="cancelButton" data-dismiss="modal" value="Cancel"/>
							<input type="button" class="btn btn-primary" id="submitButton" value="Save changes"/>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</body>
  <script src = "main.js" type="text/javascript"></script>
</html>