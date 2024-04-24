<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 6.20: index.tpl.php, v1.00 6/21/2023 3:05 PM Gewa Exp $
    *
    */
   
   use Wojo\Module\Map\Map;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<?php if($row = Map::render($this->properties['plugin_id'])):?>
   <div id="gmap_<?php echo $row->id;?>" class="height-full rounded min-height200"></div>
   <script type="text/javascript">
      // <![CDATA[
      function bootstrap() {
         if (typeof google === 'object' && typeof google.maps === 'object') {
            runMap();
         } else {
            const script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "https://maps.google.com/maps/api/js?key=<?php echo $this->properties['core']->mapapi;?>&callback=runMap";
            document.body.appendChild(script);
         }
      }
      function runMap() {
         let markers = [];
         let map;
         
         <?php $minmaxzoom = explode(',', $row->minmaxzoom);?>
         let newMapOptions = {
            center: new google.maps.LatLng(<?php echo $row->lat;?>, <?php echo $row->lng;?>),
            zoom: <?php echo $row->zoom;?>,
            minZoom: <?php echo $minmaxzoom[0];?>,
            maxZoom: <?php echo $minmaxzoom[1];?>,
            zoomControlOptions: {
               style: google.maps.ZoomControlStyle.SMALL
            },
            scaleControl: true,
            mapTypeId: "<?php echo $row->type;?>",
            mapTypeControl: <?php echo $row->type_control;?>,
            streetViewControl: <?php echo $row->streetview;?>,
            styles: <?php echo $row->style;?>,
         };
         map = new google.maps.Map(document.getElementById("gmap_<?php echo $row->id;?>"), newMapOptions);

         //set marker
         let marker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $row->lat;?>, <?php echo $row->lng;?>),
            map: map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            raiseOnDrag: false,
            icon: "<?php echo FMODULEURL . 'maps/view/images/pins/' . $row->pin;?>",
            title: "<?php echo $row->name;?>"
         });

         //set infowindow
         let content =
           '<div class="container">' +
           '<h5><?php echo $row->name;?></h5>' +
           '<div class="content">' +
           '<?php echo $row->body;?>' +
           '</div>' +
           '</div>';

         let infowindow = new google.maps.InfoWindow({
            content: content,
            maxWidth: 350,
            maxHeight: 350
         });

         marker.addListener('click', function() {
            infowindow.open(map, marker);
         });

         markers.push(marker);
      }
      bootstrap();
      // ]]>
   </script>
<?php endif;?>