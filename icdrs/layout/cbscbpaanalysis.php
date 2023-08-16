
<!DOCTYPE html>
<html>
<head>
  <title>CBPA Analysis</title>

   <!-- Load custom css layout file and jquery -->
  <link rel="stylesheet" href="/icdr/common/css/theme.css">
  <script src="/icdr/common/js/jquery-3.6.0.min.js"></script>
  <script src="/icdr/common/js/theme.js"></script>

  <!-- Load bootstrap -->
  <link href="/icdr/common/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="/icdr/common/bootstrap/js/bootstrap.min.js"></script>
  
  <!-- AMChart file -->
  <script src="/icdr/common/amchart/index.js"></script>
  <script src="/icdr/common/amchart/xy.js"></script>
  <script src="/icdr/common/amchart/Animated.js"></script>

  <!-- AMChart personal config -->
  <script src="/icdr/config/graph.js"></script>

  <?php
    // Load helper functions.
    require_once dirname(__DIR__,1)."/config/localconfig.php";
    require_once FullCommonPath."/modules/main_menu.php" ;
    
    
    require_once dirname(__DIR__)."/config/chartaveragetype.php";
    require_once dirname(__DIR__,1)."/config/chartaveragetypeconfig.php";
    require_once dirname(__DIR__,1)."/config/layoutrender.php";
    
    $layoutRender = new LayoutRender();
  ?>

</head>
<body>

<?php Main_menu(); ?>

<div class="container">
    <div class="chart-main-box">
      <h3 class='chart-title'>CBPA Analysis</h3>


      <div class="d-flex row">
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalCAPSCBSCBPAVoice', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalCAPSCBSCBPAData', ChartAverageType::OneDay); ?>      
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalCAPSCBSCBPASMS', ChartAverageType::OneDay); ?>      
        </div>
      </div>
    
    <hr class="bold-hr">
    
    <div class="d-flex row">
        <div class="col-md-6 col-md">
          <?php $layoutRender->initChart('totalOnlineSessionCBSCBPAVoice', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-6 col-md">
          <?php $layoutRender->initChart('totalOnlineSessionCBSCBPAData', ChartAverageType::OneDay); ?>      
        </div>
      </div>

      <hr class="bold-hr">
    
    <div class="d-flex row">
        <div class="col-md-6 col-md">
          <?php $layoutRender->initChart('totalOnlineDelayCBSCBPAVoice', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-6 col-md">
          <?php $layoutRender->initChart('totalOnlineDelayCBSCBPAData', ChartAverageType::OneDay); ?>      
        </div>
      </div>
    
      <hr class="bold-hr">
    
    <div class="d-flex row">
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalTPSCBSCBPAData', ChartAverageType::OneDay); ?>
        </div>
    </div>      
  </div>


</body>


<script>
  // API to get chart result.
  const API_PURE_URL = "<?php echo API_PURE_URL ?>";
  const API_ANALYTIC_PURE_URL = "<?php echo API_ANALYTIC_PURE_URL ?>";
  /**
   * Get inited chart javascript function.
   * It's uses to init chart graphic, update and etc.
   */
  let layoutRenders = <?php $layoutRender->initJavaScriptFunction()?>;
  let layoutRendersDict = <?php $layoutRender->initJavaScriptFunctionDict()?>;

   /**
   * Configure to manage place of description.
   */
  const DescriptionPlace = {
    IN_TOOLTIP: 1,
    IN_LEGEND: 2,
  };

// Control chart title show/hide.
if(!(typeof chartSetting === 'undefined')){
    // Control chart title show/hide.
    for(let chart of Object.entries(chartSetting)){
      if(chart[1].showTitle){
        $(`.chart-title[data-chart-name='${chart[0]}']`).show();
      }else{
        $(`.chart-title[data-chart-name='${chart[0]}']`).hide();
      }

      // Change name from config in js section.
      if(chart[1].title != null){
        $(`.chart-title[data-chart-name='${chart[0]}']`).html(chart[1].title);
      }
  }
}

// Interation for init charts.
$(document).ready(function (){
  for(let i=0; i<layoutRenders.length; i++){
    // Show related error for charts and ignore them.
    if(!layoutRenders[i].status){
      console.error(layoutRender.errorMessage);
      continue;
    }

    // Calc timer interval for chart.
    // Detect API gateway.
    let _url = null;
      if(layoutRenders[i].isAnalytic){
        _url = API_ANALYTIC_PURE_URL; 
      }else{
        _url = API_PURE_URL;
      }
      restartTimerTick(layoutRenders[i], _url);
  }
});

/**
* Change chartAverageType in chart object.
* To hadle change in chart averageType.
*/
$(document).on('click', `input[class='form-check-input']`, function () {
    // Get selected layoutRender to change.
    layoutRender = getLayoutRenderByNames($(this).attr('name'));
    if (!layoutRender.status) {
        //alert('No valid');
        //return;
    }
    layoutRender.chartAverageType = JSON.parse($(this).attr('data-type'));;
    // start timer again.
    // Detect API gateway.
    let _url = null;
      if(layoutRender.isAnalytic){
        _url = API_ANALYTIC_PURE_URL; 
      }else{
        _url = API_PURE_URL;
      }
      restartTimerTick(layoutRender, _url);
});

</script>

<style>
  <?php
  foreach($layoutRender->javascriptObject as $chart){
    $chartClassName = sprintf('chart-item-%s', $chart['chartName']);
    $height = ($chart['chartLengendHeightPerPixel']+$chart['chartBoxHeightPerPixel'])."px;";
    $s = ".$chartClassName{height: $height}";
    echo $s."\n";
  }
?>
</style>

</html>
