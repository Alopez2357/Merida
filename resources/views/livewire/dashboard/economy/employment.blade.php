<script src="{{ asset('js/mobile-script.js') }}"></script>
@section('title','Distribución de Empleo')
<section class="h-full overflow-y-auto p-2">
    <div class="w-full h-full  flex flex-wrap md:flex-nowrap space-y-2 md:space-y-0 md:space-x-2">
        <div class="order-2 md:order-1 w-full h-[75vh] md:h-full bg-white rounded-lg shadow  flex items-center justify-center border" style="position: relative;" >
            <h1 class="capa">Selecciona un filtro</h1>            
            <canvas id="chart"  class="w-full h-full"  ></canvas>
        </div>
        <div class="order-1 md:order-2 w-full md:w-1/3  flex flex-wrap flex-mobile-box flex-mobile-box-active">
            <div class="w-full h-[30vh] md:h-1/2  overflow-x-auto border p-2 rounded-xl shadow-inner">
                <div class="w-full bg-white rounded-xl  border">
                    @foreach($dates as $y=>$years)
                        <div class="w-full flex-wrap">
                            <div class="w-full bg-gray-200 rounded-xl font-bold text-center">{{ $years['name'] }}</div>
                            <div class="w-full flex">
                                @foreach($years['quarters'] as $q=>$quarter)
                                    <div class="w-1/3 p-2 flex justify-center">
                                        <button onclick="selectQuarter({{$years['name']}},{{$quarter}})" wire:click="selectQuarter({{$years['name']}},{{$quarter}})"
                                                class="w-full rounded-lg p-2 border {{ ($years['name']==$Year && $quarter==$Quarter)?'bg-green-500 text-white':'bg-white' }}
                                                        hover:bg-green-500 hover:text-white">{{  translateRomanNumerals($quarter) }}</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="w-full  md:h-1/2 flex flex-wrap items-start space-y-2" wire:ignore>
                <div class="w-full flex flex-wrap space-y-2 ">
                    <div class="w-full text-center ">
                            <p class="text-red-500  font-bold p-2" id="alert"></p>
                    </div>
                    <div class="w-full ">
                        <label class="font-bold" for="">Nivel</label>
                        <select disabled onchange="getKey(this)"  id="headlines" class="w-full bg-white rounded-lg" >
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="w-full ">
                        <label class="font-bold" for="">Sub-Nivel:</label>
                        <select disabled id="key" onchange="getData(this)" class="w-full bg-white rounded-lg" >
                            <option value=""></option>
                        </select>
                </div>
                </div>
                <div class="w-full flex justify-center items-center p-2">
                    <button onclick="exportChart()" class="py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">
                        <i class="fas fa-file-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
        <div class="btn-display-flex-mobile-box py-2 px-4 rounded-lg shadow hover:shadow-lg bg-blue-600 hover:bg-blue-500 text-white" onclick="toggleFlexMobileBox()">
                Filtros
        </div>
    </div>
    <script>
        let title='Empleo';
        let subtitle='';
        let Y, Q;
        let headlines=[];
        let keys=[];
        let dataChart=[];
        let chart;
        let headline = document.getElementById("headlines");
        let key = document.getElementById("key");

        function exportChart()
        {
            exportCanva(document.getElementById('chart'));
        }


        function selectQuarter(y,q){
            Y=y;
            Q=q;
            getHeadlines()
        }

        async function getHeadlines(){
            if( chart)  chart.destroy();
            const response = await fetch(Url + '/economy/employment/headlines?api_key='+Api_key, {
                method: 'POST',
                body:JSON.stringify({year:Y,quarter:Q}),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });

            if (response.ok) {
                const result = await response.json();
                if (result.body.length){
                    alert('');
                    headlines=result.body;
                    date_start=result.date_start;
                    date_end=result.date_end;
                    headline.removeAttribute('disabled');
                    let options = "<option value=''>-- SELECIONA --</option>";
                    headlines.map(item => {
                        options += "<option value='" + item + "'>" + item + "</option>";
                    })
                    headline.innerHTML = options;
                }else{
                    alert('Intervalo Vacio');
                    headline.setAttribute('disabled', 'true');
                    headline.innerHTML = '';

                    key.setAttribute('disabled', 'true');
                    key.innerHTML = '';
                }
            }else{
                console.log(response)
            }
        }

        async function getKey(el){
            title=el.value;
            if( chart)  chart.destroy();
            const response = await fetch( host+'/api/v1/economy/employment/keys?api_key='+Api_key, {
                method: 'post',
                body:JSON.stringify({year:Y,quarter:Q,headline:el.value}),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });
            if (response.ok) {
                alert('');
                const result = await response.json();
                if (result.body !== null)
                    keys=result.body;
                let options = "<option value=''>-- SELECIONA --</option>";
                keys.map(item=>{
                    options+="<option value='"+item.key+"'>"+item.key+"</option>";
                })

                if (keys.length) {
                    key.removeAttribute('disabled');
                    key.innerHTML = options;
                } else {
                    key.setAttribute('disabled', 'true');
                    key.innerHTML = '';
                }
            }else{
                console.log(response)
            }
        }

        async function getData(el){
                subtitle=el.value;
            if( chart)  chart.destroy();
                const response = await fetch( host+'/api/v1/economy/employment/data?api_key='+Api_key, {
                    method: 'post',
                    body:JSON.stringify({year:Y,quarter:Q,key:el.value}),
                    headers: {"Content-type": "application/json;charset=UTF-8"}
                });
                if (response.status===200) {
                    const result = await response.json();
                    if (result.body.length){
                        dataChart = result.body;
                        console.log(dataChart);
                        drawChart();
                        
                    }
                }else{
                    console.log(response)
                }
        }

        function drawChart(){
            if( chart)  chart.destroy();
            document.querySelector('.capa').style.display = 'none';
            const ctx=document.getElementById('chart').getContext('2d');
            let labels=[], dataMen = [], dataWomen = [];
            if(dataChart[0].attr[subtitle]){
                dataChart=dataChart[0].attr[subtitle];
            }else if(dataChart[0].attr['']){
                dataChart=dataChart[0].attr[''];
            }

            for(let x in dataChart) {
                let title='';
                let obj2;
              if(dataChart[x].hasOwnProperty('title')) {
                  title = dataChart[x].title;
                  obj2=dataChart[x].attr;
                }else{
                  title=x;
                    obj2=dataChart[x];
                }
                labels.push(title);
                for (let a in obj2) {
                    if(a==='Hombres')
                        dataMen.push(obj2['Hombres']);
                    if(a==='Mujeres')
                        dataWomen.push(obj2['Mujeres']);
                }


            }

               let datasets= [{
                    label:'Hombres',
                    data:dataMen,
                    backgroundColor: rndBgColor([1]),
                },{
                    label:'Mujeres',
                    data:dataWomen,
                    backgroundColor:rndBgColor([1]),
                }];

            ConfigHorizontalEmployment.data.labels=labels;
            ConfigHorizontalEmployment.data.datasets=datasets;

            ConfigHorizontalEmployment.options.plugins.title={display: true,text: title+' '+Y+' - '+formatQuarter(Q), padding: 10};
            ConfigHorizontalEmployment.options.plugins.subtitle={ display: true, text:subtitle, padding: 10};

            chart = new Chart(ctx,ConfigHorizontalEmployment);
        }

        function alert(message){
            if (document.getElementById("alert")) {
                let alerts = document.getElementById("alert");
                    alerts.innerHTML = message;
                    if(message.length) {
                        alerts.classList.remove('hidden');
                    }else{
                        alerts.classList.add('hidden');
                    }
            }

        }

    </script>
</section>

