(function ($) {
    $(window).on('load', function () {

        var wdtGoogleCharts = [];

        if (typeof wpDataCharts !== 'undefined') {

            for (var chart_id in wpDataCharts) {

                if (wpDataCharts[chart_id].engine == 'google') {

                    var wdtChart = new wpDataTablesGoogleChart();
                    wdtChart.setType(wpDataCharts[chart_id].render_data.type);
                    wdtChart.setColumns(wpDataCharts[chart_id].render_data.columns);
                    wdtChart.setRows(wpDataCharts[chart_id].render_data.rows);
                    wdtChart.setOptions(wpDataCharts[chart_id].render_data.options);
                    wdtChart.setContainer(wpDataCharts[chart_id].container);
                    wdtChart.setColumnIndexes(wpDataCharts[chart_id].render_data.column_indexes);
                    if (typeof wpDataChartsCallbacks !== 'undefined' && typeof wpDataChartsCallbacks[chart_id] !== 'undefined') {
                        wdtChart.setRenderCallback(wpDataChartsCallbacks[chart_id]);
                    }
                    wdtGoogleCharts.push(wdtChart);
                } else if (wpDataCharts[chart_id].engine == 'chartjs') {
                    var wdtChart = new wpDataTablesChartJS();
                    wdtChart.setData(wpDataCharts[chart_id].render_data.options.data);
                    wdtChart.setOptions(wpDataCharts[chart_id].render_data.options.options);
                    wdtChart.setGlobalOptions(wpDataCharts[chart_id].render_data.options.globalOptions);
                    wdtChart.setType(wpDataCharts[chart_id].render_data.configurations.type);
                    wdtChart.setColumnIndexes(wpDataCharts[chart_id].render_data.column_indexes);
                    wdtChart.setContainer(document.getElementById("chartJSContainer_" + chart_id));
                    wdtChart.setCanvas(document.getElementById("chartJSCanvas_" + chart_id));
                    wdtChart.setContainerOptions(wpDataCharts[chart_id].render_data.configurations);
                    if (typeof wpDataChartsCallbacks !== 'undefined' && typeof wpDataChartsCallbacks[chart_id] !== 'undefined') {
                        wdtChart.setRenderCallback(wpDataChartsCallbacks[chart_id]);
                    }
                    wdtChart.render();
                }

            }
        }

        // Setting the callback for rendering Google Charts
        if (wdtGoogleCharts.length) {
            var wdtGoogleRenderCallback = function () {
                for (var i in wdtGoogleCharts) {
                    if (!isNaN(i))
                    wdtGoogleCharts[i].render();
                }
            }
            if (typeof google.charts.setOnLoadCallback !== "undefined"){
                google.charts.setOnLoadCallback(wdtGoogleRenderCallback);
            } else {
                for (var i in wdtGoogleCharts) {
                    if (!isNaN(i))
                        wdtGoogleCharts[i].render();
                }
            }

        }

    })

})(jQuery);
