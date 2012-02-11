<?php
$this->mvc->html->setSrcScript('https://www.google.com/jsapi');
$this->mvc->html->setCodeScript(" 
      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Year');
        data.addColumn('number', 'Depenses');
        data.addColumn('number', 'Dons');
        data.addRows(4);
        data.setValue(0, 0, '2004');
        data.setValue(0, 1, ".$resumer['SUMout'].");
        data.setValue(0, 2, ".$resumer['SUMin'].");

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 400, height: 240, title: 'Company Performance',
                          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
                         });
      }
");

/*$this->mvc->html->setCodeScript("
      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task');
        data.addColumn('number', 'Hours per Day');
        data.addRows(5);
        data.setValue(0, 0, 'Total debit');
        data.setValue(0, 1, ".$resumer['SUMout'].");
        data.setValue(1, 0, 'Total credit');
        data.setValue(1, 1, ".$resumer['SUMin'].");

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 450, height: 300, title: 'Difference'});
      }
");//*/
echo debug($resumer);
?>

<div id="chart_div"></div>