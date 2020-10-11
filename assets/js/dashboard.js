  $(function() {
        "use strict";
    $('#alldata').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        maxDate: "+0M",
        dateFormat: 'MM yy'
    }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
thisCalendar.datepicker('setDate', new Date(year, month, 1));
        });
    });
});

  window.onload = function() {

    var currency = $("#currency").val();
    var totalsale = $("#totalsalep").val();
    var totalpurchase = $("#totalplurchasep").val();
    var totalexpense = $("#totalexpensep").val();
    var totalemployeesalary = $("#totalemployeesalaryp").val();
    var totalservice = $("#totalservicep").val();
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    title: {
        text: "Expense statement",
         fontColor: "green"
    },
   
    data: [{
        type: "pie",
        startAngle: 240,
        yValueFormatString: "##0.00 "+currency,
        indexLabel: "{label} {y}",
        dataPoints: [
            {y: totalsale, label: "Total Sale"},
             {y: totalpurchase, label: "Total Purchase"},
            {y: totalexpense, label: "Total Expense"},
            {y: totalemployeesalary, label: "Employee Salary"},
            {y: totalservice, label: "Service "}
        ]
    }]
});
chart.render();

}

$(function (){
   "use strict"; 
              var mvar=$("#month").val();
              var splitmonth = mvar.substring(0, mvar.length - 1);
              var month = splitmonth.split(",");

              var tmsl=$("#tlvmonthsale").val();
              var splitsale = tmsl.substring(0, tmsl.length - 1);
              var sale = splitsale.split(",");


              var tmpurchase=$("#tlvmonthpurchase").val();
              var splitpurchase = tmpurchase.substring(0, tmpurchase.length - 1);
              var purchase = splitpurchase.split(",");
              var label = $("#salspurhcaselabel").val();

               var bestslabel = $("#bestsalelabel").val();
               var splitbslabel = bestslabel.substring(0, bestslabel.length - 1);
               var bestsalelabel = splitbslabel.split(",");

               var bestsdata  = $("#bestsaledata").val();
              var splitbsdata = bestsdata.substring(0, bestsdata.length - 1);
               var bestsaledata = splitbsdata.split(",");

               var bestsalmax    = $("#bestsalemax").val();

               new Chart(document.getElementById("yearlyreport"), {
  type: 'line',
  data: {
    labels: month,
    datasets: [{ 
        data: sale,
        label: "Sales",
        borderColor: "#008000",
        fill: false
      }, { 
        data: purchase,
        label: "Purchase",
        borderColor: "#3e95cd",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: label
    }
  }
});



  var bestslabel = $("#bestsalelabel").val();
               var splitbslabel = bestslabel.substring(0, bestslabel.length - 1);
               var bestsalelabel = splitbslabel.split(",");

               var bestsdata  = $("#bestsaledata").val();
              var splitbsdata = bestsdata.substring(0, bestsdata.length - 1);
               var bestsaledata = splitbsdata.split(",");

               var bestsalmax    = $("#bestsalemax").val();
   var ctx = document.getElementById("lineChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bestsalelabel,
            datasets: [
                {
                    label: "Sales Product",
                    fillColor: "#000000",
                    strokeColor: "#000000",
                    pointColor: "#000000",
                    pointStrokeColor: "#000000",
                    pointHighlightFill: "#000000",
                    pointHighlightStroke: "#000000",
                    maintainAspectRatio: false,
                    scaleFontColor: "#000000",
                    pointLabelFontColor: "#000000",
                    pointLabelFontSize: 30,
                    data: bestsaledata
                }

            ]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Products'
                        }
                    }],
                yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            steps: 10,
                            stepValue: 5,
                            max: Number(bestsalmax)
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Quantity'
                        }
                    }]
            },
            "animation": {
                "duration": 1,
                "onComplete": function () {
                    var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                    ctx.color = '#000000';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = dataset.data[index];
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            }
        }


    });



   });


          
