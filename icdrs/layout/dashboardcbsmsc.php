
<!DOCTYPE html>
<html>
<head>
  <title>CBS MSC Success Signaling</title>

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
  <meta http-equiv="refresh" content="3600">

</head>
<body>


<div class="container">
  <div class="chart-main-box-db">
    <h3 class='chart-title-db'>CBS MSC Success Signaling</h3>
    <div class="d-flex row">
      <div class="col-md-3 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
    </div> 
    <div class="d-flex row">
      <div class="col-md-3 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-3 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
    </div>
    <div class="d-flex row">
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
    </div> 
    <div class="d-flex row">
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
    </div>  
    <div class="d-flex row">
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
    </div>  
    <div class="d-flex row">
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
        <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
      </div>
      <div class="col-md-2 col-md-db">
       <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::TwoHour); ?>
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
    totalErrorCBSSEERecMSC01: {
      title: "Tehran",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Beheshti B','Kazemian B','Rahahan C','Tohid B']
    },
    totalErrorCBSSEERecMSC02: {
      title: "Tabriz",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz A','Tabriz B','Tabriz C']
    },
    totalErrorCBSSEERecMSC03: {
      title: "Transit Huawei",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['TAhvaz A','TBabol A','Tbeheshti B','THamedan A','TRahahan B','TTabriz A']
    },
    totalErrorCBSSEERecMSC04: {
      title: "Ahvaz",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Ahvaz A','Ahvaz B','Ahvaz C']
    },
    totalErrorCBSSEERecMSC05: {
      title: "Mazandaran",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Sari A','Noshahr A','Babol A']
    },
    totalErrorCBSSEERecMSC06: {
      title: "Gilan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Lahijan A','Rasht B']
    },
    totalErrorCBSSEERecMSC07: {
      title: "Orumieh",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Orumieh A','Orumieh B']
    },
    totalErrorCBSSEERecMSC08: {
      title: "Hamedan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Hamedan A','Hamedan B']
    },
    totalErrorCBSSEERecMSC09: {
      title: "Sanandaj",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Sanandaj A']
    },
    totalErrorCBSSEERecMSC10: {
      title: "Semnan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Semnan B']
    },
    totalErrorCBSSEERecMSC11: {
      title: "Zanjan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Zanjan A']
    },
    totalErrorCBSSEERecMSC12: {
      title: "Qazvin",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Qazvin A']
    },
    totalErrorCBSSEERecMSC13: {
      title: "Golestan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Golestan A']
    },
    totalErrorCBSSEERecMSC14: {
      title: "Kermanshah",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Kermanshah A']
    },
    totalErrorCBSSEERecMSC15: {
      title: "Khoramabad",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Khoramabad A']
    },
    totalErrorCBSSEERecMSC16: {
      title: "ILAM",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ILAM A']
    },
    totalErrorCBSSEERecMSC17: {
      title: "Arak",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Arak B']
    },
    totalErrorCBSSEERecMSC18: {
      title: "Ardabil",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Ardabil A']
    },

    //////////////////NOKIA///////////////////////////////////
    totalErrorCBSSEERecMSCNokia01: {
      title: "Mashhad",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad A','Mashhad B','Mashhad C','Mashhad D']
    },
    totalErrorCBSSEERecMSCNokia02: {
      title: "Esfehan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Esfehan A','Esfehan B','Esfehan C','Esfehan D']
    },
    totalErrorCBSSEERecMSCNokia03: {
      title: "Shiraz",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz B','Shiraz C','Shiraz D']
    },
    totalErrorCBSSEERecMSCNokia04: {
      title: "Transit Nokia",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['TEsfehan A','TMashhad A','TMashhad B','TShiraz A']
    },
    totalErrorCBSSEERecMSCNokia05: {
      title: "Yazd",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Yazd A','Yazd B']
    },
    totalErrorCBSSEERecMSCNokia06: {
      title: "Kerman",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Kerman A','Kerman B']
    },
    totalErrorCBSSEERecMSCNokia07: {
      title: "BandarAbas",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['BandarAbas A','BandarAbas B']
    },
    totalErrorCBSSEERecMSCNokia08: {
      title: "Zahedan",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Zahedan B']
    },
    totalErrorCBSSEERecMSCNokia09: {
      title: "Yasoj",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Yasoj A']
    },
    totalErrorCBSSEERecMSCNokia10: {
      title: "ShahrKord",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ShahrKord A']
    },
    totalErrorCBSSEERecMSCNokia11: {
      title: "Qom",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Qom A']
    },
    totalErrorCBSSEERecMSCNokia12: {
      title: "Birjand",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Birjand A']
    },
    totalErrorCBSSEERecMSCNokia13: {
      title: "Bojnord",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Bojnord A']
    },
    totalErrorCBSSEERecMSCNokia14: {
      title: "Boshehr",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 200,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Boshehr A']
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
