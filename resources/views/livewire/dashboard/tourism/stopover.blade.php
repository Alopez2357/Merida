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

@section('title',' Llegadas de turistas con pernocta')
<section class="w-full h-full overflow-y-auto p-2">
        <div class="w-full h-full flex flex-wrap md:flex-nowrap md:space-x-2 space-y-2 md:space-y-0">
            <div class="div-overflow">
                <div class="w-full md:w-2/3 h-[50vh] md:h-full p-4   bg-white rounded-lg shadow-lg flex items-center w-150-ps">
                    <canvas id="chartStopover" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <div class="flex flex-wrap">
                    <div class="w-full   p-4  text-lg md:text-xl xl:text-2xl  text-2xl text-center bg-white rounded-lg shadow-lg">
                        <p>Comportamiento de la variable de <span id="interval"></span></p>
                        <p>El mes de <span class="font-bold" id="month"></span> llegaron (turistas):</p>
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
let  chartStopoverLine;

function exportChart(){
    const ctxStopover=document.getElementById('chartStopover');
    exportCanva(ctxStopover);
}

async function getStopovers() {
    today.setFullYear(today.getFullYear() - 3)
    let Y = today.getFullYear();
    let body = {year: Y};
    let URL = host + "/api/v1/tourism/stopover?api_key=" + Api_key;
    const response = await fetch(URL, {
        method: 'POST',
        body: JSON.stringify(body),
        headers: {"Content-type": "application/json;charset=UTF-8"}
    });
    if (response.ok) {
        const result = await response.json();
        if (result.body !== null){
            collections = result.data;
            drawChart();
        } else {
            console.log(result)
        }
    }else{
        console.log('Error')
    }
}

function drawChart(){
    const ctxStopover=document.getElementById('chartStopover').getContext('2d');
    let rgb;
    let labels=[];
    let datasets=[];
    collections.map(item => {
        rgb = rndBgColor([item.year]);
        let months=[];
        item.data.map(d=>{
            months.push(d.total);
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

    ConfigLine.data.labels = labels;
    ConfigLine.data.datasets=datasets;

    ConfigLine.options.pointRadius=5;
    ConfigLine.options.tension=0;
    ConfigLine.options.plugins.legend.display=true;
    ConfigLine.options.plugins.legend.position='bottom';
    ConfigLine.options.plugins.title.text = 'LLEGADAS DE TURISTAS CON PERNOCTA ';
    ConfigLine.options.plugins.subtitle={padding:10,display: true, text: collections[0].year+' a '+collections[collections.length-1].year};
    ConfigLine.options.plugins.datalabels.display=true;

    document.getElementById('interval').innerHTML=collections[0].year+' a '+collections[collections.length-1].year;
    let data=collections[collections.length-1].data;
    document.getElementById('month').innerHTML=data[data.length-1].month;
    document.getElementById('data').innerHTML=data[data.length-1].total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");


    chartStopoverLine= new Chart(ctxStopover, ConfigLine);

}

function init(){
    getStopovers();
}
window.onload=function(){
    init();
};

    </script>
</section>

