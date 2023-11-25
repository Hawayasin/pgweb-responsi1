<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WEBGIS GEOHERITAGE</title>
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />
    <!-- Marker Cluster -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-markercluster/MarkerCluster.css"
    />
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-markercluster/MarkerCluster.Default.css"
    />
    <!-- Routing -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-routing/leaflet-routing-machine.css"
    />
    <!-- Search CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-search/leaflet-search.css"
    />
    <!-- Geolocation CSS Library for Plugin -->
    <link
      rel="stylesheet"
      href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css"
    />
    <!-- Leaflet Mouse Position CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.css"
    />
    <!-- Leaflet Measure CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-measure/leaflet-measure.css"
    />
    <!-- EasyPrint CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-easyprint/easyPrint.css"
    />
    <style>
    html, body, #map {
        height: 100%;
        width: 100%;
        margin: 0px;
    }
    /* Background pada Judul */
    *.info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(124, 104, 104, 0.2);
        border-radius: 5px;
        text-align: center;
    }

    .info h2 {
        margin: 0 0 5px;
        color: #777;
    }
</style>
  </head>

  <body>
    <div id="map"></div>

    <!-- Include your GeoJSON data -->
    <script src="./data.js"></script>

    <!-- Leaflet and Plugins -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="assets/plugins/leaflet-markercluster/leaflet.markercluster.js"></script>
    <script src="assets/plugins/leaflet-markercluster/leaflet.markercluster-src.js"></script>
    <script src="assets/plugins/leaflet-routing/leaflet-routing-machine.js"></script>
    <script src="assets/plugins/leaflet-routing/leaflet-routing-machine.min.js"></script>
    <script src="assets/plugins/leaflet-search/leaflet-search.js"></script>
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>
    <script src="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.js"></script>
    <script src="assets/plugins/leaflet-measure/leaflet-measure.js"></script>
    <script src="assets/plugins/leaflet-easyprint/leaflet.easyPrint.js"></script>

    <script>
      // Initialize the map
      var map = L.map("map").setView([-7.7956, 110.3695], 10);

      // Basemaps
      var basemap1 = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
          maxZoom: 19,
          attribution:
            'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }
      );
      basemap1.addTo(map);

      // Point 
      
      // Create a GeoJSON layer for polygon data
      var wfsgeoserver1 = L.geoJson(null, {
        style: function (feature) {
          // Adjust this function to define styles based on your polygon properties
          var value = feature.properties.name; // Change this to your actual property name
          return {
            fillColor: getColor(value),
            weight: 2,
            opacity: 1,
            color: "white",
            dashArray: "3",
            fillOpacity: 0.7,
          };
        },
        onEachFeature: function (feature, layer) {
          // Adjust the popup content based on your polygon properties
          var content =
            "Jenis Formasi: " +
            feature.properties.name +
            "<br>" +
            "Sedimentasi: " +
            feature.properties.class_lith +
            "<br>";

          layer.bindPopup(content);
        },
      });

      // Fetch GeoJSON data from geoserver.php
      $.getJSON("wfsgeoserver1.php", function (data) {
        wfsgeoserver1.addData(data);
        wfsgeoserver1.addTo(map);
        map.fitBounds(wfsgeoserver1.getBounds());
      });

      // Array of markers
      var markersArray = [
        {
          coordinates: [-7.766715, 110.37746],
          options: { draggable: true },
          popupContent: "Gedung B DIVSIG UGM",
        },
        {
          coordinates: [-7.743168, 110.350276],
          options: {},
          popupContent: "RS.Akademik UGM",
        },
      ];

      // // Add GeoJSON data to the map
      // L.geoJson(data).addTo(map);

      // // Create a marker cluster group
      // var markers = L.markerClusterGroup();

      // // Add markers to the cluster group
      // markersArray.forEach(function (markerInfo) {
      //   var marker = L.marker(markerInfo.coordinates, markerInfo.options);
      //   marker.addTo(markers);
      //   marker.bindPopup(markerInfo.popupContent);
      // });

      // Title
      var title = new L.Control();
      title.onAdd = function (map) {
        this._div = L.DomUtil.create("div", "info");
        this.update();
        return this._div;
      };
      title.update = function () {
        this._div.innerHTML =
          "<h2> GO RUN | GEOHERITAGE HARTAKARUN NASIONAL </h2>MATAKULIAH PEMROGRAMAN SPASIAL : WEB";
      };
      title.addTo(map);

      // Watermark 
      L.Control.Watermark = L.Control.extend({
        onAdd: function (map) {
          var container = L.DomUtil.create("div", "leaflet-control-watermark");
          var img = L.DomUtil.create("img", "watermark-image");
          img.src = "assets/img/logo/LOGO_SIG_BLUE.png";
          img.style.width = "120px";
          container.appendChild(img);
          return container;
        },
      });
      L.control.watermark = function (opts) {
        return new L.Control.Watermark(opts);
      };

      L.control.watermark({ position: "bottomleft" }).addTo(map);
      
      // Legend
      L.Control.Legend = L.Control.extend({
        onAdd: function (map) {
          var img = L.DomUtil.create("img");
          img.src = "assets/img/legend/legend1.png";
          img.style.width = "250px";
          return img;
        },
      });
      L.control.Legend = function (opts) {
        return new L.Control.Legend(opts);
      };

      L.control.Legend({ position: "bottomleft" }).addTo(map);
      // Basemaps
      var basemap1 = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
          maxZoom: 19,
          attribution:
            'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }
      );

      var basemap2 = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
        {
          attribution:
            'Tiles &copy; Esri | <a href="DIVSIGUGM" target="_blank">DIVSIG UGM</a>',
        }
      );

      var basemap3 = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        {
          attribution:
            'Tiles &copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIG UGM</a>',
        }
      );

      var basemap4 = L.tileLayer(
        "https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png",
        {
          attribution:
            '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
        }
      );

      basemap3.addTo(map);

      var baseMaps = {
        OpenStreetMap: basemap1,
        "Esri World Street": basemap2,
        "Esri Imagery": basemap3,
        "Stadia Dark Mode": basemap4,
      };

      L.control.layers(baseMaps).addTo(map);

      // Plugin Search
      var searchControl = new L.Control.Search({
        position: "topleft",
        layer: wfsgeoserver1, // Nama variabel layer
        propertyName: "", // Field untuk pencarian
        marker: false,
        moveToLocation: function (latlng, title, map) {
          var zoom = map.getBoundsZoom(latlng.layer.getBounds());
          map.setView(latlng, zoom);
        },
      });

      searchControl
        .on("search:locationfound", function (e) {
          e.layer.setStyle({
            fillColor: "#ffff00",
            color: "#0000ff",
          });
        })
        .on("search:collapse", function (e) {
          wfsgeoserver1.eachLayer(function (layer) {
            wfsgeoserver1.resetStyle(layer);
          });
        });

      map.addControl(searchControl);

      // Plugin Geolocation
      var locateControl = L.control
        .locate({
          position: "topleft",
          drawCircle: true,
          follow: true,
          setView: true,
          keepCurrentZoomLevel: false,
          markerStyle: {
            weight: 1,
            opacity: 0.8,
            fillOpacity: 0.8,
          },
          circleStyle: {
            weight: 1,
            clickable: false,
          },
          icon: "fas fa-crosshairs",
          metric: true,
          strings: {
            title: "Click for Your Location",
            popup: "You're here. Accuracy {distance} {unit}",
            outsideMapBoundsMsg: "Not available",
          },
          locateOptions: {
            maxZoom: 16,
            watch: true,
            enableHighAccuracy: true,
            maximumAge: 10000,
            timeout: 10000,
          },
        })
        .addTo(map);

      // Plugin Mouse Position Coordinate
      L.control
        .mousePosition({
          position: "bottomright",
          separator: ",",
          prefix: "Point Coodinate: ",
        })
        .addTo(map);

      // Plugin Measurement Tool
      var measureControl = new L.Control.Measure({
        position: "topleft",
        primaryLengthUnit: "meters",
        secondaryLengthUnit: "kilometers",
        primaryAreaUnit: "hectares",
        secondaryAreaUnit: "sqmeters",
        activeColor: "#FF0000",
        completedColor: "#00FF00",
      });

      measureControl.addTo(map);

      // Plugin EasyPrint
      L.easyPrint({
        title: "Print",
      }).addTo(map);

      // Plugin Routing
      L.Routing.control({
        waypoints: [
          L.latLng(-7.751917, 110.491445),
          L.latLng(-7.805287455331144, 110.36420308403358),
        ],
        routeWhileDragging: true,
      }).addTo(map);

      // Layer Marker
      var addressPoints = [
        [-7.798987, 110.325046, "Geosen  gamping"],
        [-7.808237, 110.459454, "Lava Bantal Brebah"],
        [-7.783978, 110.511547, "Sedimen Abu Vulkanik Purba"],
        [-7.746577, 110.130933, "Goa Kiskendo"],
        [-7.859501, 110.11618, "Bekas Penambangan Mangan"],
        [-7.841253, 110.543056, "Gunung Api Purba Nglangeran"],
        [-7.897893, 110.563775, "Bioturbasi Sungai Nglangeran"],
        [-8.017473,110.316522, "Gumuk Pasir Parangtritis"],
        [-8.189421,110.71049, "Kawasan Pantai Wedi Ombo"],
      ];

      var markers = L.markerClusterGroup();

    

      for (var i = 0; i < addressPoints.length; i++) {
        var a = addressPoints[i];
        var title1 = a[2];
        var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title1 });

        marker.bindPopup(title1);
        markers.addLayer(marker);
      }

      map.addLayer(markers);
      //Function to determine the color based on the 'value' attribute
      function getColor(value) {
      return value === 'Aluvial'
        ? "#D2691E"    // Gold
        : value === 'Andesit'
        ? "#D2691E"    // Goldenrod
        : value === 'Batuan Gunungapi Tak Terpisahkan'
        ? " #DAA520"    // Chocolate
        : value === 'Endapan Gunungapi Merapi Tua'
        ? "#8FBC8F"    // Dark Sea Green
        : value === 'Endapan Gunungapi Muda Merapi'
        ? "#483D8B"    // Dark Slate Blue
        : value === 'Endapan Longsoran (Ladu) dari Awan Panas'
        ? "#F0E68C"    // Khaki
        : value === 'Formasi Jonggrangan'
        ? "#ADD8E6"    // Light Blue
        : value === 'Formasi Kebobutak'
        ? "#BA66FF"    // Light Cyan
        : value === 'Formasi Kepek'
        ? "#9370DB"    // Medium Aquamarine
        : value === 'Formasi Nanggulan'
        ? "#FAFAD2"    // Light Goldenrod Yellow
        : value === 'Formasi Oyo'
        ? "#FF66AA"    // Pink
        : value === 'Formasi Sambipitu'
        ? "#F7FF66"    // Yellow
        : value === 'Formasi Semilir'
        ? "#66FF6D "    // 
        : value === 'Formasi Sentolo'
        ? "#B581C4"    // Cobalt Blue
        : value === 'Formasi Wonosari'
        ? "#66CDAA "    // Medium Purple
        : value === 'Formasi Wungkai'
        ? "#F4511E"    // 
        : value === 'Formasi Wuni'
        ? "#C14242"    // 
        : value === 'Kulovial'
        ? "#6D6464"    // 
        : value === 'Nglanggran Formation'
        ? "#66FFBA"    // Chocolate
        : "#fff5f0";   // Lavender Blush
    }

// }
    </script>
 </body>
</html>