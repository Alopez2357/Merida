@section('title','Dashboard')
<section class="w-full h-full overflow-y-auto p-2">
   <div class="w-full md:h-1/3 flex flex-wrap md:flex-nowrap md:space-x-2 space-y-2 md:space-y-0 pb-2">
       <div class="w-full md:w-1/3 bg-white rounded-lg shadow-lg flex items-center justify-center">
           <canvas id="ocupation-type" class=" rounded-lg" style="width: 95%; height: 100%" ></canvas>
       </div>
       <div class="w-full md:w-1/3 bg-white rounded-lg shadow-lg  flex items-center justify-center">
           <canvas id="arrivals" class=" rounded-lg" style="width: 95%; height: 100%"></canvas>
       </div>
       <div class="w-full md:w-1/3 bg-white rounded-lg shadow-lg  flex items-center justify-center">
           <canvas id="inflation"  class="rounded-lg" style="width: 95%; height: 100%"></canvas>
       </div>
   </div>

    <div class="w-full  md:h-2/3  flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0">
        <div class="w-full h-full md:w-1/3 rounded-lg overflow-x-auto space-y-2 pr-1  ">
            <div class="min-w-0  bg-white rounded-lg shadow-xs  flex items-center justify-center min-h-[30vh]">
                <canvas id="employment" class="rounded-lg w-full h-full"></canvas>
            </div>
            <div class="min-w-0  bg-white rounded-lg shadow-xs  flex items-center justify-center min-h-[30vh]">
                <canvas id="employment-rate" class="rounded-lg w-full h-full" ></canvas>
            </div>
            <div class="min-w-0  bg-white rounded-lg shadow-xs  flex items-center justify-center min-h-[30vh]">
                <canvas id="unemployment-rate" class="rounded-lg w-full h-full"></canvas>
            </div>
        </div>

        <div class="w-full h-full md:w-2/3 pl-1">
            <div class="w-full md:h-[7%]  bg-white rounded-t-lg shadow-xs flex items-center justify-center">
                <div>
                    <img class="w-[100px] h-auto" src="{{ asset('img/inegi-2.png') }}" alt="">
                </div>
                <div class="flex items-center font-semibold">Empresas Mérida <span id="pins" class="px-4">0</span>Unidades</div>
            </div>
            <div class="w-full h-[75vh] md:h-[93%] ">
                <div id="mainMap" class=" w-full h-full" ></div>
            </div>

            </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe3fVMFtZ9tjqzfIviCztwC5WUn0S5iYM"></script>
    <script src="{{ asset('js/areas.js') }}"></script>
    <script>

        let Areas=[];
        let body={};
        let type_ocupations=[];
        let arrivals=[];
        let chartOcupationtypeBars;
        let chartArrivals;
        let chartInflation;
        let chartEmployment;
        let chartEmploymentRate;
        let chartUnEmploymentRate;

        function init() {
            mainMap();
            getDates();
            getCompanies();
            getOcupationType();
            getEmployment();
            getEmploymentRate();
            getUnEmploymentRate();
            getArrivals();
            getInflation();
            //getAreas();

        }
        function getDates(){
            today.setMonth(today.getMonth()-2)
            getParseInterval(today)
            body={start:date_start,end:date_end};
        }

        async function getCompanies() {
            const response = await fetch(host+ "/api/v1/maps/inegi/companies?api_key="+Api_key,{
                method:'POST',
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.status===200) {
                const result = await response.json();
                if (result.body!==null) {
                    Companies = result.body.companies;
                    if(document.getElementById('pins'))document.getElementById('pins').innerHTML=Companies.length.toLocaleString("en-US");
                    drawCompanies();
                } else {
                    if(document.getElementById('alert'))document.getElementById('alert').innerHTML='Sin Datos';
                }
            }else{
                console.log(response)
            }
        }

        async function getOcupationType() {
            const URL =host+"/api/v1/dashboard/accommodation?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'get',
            });
            const result = await response.json();
             type_ocupations=result.body;
            drawOcupationChart();
        }

        function drawOcupationChart(){
            const ctxOcupationtype=document.getElementById('ocupation-type').getContext('2d');
            if(chartOcupationtypeBars) chartOcupationtypeBars.destroy();
            let labels =[], sets=[];
            type_ocupations.map(item=>{
               labels.push(item.listing_type);
                sets.push(item.count);
            });
            let rgb=rndBgColor(type_ocupations)

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
            ConfigHorizontalBars.options.plugins.title={display: true,text:'Tipo Alojamiento',font:{size:12}};
            ConfigHorizontalBars.options.plugins.subtitle.display=true;
            ConfigHorizontalBars.options.plugins.subtitle.text='unidades';
            chartOcupationtypeBars= new Chart(ctxOcupationtype, ConfigHorizontalBars);
        }

        async function getArrivals() {
            const URL =host+"/api/v1/tourism/arrivals/monthly?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'get',
            });

            if(response.ok) {
                const result = await response.json();
                arrivals = result.body;
                drawArrivalsChart();
            }else{
                console.log(response)
            }
        }
        function obtenerUltimos12Elementos(arr) {
        // Verificamos que el arreglo tenga al menos 12 elementos
        if (arr.length < 12) {
            return arr; // Si no hay suficientes elementos, retornamos el arreglo completo
        } else {
            // Usamos el método slice para obtener los últimos 12 elementos
            return arr.slice(-12);
        }
        }
        function drawArrivalsChart(){
            const ctxArrivals=document.getElementById('arrivals').getContext('2d');
            if(chartArrivals) chartArrivals.destroy();
            let labels =[], sets=[];
            arrivals.map(item=>{
                if(item.total != "null") {
                    labels.push(item.int_year + ' ' + item.str_month);
                    sets.push(item.total);
                }
            });
            labels = obtenerUltimos12Elementos(labels)
            sets = obtenerUltimos12Elementos(sets)
            console.log(sets)

            let ConfigArrivals=ConfigMiniLine;
            ConfigArrivals.data.datasets=[{
                borderColor: rndBgColor(labels),
                data: sets,
            }];

            ConfigArrivals.options.plugins.title.text='Arribos Aeropuerto ';
            ConfigArrivals.data.labels=labels;
            ConfigArrivals.options.plugins.subtitle.display=true;
            ConfigArrivals.options.plugins.subtitle.text='pasajeros';
            chartArrivals= new Chart(ctxArrivals, ConfigArrivals);
            console.log(labels)
        }

        async function getInflation() {
            const URL =host+"/api/v1/dashboard/inflation?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'get',
            });
            const result = await response.json();
            arrivals=result.body;
            drawInflationChart();
        }

        function drawInflationChart(){
            const ctxInflation=document.getElementById('inflation').getContext('2d');
            if(chartInflation) chartInflation.destroy();
            let labels =[], sets=[];
            arrivals.map(item=>{
                if(item.key !==null || item.percent > 0) {
                    labels.push(item.key);
                    sets.push(item.percent);
                }
            });

            let ConfigInflation=ConfigMiniLine;
            ConfigInflation.options.plugins.title.text='Inflación Merida';
            ConfigInflation.data.labels=labels;
            ConfigInflation.data.datasets=[{
                borderColor: rndBgColor(labels),
                data: sets,
            }];
            ConfigInflation.options.plugins.subtitle.display=true;
            ConfigInflation.options.plugins.subtitle.text='%';
            chartInflation= new Chart(ctxInflation, ConfigInflation);
        }
        async function getEmployment(){
            const URL =host+"/api/v1/dashboard/employment?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'get',
            });
            const result = await response.json();
            if(response.ok){
                if(result.body!==null) {
                    drawEmploymentChart(result.body);
                }else{
                    console.log('Sin datos Empleo')
                }
            }else{
                console.log(result)
            }
        }
        function drawEmploymentChart(result){
            let json = result.json;
            delete json['Total'];
            let datas=[], labels = [];
            for(let x in json){
                datas.push(json[x]);
                labels.push(x);
            }
            let ctxEmployment=document.getElementById('employment').getContext('2d');
            if(ctxEmployment){
               ConfigDonut.data={
                    datasets: [{data: datas,
                                backgroundColor: rndBgColor(datas)}],
                                labels: labels};
               ConfigDonut.options.responsive=false;
               ConfigDonut.options.plugins.legend={display: true,  position:'bottom'}
                chartEmployment = new Chart(ctxEmployment, ConfigDonut)
            }
        }

       async function getEmploymentRate(){
           const URL =host+"/api/v1/dashboard/employment-rate?api_key="+Api_key;
           const response = await fetch(URL,{
               method:'get',
           });
           const result = await response.json();
           if(response.ok){
               if(result.body!==null) {
                   drawEmploymentRateChart(result.body)
               }else{
                   console.log('Sin datos Tasa Empleo ')
               }
           }else{
               console.log(result)
           }
        }
        function drawEmploymentRateChart(result){
            let sets = [],labels=[];
            for(let x in result){
                sets.push(result[x].json.Total);
                labels.push(result[x].year+' - '+formatQuarter(result[x].quarter))
            }
            console.log(sets);
            ConfigMiniLine.data.labels=labels;
            ConfigMiniLine.data.datasets=[{
                borderColor: rndBgColor(labels),
                data: sets,
            }];

            let ctxEmploymentRate=document.getElementById('employment-rate').getContext('2d');
            if(ctxEmploymentRate){
                ConfigMiniLine.options.plugins.legend.display=false;
                ConfigMiniLine.options.plugins.title.text='Tasa de ocupación Empleo ';

                chartEmploymentRate= new Chart(ctxEmploymentRate, ConfigMiniLine);

            }
        }

        async function getUnEmploymentRate(){
            const URL =host+"/api/v1/dashboard/unemployment-rate?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'get',
            });
            const result = await response.json();
            if(response.ok){
                if(result.body!==null) {
                    drawUnEmploymentRateChart(result.body)
                }else{
                    console.log('Sin datos Tasa Des Empleo ')
                }
            }else{
                console.log(result)
            }
        }
        function drawUnEmploymentRateChart(result){
            let sets = [],labels=[];
            for(let x in result){
                sets.push(result[x].json.Total);
                labels.push(result[x].year+' - '+formatQuarter(result[x].quarter))
            }
            console.log(sets);
            ConfigMiniLine.data.labels=labels;
            ConfigMiniLine.data.datasets=[{
                borderColor: rndBgColor(labels),
                data: sets,
            }];

            let ctxUnEmploymentRate=document.getElementById('unemployment-rate').getContext('2d');
            if(ctxUnEmploymentRate){
                ConfigMiniLine.options.plugins.legend.display=false;
                ConfigMiniLine.options.plugins.title.text='Tasa de Desocupación ';

                chartUnEmploymentRate= new Chart(ctxUnEmploymentRate, ConfigMiniLine);

            }
        }

        window.onload=function(){
            init();
        };


    </script>


</section>