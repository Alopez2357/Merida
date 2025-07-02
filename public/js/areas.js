let requestAreas={areas:[]}

async function getAreas() {
    clearPolygons();
    const URL =window.location.protocol + "//" + window.location.host+ "/api/v1/maps/areas?api_key="+Api_key;
    const response = await fetch(URL,{
        method:'post',
        body: JSON.stringify(requestAreas),
        headers: {"Content-type": "application/json;charset=UTF-8" }
    });

    const areas = await response.json();
    Areas =areas.areas;
    console.log(Areas)
    drawAreas();
}

function drawAreas(){
    for(let a=0; a <  Areas.length; ++a) {
        let Coords=[];
        for(let c=0; c <  Areas[a].coordinates.length; ++c) {
            Coords[c]= {
                'lat': Areas[a].coordinates[c].lat,
                'lng': Areas[a].coordinates[c].lng,
            };

        }
        const Polygon =  new google.maps.Polygon({
            paths: Coords,
            strokeColor: "#607c3c",
            strokeOpacity:(Areas.length===1) ? 0.0:0.0,
            strokeWeight: 1,
            fillColor: "#ececa3",
            fillOpacity: (Areas.length===1) ? 0.0:0.0,
        });

        Polygon.setMap(map);
        polygons.push(Polygon);
    }



}