
<!DOCTYPE html>
<html>
<head>
  <title>CBS Data</title>

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
  <script src="/icdr/config/graphdashboard.js"></script>

  <?php
    // Load helper functions.
    require_once dirname(__DIR__)."/config/localconfig.php";
    require_once FullCommonPath."/modules/main_menu.php" ;
    
    
    require_once dirname(__DIR__)."/config/chartaveragetype.php";
    require_once dirname(__DIR__)."/config/chartaveragetypeconfig.php";
    require_once dirname(__DIR__)."/config/layoutrenderdashboard.php";
    
    $layoutRender = new LayoutRender();
    $layoutRender->showLayoutBox = false;
  ?>

</head>
<body>

<div class="container">
  <div class="chart-main-box-db">
    <h3 class='chart-title-db'>CBS Data</h3>

      <div class="d-flex row row-db">
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Tehran</h3>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
        </div>  
        
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Tabriz</h3>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
        </div> 
      </div> 
      <div class="d-flex row">
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Shiraz</h3>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Mashhad</h3>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-6 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSCBPData', ChartAverageType::TwoHour); ?>
            </div>
          </div>
        </div>
      </div>
    </div>      
  </div>


</body>

<script>
  // API to get chart result.
  const API_PURE_URL = "<?php echo API_PURE_URL ?>";
  
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

  let chartSetting = {
    totalSuccessCBSCBPData01: {
      title: 'Total Success 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessCBSCBPData02: {
      title: 'Total Success 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalFailedCBSCBPData01: {
      title:  'Total Failed 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalFailedCBSCBPData02: {
      title: 'Total Failed 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessRateCBSCBPData01: {
      title:  'Success Rate Total 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessRateCBSCBPData02: {
      title: 'Success Rate Total 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },

    /////////////////////////////////tabriz///////////////////////////
    totalSuccessCBSCBPData03: {
      title: 'Total Success 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessCBSCBPData04: {
      title: 'Total Success 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalFailedCBSCBPData03: {
      title: 'Total Failed 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalFailedCBSCBPData04: {
      title: 'Total Failed 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessRateCBSCBPData03: {
      title:  'Success Rate Total 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessRateCBSCBPData04: {
      title: 'Success Rate Total 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },

    /////////////////////////////////Shiraz///////////////////////////
    totalSuccessCBSCBPData05: {
      title: 'Total Success 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessCBSCBPData06: {
      title: 'Total Success 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalFailedCBSCBPData05: {
      title: 'Total Failed 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalFailedCBSCBPData06: {
      title: 'Total Failed 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessRateCBSCBPData05: {
      title:  'Success Rate Total 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessRateCBSCBPData06: {
      title: 'Success Rate Total 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },

     /////////////////////////////////Mashhad///////////////////////////
     totalSuccessCBSCBPData07: {
      title: 'Total Success 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessCBSCBPData08: {
      title: 'Total Success 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalFailedCBSCBPData07: {
      title: 'Total Failed 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalFailedCBSCBPData08: {
      title: 'Total Failed 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessRateCBSCBPData07: {
      title:  'Success Rate Total 24H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessRateCBSCBPData08: {
      title: 'Success Rate Total 2H',
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
  };

// Control chart title show/hide.
if(!(typeof chartSetting === 'undefined')){
    // Control chart title show/hide.
    for(let chart of Object.entries(chartSetting)){
      if(chart[1].showTitle){
        $(`.chart-title-db[data-chart-name='${chart[0]}']`).show();
      }else{
        $(`.chart-title-db[data-chart-name='${chart[0]}']`).hide();
      }

      // Change name from config in js section.
      if(chart[1].title != null){
        $(`.chart-title-db[data-chart-name='${chart[0]}']`).html(chart[1].title);
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
    restartTimerTick(layoutRenders[i], API_PURE_URL);
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
    restartTimerTick(layoutRender, API_PURE_URL);
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
