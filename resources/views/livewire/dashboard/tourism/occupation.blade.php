<style>
    @media (min-width: 768px) {
        .w-150-ps{
            width: 100%;
        }
    }
    @media (max-width: 767px) {
        .w-150-ps{
            width: 200%;
        }
        .div-overflow{
            overflow:auto;
        }
        .w-50-ps{
            width: 50%;
        }
    }
    @media (min-width: 768px) and (max-width: 1023px) {
    
    }
    .div-overflow{
            width:100%;
    }
</style>
@section('title',' Ocupación Hotelera Mensual Merida ')
<section class="w-full h-full overflow-y-auto p-2">
    <div class="h-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="div-overflow">
            <div class="w-full md:w-2/3 h-[60vh] md:h-full   bg-white rounded-lg shadow-lg flex items-center p-4 w-150-ps">
                <canvas  id="chartOccupation" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
        <div class="w-full  md:w-1/3">
            <div class="flex flex-wrap">
                <div class="p-6 md:text-xl xl:text-2xl col-start-1 col-span-12 text-2xl text-center bg-white rounded-lg shadow-lg">
                    <h2 class="text-xl">De los Arribos Historico al Aeropuerto Internacional de la ciudad de Mérida</h2>
                    <h3 id="interval" class="text-xl font-bold"></h3>

                </div>
                <div class="my-5 w-full flex flex-wrap items-center mx-auto ">
                    <button onclick="exportChart()"
                            class="mx-auto text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow hover:bg-green-500 py-2 px-4">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let collection=[];
        let  chartOccupationLine;
        function exportChart(){
            exportCanva(document.getElementById('chartOccupation'));
        }

        async function getOccupation(){
            today.setFullYear(today.getFullYear() - 3)
            let URL = host + "/api/v1/tourism/occupation?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'POST',
                body: JSON.stringify({since_year: today.getFullYear()}),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                    collection=result.body.data;
                drawChartOccupation();
            }
        }

        function drawChartOccupation(){
            const ctxOccupation=document.getElementById('chartOccupation').getContext('2d');
            let rgb, labels=[], datasets=[];
            console.log(collection)
            collection.map(item => {
                rgb = rndBgColor([item.year]);
                let months=[];
                item.data.map(d=>{
                    months.push(d.percent);
                    labels.push(d.month);
                })
                datasets.push({
                    backgroundColor:rgb,
                    borderColor: rgb,
                    label:item.year,
                    data: months,
                })
            })

            labels=labels.filter((item,index) => labels.indexOf(item) === index);

            let interval=collection[0].year+' a '+collection[collection.length-1].year;
            ConfigLine.options.pointRadius=5;
            ConfigLine.options.tension=0;
            ConfigLine.options.plugins.legend.display=true;
            ConfigLine.options.plugins.legend.position='bottom';
            ConfigLine.options.plugins.title.text = ' OCUPACIÓN HOTELERA MENSUAL MERIDA '+interval;
            ConfigLine.options.plugins.datalabels.display=true;
            ConfigLine.data.labels = labels;
            ConfigLine.data.datasets=datasets;
            chartStopoverLine= new Chart( ctxOccupation, ConfigLine);
            document.getElementById('interval').innerHTML=interval;
            /*let data=collections[collection.length-1].data;
            document.getElementById('month').innerHTML=data[data.length-1].month;
            document.getElementById('data').innerHTML=data[data.length-1].total;*/
        }

        function init(){
            getOccupation();
        }
        window.onload=function(){
            init();
        };

    </script>
</section>
</section>

