@section('title','Distribución de Empleo por Ciudad')
<section class="h-full overflow-y-auto p-2">
    <div class="w-full h-full  flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="order-2 md:order-1 w-full h-[75vh] md:h-full bg-white rounded-lg shadow  flex items-center justify-center border" >
            <div id="initMap"  class="w-full h-full"  ></div>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/3  flex flex-wrap ">
            <div class="w-full h-[30vh] md:h-1/2  overflow-x-auto border p-2 rounded-xl shadow-inner">
                <livewire:trimester-calendar />
            </div>
            <div class="w-full  md:h-1/2 flex flex-wrap items-start space-y-2" wire:ignore>
               <div class="w-full flex justify-center items-center p-2">
                  <!-- <button onclick="exportChart()" class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>-->
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe3fVMFtZ9tjqzfIviCztwC5WUn0S5iYM"></script>
    <script>
        let title='Tasa de ocupación por ciudad';
        let subtitle='';
        let Y, Q;
        let Areas=[];


        function selectQuarter(y,q){
            Y=y;
            Q=q;

        }
        async function getAllCoordinates(){
                const response = await fetch(host + "/api/v1/maps/inegi/politic-division?api_key="+Api_key);
                if (response.ok) {
                    const result = await response.json();
                    let data=result.body
                    for (let c in data) {
                        Areas.push(data[c]);
                    }
                    drawEntities();
                } else {
                    console.log(response)
                }

        }

        function drawEntities(){
            for(let a=0; a <  Areas.length; ++a) {

                const Polygon =  new google.maps.Polygon({
                    paths: Areas[a].coordinates,
                    strokeColor: "#607c3c",
                    strokeOpacity:1,
                    strokeWeight: 1,
                    fillColor: "#ececa3",
                    fillOpacity: 1,
                });

                Polygon.setMap(map);
                Polygon.addListener("click",(event)=> {
                    let content="<div>";
                    content+="<p style='font-size:small'><b>" +Areas[a].name + "</b></p>";
                    content+="<p style='font-size:small'>Población: " + parseInt(Areas[a].properties.pob).toLocaleString("en-US") + "</p>";
                    content+="</div>";
                    infowindow.setContent(content);
                    infowindow.setPosition(event.latLng);
                    infowindow.open({
                        map,
                        shouldFocus: false,
                    });
                });
            }
        }

        function drawMap(){
            map = new google.maps.Map(document.getElementById("initMap"), {
                center: new google.maps.LatLng(22.528273, -102.128751),
                zoom: 5,
                zoomControl: false,
                gestureHandling: 'none',
                disableDefaultUI: true,
            });

            infowindow = new google.maps.InfoWindow({});
        }
        window.onload=function(){
            drawMap();
            getAllCoordinates();
        };

    </script>
</section>

