<!DOCTYPE html>
<html>
<head>
    <title>ITMY Prototype</title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@[VERSION]/dist/L.Control.Locate.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@[VERSION]/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
<!--    <script src="https://unpkg.com/georaster"></script> -->
	<script src="https://unpkg.com/georaster-layer-for-leaflet/dist/georaster-layer-for-leaflet.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/georaster@1.6.0/dist/georaster.browser.bundle.min.js"></script>
	<script src="https://unpkg.com/shpjs@latest/dist/shp.js"></script>
    <script src="https://cdn.rawgit.com/calvinmetcalf/leaflet.shapefile/gh-pages/leaflet.shpfile.js"></script>
	<script src="https://d3js.org/colorbrewer.v1.min.js"></script>
</head>
<body>

<div id="timestamp" style="text-align:center; position: absolute;top: 0px; left: 0; right: 0; height: 100px;">Center for Research and Development</div>
<div id="timestamp" style="text-align:center; position: absolute;top: 20px; left: 0; right: 0; height: 100px;">Meteorological Climatological and Geophysical Agency (BMKG) - Indonesia</div>
<div id="timestamp" style="text-align:center; position: absolute;top: 40px; left: 0; right: 0; height: 100px;">CLIMATE ZONE AND STANDARD WEATHER DATA</div>

<div style="text-align:center; position: absolute; top: 58px; left: 0; bottom: 0; right: 0;">
    <label for="fol">Parameter:&nbsp;&nbsp;</label>
        <!-- <select id="folder" class="inputfields" tabindex="1" name="folder" size="1" style="width:100px;""> -->
        <select id="fol" onchange="setfol()">
            <?php
				$path = './Grafik';
				$dirs = array();
				// directory handle
				$dir = dir($path);
				while (false !== ($entry = $dir->read())) {
					if ($entry != '.' && $entry != '..') {
					   if (is_dir($path . '/' .$entry)) {
							$dirs[] = $entry; 
					   }
					}
				}
				arsort($dirs); 
				reset($dirs); 
				foreach($dirs as $value):
				echo '<option value="'.$value.'">'.$value.'</option>'; 
				endforeach; ?>
        </select>
</div>
<div id="mapid" style="position: absolute; top: 80px; left: 0; bottom: 0; right: 0;"></div>
<div id="map" style="...">
   <div id="logoContainer">
     <!-- <img src="bmkg.png" width="50"> -->
            <img id="float_image_31389dc4a8d647ec9ca295c873557b9b" alt="float_image"
                 src="http://202.90.199.54/itmy/bmkg.png"
                 style="z-index: 999999">
            </img>
   </div>
</div>
<script>

    var map = L.map('mapid').setView([0., 118.], 5); 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attributions: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);
	// var parse_georaster = require("georaster");
	// var GeoRasterLayer = require("georaster-layer-for-leaflet");
	// var someUrl = "http://202.90.199.54/itmy/CZ-Georeferences.tif";
	// const response = fetch(someUrl);
	// const arrayBuffer = response.arrayBuffer();
	// const tiff = fromArrayBuffer(arrayBuffer);
	// // var reader = new FileReader();
        // // reader.readAsDataURL(file);
        // // reader.onloadend = function() {
          // // var arrayBuffer = reader.result;
          // parseGeoraster(tiff).then(georaster => {

            // console.log("georaster:", georaster);
            // /*
                // GeoRasterLayer is an extension of GridLayer,
                // which means can use GridLayer options like opacity.

                // Just make sure to include the georaster option!

                // http://leafletjs.com/reference-1.2.0.html#gridlayer
            // */
            // var layer = new GeoRasterLayer({
                // georaster: georaster,
                // opacity: 0.7,
                // resolution: 256
            // });
            // console.log("layer:", layer);
            // layer.addTo(map);

            // map.fitBounds(layer.getBounds());
            // // document.getElementById("overlay").style.display = "none";
          // });
        // // };
	// var url_to_geotiff_file = "./CZ-Georeferences.tif";
	// var url_to_geotiff_file = "./ClimateZonesGeoreferences.tif";
	// fetch(url_to_geotiff_file).then(response => response.arrayBuffer())
	  // .then(arrayBuffer => {
		// parseGeoraster(arrayBuffer).then(georaster => {
		  // // console.log("georaster:", georaster);

		  // /*
			  // GeoRasterLayer is an extension of GridLayer,
			  // which means can use GridLayer options like opacity.

			  // Just make sure to include the georaster option!

			  // Optionally set the pixelValuesToColorFn function option to customize
			  // how values for a pixel are translated to a color.

			  // https://leafletjs.com/reference.html#gridlayer
		  // */
		  // var layer = new GeoRasterLayer({
			  // georaster: georaster,
			  // opacity: 0.7,
			  // // pixelValuesToColorFn: function(values) {
				// // if (value < 50) {
					// // return "yellow";
				// // } else if (value > 50 && value < 130) {
					// // return "green";
				// // } else {
					// // return "transparent";
				// // }
			  // // },
			  // resolution: 256 // optional parameter for adjusting display resolution
		  // });
		  // layer.addTo(map);

		  // map.fitBounds(layer.getBounds());
	  // });
	// });

		var shpfile = new L.Shapefile('./zona_iklim.zip', {
			onEachFeature: function(feature, layer) {
				if (feature.properties) {
					layer.bindPopup(Object.keys(feature.properties).map(function(k) {
						if(k === '__color__'){
							return;
						}
						return k + ": " + feature.properties[k];
					}).join("<br />"), {
						maxHeight: 200
					});
				}
			},
			style: function(feature) {
				var num = Math.floor (Number (feature.properties.gridcode) + 0);
				// num = num / 8;
				if(num === 1){
					var r = Math.floor (1 * 255);
					var g = Math.floor (0 * 255);
					var b = Math.floor (0 * 255);	
				} else if(num === 2){
					var r = Math.floor (1 * 255);
					var g = Math.floor (1 * 128);
					var b = Math.floor (0 * 255);	
				} else if(num === 3){
					var r = Math.floor (1 * 255);
					var g = Math.floor (1 * 255);
					var b = Math.floor (0 * 255);	
				} else if(num === 4){
					var r = Math.floor (1 * 128);
					var g = Math.floor (1 * 255);
					var b = Math.floor (0 * 255);	
				} else if(num === 5){
					var r = Math.floor (0 * 128);
					var g = Math.floor (1 * 255);
					var b = Math.floor (0 * 255);	
				} else if(num === 6){
					var r = Math.floor (0 * 128);
					var g = Math.floor (1 * 255);
					var b = Math.floor (1 * 128);	
				} else if(num === 7){
					var r = Math.floor (0 * 128);
					var g = Math.floor (1 * 255);
					var b = Math.floor (1 * 255);	
				} else if(num === 8){
					var r = Math.floor (0 * 128);
					var g = Math.floor (1 * 128);
					var b = Math.floor (1 * 255);	
				}

				return { color: 'rgba(' + r.toString () + ',' + g.toString () + ',' + b.toString () + ',' + 0.6 + ')',
					opacity: .8,
					fillOpacity: 0.7, };
			}
		});
		shpfile.addTo(map);
		
	// var shpfile = new L.Shapefile("./Final_zona_Iklim.zip");

	// shpfile.addTo(map);

	// var overlayMaps = {
	// TestShapefile: shpfile
	// };

	// L.control.layers(null, overlayMaps).addTo(map);
	  
	function setfol(){
	  var folder = document.getElementById('fol').value;
	const markerIcon = L.icon({
	  iconUrl: 'http://202.90.199.54/itmy/bmkg.png',
	  iconSize: [31, 46], // size of the icon
	  iconAnchor: [15.5, 42], // point of the icon which will correspond to marker's location
	  popupAnchor: [0, -45] // point from which the popup should open relative to the iconAnchor
	});
	var m0 = L.marker([5.23, 96.95],{title:"Kab. Aceh Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96009.csv"+folder+".png /> ") 
	var m1 = L.marker([4.05, 96.25],{title:"Kab. Nagan Raya"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96015.csv"+folder+".png /> ")
	var m2 = L.marker([3.79, 98.71],{title:"Kota Medan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96033.csv"+folder+".png /> ")
	var m3 = L.marker([3.54, 98.64],{title:"Kota Medan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96041.csv"+folder+".png /> ")
	var m4 = L.marker([1.55, 98.88],{title:"Kab. Tapanuli Tengah"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96073.csv"+folder+".png /> ")
	var m5 = L.marker([1.16, 97.7],{title:"Kota Gunungsitoli"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96075.csv"+folder+".png /> ")
	var m6 = L.marker([0.46, 101.45],{title:"Kota Pekanbaru"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96109.csv"+folder+".png /> ")
	var m7 = L.marker([-1.0, 100.37],{title:"Kota Padang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96161.csv"+folder+".png /> ")
	var m8 = L.marker([-0.79, 100.29],{title:"Kota Padang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96163.csv"+folder+".png /> ")
	var m9 = L.marker([-0.33, 102.32],{title:"Kab. Indragiri Hulu"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96171.csv"+folder+".png /> ")
	var m10 = L.marker([-1.6, 103.48],{title:"Kab. Muaro Jambi"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96191.csv"+folder+".png /> ")
	var m11 = L.marker([-1.63, 103.64],{title:"Kota Jambi"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96195.csv"+folder+".png /> ")
	var m12 = L.marker([-2.89, 104.7],{title:"Kota Palembang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96221.csv"+folder+".png /> ")
	var m13 = L.marker([4.13, 117.67],{title:"Kab. Nunukan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96503.csv"+folder+".png /> ")
	var m14 = L.marker([3.33, 117.57],{title:"Kota Tarakan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96509.csv"+folder+".png /> ")
	var m15 = L.marker([2.5, 117.22],{title:"Kab. Bulungan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96525.csv"+folder+".png /> ")
	var m16 = L.marker([2.15, 117.43],{title:"Kab. Berau"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96529.csv"+folder+".png /> ")
	var m17 = L.marker([1.74, 109.3],{title:"Kab. Sambas"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96535.csv"+folder+".png /> ")
	var m18 = L.marker([0.06, 111.47],{title:"Kab. Sintang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96559.csv"+folder+".png /> ")
	var m19 = L.marker([0.84, 112.93],{title:"Kab. Kapuas Hulu"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96565.csv"+folder+".png /> ")
	var m20 = L.marker([-0.14, 109.45],{title:"Kota Pontianak"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96581.csv"+folder+".png /> ")
	var m21 = L.marker([-0.03, 109.34],{title:"Kota Pontianak"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96585.csv"+folder+".png /> ")
	var m22 = L.marker([-0.56, 114.53],{title:"Kab. Barito Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96595.csv"+folder+".png /> ")
	var m23 = L.marker([-1.8, 109.97],{title:"Kab. Ketapang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96615.csv"+folder+".png /> ")
	var m24 = L.marker([-1.26, 116.9],{title:"Kota Balikpapan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96633.csv"+folder+".png /> ")
	var m25 = L.marker([-2.73, 111.66],{title:"Kab. Kotawaringin Barat"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96645.csv"+folder+".png /> ")
	var m26 = L.marker([-2.55, 112.93],{title:"Kab. Kotawaringin Timur"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96651.csv"+folder+".png /> ")
	var m27 = L.marker([-2.22, 113.95],{title:"Kota Palangkaraya"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96655.csv"+folder+".png /> ")
	var m28 = L.marker([-6.11, 106.11],{title:"Kota Serang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96737.csv"+folder+".png /> ")
	var m29 = L.marker([-6.29, 106.56],{title:"Kab. Tangerang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96739.csv"+folder+".png /> ")
	var m30 = L.marker([1.44, 125.18],{title:"Kota Bitung"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97016.csv"+folder+".png /> ")
	var m31 = L.marker([1.12, 120.79],{title:"Kab. Toli Toli"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97028.csv"+folder+".png /> ")
	var m32 = L.marker([-1.04, 122.77],{title:"Kab. Banggai"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97086.csv"+folder+".png /> ")
	var m33 = L.marker([-2.55, 120.32],{title:"Kab. Luwu Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97126.csv"+folder+".png /> ")
	var m34 = L.marker([-0.64, 127.5],{title:"Kab. Halmahera Selatan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97460.csv"+folder+".png /> ")
	var m35 = L.marker([-0.89, 134.05],{title:"Kab. Manokwari"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97530.csv"+folder+".png /> ")
	var m36 = L.marker([-1.19, 136.1],{title:"Kab. Biak Numfor"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97560.csv"+folder+".png /> ")
	var m37 = L.marker([-2.05, 126.0],{title:"Kab. Kepulauan Sula"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97600.csv"+folder+".png /> ")
	var m38 = L.marker([-3.35, 135.52],{title:"Kab. Nabire"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97682.csv"+folder+".png /> ")
	var m39 = L.marker([-3.67, 133.75],{title:"Kab. Kaimana"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97760.csv"+folder+".png /> ")
	var m40 = L.marker([-4.53, 136.89],{title:"Kab. Mimika"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97796.csv"+folder+".png /> ")
	var m41 = L.marker([5.88, 95.34],{title:"Kota Sabang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96001.csv"+folder+".png /> ")
	var m42 = L.marker([5.52, 95.42],{title:"Kab. Aceh Besar"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96011.csv"+folder+".png /> ")
	var m43 = L.marker([3.65, 98.88],{title:"Kab. Deli Serdang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96035.csv"+folder+".png /> ")
	var m44 = L.marker([1.55, 99.45],{title:"Kab. Padang Lawas Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96071.csv"+folder+".png /> ")
	var m45 = L.marker([1.12, 104.12],{title:"Kota Batam"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96087.csv"+folder+".png /> ")
	var m46 = L.marker([1.03, 103.38],{title:"Kab. Karimun"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96089.csv"+folder+".png /> ")
	var m47 = L.marker([0.92, 104.53],{title:"Kota Tanjung Pinang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96091.csv"+folder+".png /> ")
	var m48 = L.marker([3.2, 106.25],{title:"Kab. Kepulauan Anambas"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96145.csv"+folder+".png /> ")
	var m49 = L.marker([-0.48, 104.58],{title:"Kab. Lingga"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96179.csv"+folder+".png /> ")
	var m50 = L.marker([-2.17, 106.13],{title:"Kota Pangkal Pinang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96237.csv"+folder+".png /> ")
	var m51 = L.marker([-2.75, 107.75],{title:"Kab. Belitung"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96249.csv"+folder+".png /> ")
	var m52 = L.marker([-3.86, 102.34],{title:"Kota Bengkulu"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96253.csv"+folder+".png /> ")
	var m53 = L.marker([-7.72, 109.01],{title:"Kab. Cilacap"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96805.csv"+folder+".png /> ")
	var m54 = L.marker([0.83, 127.38],{title:"Kota Ternate"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97430.csv"+folder+".png /> ")
	var m55 = L.marker([-2.57, 140.48],{title:"Kab. Jayapura"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97690.csv"+folder+".png /> ")
	var m56 = L.marker([-3.71, 128.1],{title:"Kota Ambon"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97724.csv"+folder+".png /> ")
	var m57 = L.marker([-3.88, 130.88],{title:"Kab. Seram Bagian Timur"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97748.csv"+folder+".png /> ")
	var m58 = L.marker([-4.52, 129.9],{title:"Kab. Maluku Tengah"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97790.csv"+folder+".png /> ")
	var m59 = L.marker([-5.66, 132.74],{title:"Kab. Maluku Tenggara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97810.csv"+folder+".png /> ")
	var m60 = L.marker([-8.52, 140.42],{title:"Kab. Merauke"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97980.csv"+folder+".png /> ")
	var m61 = L.marker([-2.08, 101.45],{title:"Kab. Kerinci"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96207.csv"+folder+".png /> ")
	var m62 = L.marker([-6.7, 106.85],{title:"Kab. Bogor"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96751.csv"+folder+".png /> ")
	var m63 = L.marker([-3.05, 119.82],{title:"Kab. Tana Toraja"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97124.csv"+folder+".png /> ")
	var m64 = L.marker([-4.07, 138.95],{title:"Kab. Jayawijaya"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97686.csv"+folder+".png /> ")
	var m65 = L.marker([-6.16, 106.84],{title:"Kota Adm. Jakarta Pusat"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96745.csv"+folder+".png /> ")
	var m66 = L.marker([-6.5, 106.75],{title:"Kota Bogor"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96753.csv"+folder+".png /> ")
	var m67 = L.marker([-6.75, 108.27],{title:"Kab. Majalengka"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96791.csv"+folder+".png /> ")
	var m68 = L.marker([-8.21, 114.36],{title:"Kab. Banyuwangi"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96987.csv"+folder+".png /> ")
	var m69 = L.marker([3.69, 125.53],{title:"Kab. Kepulauan Sangihe"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97008.csv"+folder+".png /> ")
	var m70 = L.marker([1.55, 124.92],{title:"Kab. Minahasa Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97012.csv"+folder+".png /> ")
	var m71 = L.marker([1.55, 124.92],{title:"Kota Manado"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97014.csv"+folder+".png /> ")
	var m72 = L.marker([-3.55, 118.98],{title:"Kab. Majene"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97120.csv"+folder+".png /> ")
	var m73 = L.marker([-3.97, 122.59],{title:"Kota Kendari"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97144.csv"+folder+".png /> ")
	var m74 = L.marker([-5.07, 119.55],{title:"Kab. Maros"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97180.csv"+folder+".png /> ")
	var m75 = L.marker([-5.47, 122.62],{title:"Kota Bau Bau"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97192.csv"+folder+".png /> ")
	var m76 = L.marker([1.49, 127.5],{title:"Kab. Halmahera Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97406.csv"+folder+".png /> ")
	var m77 = L.marker([-5.47, 105.32],{title:"Kota Bandar Lampung"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96293.csv"+folder+".png /> ")
	var m78 = L.marker([-5.16, 105.11],{title:"Kab. Lampung Selatan"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96295.csv"+folder+".png /> ")
	var m79 = L.marker([-3.44, 114.75],{title:"Kota Banjarmasin"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96685.csv"+folder+".png /> ")
	var m80 = L.marker([-6.11, 106.88],{title:"Kota Adm. Jakarta Utara"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96741.csv"+folder+".png /> ")
	var m81 = L.marker([-6.12, 106.65],{title:"Kota Tangerang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96749.csv"+folder+".png /> ")
	var m82 = L.marker([-6.87, 109.12],{title:"Kab. Tegal"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96797.csv"+folder+".png /> ")
	var m83 = L.marker([-6.95, 110.42],{title:"Kota Semarang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96837.csv"+folder+".png /> ")
	var m84 = L.marker([-6.98, 110.38],{title:"Kota Semarang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96839.csv"+folder+".png /> ")
	var m85 = L.marker([-5.85, 112.63],{title:"Kab. Gresik"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96925.csv"+folder+".png /> ")
	var m86 = L.marker([-7.38, 112.78],{title:"Kab. Sidoarjo"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96935.csv"+folder+".png /> ")
	var m87 = L.marker([0.64, 122.85],{title:"Kab. Gorontalo"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97048.csv"+folder+".png /> ")
	var m88 = L.marker([-0.92, 119.91],{title:"Kota Palu"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97072.csv"+folder+".png /> ")
	var m89 = L.marker([-5.11, 119.42],{title:"Kota Makassar"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97182.csv"+folder+".png /> ")
	var m90 = L.marker([-8.75, 115.17],{title:"Kab. Badung"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97230.csv"+folder+".png /> ")
	var m91 = L.marker([-8.75, 116.25],{title:"Kota Mataram"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97240.csv"+folder+".png /> ")
	var m92 = L.marker([-8.49, 119.89],{title:"Kab. Manggarai Barat"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97282.csv"+folder+".png /> ")
	var m93 = L.marker([-0.89, 131.29],{title:"Kota Sorong"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97502.csv"+folder+".png /> ")
	var m94 = L.marker([-8.49, 117.41],{title:"Kab. Sumbawa"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97260.csv"+folder+".png /> ")
	var m95 = L.marker([-8.54, 118.69],{title:"Kota Bima"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97270.csv"+folder+".png /> ")
	var m96 = L.marker([-8.64, 122.24],{title:"Kab. Sikka"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97300.csv"+folder+".png /> ")
	var m97 = L.marker([-8.28, 123.0],{title:"Kab. Flores Timur"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97310.csv"+folder+".png /> ")
	var m98 = L.marker([-8.13, 124.59],{title:"Kab. Alor"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97320.csv"+folder+".png /> ")
	var m99 = L.marker([-9.67, 120.3],{title:"Kab. Sumba Timur"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97340.csv"+folder+".png /> ")
	var m100 = L.marker([-10.17, 123.67],{title:"Kota Kupang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97372.csv"+folder+".png /> ")
	var m101 = L.marker([-10.77, 123.07],{title:"Kab. Rote Ndao"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97378.csv"+folder+".png /> ")
	var m102 = L.marker([-10.5, 121.83],{title:"Kab. Kupang"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/97380.csv"+folder+".png /> ")
	var m103 = L.marker([-7.22, 112.72],{title:"Kota Surabaya"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96933.csv"+folder+".png /> ")
	var m104 = L.marker([-7.21, 112.74],{title:"Kota Surabaya"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96937.csv"+folder+".png /> ")
	var m105 = L.marker([-7.05, 113.97],{title:"Kab. Sumenep"}, {icon: markerIcon}).addTo(map).bindPopup("<img src=http://202.90.199.54/itmy/Grafik/"+folder+"/96973.csv"+folder+".png /> ")


	}
	setfol();

	
</script>

</body>
</html>
