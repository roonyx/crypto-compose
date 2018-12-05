// $(document).ready(function () {
//     var countChecked = function () {
//         var n = $("input:checked").length;
//         if ($("input:checked").length > 0) {
//             $("#coins").submit();
//         }
//     };
//     // countChecked();
//
//     // $("input[type=checkbox]").on("click", countChecked);
// });

document.addEventListener("DOMContentLoaded", function (event) {

    $(function () {
        $('#datetimepicker6').datetimepicker({
            format: 'MM/DD/YYYY'
        });
        $('#datetimepicker7').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });


    function drawChart() {
        var logCoinRate = $('.js-log-coin-rate').data('logCoinRate');

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');

        var rows = [];
        var row = [];
        var coinName = '';
        $.each(logCoinRate, function (ind, logDate) {
            row.push(ind);
            $.each(logDate, function (i, l) {
                var rate = parseFloat($.trim(l.rate));
                rate = Math.round(rate * 100) / 100;
                row.push(rate);
                coinName = l.coin;
            });
            rows.push(row);
            row = [];
        });
        data.addColumn('number', '$ per ' + coinName);
        data.addRows(rows);

        // Set chart options
        var options = {
            hAxis: {
                // title: 'Date',
                textStyle: {color: '#FFF'}
            },
            vAxis: {
                // title: 'Rate',
                textStyle: {color: '#FFF'}
            },
            titleTextStyle: {
                color: '#FFF'
            },
            backgroundColor: 'transparent',
            'width': 1100,
            'height': 400,
            legend: {textStyle: {color: '#FFF'}}
        };

        // Instantiate and draw the chart.
        var chart = new google.visualization.LineChart(document.getElementById('container'));
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawChart);

});