<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

if(!defined('sugarEntry')) define('sugarEntry', true);

global $app_strings, $app_list_strings;

$account = new Account();
if(!is_null($account->retrieve($_REQUEST['account_id']))){
	$account->load_relationship('opportunities');
	
	$sales_stage = array();
	foreach($app_list_strings['sales_stage_dom'] as $key => $value){
		$sales_stage[$key] = 0;
	}
	
	$cols = array();
	$cols[] = array("id" => "", "label" => "Proyecto", "pattern" => "", "type" => "string");
	$cols[] = array("id" => "", "label" => "Total Abiertos", "pattern" => "", "type" => "number");

	foreach($account->opportunities->getBeans() as $opportunity){
		$sales_stage[$opportunity->sales_stage] = $sales_stage[$opportunity->sales_stage] + $opportunity->amount_usdollar;
	}
	
	$rows = array();
	foreach($sales_stage as $key => $value){
		$rows[] = array("c" => array(
			array("v" => $key, "f" => null),
			array("v" => $value, "f" => null),
		));
	}
}

$sales_stage = array();
$sales_stage['cols'] = $cols;
$sales_stage['rows'] = $rows;

$sales_stage = json_encode($sales_stage);

// echo "<pre>";
// var_dump($app_strings);
// die;

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable(<?php echo $sales_stage; ?>);

        var options = {
          // title: '<?php echo $app_strings['LBL_OPPORTUNITIES']; ?>',
          pieHole: 0.4,
		  legend: 'none',
		  chartArea:{left:5,top:5,width:'75%',height:'75%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="donutchart" style="width: 400px; height: 350px;"></div>
  </body>
</html>