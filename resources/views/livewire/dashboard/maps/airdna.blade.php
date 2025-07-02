@section('title', 'Ocupación AirDNA')
<div class="w-full overflow-y-auto px-2 md:py-2 ">
    <div class="flex flex-wrap md:flex-nowrap w-full h-full space-y-2 md:space-x-2 md:space-y-0"  >
        <div class="order-2 md:order-1 flex flex-wrap w-full md:w-1/3 h-auto md:h-full md:overflow-y-auto space-y-2">
            <div class="w-full bg-white rounded-lg shadow flex items-center justify-center h-[20%]  md:h-[30%]">
               <canvas id="chartFee" style="width: 95%; height: 100%"></canvas>
            </div>
            <div class="w-full bg-white rounded-lg shadow flex items-center justify-center h-[20%] md:h-[30%]">
                <canvas id="chartOccupancy"  style="width: 95%; height: 100%"></canvas>
            </div>
            <div class="w-full bg-white rounded-lg shadow flex items-center justify-center h-[20%] md:h-[30%]">
                <canvas id="chartEarns" style="width: 95%; height: 100%"></canvas>
            </div>
            <div class="w-full bg-white rounded-lg shadow flex items-center justify-center h-[20%] md:h-[30%]">
                <canvas id="chartAssets" style="width: 95%; height: 100%"></canvas>
            </div>
            <div class="w-full bg-white rounded-lg shadow flex items-center justify-center h-[20%] md:h-[30%]">
                <canvas id="chartDemand"  style="width: 95%; height: 100%" ></canvas>
            </div>
            <!--<div class="w-full bg-white rounded-lg shadow-lg mb-2  flex items-center justify-center md:h-[30vh]">
                <h3 class="font-semibold ">6.Análisis de la demanda</h3>
                <div><a href="#">
                        <img src="#" alt="">
                    </a>
                </div>
            </div>-->
        </div>
        <div class="order-1 md:order-2 w-full md:w-2/3 h-[75%] md:h-full ">
            <div class="w-full h-full flex flex-wrap space-y-2 ">
                <div class="w-full flex flex-wrap h-[20%] md:h-[10%]  bg-white rounded shadow border " >
                    <div class="w-full md:w-1/2 flex items-center justify-center">
                        <label  for="" class="font-semibold pr-2">Poligono: </label>
                        <select class="w-auto border border-gray-100 rounded-lg" name="" id="" onchange="selectPolygon(this)" >
                            <option value="0"> MÉRIDA </option>
                            @foreach($poligons as $polygon)
                                <option value="{{ $polygon->id }}">{{ $polygon->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-1/2 flex">
                        <div class="w-1/2 flex"  x-data="{ open: false }">
                            <div class="flex items-center justify-center w-full">
                                <div class="w-ful">
                                    <button class="mx-auto z-30 py-2 px-4 text-white shadow hover:shadow-lg rounded-lg bg-green-600 hover:bg-green-500" x-on:click="open = ! open">
                                       <span id="current-interval">Calendario</span> <i class="fas fa-calendar"></i>
                                    </button>
                                    <div class="absolute md:w-auto z-20  flex" x-show="open" x-on:click:away="open = ! false">
                                        <div class="relative  ">
                                            <livewire:month-calendar />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-1/2 flex items-center justify-center">
                            <div class="text-center font-semibold">
                                <span  id="total">0</span> Registros
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[80%] md:h-[90%]  rounded pb-2">
                    <div id="mainMap" class="w-full h-full rounded-lg"   wire:ignore></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe3fVMFtZ9tjqzfIviCztwC5WUn0S5iYM"></script>
    <script src="{{ asset('js/areas.js') }}"></script>
<script>
    let areas=[];
    let statistics=[];
    let feeMonth=[];

    let chartFeeLine;
    let chartOccupancyLine;
    let chartEarnsLine;
    let chartAssetsLine;
    let chartDemandLine;
    let strAreaSelected='Mérida';
    
function init() {
    today.setMonth(today.getMonth()-1)
    setTimeInterval(today.getFullYear(),today.getMonth()+1);
    mainMap();
    getAreas();
    getData();
    getStatistics();
}

async function getData() {
    clearMarkers();
    let body={start:date_start,end:date_end,areas:areas};
    let URL =window.location.protocol + "//" + window.location.host+ "/api/v1/maps/airdna?api_key="+Api_key;
    const response = await fetch(URL,{
                method:'POST',
                body: JSON.stringify(body),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });

    if (response.ok) {
        const result = await response.json();
        if (result.body !== null) {
            Locations = result.body.locations;
            document.getElementById('total').innerHTML=Locations.length.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            drawMarkers();
        } else {
            document.getElementById('alert').innerHTML = 'Intervalo Vacio';
        }
    }
}

function selectPolygon(el){
    areas=[];
    strAreaSelected=el.options[el.selectedIndex].text;
   if(el.value!=0) {
       areas.push(el.value)
   }
    requestAreas = {areas:areas}
    getAreas();
    getData();
    getStatistics();
}
function selectMonth(Y,M){
    setTimeInterval(Y,M);
    getData();
}

async function getStatistics(){
    let date=new Date(date_end);
    date.setFullYear(date.getFullYear()-1);
    let Y=date.getFullYear();
    let m=String(date.getMonth()+1).padStart(2, '0');
    let d=String(new Date(Y,m,1).getDate()).padStart(2, '0');
    let start=Y+"-"+m+"-"+d;
    let body={start:start,end:date_end,areas:areas};
    console.log(body)
    let URL =host + "/api/v1/maps/statistics?api_key="+Api_key;
    const response = await fetch(URL,{
        method:'POST',
        body: JSON.stringify(body),
        headers: { "Content-type": "application/json;charset=UTF-8"}
    });
    if (response.ok) {
        const result = await response.json();
        if (result.body !== null) {
            statistics = result.body;
        }
    }

    drawFeeChart();
    drawOccupancyChart();
    drawEarnsChart();
    drawAssetsChart();
    drawDemandChart();

}

function drawFeeChart(){
    let ctxFee = document.getElementById('chartFee').getContext('2d');
    if(chartFeeLine) chartFeeLine.destroy();
    let labels=[];
    let datasets=[];
    for (let f=0; f < statistics.length; ++f){
        labels.push(statistics[f].month_year);
        datasets.push(statistics[f].fee_revenue_native);
    }
        ConfigMiniLine.options.plugins.legend.display=false;
        ConfigMiniLine.options.plugins.title.text='Tarifa Diaria Promedio '+strAreaSelected;
        ConfigMiniLine.data.labels=labels;
        ConfigMiniLine.data.datasets=[{
                                borderColor: rndBgColor(labels),
                                data: datasets,
                                }];
    chartFeeLine = new Chart(ctxFee,ConfigMiniLine);
}

function drawOccupancyChart() {
    let ctxOccupancy = document.getElementById('chartOccupancy').getContext('2d');
    if ( chartOccupancyLine) chartOccupancyLine.destroy();
    let labels = [];
    let datasets = [];
    for (let f = 0; f < statistics.length; ++f) {
        labels.push(statistics[f].month_year);
        datasets.push(statistics[f].ocupancy);
    }
        ConfigMiniLine.options.plugins.legend.display=false;
        ConfigMiniLine.options.plugins.title.text = 'Tasa de ocupación ' + strAreaSelected;
        ConfigMiniLine.data.labels = labels;
        ConfigMiniLine.data.datasets = [{
            borderColor: rndBgColor([1]),
            data: datasets,
        }];
    chartOccupancyLine = new Chart(ctxOccupancy, ConfigMiniLine);
}

function drawEarnsChart() {
        let ctxEarns = document.getElementById('chartEarns').getContext('2d');
        if ( chartEarnsLine) chartEarnsLine.destroy();
        let labels = [];
        let datasets = [];
        for (let f = 0; f < statistics.length; ++f) {
            labels.push(statistics[f].month_year);
            datasets.push(statistics[f].revenue_native);
        }
            ConfigMiniLine.options.plugins.legend.display=false;
            ConfigMiniLine.options.plugins.title.text = 'Ganancias promedio ' + strAreaSelected;
            ConfigMiniLine.data.labels = labels;
            ConfigMiniLine.data.datasets = [{
                        borderColor: rndBgColor([1]),
                        data: datasets,
                    }];
        chartEarnsLine = new Chart(ctxEarns,ConfigMiniLine);
    }


function drawAssetsChart() {
    let ctxAssets = document.getElementById('chartAssets').getContext('2d');
    if ( chartAssetsLine) chartAssetsLine.destroy();
    let labels = [];
    let datasets = [];
    for (let f = 0; f < statistics.length; ++f) {
        labels.push(statistics[f].month_year);
        datasets.push(statistics[f].assets);
    }
        ConfigMiniLine.options.plugins.legend.display=false;
        ConfigMiniLine.options.plugins.title.text = 'Listado de Activos ' + strAreaSelected;
        ConfigMiniLine.data.labels = labels;
        ConfigMiniLine.data.datasets = [{
                borderColor: rndBgColor([1]),
                data: datasets,
            }];
    chartAssetsLine= new Chart(ctxAssets, ConfigMiniLine);
}

function drawDemandChart() {
    let ctxDemand = document.getElementById('chartDemand').getContext('2d');
    if ( chartDemandLine) chartDemandLine.destroy();
    let labels = [];
    let reservations = [];
    let days = [];
    let datasets=[];
    for (let f = 0; f < statistics.length; ++f) {
        labels.push(statistics[f].month_year);
        reservations.push(statistics[f].reservations);
        days.push(statistics[f].reservation_days);
    }
    ConfigMiniLine.options.plugins.legend={ display:true, font: {size: 10}};
    ConfigMiniLine.options.plugins.legend.position='bottom';
    ConfigMiniLine.options.plugins.title.text = 'Demanda Reserva ' + strAreaSelected;
    ConfigMiniLine.data.labels = labels;
    let rgb = rndBgColor(['Reservations']);
    datasets.push({
            backgroundColor:rgb,
            borderColor: rgb,
            label:'Reservations',
            data: reservations,
        })
        rgb= rndBgColor(['Days']);
    datasets.push({
            backgroundColor:rgb,
            borderColor: rgb,
            label:'Days',
            data: days,
        });

    ConfigMiniLine.data.datasets=datasets;
    chartDemandLine= new Chart(ctxDemand, ConfigMiniLine);
}

window.onload=function(){
    init();
};


    </script>


</div>