@section('title','  Ocupación y Empleo Mérida')
<section class="h-full overflow-y-auto p-2 ">
    <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-4 md:space-y-0 md:space-x-2 w-150-ps">
        <div class=" w-full md:w-2/3 h-[75vh] md:h-full py-2 md:py-0  flex items-center w-150-ps">
            <div class="bg-white rounded-lg shadow-lg w-full h-full p-6 border flex items-center">
                <canvas id="chart" class="w-full h-full"></canvas>
            </div>
        </div>
        <div class=" w-full  md:w-1/3 flex flex-wrap space-y-2 w-50-ps">
            <div class="w-full">
                <div class="w-full bg-white rounded-lg shadow-lg p-4 ">
                    <ul class="list-disc list-inside text-lg">
                        <li>Comportamiento Variable en el ultimo trimestre</li>
                        <li>Datos obtenidos de la Encuesta Nacional de Ocupación y Empleo del INEGI</li>
                    </ul>
                </div>
                <div class="w-full flex justify-center py-2">
                    <button onclick="exportChart()" class=" mx-auto py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let collection=[];
        let  chartOccupationBars;
        let ctxOccupation;
        function exportChart(){
            exportCanva(document.getElementById('chart'));
        }

        function init(){

            getOccupation();
        }

        async function getOccupation(){
            ctxOccupation = document.getElementById('chart').getContext('2d');
            if (chartOccupationBars) chartOcupationtypeBars.destroy();
            let body = {"type":'Llegada'};
            let URL = host + "/api/v1/economy/employment/history?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'GET',
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null){
                   drawChartOccupation(result.body);
                }
            }
        }

        function drawChartOccupation(result) {
            let labels=[], hombres=[], mujeres=[];
            for (let x in result){
                labels.push(x);
                hombres.push(result[x].Hombres);
                mujeres.push(result[x].Mujeres);
            }
             let data= [{
                    label: 'Hombres',
                    data:  hombres,
                    backgroundColor:  rndBgColor([1])
                }, {
                    label: 'Mujeres',
                    data:  mujeres,
                    backgroundColor:  rndBgColor([1]),
                }];

            ConfigStacked.data.labels = labels;
            ConfigStacked.data.datasets=data;

            //ConfigStacked.options.plugins.title.text = "OCUPACIÓN Y EMPLEO MERIDA "+labels[0]+" a "+labels[labels.length-1];
            ConfigStacked.options.plugins.title.text = "POBLACIÓN TOTAL "+labels[0]+" a "+labels[labels.length-1];
            ConfigStacked.options.plugins.subtitle={text:'personas', display: true, padding: 10};

            chartOccupationBars =new Chart(ctxOccupation, ConfigStacked);
        }


        window.onload=function(){
            init();
        };

    </script>
</section>

