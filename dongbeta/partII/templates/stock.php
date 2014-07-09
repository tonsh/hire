<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>Yahoo Finance</title>
        <script src="<?php echo $proj_url ?>/statics/js/jquery.min.js"></script>
        <script src="<?php echo $proj_url ?>/statics/js/highstock.js"></script>
    </head>

    <body>
        <div id="container" style="min-width: 310px; height: 400px"></div>

        <script type="text/javascript">
        $(function() {
            $.getJSON('<?php echo PROJ_URL . "/index.php?ctl=stock&mt=list&code=" . $code . "&startdate=" . $startdate . "&enddate=" . $enddate . "&data_type=json" ?>', function(data) {
                $('#container').highcharts('StockChart', {
                    rangeSelector: {
                        selected: 1,
                        inputEnabled: false,
                        buttons: [
                            {
                                type: 'day',
                                count: 1,
                                text: '1D',
                            },
                            {
                                type: 'all',
                                count: 1,
                                text: 'All',
                            },
                        ],
                    },

                    title: {
                        text: '<?php echo $code; ?> Stock Price',
                    },

                    xAxis: {
                        labels: {
                            formatter: function() {
                                return Highcharts.dateFormat('%Y-%m-%d', this.value); 
                            },
                        },
                    },

                    series: [{
                        name: '<?php echo $code; ?>',
                        data: data,
                        tooltip: {
                            valueDecimals: 2,
                        },
                    }],
                });
            });
        });
        </script>
    </body>
</html>
