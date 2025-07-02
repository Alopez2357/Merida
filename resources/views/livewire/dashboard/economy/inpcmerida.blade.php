@section('title',' Indice Nacional de Precios del Consumidor')
<section class="w-full h-full overflow-y-auto p-2">
    <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 space-x-2">
            <div class=" w-full min-h-[50vh] md:w-2/3  bg-white rounded-lg shadow-lg flex items-center md:p-4 w-150-ps">
                <canvas id="chartInpcMerida" style="width: 100%; height: 100%;"></canvas>
            </div>
        <div class=" w-full md:w-1/3">
            <div class="flex flex-wrap">
                <div class="w-full   p-4  text-lg md:text-xl xl:text-2xl  text-2xl text-center bg-white rounded-lg shadow-lg">
                    <p>Comportamiento de la variable de <span id="interval"></span></p>
                    <p>El mes de <span class="font-bold" id="month"></span></p>
                    <p class="text-3xl text-blue-500" id="data"></p>
                </div>
                <div class="w-full flex justify-center items-center py-4">
                    <button onclick="exportChart()"
                            class="text-lg mx-auto text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow hover:bg-green-500 py-2 px-4">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let collections=[];
        let chartInpcMeridaLine;
        function init(){
            getInpcMerida();
        }
        function exportChart(){
            exportCanva(document.getElementById('chartInpcMerida'));
        }

        async function getInpcMerida() {
            today.setFullYear(today.getFullYear() - 3)
            let Y = today.getFullYear();
            let body = {year: Y};
            let URL = host + "/api/v1/economy/inpc-merida?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'get'
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                    collections=result.body;
                drawChart();
            }else{
                console.log(response)
            }
        }

        function drawChart(){
            const ctxInpcMerida=document.getElementById('chartInpcMerida').getContext('2d');
            let rgb;
            let labels=[];
            let datasets=[];
            let sets=[];
            rgb = rndBgColor([1]);
            collections.map(item => {
                labels.push(item.key)
                sets.push(item.total)
            });

            datasets=[{
                backgroundColor:rgb,
                borderColor: rgb,
                label:labels,
                data: sets,
            }];

            ConfigLine.options.pointRadius=5;
            ConfigLine.options.tension=0;
            ConfigLine.options.plugins.legend.display=false;
            ConfigLine.options.plugins.legend.position='bottom';
            ConfigLine.options.plugins.title.text = ' Inflacíon Mérida ';
            ConfigLine.options.plugins.datalabels.display=true;
            ConfigLine.data.labels = labels;
            ConfigLine.data.datasets=datasets;
            chartInpcMeridaLine= new Chart(ctxInpcMerida, ConfigLine);
            document.getElementById('interval').innerHTML=collections[0].key+' a '+collections[collections.length-1].key;
            let data=collections[collections.length-1];
            document.getElementById('month').innerHTML=data.key;
            document.getElementById('data').innerHTML=data.total;
        }

        window.onload=function(){
            init();
        };

    </script>
</section>



