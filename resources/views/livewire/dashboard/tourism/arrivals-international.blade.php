@section('title','Distribución de Pasajeros Internacional')
<section class="w-full h-full overflow-y-auto p-2">
    <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="order-2 md:order-1 w-full md:w-2/3 h-[50vh] md:h-full p-2 bg-white rounded-lg shadow-lg flex items-center justify-center" wire:ignore>
            <canvas class="w-full h-auto"  id="chartOrigin" ></canvas>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/3">
            <div class="flex flex-wrap space-y-2">
                <div class="w-full p-2 text-base md:text-lg xl:text-xl   text-center bg-white rounded-lg shadow"  wire:ignore >
                    <ul class="text-center">
                        <li class="font-bold text-red-500 uppercase">Llegadas Internationales</li>
                        <li class="font-semibold" id="interval-start"></li>
                        <li class="uppercase">a</li>
                        <li class="font-semibold" id="interval-end"></li>
                        <li class="uppercase">ASUR Mérida</li>
                    </ul>
                </div>
                <div class="w-full  uppercase">
                    <div class="w-full flex flex-wrap">
                        <p class="w-full font-bold text-red-500 pb-2 text-center" id="alert" wire:ignore></p>
                        <div class="w-full rounded-lg overflow-x-auto h-[20vh] md:h-full  p-2 shadow-inner md:shadow-none">
                            <livewire:month-calendar />
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="w-full p-2 flex justify-center">
                        <button onclick="exportChart()"
                                class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                            <i class="fas fa-file-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        let interval;
        let collection=[];
        let  chartOriginPie;
        function exportChart(){
            exportCanva(document.getElementById('chartOrigin'));
        }

        function selectMonth(Y,M){
            setTimeInterval(Y,M);
            resetChart(chartOriginPie);
            getOrigin();
        }

        async function getOrigin(){
            let body = {'national':false,'start':date_start,'end':date_end};
            let URL = host + "/api/v1/tourism/movements/arrives?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'POST',
                body:JSON.stringify(body),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.status===200) {
                const result = await response.json();
                if(result.body.length){
                    interval=result.interval;
                    collection=result.body;
                    drawChartOrigin();
                    document.getElementById('alert').innerHTML = '';
                    document.getElementById('interval-start').innerHTML=interval.start;
                    document.getElementById('interval-end').innerHTML=interval.end;
                }else{
                    document.getElementById('alert').innerHTML = 'Intervalo Vacio';
                }
            }else{
                console.log(response);
            }
        }

        function drawChartOrigin(){
            const ctxOrigin=document.getElementById('chartOrigin').getContext('2d');
            let labels=[];
            let data=[];
            for(let i=0; i < collection.length; ++i){
                labels.push(collection[i].province+" "+numberWithCommas(collection[i].passengers));
                data.push(collection[i].passengers);
            }
            ConfigPie.data.labels=labels;
            ConfigPie.options.plugins.title.text='LLEGADAS INTERNACIONALES ';
            ConfigPie.options.plugins.subtitle={text:interval.start+ ' a '+interval.end, display: true};
            ConfigPie.options.plugins.datalabels.font={weight: 'bold', size:14};
            ConfigPie.data.datasets=[{data: data,backgroundColor: rndBgColor(data)}];
            chartOriginPie= new Chart(ctxOrigin, ConfigPie);
        }

        window.onload=function(){
            today.setMonth(today.getMonth()-1)
            selectMonth(today.getFullYear(),today.getMonth()+1);
        };

    </script>
</section>

