<script src="{{ asset('js/mobile-script.js') }}"></script>
@section('title','DISTRIBUCIÓN POR TIPO DE UNIDAD ECONÓMICA')
<div class="h-full overflow-y-auto p-2 flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
    <div class="order-2 md:order-1 w-full md:w-2/3 h-[50vh] md:h-full p-2 bg-white rounded shadow flex items-center"  wire:ignore style="position: relative;">
        <h1 class="capa">Selecciona un filtro</h1>
        <canvas class="w-full h-full"  id="chart" ></canvas>
    </div>
    <div class="order-1 md:order-2 w-full md:w-1/3 flex-mobile-box flex-mobile-box-active">
        <div class="w-full h-[30vh] md:h-[50vh]  overflow-x-auto shadow-inner p-1 rounded">
            <livewire:trimester-calendar />
        </div>
        <div class="w-full text-center ">
            <p class="text-red-500  font-bold p-2" id="alert"></p>
        </div>
        <div class="w-full flex justify-center">
            <div class="py-2">
                <button class=" py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white"
                        onclick="exportChart()">
                    <i class="fas fa-file-download"></i>
                    Exportar
                </button>
            </div>
        </div>

    </div>
    <div class="btn-display-flex-mobile-box py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white" onclick="toggleFlexMobileBox()">
            Filtros
    </div>
</div>
<script>
    let Y, Q;
    let  chartDeparturesPie;
    let chart, ctx;
    let title='3.7 Distribución por tipo de unidad económica';
    function exportChart()
    {
        exportCanva(document.getElementById('chart'));
    }

    function selectQuarter(y,q){
        Y=y;
        Q=q;
        ctx=document.getElementById('chart').getContext('2d');
        if( chart)  chart.destroy();
        getData()
    }

    async function getData(){
        const response = await fetch( host+'/api/v1/economy/employment/data?api_key='+Api_key, {
            method: 'post',
            body:JSON.stringify({year:Y,quarter:Q,key:title}),
            headers: {"Content-type": "application/json;charset=UTF-8"}
        });
        if (response.ok) {
            const result = await response.json();
            if (result.body.length){
                if(document.getElementById('alert')) document.getElementById('alert').innerHTML='';
                drawChart(result.body);
            }else{
                if(document.getElementById('alert')) document.getElementById('alert').innerHTML='Intervalo Vacio';
            }
        }else{
            console.log(response)
        }
    }

    function drawChart(result){
        let labels=[], datasets=[], hombres=[], mujeres=[];
        document.querySelector('.capa').style.display = 'none';
        for(let x in result) {
            labels.push(result[x].title);
            hombres.push(result[x].attr.Hombres);
            mujeres.push(result[x].attr.Mujeres);
        }

        datasets=[{
            label: 'Hombres',
            data:hombres,
            backgroundColor: rndBgColor([1])
        },{
            label: 'Mujeres',
            data: mujeres,
            backgroundColor: rndBgColor([1])
        }];

        ConfigHorizontalEmployment.data.labels=labels;
        ConfigHorizontalEmployment.data.datasets=datasets;

        ConfigHorizontalEmployment.options.plugins.title={display: true,text: title, padding: 10};
        ConfigHorizontalEmployment.options.plugins.subtitle={ display: true, text:Y+' - '+formatQuarter(Q), padding: 10};

        chart = new Chart(ctx,ConfigHorizontalEmployment);
    }


</script>
</section>

