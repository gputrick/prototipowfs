<!DOCTYPE html>
<html lang="pt">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

      <!-- JQuery -->
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
      
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

      <!-- Latest compiled and minified JavaScript -->
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

      <!-- Custom styles for this template -->
      <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">

      <!-- OpenLayers 3 -->
      <link rel="stylesheet" href="http://openlayers.org/en/v3.0.0/css/ol.css" type="text/css">
      <script src="http://openlayers.org/en/v3.0.0/build/ol.js" type="text/javascript"></script>

      <!-- "Imports" -->
      <script src = "objects/Interface.js" type="text/javascript"></script>
      <script src = "objects/Styles.js" type="text/javascript"></script>
      <script src = "objects/Sources.js" type="text/javascript"></script>
      <script src = "objects/Layers.js" type="text/javascript"></script>
      <script src = "objects/Interactions.js" type="text/javascript"></script>
      <script src = "objects/Options.js" type="text/javascript"></script>

      <title>My Map</title>
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
                <li><a href="#" id="GoogleMaps">Google Maps</a></li>
                <li><a href="#" id="OSM">Open Street Maps</a></li>
              </ul>
          </ul>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-md-3 col-md-offset-9 sidebar">
          <table class="table" id="layersTable">
            <thead>
              <tr>
                <th>Layer</th>
                <th>on</th>
              </tr>
            </thead>
            <tbody>
              <tr>
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
                            "</tr>";
                    }
                  }

                  curl_close($ch); // free resources if curl handle will not be reused
                ?>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-offset-3 col-md-9 col-md-offset-0 main">
           <?php
              session_start();
              if(@$_SESSION['Successful']){ 
                if($_SESSION['Successful'] == "yes"){    
                 echo  '<div class="row">'.                      // print Success message
                      '<div class="alert alert-success alert-dismissible" role="alert">'.
                        '<span class="glyphicon glyphicon-ok"></span>'.
                        '<a href="mostraLogServidor.php" class="alert-link">Success!</a>'.
                        '<button type="button" class="close" data-dismiss="alert">'.
                          '<span aria-hidden="true">&times;</span>'.
                          '<span class="sr-only">Close</span>'.
                        '</button>'.
                      '</div>'.
                    '</div>';
                }elseif ($_SESSION['Successful'] == "no"){    
                  echo  '<div class="row">'.                    // print Error message
                          '<div class="alert alert-danger alert-dismissible" role="alert">'.
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
            <div class="pull-lefth">
              <div class="btn-group" data-toggle="buttons" id="radioOptions">
                  <label class="btn btn-default active" title="Select" id="selectOption">
                    <input type="radio" name="radioAction" checked> <span class="glyphicon glyphicon-comment"></span>
                  </label>
                  <label class="btn btn-primary" title="Draw" id="drawOption">
                    <input type="radio" name="radioAction"> <span class="glyphicon glyphicon-pencil"></span>
                  </label>
                  <label class="btn btn-warning" title="Modify" id="modifyOption">
                    <input type="radio" name="radioAction"> <span class="glyphicon glyphicon-move"></span>
                  </label>
                  <label class="btn btn-danger" title="Delete" id="deleteOption">
                    <input type="radio" name="radioAction"> <span class="glyphicon glyphicon-trash"></span>
                  </label>
              </div>
              <div class="pull-right">
                <button type="button" class="btn btn-success" id="saveModifyButton" title="Save" style="display : none">
                 <span class="glyphicon glyphicon-floppy-save"></span>
                </button>
                <button type="button" class="btn btn-success" id="mostrar">
                 Mostrar Layers
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div id="map" class="thumbnail" style="height : 575px">
              <div id="popup" class="ol-popup"></div>
            </div>
          </div>
        </div>
      </div>
    </div>


      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
            </div>
            <form class="form-inline" id="form" action="servidor.php" method="post">
              <div class="modal-body">
                <label class="control-label">Name</label>
                <input type="text" class="form-control" placeholder="Name" name="name">
                <input type="hidden" name="textXML"/>
                <label class="text-danger" id="nameControl" hidden="true">The space can't be empty</label>
              </div>
              <div class="modal-footer">
                <input type="button" class="btn btn-default" name="cancelButton" data-dismiss="modal" value="Cancel"/>
                <input type="button" class="btn btn-primary" name="submitButton" value="Save changes"/>
              </div>
            </form>
          </div>
        </div>
      </div>
    <script src = "main.js" type="text/javascript"></script>
  </body>
</html>
