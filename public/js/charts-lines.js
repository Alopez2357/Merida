const ctxLine = document.getElementById('line')
if(ctxLine) {
  const Line = {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May'],
      datasets: [
        {
          label: 'Organic',
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 150],
          fill: false,
        },
        {
          label: 'Paid',
          fill: false,
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#7e3af2',
          borderColor: '#7e3af2',
          data: [24, 50, 64, 74, 300],
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    }
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxLine, Line);
}
/*
* */
const ctxLine2 = document.getElementById('line2')
if(ctxLine) {
  const Line2 = {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March','April','May'],
      datasets: [
        {
          label: 'Organic',
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70],
          fill: false,
        },
        {
          label: 'Paid',
          fill: false,
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#7e3af2',
          borderColor: '#7e3af2',
          data: [24, 50, 64, 74, 52, 51, 65],
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxLine2, Line2);
}
/*
*
* */

const ctxEconomyFive = document.getElementById('economyFive')
if(ctxEconomyFive) {
  const economyFive = {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Organic',
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70],
          fill: false,
        },
        {
          label: 'Paid',
          fill: false,
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#7e3af2',
          borderColor: '#7e3af2',
          data: [24, 50, 64, 74, 52, 51, 65],
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxEconomyFive, economyFive);
}
/*
 SEVEN
 */

const ctxEconomySeven = document.getElementById('economySeven')
if(ctxEconomySeven) {
  const economySeven = {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Organic',
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70],
          fill: false,
        },
        {
          label: 'Paid',
          fill: false,
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: 'Blue',
          borderColor: 'Red',
          data: [24, 50, 64, 74, 52, 51, 65],
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxEconomySeven, economySeven);
}



const ctxSightseeingTwo = document.getElementById('SightseeingTwo')
if(ctxSightseeingTwo) {
  const SightseeingTwo= {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: '2020',
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70],
          fill: false,
        },
        {
          label: '2021',
          fill: false,
          backgroundColor: 'Blue',
          borderColor: 'Red',
          data: [24, 50, 64, 74, 52, 51, 65],
        },
        {
          label: '2022',
          backgroundColor: '#0694a2',
          borderColor: '#000000',
          data: [ 52, 51, 65,24, 50, 64, 74],
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxSightseeingTwo, SightseeingTwo);
}

/*
* SightseeingEight
 */

const ctxSightseeingEight = document.getElementById('SightseeingEight')
if(ctxSightseeingEight) {
  const SightseeingEight= {
    type: 'line',
    data: {
      labels: ['2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021','2022'],
      datasets: [
        {
          label: 'Domestic',
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70,43, 48, 40, 54, 67, 73],
          fill: false,
        },
        {
          label: 'International',
          fill: false,
          backgroundColor: 'Blue',
          borderColor: 'Red',
          data: [24, 50, 64, 74, 52, 51, 65,24, 50, 64, 74, 52, 51],
        },
        {
          label: 'Total',
          backgroundColor: '#0694a2',
          borderColor: '#000000',
          data: [ 52, 51, 65,24, 50, 64, 74, 52, 51, 65,24, 50, 64],
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxSightseeingEight, SightseeingEight);
}

/*
* SightseeingNine
 */


const ctxSightseeingNine = document.getElementById('SightseeingNine')
if(ctxSightseeingNine) {
  const SightseeingNine= {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','Aug','Sep','Oct','Dic'],
      datasets: [
        {
          label: '2020',
          backgroundColor: '#0694a2',
          borderColor: '#0694a2',
          data: [43, 48, 40, 54, 67, 73, 70,54, 67],
          fill: false,
        },
        {
          label: '2021',
          fill: false,
          backgroundColor: 'Blue',
          borderColor: 'Red',
          data: [24, 50, 64, 74, 52, 51, 65,74, 52],
        },
        {
          label: '2022',
          backgroundColor: '#0694a2',
          borderColor: '#000000',
          data: [ 52, 51, 65,74, 52, 64, 74,74, 54],
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true,
      },
      scales: {
        x: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month',
          },
        },
        y: {
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value',
          },
        },
      },
    },
  }

// change this to the id of your chart element in HMTL

  window.myLine = new Chart(ctxSightseeingNine, SightseeingNine);
}
