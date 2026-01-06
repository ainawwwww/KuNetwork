
            var pro_label_array1=[<?php 
              $length = count($pro_array_label1);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_label1[$i].',';
            
            }
            ?>];
            var pro_label_array7=[<?php 
              $length = count($pro_array_label7);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_label7[$i].',';
            
            }
            ?>];
            var pro_label_array30=[<?php 
              $length = count($pro_array_label30);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_label30[$i].',';
            
            }
            ?>];
            var pro_label_array365=[<?php 
              $length = count($pro_array_label365);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_label365[$i].',';
            
            }
            ?>];
            var pro_label_array=[<?php 
              $length = count($pro_array_label);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_label[$i].',';
            
            }
            ?>];

             var pro_data_array1=[<?php 
              $length = count($pro_array_data1);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_data1[$i].',';
            }
            ?>];
            var pro_data_array7=[<?php 
              $length = count($pro_array_data7);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_data7[$i].',';
            }
            ?>];
            var pro_data_array30=[<?php 
              $length = count($pro_array_data30);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_data30[$i].',';
            }
            ?>];
            var pro_data_array365=[<?php 
              $length = count($pro_array_data365);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_data365[$i].',';
            }
            ?>];
            var pro_data_array=[<?php 
              $length = count($pro_array_data);
            for ($i = 0; $i < $length; $i++) {
            echo $pro_array_data[$i].',';
            }
            ?>];

     
     
     
var pro_xValues1 = pro_label_array1;
var pro_xValues7 = pro_label_array7;
var pro_xValues30 = pro_label_array30;
var pro_xValues365 = pro_label_array365;
var pro_xValues = pro_label_array;

var pro_yValues1 = pro_data_array1;
var pro_yValues7 = pro_data_array7;
var pro_yValues30 = pro_data_array30;
var pro_yValues365 = pro_data_array365;
var pro_yValues = pro_data_array;





// for products graph
    //  24 hours
  var pro_salesGraphChartCanvas1 = $('#pro_line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var pro_salesGraphChartData1 = {
    labels: pro_xValues1,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: pro_yValues1
      }
    ]
  }

  var pro_salesGraphChartOptions1 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(pro_salesGraphChartCanvas1, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: pro_salesGraphChartData1,
    options: pro_salesGraphChartOptions1
  })

    //  7 days
  var pro_salesGraphChartCanvas7 = $('#pro_line-chart7').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var pro_salesGraphChartData7 = {
    labels: pro_xValues7,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: pro_yValues7
      }
    ]
  }

  var pro_salesGraphChartOptions7 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(pro_salesGraphChartCanvas7, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: pro_salesGraphChartData7,
    options: pro_salesGraphChartOptions7
  })

    //  30 days
  var pro_salesGraphChartCanvas30 = $('#pro_line-chart30').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var pro_salesGraphChartData30 = {
    labels: pro_xValues30,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: pro_yValues1
      }
    ]
  }

  var pro_salesGraphChartOptions30 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(pro_salesGraphChartCanvas30, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: pro_salesGraphChartData30,
    options: pro_salesGraphChartOptions30
  })

    //  365 days
  var pro_salesGraphChartCanvas365 = $('#pro_line-chart365').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var pro_salesGraphChartData365 = {
    labels: pro_xValues365,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: pro_yValues365
      }
    ]
  }

  var pro_salesGraphChartOptions365 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(pro_salesGraphChartCanvas365, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: pro_salesGraphChartData365,
    options: pro_salesGraphChartOptions365
  })

    //  all time
  var pro_salesGraphChartCanvasall = $('#pro_line-chartall').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var pro_salesGraphChartDataall = {
    labels: pro_xValues,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: pro_yValues
      }
    ]
  }

  var pro_salesGraphChartOptionsall = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(pro_salesGraphChartCanvasall, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: pro_salesGraphChartDataall,
    options: pro_salesGraphChartOptionsall
  })
