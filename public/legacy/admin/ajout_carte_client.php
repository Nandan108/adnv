<?php
        session_start ();
        if (isset($_SESSION['account_login'])) {

            $account_login=$_SESSION['account_login'];
            require 'database.php';
            $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
            $stmt7 ->bindValue('account_login', $account_login);
            $stmt7 ->execute();
            $account7 = $stmt7 ->fetch(PDO::FETCH_OBJ);

            $nom = $account7 -> nom;
            $prenom = $account7 -> prenom;

            include 'header.php';




      if(isset($_POST['save']))
{
$characts   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';    
            $characts   .= '12345678909876543210'; 
            $code_aleatoire      = ''; 
            for($i=0;$i < 15;$i++)
            { 
                $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
            }
            $date = date("dmY");
            $nom_image=$code_aleatoire."_".$date.".png";

       if(!file_exists("upload"))
         {
          mkdir ("upload");
       }

     //////////////SLIDER//////////////////////

     if ($_FILES["file"]["error"] > 0)
          {
          $er = "ERROR Return Code: " .$_FILES["file"]["error"]. "<br />";
          //$url_photo="http://bientotenligne.fr/tom/admin/pages/upload/imagedeloffrenondisponible.jpg";
          $url_photo="upload/imagedeloffrenondisponible.jpg";
      
      }
     else
          {

          $img = $nom_image;
          move_uploaded_file($_FILES["file"]["tmp_name"],
          "upload/" .$nom_image);
          //$url_photo="http://bientotenligne.fr/tom/admin/pages/" . "upload/" . $nom_image;
           $url_photo="https://adnvoyage.com/admin/upload/" . $nom_image;
                  
          }
      


    $stmt = $conn ->prepare ("insert into `carte_client`(`titre`, `lat`, `longitude`, `photo`, `pays`, `ville`, `adresse`, `tel`, `categorie`, `description`, `quartier`, `etoile`, `etat`, `status`) VALUE (:titre, :lat, :longitude, :photo, :pays, :ville, :adresse, :tel, :categorie, :description, :quartier, :etoile, :etat, :status)");
    $stmt->bindValue('titre',addslashes(($_POST['titre'])));
    $stmt->bindValue('lat',addslashes(($_POST['lat'])));
    $stmt->bindValue('longitude',addslashes(($_POST['longitude'])));
    $stmt->bindValue('pays',addslashes(($_POST['pays'])));
    $stmt->bindValue('ville',addslashes(($_POST['ville'])));
    $stmt->bindValue('adresse',addslashes(($_POST['adresse'])));
    $stmt->bindValue('tel',addslashes(($_POST['tel'])));
    $stmt->bindValue('categorie',addslashes(($_POST['categorie'])));
    $stmt->bindValue('description',addslashes(($_POST['description'])));
    $stmt->bindValue('photo',$url_photo);
    $stmt->bindValue('quartier',addslashes(($_POST['quartier'])));
    $stmt->bindValue('etoile',addslashes(($_POST['etoile'])));
    $stmt->bindValue('etat',addslashes(($_POST['etat'])));
    $stmt->bindValue('status', '1');
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Ajout carte effectué, vous pouvez ajouté un autre');</script>";
    echo "<meta http-equiv='refresh' content='0;url=carte_client.php'/>";


}



?>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />


        <style type="text/css">
            #map{
                width: 100%;
                height: 600px;
                border-radius: 10px;
            }

input.span4, textarea.span4, .uneditable-input.span4 {
  width: 260px;
  margin-left: -84px;
}

        </style>

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Cartes</h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="cartes.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des cartes
                                    </a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>



        <section id="my-account-security-form" class="page container">
            <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                            Pour une meilleur visibilité de la liste dans la liste des cartes, assurer vous de bien remplir tous les champs ci-dessous.
                        </p>
                    </div>
                    <div class="row">
                        
                        <div id="acct-verify-row" class="span12">
                            <fieldset>
                                <legend>Veuillez choisir un point dans la carte</legend>

                        <input id="latitude" name="lat" class="span3" type="hidden" value="46.20736139170424" autocomplete="false" required>
                        <input id="longitude" name="longitude" class="span3" type="hidden" value="6.1559003591537484" autocomplete="false" required>

                                    <table style="width: 100%;">
                                        <tr>
                                            <td>Latitude</td>
                                            <td>Longitude</td>
                                        </tr>
                                        <tr>
                                            <td><input id="latitude2" class="span3" type="text" value="" autocomplete="false" disabled><br><br></td>
                                            <td><input id="longitude2" name="longitude" class="span3" type="text" value="" autocomplete="false" disabled><br><br></td>
                                        </tr>


                                    </table>
                        

                             <div id="map"></div>

                            </fieldset>
                        </div>



<div id="acct-password-row" class="span4">
                            <fieldset>
                                <legend>Information  | <span style="font-size: 12px;color:#00CCF4;">Ajout nouvelle cartes </span></legend><br>

                                <div class="control-group ">
                                    <label class="control-label">Photo</label>
                                    <div class="controls">
                                        <input type="file"  name="file"  style="margin-left: -84px;"/>
                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Titre</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="titre" class="span4" type="text" value="" autocomplete="false">

                                    </div>
                                </div>
                           
                                <div class="control-group ">
                                    <label class="control-label">Description</label>
                                    <div class="controls">
                                        <textarea id="current-pass-control" name="description" class="span4"></textarea>

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Catégorie</label>
                                    <div class="controls">

                                        <select class="span4" name="categorie"  style="margin-left: -84px;">
                                                <?php
                                                    $stmt0 = $conn->prepare('SELECT * FROM carte_categorie ORDER BY titre_categorie ASC');
                                                    $stmt0 ->execute();
                                                    while($account0 = $stmt0 ->fetch(PDO::FETCH_OBJ))
                                                    {
                                                ?>
                                                     
                                                        <option value="<?php echo stripslashes($account0 -> titre_categorie); ?>"><?php echo stripslashes($account0 -> titre_categorie); ?></option>
                                                <?php
                                                    }
                                                ?>

                                        </select>

                                       
                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Etoile</label>
                                    <div class="controls">
                                        <select class="span4" id="name" name="etoile" style="margin-left: -84px;">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Pays</label>
                                    <div class="controls">

                                    
                                        
                                       <?php
                                            if(isset($_GET['id_lieu']))
                                            {
                                                ?>

                                                <input id="current-pass-control" name="" class="span5" type="text" value="<?php echo $_GET['id_lieu']; ?>" autocomplete="false" disabled>
                                                <input id="current-pass-control" name="pays" class="span5" type="hidden" value="<?php echo $_GET['id_lieu']; ?>">

                                                <?php
                                            }
                                            else
                                            {
                                                ?>

                                                    <select class="span4" name="pays" id="mark"  style="margin-left: -84px;">
                                                <?php
                                                    $stmt0 = $conn->prepare('SELECT * FROM pays');
                                                    $stmt0 ->execute();
                                                    while($account0 = $stmt0 ->fetch(PDO::FETCH_OBJ))
                                                    {
                                                ?>
                                                     
                                                        <option value="<?php echo ($account0 -> nom_fr_fr); ?>"><?php echo ($account0 -> nom_fr_fr); ?></option>
                                                <?php
                                                    }
                                                ?>

                                                </select>
                                                <?php
                                                  
                                            }
                                        ?> 

                                        

                                    </div>
                                </div>
                               


                                <div class="control-group ">
                                    <label class="control-label">Etat</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="quartier" class="span4" type="text" value="" autocomplete="false">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Quartier</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="etat" class="span4" type="text" value="" autocomplete="false">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Adresse</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="adresse" class="span4" type="text" value="" autocomplete="false">

                                    </div>
                                </div>


                               

                                <div class="control-group ">
                                    <label class="control-label">Ville</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville" class="span4" type="text" value="" autocomplete="false" >

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Téléphone</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="tel" class="span4" type="text" value="" autocomplete="false" >

                                    </div>
                                </div>

                                
                            </fieldset>
                        </div>


                    </div>
                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
                        
                    </footer>
                </div>
            </form>
        </section>
    
            </div>
        </div>

        <footer class="application-footer">
            <div class="container">
                                <p>ADN voyage Sarl <br>
Rue Le-Corbusier, 8
1208 Genève - Suisse
info@adnvoyage.com</p>
                <div class="disclaimer">
                    <p>Ramseb & Urssy - All right reserved</p>
                    <p>Copyright © ADN voyage Sarl 2022</p>
                </div>
            </div>
        </footer>
        <script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-button.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-tablesorter.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-chosen.js" type="text/javascript" ></script>
        <script src="../js/jquery/virtual-tour.js" type="text/javascript" ></script>
        <script type="text/javascript">
        $(function(){
            $('.chosen').chosen();
            $("[rel=tooltip]").tooltip();

            $("#vguide-button").click(function(e){
                new VTour(null, $('.nav-page')).tourGuide();
                e.preventDefault();
            });
            $("#vtour-button").click(function(e){
                new VTour(null, $('.nav-page')).tour();
                e.preventDefault();
            });
        });
    </script>


<script src="../js/jquery-1.11.3.min.js"></script>
<script src="../js/jquery.chained.min.js"></script>
<script charset=utf-8>

(function($, window, document, undefined) {
    "use strict";

    $.fn.chained = function(parent_selector, options) {

        return this.each(function() {
            var child   = this;
            var backup = $(child).clone();
            $(parent_selector).each(function() {
                $(this).bind("change", function() {
                    updateChildren();
                });

                if (!$("option:selected", this).length) {
                    $("option", this).first().attr("selected", "selected");
                }

                updateChildren();
            });

            function updateChildren() {
                var trigger_change = true;
                var currently_selected_value = $("option:selected", child).val();

                $(child).html(backup.html());

                var selected = "";
                $(parent_selector).each(function() {
                    var selectedClass = $("option:selected", this).val();
                    if (selectedClass) {
                        if (selected.length > 0) {
                            if (window.Zepto) {
                                selected += "\\\\";
                            } else {
                                selected += "\\";
                            }
                        }
                        selected += selectedClass;
                    }
                });

                var first;
                if ($.isArray(parent_selector)) {
                    first = $(parent_selector[0]).first();
                } else {
                    first = $(parent_selector).first();
                }
                var selected_first = $("option:selected", first).val();

                $("option", child).each(function() {

                    if ($(this).hasClass(selected) && $(this).val() === currently_selected_value) {
                        $(this).prop("selected", true);
                        trigger_change = false;
                    } else if (!$(this).hasClass(selected) && !$(this).hasClass(selected_first) && $(this).val() !== "") {
                        $(this).remove();
                    }
                });

                if (1 === $("option", child).size() && $(child).val() === "") {
                    $(child).prop("disabled", true);
                } else {
                    $(child).prop("disabled", false);
                }
                if (trigger_change) {
                    $(child).trigger("change");
                }
            }
        });
    };

    $.fn.chainedTo = $.fn.chained;

    $.fn.chained.defaults = {};

})(window.jQuery || window.Zepto, window, document);


 $(document).ready(function(){
      $("#series").chained("#mark");
      $("#model").chained("#series");
      $("#engine").chained("#model");
      $("#engine2").chained("#engine");
      $("#employe").chained("#departement");

      $("#type").chained("#category");
      $("#marque").chained("#type");
 });

  </script>


    </body>
</html>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="./data/usstates.js"></script>

<script src="https://adnvoyage.com/admin/js/jquery-1.11.3.min.js" type="text/javascript" ></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



<script>



   let mapOptions = {
    center:[30.524413269923986, 29.021484106779102],
    zoom:6
}



var LeafIcon = L.Icon.extend({
    options: {
        iconSize:     [38, 38],
        iconAnchor:   [36, 36],
        shadowAnchor: [4, 10],
        popupAnchor:  [-16, -26]
    }
});


var iconposition = new LeafIcon({iconUrl: 'https://adnvoyage.com/app/client/img/iconemarker.png'});
L.icon = function (options) {
    return new L.Icon(options);
};




let map = new L.map('map' , mapOptions);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);



  if(!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!")
    } else {
        //setInterval(() => {
            navigator.geolocation.getCurrentPosition(getPosition)
       // }, 500);
    }

var marker2, circle;

    function getPosition(position){
        // console.log(position)
        var lat = position.coords.latitude
        var long = position.coords.longitude
        var accuracy = position.coords.accuracy

        if(marker2) {
            map.removeLayer(marker)
        }

        if(circle) {
            map.removeLayer(circle)
        }
/*
        marker2 = L.marker([lat, long], {icon: iconposition})
        .addTo(map)
        .bindPopup("<h5> Votre position actuelle </h5> <p><b><a href='add_note.php?xw=<?php echo MD5($id_client); ?>&lat="+ lat +"&long="+ long +"' id='add_note'>+ Ajouter une note</a></b></p>")
         .openPopup();
*/


        var latt = document.getElementById('latitude').value;
        var longg =  document.getElementById('longitude').value;

        marker2 = L.marker([lat, long, {icon: iconposition}])
        .addTo(map)
        .bindPopup("<h5> Votre position actuelle </h5>")
        .openPopup();
        circle = L.circle([lat, long], {radius: 300})
       // marker3 = L.marker([latt , longg])





        var featureGroup = L.featureGroup([marker2, circle]).addTo(map)

        map.fitBounds(featureGroup.getBounds())

        console.log("Your coordinate is: Lat: "+ lat +" Long: "+ long + " Accuracy: "+ accuracy)


    }




let marker = null;
map.on('click', (event)=> {

    if(marker !== null){
        map.removeLayer(marker);
    }

    marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);

    document.getElementById('latitude').value = event.latlng.lat;
    document.getElementById('longitude').value = event.latlng.lng;
    document.getElementById('latitude2').value = event.latlng.lat;
    document.getElementById('longitude2').value = event.latlng.lng;

    
})


var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});
osm.addTo(map);

/*===================================================
                      MARKER               
===================================================*/



/*===================================================
                     TILE LAYER               
===================================================*/

var CartoDB_DarkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
subdomains: 'abcd',
    maxZoom: 19
});
CartoDB_DarkMatter.addTo(map);

// Google Map Layer

googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
 });
 googleStreets.addTo(map);

 // Satelite Layer
googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
   maxZoom: 20,
   subdomains:['mt0','mt1','mt2','mt3']
 });
googleSat.addTo(map);

var Stamen_Watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
 attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
subdomains: 'abcd',
minZoom: 1,
maxZoom: 16,
ext: 'jpg'
});
Stamen_Watercolor.addTo(map);


var baseLayers = {
    "Satellite":googleSat,
    "Google Map":googleStreets,
    "Water Color":Stamen_Watercolor,
    "OpenStreetMap": osm,
};


L.control.layers(baseLayers).addTo(map);

L.Control.geocoder().addTo(map);




function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }

    info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
    info.update();
}

var geojson;
// ... our listeners
geojson = L.geoJson(statesData);

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

geojson = L.geoJson(statesData, {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);



</script>

<?php
}
else{
            header('Location:index.php');
           }
?>

