@section('title',' Arribos al Aeropuerto de Merida')
<section class="h-full overflow-y-auto p-2">
    <div class="h-full flex  flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="order-2 md:order-1 w-full md:w-2/3 bg-white rounded-lg shadow-lg p-6 flex items-center justify-center max-h-full h-full " wire:ignore>
            <canvas  id="chart" style="width: 100%; height: 100%;"></canvas>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/3 flex flex-wrap space-y-2 ">
            <div class="w-full p-4 text-base md:text-lg text-center bg-white rounded-lg shadow" wire:ignore >
                <p>Para el año <strong id="current-interval" class="uppercase"></strong> se desagregaron los datos por el tripo de arribo al aeropuerto, entre nacionales e internacionales</p>
                <p>Por cada 100 turistas que arriban al aeropuerto de Merida</p>
            </div>
            <div class="w-full text-center " >
                <p class="w-full font-bold text-red-500 p-2 text-center" id="alert" wire:ignore></p>
                <div class=" w-full flex flex-wrap items-center h-[30vh] md:h-full overflow-x-auto  rounded-lg shadow p-2 md:p-0 md:shadow-none border md:border-0">
                    <livewire:month-calendar />
                </div>
            </div>
            <div class="w-full flex justify-center">
                <div class="py-2">
                    <button class=" py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white"
                            onclick="exportArrivals()">
                        <i class="fas fa-file-download"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>

let Labels=['Nacionales','Internationales'];
let ctx=document.getElementById('chart');
let pieArrivals;
let Data=[];

function selectMonth(Y,M){
    setTimeInterval(Y, M);
    if(pieArrivals) pieArrivals.destroy();
    getArrivals();
}

function selectMonthType2(Y, M){
    /*setTimeInterval(Y, M);  */

    date_start='2022-12-31';
    date_start='2022-12-1';

    if(pieArrivals) pieArrivals.destroy();
    getArrivals();
}

function exportArrivals()
{
    exportCanva(ctx);
}

async function getArrivals(){
       const response = await fetch(Url + "/tourism/arrivals?api_key=" + Api_key, {
           method: 'POST',
           body: JSON.stringify({start:date_start, end:date_end}),
           headers: {"Content-type": "application/json;charset=UTF-8"}
       });

    if (response.status===200) {
        const result = await response.json();
        console.log(result)
        if(result.body!==null) {
            Data = result.body;
            document.getElementById('alert').innerHTML = '';
            drawArrivals();
        }
    } else {
        document.getElementById('alert').innerHTML = 'Intervalo Vacio';
        console.log(response);
    }
}

function drawArrivals(){
    let data=[Data.Domestic,Data.International];
    if(ctx){
       ConfigPie.data.labels=['Domestic','International'];
       ConfigPie.options.plugins.title.text='ARRIBOS AL AEROPUERTO DE MERIDA ' + Interval;
       ConfigPie.data.datasets=[
           {
               data:data,
               backgroundColor: rndBgColor(data),
           }
       ];

       ConfigPie.options.plugins.datalabels.font.size=16;
        ConfigPie.options.plugins.legend={
            position:'bottom',
            display: true,
        },

      pieArrivals = new Chart(ctx,ConfigPie);
   }
}


window.onload=function(){
    today.setMonth(today.getMonth() - 1 );
    selectMonth(today.getFullYear(),today.getMonth() + 1);
};

    </script>
</section>

