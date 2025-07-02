@section('title',' Arribos Histórico ')
<section class="h-full overflow-y-auto p-2">
    <div class="h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="w-full md:w-2/3 h-[60vh]  md:h-full bg-white rounded-lg shadow-lg flex items-center p-4">
            <canvas  id="chartHistorical" style="width: 100%; height: 100%;"></canvas>
        </div>
        <div class="w-full md:w-1/3 md:h-full ">
            <div class="flex flex-wrap">
                <div class="p-6 md:text-xl xl:text-2xl col-start-1 col-span-12 text-2xl text-center bg-white rounded-lg shadow-lg">
                    <ul>
                        <li>De los Arribos Historico al Aeropuerto Internacional de la ciudad de Mérida</li>
                    </ul>
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
        let  chartHistoricalLine;
        ConfigLine.options.scales={
            y:{grid:{ color:'white' },ticks: { display:false, font: { size: 7  }}},
            x:{grid:{color:'white'}}
        };
        function exportChart(){
            exportCanva(document.getElementById('chartHistorical'));
        }

        async function getHistorical(){
            today.setFullYear(today.getFullYear() - 3)
            let Y = today.getFullYear();
            let body = {year: Y};
            let URL = host + "/api/v1/tourism/arrivals/historical?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'GET'
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                    collection=result.body;
                console.log(collection)
                drawChartHistorical();
            }
        }

        function drawChartHistorical(){
            const ctxHistorical=document.getElementById('chartHistorical').getContext('2d');
            let rgb,labels=[], datasets=[];
            let totals=[], internationals=[], domestics=[];
            collection.map(item => {
                labels.push(item.int_year);
                totals.push(item.total);
                domestics.push(item.domestic)
                internationals.push(item.international)
            })
            rgb=rndBgColor([1])
            datasets.push({backgroundColor:rgb,
                borderColor: rgb,
                label:'Domestic',
                data: domestics,
            });
            rgb=rndBgColor([1])
            datasets.push({backgroundColor:rgb,
                borderColor: rgb,
                label:'International',
                data: internationals,
            });
            rgb=rndBgColor([1])
            datasets.push({backgroundColor:rgb,
                borderColor: rgb,
                label:'Total',
                data: totals,
            });


            ConfigLine.options.pointRadius=5;
            ConfigLine.options.tension=0;
            ConfigLine.options.layout= {padding: 25};
            ConfigLine.options.plugins.legend.display=true;
            ConfigLine.options.plugins.legend.position='bottom';
            ConfigLine.options.plugins.title.text = 'ARRIBOS HISTÓRICO '+labels[0]+" a "+labels[labels.length-1];
            ConfigLine.options.plugins.datalabels.font={
                weight: 'bold',
                size:12
            };
            ConfigLine.options.plugins.datalabels.display='auto';
            ConfigLine.options.plugins.datalabels.padding={bottom:15};
            ConfigLine.data.labels = labels;
            ConfigLine.data.datasets=datasets;

            chartHistoricalLine= new Chart(ctxHistorical, ConfigLine);
        }

        function init(){
            getHistorical();
        }
        window.onload=function(){
            init();
        };

    </script>
</section>

