<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
<style type="text/css">
    .bar_graph{
        margin-top: 100px;
    }

</style>
<div id="pie_graph" style="height: 400px; width: 900px;"><?=number_format($sum,2)?></div>
<div id="bar_graph" class="bar_graph" style="height: 400px; width: 900px;"></div>

<script type="text/javascript">
    var dom = document.getElementById("pie_graph");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        title : {
            text: 'Final Budget for 2019',
            subtext: 'Total - P<?=number_format($sum,2)?>',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: [<?php foreach ($item as $key => $value) { 
                    echo "'".$value['abbreviation']."',";
                    } ?>]
        },
        series : [
            {
                name: 'Percentage',
                type: 'pie',
                radius : '80%',
                center: ['50%', '60%'],
                data:[<?php foreach ($item as $key => $value) {
                        echo "{value:".$value['company_budget'].", name:'".$value['abbreviation']."'},";
                        } ?>],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>

<!-- Bar Graph -->
<script type="text/javascript">
    var dom = document.getElementById("bar_graph");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    app.title = 'Bar Graph';

    option = {
        title: {
            text: 'Bar Graph',
            subtext: 'subtext'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['2011']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value',
            boundaryGap: [0, 0.01]
        },
        yAxis: {
            type: 'category',
            data: [<?php foreach ($item as $key => $value) {
                        echo "'".$value['abbreviation']."',";
                    } ?>]
        },
        series: [
            {
                name: '2019',
                type: 'bar',
                data: [<?php foreach ($item as $key => $value) {
                        echo $value['company_budget'].",";
                        } ?>]
            },
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>