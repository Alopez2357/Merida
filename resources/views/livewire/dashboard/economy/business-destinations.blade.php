@section('title',' Destinos Comerciales Mérida')
<section class="h-full overflow-y-auto md:p-2">
    <div class="h-full flex  flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="order-2 md:order-1 w-full md:w-4/5 h-[50%] md:h-full bg-white rounded-lg shadow-lg p-2 flex items-center justify-center max-h-full h-full " wire:ignore>
            <canvas  id="chart" class="w-full h-full"></canvas>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/5 h-[50%]">
            <div class="w-full max-h-[50vh]">
                <p class="w-full font-bold text-red-500 px-2 text-center" id="alert" wire:ignore></p>
                <div class="border w-full flex flex-wrap items-center max-h-[50vh] md:h-full overflow-x-auto  rounded-lg shadow p-1 md:p-0 md:shadow-none border md:border-0">
                    <livewire:month-calendar />
                </div>
            </div>
            <div class="w-full  flex space-y-2">
                <div class="w-full flex justify-center items-center py-2">
                    <button class=" py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white"
                            onclick="exportChart()">
                        <i class="fas fa-file-download"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo@4.1.2/build/index.umd.min.js"></script>
    <script>

        let myChart;
        let countries;
        let info;
        let year='', month='';
        let ctx=document.getElementById('chart');
        function init(){
            getGeoInfo();
        }

        function exportChart(){
            exportCanva(ctx);
        }

        function selectMonth(Y,M){
            year=Y;
            month=M;
            if(myChart) myChart.destroy();
            getGeoInfo();
        }

        async function getGeoInfo(){
            const response = await fetch('https://cdn.jsdelivr.net/npm/world-atlas@2.0.2/countries-50m.json');

            if(response.ok){
                const result = await response.json();
                countries = ChartGeo.topojson.feature(result,result.objects.countries).features
                getData();

            }
        }

        async function getData(){
            const response = await fetch(host + "/api/v1/economy/business-destinations?api_key="+Api_key)

            if(response.ok) {
                const result = await response.json();
                if(result.body.length) {
                    drawChart(result.body);
                }else{
                    if(document.getElementById('alert'))document.getElementById('alert').innerHTML='Intervalo Vacio';
                }
            }
        }

        function drawChart(info)
        {
            let labels=[],datas=[], bgColors=[];
            for(let x in countries) {
                labels.push(countries[x].properties.name);
                let value =0;
                let color='#d2d2d2';
                for(let y in info){
                    if(countries[x].id===info[y].country_id){
                        value=info[y].share;
                        color=rndBgColor([1]);
                        break;
                    }
                }
                datas.push({feature: countries[x], value: value});
                bgColors.push(color);
            }

            configGeoMap.data={
                labels:labels,
                datasets: [{
                    label: 'Destinos comerciales',
                    data:datas,
                    backgroundColor:bgColors,

                }]
            };


            configGeoMap.options.plugins.title.text='Destinos Comerciales '+year+' '+getMonthShortName(month);

            // render init block
            myChart = new Chart( ctx, configGeoMap  );
        }



        window.onload=function(){
            init();
        };



    </script>
</section>