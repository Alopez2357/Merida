/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
*/
const pieCtx = document.getElementById('hotelera')

Chart.register(ChartDataLabels);
Chart.register(PluginBgColor);



/**
 * THREE
*/
const ctxEconomyThree = document.getElementById('economyThree')
if(ctxEconomyThree) {
  Chart.register(ChartDataLabels);
  let datas = [5.8, 12.2, 12.7, 8.1, 10.4, 21.3, 10.1, 12];
  const economyThree = {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
          datalabels: {
            color: '#fff'
          }
        },

      ],
      labels: ['Gobiernos Internacionales', 'Industria manufacturera', 'Construcción', 'Servicios Sociales', 'Comercio', 'Restaurantes y alojamiento'],
    },
    plugins: [ChartDataLabels],
    options: {
      responsive: true,
      cutoutPercentage: 50,
      plugins: {
        datalabels: {
          color: '#fff',
          anchor: 'center',
          position: 'outside',
          font: {
            weight: 'bold'
          },
          padding: 6,
        }
      }
    }
  }
  window.myPie = new Chart(ctxEconomyThree, economyThree);
}

/**
 * FOUR
*/

const ctxEconomyFour = document.getElementById('economyFour')
if(ctxEconomyFour){
  let datas=[5.8,12.2,12.7,8.1,10.4];
  const economyFour= {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
        },
      ],
      labels: ['$ 172.87','$ 345.74','$ 864.35','$ 518.61','$ 692.48'],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      legend: {
        display: true,
      },
    },
  }
  window.myPie = new Chart(ctxEconomyFour, economyFour);
}





/*
* SightseeingFour
 */
const ctxSightseeingFour = document.getElementById('SightseeingFour')
if(ctxSightseeingFour){
  let datas=[30,25,35,50];
  const SightseeingFour= {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
        },
      ],
      labels: ['Santa Lucia','Guadalajara','Monterrey','México'],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      legend: {
        display: true,
      },
    },
  }
  window.myPie = new Chart(ctxSightseeingFour, SightseeingFour);
}

/*
*
* SightseeingFive
*/
const ctxSightseeingFive = document.getElementById('SightseeingFive')
if(ctxSightseeingFive){
  let datas=[30,25,35,50];
  const SightseeingFive= {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
        },
      ],
      labels: ['Guatemala','Caribe:Havana','Houston','Miami'],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      legend: {
        display: true,
      },
    },
  }
  window.myPie = new Chart(ctxSightseeingFive, SightseeingFive);
}

/*
* SightseeingSix
 */
const ctxSightseeingSix = document.getElementById('SightseeingSix')
if(ctxSightseeingSix){
  let datas=[30,25,35,50];
  const SightseeingSix= {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
        },
      ],
      labels: ['Santa Lucia','Guadalajara','Monterrey','México'],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      legend: {
        display: true,
      },
    },
  }
  window.myPie = new Chart(ctxSightseeingSix, SightseeingSix);
}


/*
*
* SightseeingSeven
*/
const ctxSightseeingSeven = document.getElementById('SightseeingSeven')
if(ctxSightseeingSeven){
  let datas=[30,25,35,50];
  const SightseeingFive= {
    type: 'pie',
    data: {
      datasets: [
        {
          data: datas,
          backgroundColor: rndBgColor(datas),
          label: 'Dataset 1',
        },
      ],
      labels: ['Guatemala','Caribe:Havana','Houston','Miami'],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      legend: {
        display: true,
      },
    },
  }
  window.myPie = new Chart(ctxSightseeingSeven, SightseeingSeven);
}

