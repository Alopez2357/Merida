@section('title','Distribución de Pasajeros Salidas Internacionales')
<section class="w-full h-full overflow-y-auto p-2">
        <div class="w-full h-full flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
            <div class="order-2 md:order-1 w-full md:w-2/3 h-[50vh] md:h-full p-4 bg-white rounded-lg shadow flex items-center justify-center " wire:ignore>
                <canvas  id="chartDepartures" style="width: 100% ; height: 100%"></canvas>
            </div>
            <div class="order-1 md:order-2 w-full md:w-1/3 ">
                <div class="w-full flex flex-wrap">
                    <div class="w-full p-2 text-base md:text-lg xl:text-xl  text-center bg-white rounded-lg shadow" wire:ignore >
                        <ul class="text-center">
                            <li class="font-bold text-red-500">Salidas Internacionales</li>
                            <li class="font-semibold" id="interval-start"></li>
                            <li class="uppercase">a</li>
                            <li class="font-semibold" id="interval-end"></li>
                            <li class="uppercase">ASUR Mérida</li>
                        </ul>
                    </div>
                    <div class="w-full  text-center  uppercase">
                        <div class="w-full flex flex-wrap">
                            <p class="w-full font-bold text-red-500 p-2 text-center" id="alert" wire:ignore></p>
                            <div class="w-full rounded-lg overflow-x-auto h-[20vh] md:h-full  p-2 shadow-inner md:shadow-none">
                                <livewire:month-calendar />
                            </div>
                        </div>
                    </div>
                    <div class="w-full ">
                        <div class="w-full p-4 flex justify-center">
                            <buton onclick="exportChart()" class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                                <i class="fas fa-file-download"></i> Exportar
                            </buton>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        let interval;
        let collection=[];
        let  chartDeparturesPie;
        function exportChart(){
            exportCanva(document.getElementById('chartDepartures'));
        }
        function selectMonth(Y,M){
            setTimeInterval(Y,M);
            resetChart(chartDeparturesPie);
            getDepartures();
        }

        async function getDepartures(){

            let body = {'national':false,'start':date_start,'end':date_end};
            let URL = host + "/api/v1/tourism/movements/departures?api_key=" + Api_key;
            const response = await fetch(URL, {
                method: 'POST',
                body:JSON.stringify(body),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.status===200) {
                const result = await response.json();
                if (result.body.length) {
                    interval = result.interval;
                    collection = result.body;
                    drawChartDepartures();
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

        function drawChartDepartures(){
            const ctxDepartures=document.getElementById('chartDepartures').getContext('2d');
            if( chartDeparturesPie)  chartDeparturesPie.destroy();
            let labels=[];
            let data=[];
            for(let i=0; i < collection.length; ++i){
                labels.push(collection[i].province+" "+numberWithCommas(collection[i].passengers));
                data.push(collection[i].passengers);
            }
            ConfigPie.data.labels=labels;
            ConfigPie.options.plugins.title.text='SALIDAS INTERNACIONALES';
            ConfigPie.options.plugins.subtitle={text:interval.start+ ' a '+interval.end, display: true};
            ConfigPie.options.plugins.datalabels.font={weight: 'bold', size:14};
            ConfigPie.data.datasets=[{data: data,backgroundColor: rndBgColor(data)}];
            chartDeparturesPie= new Chart(ctxDepartures, ConfigPie);
        }

        window.onload=function(){
            today.setMonth(today.getMonth()-1)
            selectMonth(today.getFullYear(),today.getMonth()+1);
        };

    </script>
</section>

