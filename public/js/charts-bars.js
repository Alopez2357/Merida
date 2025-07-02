
const ctxbars = document.getElementById('bars')
if(ctxbars) {
  const bars = {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April'],
      datasets: [
        {
          label: 'Shoes',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [  300, 90 ,120,150],
        },
        {
          label: 'Bags',
          backgroundColor: '#7e3af2',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [ 400, 54,140,160],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: true,
      },
      plugins:{
        datalabels: {
          color:'black',
          formatter:function(value, context){
            return value.toLocaleString("en-US");
          }
        }
      }
    },
  }

  window.myBar = new Chart(ctxbars,bars);
}

/*
* */

const ctxbars2 = document.getElementById('bars2')
if(ctxbars2) {
  const bars2 = {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Shoes',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [-3, 14, 52, 74, 33, 90, 70],
        },
        {
          label: 'Bags',
          backgroundColor: '#7e3af2',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [66, 33, 43, 12, 54, 62, 84],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: true,
      },
      plugins:{
        datalabels: {
          color:'black',
          formatter:function(value, context){
            return value.toLocaleString("en-US");
          }
        }
      }
    },
  }

  window.myBar = new Chart(ctxbars2,bars2);
}
/*
* */

const ctxEconomyTwo = document.getElementById('economyTwo')
if(ctxEconomyTwo) {
  const economyTwo = {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Shoes',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [-3, 14, 52, 74, 33, 90, 70],
        },
        {
          label: 'Bags',
          backgroundColor: '#7e3af2',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [66, 33, 43, 12, 54, 62, 84],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: true,
      },
      plugins:{
        datalabels: {
          color:'black',
          formatter:function(value, context){
            return value.toLocaleString("en-US");
          }
        }
      }
    },
  }

  window.myBar = new Chart(ctxEconomyTwo, economyTwo);
}


/*

 */
const ctxSightseeingThree = document.getElementById('SightseeingThree')
if(ctxSightseeingThree) {
  const SightseeingThree = {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Norte America',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [-3, 14, 52, 74, 33, 90, 70],
        },
        {
          label: 'Sudamerica',
          backgroundColor: '#7e3af2',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [66, 33, 43, 12, 54, 62, 84],
        },
        {
          label: 'Europa',
          backgroundColor: '#272727',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [-3, 14, 52, 74, 33, 90, 70],
        },
        {
          label: 'México',
          backgroundColor: '#D4AA7D',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [66, 33, 43, 12, 54, 62, 84],
        },
        {
          label: 'Resto del Mundo',
          backgroundColor: '#EFD09E',
          // borderColor: window.chartColors.blue,
          borderWidth: 1,
          data: [66, 33, 43, 12, 54, 62, 84],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: true,
      },
    },
  }

  window.myBar = new Chart(ctxSightseeingThree, SightseeingThree);
}
