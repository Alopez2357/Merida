const host =window.location.protocol + "//" + window.location.host;
const center={lat:20.978847994538597,lng:-89.61960884983415};
const Url = window.location.protocol + "//" + window.location.host+"/api/v1";
const Url_INEGI="https://www.inegi.org.mx/app/api/denue/v1/";
const Token_INEGI="6e585700-35dc-4fd3-839f-f8a9a07c265d";
const Api_key="72b01945c5df6d598bf917ce4e8f72551787e8781551c208a0c2901facc2bb92";
let Interval;
let map;
let locations=[];
let date_start;
let date_end;
let mapMarkers=[];
let polygons=[];
let Locations=[];
let Companies=[];
let infowindow;

let today=new Date();


function formatQuarter(Quarter) {
    let roman=0;
    switch (Quarter) {
        case 1:
            roman = 'I';
            break;
        case 2:
            roman = 'II';
            break;
        case 3:
            roman = 'III';
            break;
        case 4:
            roman = 'IV';
            break;
    }
    return roman;
}

function getMonthShortName(monthNo) {
    if(monthNo){
        let d = new Date();
        d.setMonth(monthNo - 1);
        return d.toLocaleString('en-US', {month: 'short'});
    }
        return '';
}

function setPinStratum(stratum) {
    let pin;
    switch (stratum) {
        case '0 a 5 personas':
            pin = '/img/pin_one.png';
            break;
        case '6 a 10 personas':
            pin = '/img/pin_two.png';
            break;
        case "11 a 30 personas":
            pin = '/img/pin_three.png';
            break;
        case "31 a 50 personas":
            pin = '/img/pin_four.png';
            break;
        case "51 a 100 personas":
            pin = '/img/pin_five.png';
            break;
        case "101 a 250 personas":
            pin = '/img/pin_six.png';
            break;
        case "251 y más personas":
            pin = '/img/pin_seven.png';
            break;
        default:
            pin = '/img/pin.png';
    }

    return host+pin;
}

function rndBgColor(data) {
    let bgColors = [];
    for (let i = 0; i < data.length; i++) {
        const r = Math.floor(Math.random() * 255);
        const g = Math.floor(Math.random() * 255);
        const b = Math.floor(Math.random() * 255);
        bgColors.push('rgb(' + r + ',' + g + ',' + b + ')');
    }
    return bgColors;
}

function getParseInterval(init)
{
    let date_parse = new Date(init);
    let firstDay = new Date(date_parse.getFullYear(), date_parse.getMonth(), 1);
    date_start=firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate()
    let lastDay = new Date(date_parse.getFullYear(), date_parse.getMonth() + 1, 0);
    date_end=lastDay.getFullYear()+'-'+(lastDay.getMonth()+1)+'-'+lastDay.getDate()
}

function setTimeInterval(Y,M)
{
    //let date_init=Y+"-"+M+"-"+1;
    let date_init = Y + '/' + ('00' + M).slice(-2) + '/' + '01';
    let date_interval = new Date(date_init);
    let month = date_interval.toLocaleString('default', { month: 'short' });
    Interval = month + " " + Y;
    if(document.getElementById('current-interval')) document.getElementById('current-interval').innerHTML=Interval;
    getParseInterval(date_init)
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function mainMap(){
    map = new google.maps.Map(document.getElementById("mainMap"), {
        center: new google.maps.LatLng(center.lat,center.lng),
        zoom: 12,
    })

    infowindow = new google.maps.InfoWindow();
}

function drawMarkers(){
    for(let i=0; i < Locations.length; i++) {
        let newMarker = new google.maps.Marker({
            position: {lat: parseFloat(Locations[i].json.latitude),lng: parseFloat(Locations[i].json.longitude)},
            icon: host+'/img/pin.png',
            map: map,
            title: "Type: " + Locations[i].json.property_type + " Ocupancy Rate: " + Locations[i].json.occupancy_rate,
        });

        let content="<div>";
        content+="<p><strong>Bedrooms: </strong>"+Locations[i].json.bedrooms+"</p>";
        content+="<p><strong>Occupancy Rate: </strong>"+Locations[i].json.occupancy_rate+"</p>";
        content+="</div>";
        infowindow.setContent(content);
        newMarker.addListener("click", () => {
            infowindow.open({
                anchor: newMarker,
                map,
            });
        });
        mapMarkers.push(newMarker);
    }
}

function drawCompanies(){
    console.log('dibujando')
    for(let i=0; i < Companies.length; i++) {
        let newMarker = new google.maps.Marker({
            position: {lat: parseFloat(Companies[i].lat),lng: parseFloat(Companies[i].lng)},
            icon: setPinStratum(Companies[i].estrato),
            map: map,
            title: Companies[i].name,
        });

        newMarker.addListener("click", () => {
            drawInfowindow(Companies[i].location_id,  newMarker )
        });

        mapMarkers.push(newMarker);
    }

    console.log('termina todo')

}

async function drawInfowindow(id,thisMarker){

    let Company={};
    const response = await fetch(host+ "/api/v1/maps/inegi/company/"+id+"?api_key="+Api_key,{
        method:'GET',
    });

    if (response.status===200) {
        const result = await response.json();
        if(result.body!==null) {
            Company = result.body;

            let content="<div>";
            content+="<p><strong>Razón Social: </strong>"+Company.Razon_social+"</p>";
            content+="<p><strong>Clase Actividad: </strong>"+Company.Clase_actividad+"</p>";
            content+="<p><strong>Estrato: </strong>"+Company.Estrato+"</p>";
            content+="<p><strong>Fecha Alta: </strong>"+Company.Fecha_Alta+"</p>";

            infowindow.setContent(content);
            infowindow.open({
                anchor: thisMarker,
                map,
            });

        }
    }



}

function clearMarkers()
{
    for(let i=0; i < mapMarkers.length; i++) {
        mapMarkers[i].setMap(null)
    }
    locations=[];
    Companies=[];
    Locations=[];
}

function clearPolygons()
{
    for(let i=0; i < polygons.length; i++) {
        polygons[i].setMap(null)
    }
    polygons=[];
}

function exportCanva(ctx) {
    let enlace = document.createElement('a');
    enlace.download = "chart.jpg";
    enlace.href = ctx.toDataURL("image/jpeg", 1);
    enlace.click();
}

const PluginBgColor = {
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart, args, options) => {
        const {ctx} = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = options.color || '#ffffff';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

function resetChart(chart) {
    if (chart) chart.destroy();
    document.getElementById('interval-start').innerHTML = '-';
    document.getElementById('interval-end').innerHTML = '-';
}


Chart.register(ChartDataLabels);
Chart.register(PluginBgColor);

const ConfigPie= {
    type: 'pie',
    data: {
        labels: null,
        datasets: null
    },
    options: {
        layout:{
            padding:15
        },
        responsive: true,
        cutoutPercentage: 100,
        hoverOffset:35,
        borderWidth:1,
        plugins: {
            customCanvasBackgroundColor: {
                color: 'white',
            },
            title: {
                display: true,
                padding: {
                    top: 0,
                    bottom: 10
                }
            },
            subtitle:{
                display: false,
                text: '',
                position:'bottom',
                font: {
                    size: 12,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            legend: {
                position:'left',
                display: true,
                fullSize:true,
                usePointStyle:true,
            },
            // Change options for ALL labels of THIS CHART
            datalabels: {
                anchor:'center',
                font:{
                    weight: 'bold',
                    size:10
                },
                color: 'white',
                display:'auto',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }

            }
        }
    },
}

const ConfigMiniLine = {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May'],
    },
    backgroundColor: "#F5DEB3",
    options: {
        layout: {
            padding: 20
        },
        scales:{
            y:{grid:{ color:'white' },ticks: { display:false, font: { size: 7 }}},
            x:{grid:{color:'white'},ticks: {font: {size: 7}}}
        },
        pointRadius:0,
        tension:0.3,
        responsive: false,
        legend: {
            display: false
        },
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true,
        },
        plugins: {
            customCanvasBackgroundColor: {
                color: 'white',
            },
            title: {
                display: true,
                font: {
                    size: 10
                }
            },
            subtitle:{
                display: false,
                text: '',
                position:'bottom',
                font: {
                    size: 12,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            legend: {
                display: false,
            },
            datalabels: {
                font:{
                    weight: 'bold',
                    size:9
                },
                anchor:'end',
                align:'end',
                clamp:true,
                display: function(context){ return (context.dataIndex===context.dataset.data.length-1 || context.dataIndex===0 )},
                color: '#000',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    }
}
/// config LINE

const ConfigLine = {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May'],
    },
    backgroundColor: "#F5DEB3",
    options: {
        layout: {
            padding: 20
        },
        pointRadius:0,
        tension:0.3,
        responsive: true,
        legend: {
            display: false
        },
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true,
        },
        plugins: {
            customCanvasBackgroundColor: {
                color: 'white',
            },
            title: {
                display: true,
                padding:20
            },
            subtitle:{
                display: false,
                text: '',
                position:'bottom',
                font: {
                    size: 12,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            legend: {
                display: false,
            },
            datalabels: {
                font:{
                    weight: 'bold',
                    size:10
                },
                anchor:'end',
                align:'end',
                clamp:true,
                display: function(context){
                    return (context.dataIndex===context.dataset.data.length-1 || context.dataIndex===0 )
                },
                color: '#000',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    }
}

const ConfigBars = {
    type: 'bar',
    data: {
        labels: [],
        datasets: [
            {}
        ],
    },
    options: {
        layout:{
            padding:30
        },
        responsive: true,
        maintainAspectRatio: false,
        scales:{
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        },
        plugins:{
            legend: {
                display: false,
                position:'bottom'
            },
            title: {
                display: true,
                text:'hello',
                font:{
                    size:14
                },
                padding: {
                    top: 10,
                    bottom: 30
                }
            },
            datalabels: {
                font:{
                    weight: 'bold',
                    size:10
                },
                anchor:'end',
                align:'end',
                color: 'black',
                display:'auto',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    },
}

const ConfigHorizontalBars= {
    type: 'bar',
    data:{},
    options: {
        layout:{
            padding:30
        },
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales:{
            /*yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }],*/
            y:{grid:{ color:'white' },ticks: { font: { size: 8  }}},
            x:{grid:{color:'white'},ticks: {display:false,font: {size: 8}}}
        },
        legend: {
            display: false,
        },
        plugins:{
            title: {
                display: true,
                text:'Tipo Ocupación',
                font:{
                    size:16
                }
            },
            subtitle:{
                display: false,
                text: 'unidades',
                position:'bottom',
                font: {
                    size: 10,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            legend: {
                display: false,
            },
            datalabels: {
                font:{
                    weight: 'bold',
                    size:8
                },
                anchor: 'end',
                align:'end',
                color:'black',
                display:'auto',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    }
};

const ConfigHorizontalEmployment= {
    type: 'bar',
    data:{},
    options: {
        layout:{
            padding:30
        },
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales:{
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }],
            y:{stacked:true, font:{size:8}},
            x:{stacked:true, font:{size:8}}
        },
        legend: {
            display: false,
        },
        plugins:{
            title: {
                display: true,
                text:'Tipo Ocupación',
                font:{
                    size:16
                }
            },
            subtitle:{
                display: false,
                text: 'unidades',
                position:'bottom',
                font: {
                    size: 10,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            legend: {
                display: true,position:'bottom'
            },
            datalabels: {
                font:{
                    weight: 'bold', size:10
                },
                anchor: 'center',
                align:'center',
                color:'white',
                display:'auto',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    }
};

const ConfigDonut = {
    type: 'doughnut',
    options: {
        responsive: false,
        cutoutPercentage: 80,
        legend: {
            display: false,
        },
        layout:{
            padding:15
        },
        plugins:{
            title: {
                display: true,
                text:'Ocupación Empleo'
            },
            subtitle:{
                display: true,
                text: 'personas',
                position:'bottom',
                font: {
                    size: 12,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            datalabels: {
                font:{
                    weight: 'bold',
                    size:10,
                },
                color: 'white',
                display:'auto',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        }
    },
}

const ConfigStacked = {
    type: 'bar',
    data:{
        labels:[],
        datasets:[]
    },
    options: {
        layout:{
            padding:15
        },
        responsive: true,
        maintainAspectRatio: false,
        scales:{
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }],
            x:{
                stacked: true,
                font: { size: 8  }
            },
            y:{
                stacked: true,
                font: {size: 8}
            }
        },
        plugins: {
            title: {
                display: true,
                text: '',
                font:{size:14}
            },
            subtitle:{
                display: false,
                text: 'personas',
                position:'top',
                font: {
                    size: 12,
                    family: 'tahoma',
                    weight: 'normal',
                    style: 'italic'
                },
                padding: {
                    bottom: 10
                }
            },
            legend: {
                display: true,
                position:'bottom',
            },
            datalabels: {
                font: {
                    weight: 'bold',
                    size: 14,
                },
                display:'auto',
                color: 'white',
                formatter:function(value, context){
                    return value.toLocaleString("en-US");
                }
            }
        },

    }
};


// config
const configGeoMap = {
    type: 'choropleth',
    data:{},
    options: {
        showOutline:false,
        showGraticule:false,
        scales:{
            projection: {
                axis: 'x',
                projection: 'equalEarth',
            },
        },
        plugins:{
            customCanvasBackgroundColor: {
                color: 'white',
            },
            title:{
                display:true,
                text:'Destinos Comerciales',
                font: {
                    size: 15
                }
            },
            legend:{
                display:false
            },
            datalabels:{
                color: '#000',
                font:{
                    weight: 'bold',
                    size:10
                },
                anchor:'end',
                align:'center',
                clamp:true,
                display: function(context){
                    return (context.dataset.data[context.dataIndex].value>0);
                },
                formatter:function(value, context){
                    let percent=parseFloat(value.value).toFixed(2);
                    return value.feature.properties.name+' '+percent+' %';
                }

            }
        }
    },
};



