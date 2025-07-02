@section('title','Medición Pobreza')
<section class="h-full overflow-y-auto p-2">
    <div class="h-full xl:container flex flex-wrap space-y-2" wire:ignore>
        <div class="w-full h-auto p-2 bg-white rounded-lg shadow-lg flex flex-wrap items-center">
            <div class="flex w-full md:w-1/3 justify-center md:justify-start items-center">
                <img class="w-1/2 h-auto" src="{{ asset('img/coneval.png') }}" alt="coneval">
            </div>
            <div class="w-full md:w-1/3 flex flex-wrap justify-end">
                <div class="w-full p-2">
                    <select onchange="getConeval()" class="w-full rounded-lg border border-gray-200" name="" id="select">
                        <option value="1">Carencias Sociales</option>
                        <option value="2">Pobreza</option>
                    </select>
                </div>
            </div>
            <div class="w-full md:w-1/3 flex justify-center ">
                    <button onclick="exportChart()" class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
            </div>
        </div>
        <div class="w-full h-full md:h-[80vh] md:px-6 bg-white rounded-lg shadow-lg flex flex-wrap items-center">
                    <canvas   id="chartConeval"  style="width: 100%; height: 100%;"></canvas>
            </div>
    </div>
    <script>
        let title='Carencias Sociales';
        let collection=[];
        let  chartConeval;
        function exportChart(){
            exportCanva(document.getElementById('chartConeval'));
        }
        async function getConeval() {
            let  val=1;
            let select=document.getElementById('select');
            val=select.value;
            title=select.options[select.selectedIndex].text;
            let URL=host;
            switch (val) {
                case '1':
                    URL += "/api/v1/economy/coneval/deficiencies?api_key=";
                break;
                case '2':
                    URL += "/api/v1/economy/coneval/poverty?api_key=";
                break;
                default:
                    console.log(`Sorry, we are out.`);
                break;
            }
            const response = await fetch( URL+Api_key, {
                method: 'get'
            });
            if (response.ok) {
                const result = await response.json();
                if (result.body !== null)
                collection=result.body;
                drawChartConeval();
            }
        }

        function drawChartConeval(){
            const ctxConeval=document.getElementById('chartConeval').getContext('2d');
            if( chartConeval)  chartConeval.destroy();
            let labels=Object.values(collection.labels), datasets=[];
            for(let x in collection.data){
                let data=Object.values(collection.data[x])
                let rgb=rndBgColor([x]);
                datasets.push({
                    label:x,
                    backgroundColor: rgb ,
                    borderColor: rgb,
                    borderWidth: 1,
                    data:data,
                })
            }
            ConfigHorizontalBars.data={labels:labels, datasets:datasets};
            ConfigHorizontalBars.options.plugins.title.text=title;
            ConfigHorizontalBars.options.plugins.datalabels.font={ size:12};
            ConfigHorizontalBars.options.plugins.datalabels.formatter=function(value, context){
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' %';
            }
            ConfigHorizontalBars.options.scales.y={ticks:{font:{ size:12, weight:'bold'}}};
            ConfigHorizontalBars.options.plugins.legend={ display: true,position:'bottom'};
            chartConeval= new Chart(ctxConeval, ConfigHorizontalBars);
        }

        function init(){
            getConeval();
        }
        window.onload=function(){
            init();
        };

    </script>
</section>

