@section('title','DENUE INEGI')
<section class="h-full overflow-y-auto p-2">
    <div class="flex flex-wrap w-full "  x-data="app"   @map-loaded.window="doMapStuff()">
        <div class="w-4/5 border" >
            <div id="commerceMap" class=" w-full border md:h-[90vh] xl:h-[85vh]"></div>
        </div>
        <div class="w-1/5  px-2">
            <img class="md:w-1/2 h-auto mx-auto" src="{{ asset('img/inegi.png') }}" alt="">
            <div class="bg-white rounded-lg shadow-lg  w-full p-2">
                <label for="" class="font-semibold py-2">Comercios</label>
                <p class="py-2"><input type="text" x-model="search" class="border w-full rounded shadow p-2"></p>
                <p class="flex w-full justify-end">
                    <button id="reloadMarkers"  @click="getSearch()"
                            class="rounded-lg shadow hover:shadow-lg bg-green-600 hover:bg-green-500 py-2 px-4 text-white">Buscar</button>
                </p>
                <p > <strong x-text="lengthLocations()"></strong></p>
            </div>

            <div class="bg-white rounded-lg shadow-lg  w-full md:h-[50vh] xl:h-[45vh] my-4 p-2 overflow-y-auto">
                <ul class="text-xs">
                    <template x-for="location in locations">
                        <li x-text="location.Nombre"></li>
                    </template>
                </ul>

            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe3fVMFtZ9tjqzfIviCztwC5WUn0S5iYM"></script>
    <script src="{{ asset('js/areas.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', (comp) => ({
                URL:'',
                search:'neveria',
                locations:[],
                radio:5000,
                init() {
                    this.getSearch()

                },
                getSearch(){
                    this.clearMarkers();
                    this.URL ="https://www.inegi.org.mx/app/api/denue/v1/consulta/Buscar/"+this.search+"/20.978847994539,-89.619608849834/"+this.radio+"/"+Token_INEGI;
                    const INEGI = fetch(this.URL)
                        .then((response) => {
                            console.log('status code: ', response.status); // 👉️ 200
                            if (!response.ok) {
                                console.log(response);
                                throw new Error(`Error! status: ${response.status}`);
                            }
                            const result = response.json();
                            return result;
                        }).catch(err => {
                            console.log(err);
                        });

                    const print = async () => {
                        const a = await INEGI;
                        this.locations=a;
                        if(typeof this.locations === 'object') {
                            this.doMapStuff();
                        }
                    };
                    print()
                },
                lengthLocations(){
                     if(typeof this.locations !== 'object'){
                        return 'No Hay Resultados';
                     }else{
                         return this.locations.length + ' - Resultados';
                    }
                },
                doMapStuff(){
                    for(let i=0; i < this.locations.length; i++) {
                        const newMarker = new google.maps.Marker({
                            position: {lat: parseFloat(this.locations[i].Latitud), lng: parseFloat(this.locations[i].Longitud)},
                            map: map,
                            animation: google.maps.Animation.DROP,
                            title: this.locations[i].Nombre,
                        });
                        mapMarkers.push(newMarker);
                    }
                },
                clearMarkers()
                {
                    for(let i=0; i < mapMarkers.length; i++) {
                        mapMarkers[i].setMap(null)
                    }
                    this.locations=[];

                }
            }));
        });

        function initMap(){
            map = new google.maps.Map(document.getElementById("commerceMap"), {
                center: new google.maps.LatLng(20.978847994538597, -89.61960884983415),
                zoom: 12,
            })
            getAreas();
        }
        window.dispatchEvent(new Event('map-loaded'));
        initMap();
    </script>
</section>


