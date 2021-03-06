// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

$.ajax({
    type: "POST",
    url: "/api/get/indexData",
    dataType: 'json',
    success: function (data) {

        /// Chart
        var labelsMes = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];

        var getGanhos = data.chart.map(function (e) {
            return e.ganhos;
        });

        var getGastos = data.chart.map(function (e) {
            return e.gastos;
        });

        var ctx = document.getElementById("chartGanhos");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsMes,
                datasets: [
                    {
                        label: "Ganhos",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: getGanhos,
                    }
                ],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return data.currency_simbol + '' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + data.currency_simbol + ' ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });

        // Chart Gastos
        var ctx = document.getElementById("chartGastos");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsMes,
                datasets: [{
                    label: "Gastos",
                    lineTension: 0.3,
                    backgroundColor: "rgba(222, 78, 78, 0.53)",
                    borderColor: "rgba(222, 78, 78, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(222, 78, 78, 1)",
                    pointBorderColor: "rgba(222, 78, 78, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(176, 78, 78, 1)",
                    pointHoverBorderColor: "rgba(176, 78, 78, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: getGastos,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return data.currency_simbol + ' ' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + data.currency_simbol + ' ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        /// Pie


        // ganhos pie
        var nomeCategoriasGanhos = data.pie.ganhos.map(function (e) {
            return e.nome;
        });

        var valorGanhosPie = data.pie.ganhos.map(function (e) {
            return e.valor;
        });

        var transacoesPieGanhos = data.pie.ganhos.map(function (e) {
            return e.registros;
        });

        var corGanhosPie = data.pie.ganhos.map(function (e) {
            return e.cor;
        });

        var ctx = document.getElementById("pieGanhos");
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: nomeCategoriasGanhos,
                datasets: [{
                    data: valorGanhosPie,
                    backgroundColor: corGanhosPie,
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            return "Valor: " + data.currency_simbol + ' ' + number_format(chart.datasets[tooltipItem.datasetIndex].data[0]) + " / " + "Registros: " + transacoesPieGanhos[tooltipItem.datasetIndex];
                        }
                    }
                }
            },
        });

        /// gastos pie

        var nomeCategoriasGastos = data.pie.gastos.map(function (e) {
            return e.nome;
        });

        var valorGastosPie = data.pie.gastos.map(function (e) {
            return e.valor;
        });

        var transacoesPieGastos = data.pie.gastos.map(function (e) {
            return e.registros;
        });

        var corGastosPie = data.pie.gastos.map(function (e) {
            return e.cor;
        });

        var ctx = document.getElementById("pieGastos");
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: nomeCategoriasGastos,
                datasets: [{
                    data: valorGastosPie,
                    backgroundColor: corGastosPie
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            return "Valor: " + data.currency_simbol + ' ' + number_format(chart.datasets[tooltipItem.datasetIndex].data[0]) + " / " + "Registros: " + transacoesPieGastos[tooltipItem.datasetIndex];
                        }
                    }
                }
            },
        });


    }
});