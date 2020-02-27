export default function () {

    const mapContainer = document.getElementById('map-target');

    function initMap() {
        var stylez = [
            {
                featureType: "all",
                elementType: "all",
                stylers: [
                    { saturation: -100 } // <-- THIS
                ]
            }
        ];

        let targetLocation = {lat: 47.8340187, lng: 35.1720227};
        var mapOptions = {
            zoom: 15,
            center: targetLocation,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'tehgrayz']
            }
        };

        var map = new google.maps.Map(mapContainer, mapOptions);

        var mapType = new google.maps.StyledMapType(stylez);
        map.mapTypes.set('tehgrayz', mapType);
        map.setMapTypeId('tehgrayz');

        var marker = new google.maps.Marker({
            position: targetLocation,
            map: map,
            title: 'Форос'
        });
    }

    window.initMap = initMap;


}
