@section('title','Tamaño del Establecimiento')
<div   class=" h-full overflow-y-auto p-2">
    <div class="flex flex-wrap md:flex-nowrap md:space-x-2 h-full">
        <div class="order-2 md:order-1 w-full md:w-2/3 h-full flex space-x-2 pb-2 ">
            <div class="border w-full md:h-[100%]  bg-white rounded-lg shadow" id="mainMap"   wire:ignore></div>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/3 flex flex-wrap ">
            <div class="w-full  py-2 flex flex-wrap justify-center items-center  space-y-2">
                <div class="w-full">
                    <img class="w-1/3 h-auto mx-auto" src="{{ asset('img/inegi.png') }}" alt="">
                </div>
                <div class="w-full text-center bg-white rounded-lg shadow py-2">
                        <label  for="" class="font-semibold pr-2">Poligono: </label>
                        <select class="w-auto border border-gray-100 rounded-lg" name="" id="" onchange="selectPolygon(this)" >
                            <option value="0"> MERIDA </option>
                            @foreach($poligons as $polygon)
                                <option value="{{ $polygon->id }}">{{ $polygon->name }}</option>
                            @endforeach
                        </select>
                    </div>
                <div class="w-full text-center font-semibold   bg-white rounded-lg shadow py-2 max-h-[7vh]">
                    <span  id="total">0</span> Registros
                </div>
                <div class="w-full bg-white rounded-lg shadow-lg py-2 min-h-[30vh] flex items-center">
                    <canvas id="chart" class="w-full h-full"></canvas>
                </div>
                <div class="w-full flex justify-center p-2">
                    <button onclick="exportChart()" class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe3fVMFtZ9tjqzfIviCztwC5WUn0S5iYM"></script>
<script src="{{ asset('js/areas.js') }}"></script>
<script>
    let areas=[];
    let stratum=[];
    let chart;
    let strAreaSelected=' MERIDA ';

    function init()
    {
        mainMap();
        getAreas();
        clearMarkers();
        getCompanies();
        getStratum();
    }


    function exportChart(){
        exportCanva(document.getElementById('chart'));
    }

    function selectPolygon(el) {
        if(document.getElementById('total'))document.getElementById('total').innerHTML='-';
        areas = [];
        strAreaSelected = el.options[el.selectedIndex].text;
        if (el.value != 0) {
            areas.push(el.value)
        }
        requestAreas = {areas: areas}

        getAreas();
        clearMarkers();
        getCompanies();
        getStratum();
    }

    async function getCompanies() {
        console.log('inicia')
        const response = await fetch(host+ "/api/v1/maps/inegi/companies?api_key="+Api_key,{
            method:'POST',
            body: JSON.stringify({areas:areas}),
            headers: {"Content-type": "application/json;charset=UTF-8"}
        });

        if (response.status===200) {
            const result = await response.json();
            if (result.body!==null) {
                Companies = result.body.companies;
                if(document.getElementById('total'))document.getElementById('total').innerHTML=Companies.length.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                drawCompanies();
            } else {
                if(document.getElementById('alert'))document.getElementById('alert').innerHTML='Sin Datos';
            }
        }else{
            console.log(response)
        }
    }


    async function getStratum() {
        let URL =window.location.protocol + "//" + window.location.host+ "/api/v1/maps/inegi/stratum?api_key="+Api_key;
        const response = await fetch(URL,{
            method:'POST',
            body: JSON.stringify({areas:areas}),
            headers: {"Content-type": "application/json;charset=UTF-8"}
        });

        if (response.status===200) {
            const result = await response.json();
            if (result.body!==null) {
                stratum = result.body.stratum;
                drawChart();
            } else {
                if(document.getElementById('alert'))document.getElementById('alert').innerHTML='Sin Datos';
            }
        }
    }

    function drawChart()
    {

        const ctx=document.getElementById('chart').getContext('2d');
        if(chart) chart.destroy();
        let labels =[], sets=[];
        stratum.map(item=>{
            labels.push(item.estrato);
            sets.push(item.total);
        });
        let rgb=rndBgColor(stratum)

        ConfigHorizontalBars.data= {
            labels: labels,
            datasets: [{
                axis: 'y',
                data: sets,
                fill: false,
                backgroundColor: rgb,
                borderWidth: 1
            }]
        };
        ConfigHorizontalBars.options.plugins.legend.display=false;
        ConfigHorizontalBars.options.plugins.title={display: true,text:' Tamaño del Establecimiento ' + strAreaSelected,font:{size:12}};
        chart= new Chart(ctx, ConfigHorizontalBars);
    }



    window.onload=function(){
        init();
    };
</script>
</div>
