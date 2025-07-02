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
@section('title',' Arribos Mensual ')
<section class="h-full overflow-y-auto p-2">
    <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="div-overflow">
            <div class="w-full md:w-2/3 h-[50vh] md:h-full p-4 bg-white rounded-lg shadow-lg flex items-center w-150-ps">
                <canvas  id="chartMonthly" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
        <div class="w-full md:w-1/3">
            <div class="flex flex-wrap">
                <div class="p-6 md:text-xl xl:text-2xl col-start-1 col-span-12 text-2xl text-center bg-white rounded-lg shadow-lg">
                    <ul>
                        <li>De los arribos Mensuales al Aeropuerto Internacional de la ciudad de Mérida</li>
                    </ul>
                </div>
                <div class="my-5 w-full flex flex-wrap items-center mx-auto ">
                    <button onclick="exportChart()"
                            class="mx-auto text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow hover:bg-blue-500 py-2 px-4">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let collection=[];
        let  chartMonthlyLine;
        function exportChart(){
            exportCanva(document.getElementById('chartMonthly'));
        }

        async function getMonthly(){
            today.setFullYear(today.getFullYear() - 3)
            let Y = today.getFullYear();
            let body = {year: Y};
            let URL = host + "/api/v1/tourism/arrivals/monthly?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'GET'
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                    collection=result.body;
                drawChartMonthly();
            }
        }

        function drawChartMonthly(){
            const ctxMonthly=document.getElementById('chartMonthly').getContext('2d');
            let rgb;
            let labels=[];
            let datasets=[];
            let totals=[], internationals=[], domestics=[];
            collection.map(item => {
                labels.push(item.str_month+" "+item.int_year);
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
            ConfigLine.options.plugins.legend.display=true;
            ConfigLine.options.plugins.legend.position='bottom';
            ConfigLine.options.plugins.title.text = 'ARRIBOS MENSUAL ';
            ConfigLine.options.plugins.datalabels.display=true;
            ConfigLine.data.labels = labels;
            ConfigLine.data.datasets=datasets;
            chartMonthlyLine= new Chart(ctxMonthly, ConfigLine);
        }

        function init(){
            getMonthly();
        }
        window.onload=function(){
            init();
        };

    </script>
</section>

