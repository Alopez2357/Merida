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
@section('title','  Movimiento operacional')

<section class="h-full overflow-y-auto p-2">
    <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="div-overflow">
            <div class="w-full md:w-2/3 h-[50vh] md:h-full bg-white rounded-lg p-2 shadow-lg flex items-center w-150-ps">
                <canvas id="chartOperational" class="w-full h-full"  ></canvas>
            </div>
        </div>
        <div class=" w-full  md:w-1/3 h-full">
            <div class="w-full flex flex-wrap space-y-4">
                <div class="w-full text-start p-4 md:text-base xl:text-lg text-center bg-white rounded-lg shadow">
                    <ul class="list-disc list-inside">
                        <li>Comportamiento Variable <br><span class="font-semibold" id="interval"></span></li>
                        <li>Datos obtenidos ASUR Mérida</li>
                        <li>EL <span id="sum" class="font-semibold"></span>% de las operaciones del Areopuerto fueron <span class="font-semibold lowercase" id="type"></span></li>
                    </ul>
                </div>
                <div class="w-full bg-white rounded-lg shadow p-4 ">
                    <div class="w-full h-full">
                        <label for="" class="w-full font-semibold">Operación:</label>
                        <select onchange="getOperational()" class="w-full rounded-lg border border-gray-200" name="" id="operation">
                            <option value="Llegada">Llegada</option>
                            <option value="Salida">Salida</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap w-full ">
                    <div class="w-full flex items-start justify-center">
                        <button onclick="exportChart()" class="mx-auto py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                            <i class="fas fa-file-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let collection=[];
        let  chartOperationalBars;
        let operation;
        function exportChart(){
            exportCanva(document.getElementById('chartOperational'));
        }

        async function getOperational(){
            operation=document.getElementById('operation').value;
            let body = {"type":operation};
            let URL = host + "/api/v1/tourism/movements/operational?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'POST',
                body:JSON.stringify(body),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                    collection=result.body;
                drawChartOperational();
            }
        }

        function drawChartOperational(){
            const ctxOperational=document.getElementById('chartOperational').getContext('2d');
            if(chartOperationalBars) chartOperationalBars.destroy();
            let rgb, labels=[],data_labels=[], datasets=[];
            for (let x in collection.labels) {
               labels.push(collection.labels[x])
            }
            for (let x in collection.data_labels) {
                let data=[];
                let mydata=collection.datas[collection.data_labels[x]];
                for(let y in mydata){
                    data.push(mydata[y])
                }
                datasets.push({
                    label:collection.data_labels[x],
                    backgroundColor: rndBgColor([collection.data_labels[x]]),
                    // borderColor: window.chartColors.red,
                    borderWidth: 1,
                    data: data,
                });
            }

            let interval=labels[0]+" a "+labels[labels.length-1]

            document.getElementById('interval').innerHTML=interval;
            document.getElementById('type').innerHTML=operation+'s';
            document.getElementById('sum').innerHTML=collection.sum;

            ConfigBars.data.labels = labels;
            ConfigBars.data.datasets=datasets;
            ConfigBars.options.responsive=true;
            ConfigBars.options.plugins.title.text = ' '+operation+'s '+interval;
            ConfigBars.options.plugins.legend={display: 'auto',position: 'bottom'};

            chartOperationalBars= new Chart(ctxOperational, ConfigBars);
        }

        function init(){
            getOperational();
        }
        window.onload=function(){
            init();
        };

    </script>
</section>

