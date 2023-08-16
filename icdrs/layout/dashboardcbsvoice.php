
<!DOCTYPE html>
<html>
<head>
  <title>CBS Voice</title>

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
<body class='body-db'>



<div class="container">
  <div class="chart-main-box-db">
    <h3 class='chart-title-db'>CBS Voice</h3>

      <div class="d-flex row row-db">
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Tehran</h3>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SEE1 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
            <h4 class="chart-title-db-3">SEE2 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
            <h4 class="chart-title-db-3">SEE3 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>     
            </div>
            <div class="col-md-2 col-md-db">
            <h4 class="chart-title-db-3">SEE4 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
            <h4 class="chart-title-db-3">SEE5 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
            <h4 class="chart-title-db-3">SEE6 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTehran', ChartAverageType::OneHour); ?>     
            </div>
          </div>
        </div>  
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Tabriz</h3>
          <div class="d-flex row">
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
              </div>
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
              </div>
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>     
              </div>
            </div>
            <div class="d-flex row">
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-4 col-md-db">
                <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>     
              </div>
            </div>
            <div class="d-flex row">
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE1 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE2 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE3 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>     
              </div>
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE4 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE5 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>
              </div>
              <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">TSEE6 Success 1h</h4>
                <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTTabriz', ChartAverageType::OneHour); ?>     
              </div>
            </div>
          </div>
      </div>
      <div class="d-flex row">
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Shiraz</h3>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE1 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE2 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE3 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>     
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE4 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE5 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">SSEE61 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTShiraz', ChartAverageType::OneHour); ?>     
            </div>
          </div>
        </div>
          
        <div class="col-md-6 col-md-db">
        <h3 class="chart-title-db-2">Mashhad</h3>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneDay); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalFailedCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-4 col-md-db">
              <?php $layoutRender->initChart('totalSuccessRateCBSSEEVoiceMOMT', ChartAverageType::OneHour); ?>     
            </div>
          </div>
          <div class="d-flex row">
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE1 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE2 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE3 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>     
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE4 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE5 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>
            </div>
            <div class="col-md-2 col-md-db">
              <h4 class="chart-title-db-3">MSEE6 Success 1h</h4>
              <?php $layoutRender->initChart('totalSuccessCBSSEEVoiceMOMTMashhad', ChartAverageType::OneHour); ?>     
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
    totalSuccessCBSSEEVoiceMOMT01: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessCBSSEEVoiceMOMT02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalFailedCBSSEEVoiceMOMT01: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalFailedCBSSEEVoiceMOMT02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessRateCBSSEEVoiceMOMT01: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    totalSuccessRateCBSSEEVoiceMOMT02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tehran']
    },
    

    totalSuccessCBSSEEVoiceMOMTTehran01: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see1']
    },
    totalSuccessCBSSEEVoiceMOMTTehran02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see2']
    },
    totalSuccessCBSSEEVoiceMOMTTehran03: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see3']
    },
    totalSuccessCBSSEEVoiceMOMTTehran04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see4']
    },
    totalSuccessCBSSEEVoiceMOMTTehran05: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see5']
    },
    totalSuccessCBSSEEVoiceMOMTTehran06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['see6']
    },


    /////////////////////////////////tabriz///////////////////////////
    totalSuccessCBSSEEVoiceMOMT03: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessCBSSEEVoiceMOMT04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalFailedCBSSEEVoiceMOMT03: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalFailedCBSSEEVoiceMOMT04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessRateCBSSEEVoiceMOMT03: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    totalSuccessRateCBSSEEVoiceMOMT04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Tabriz']
    },
    

    totalSuccessCBSSEEVoiceMOMTTabriz01: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee1']
    },
    totalSuccessCBSSEEVoiceMOMTTabriz02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee2']
    },
    totalSuccessCBSSEEVoiceMOMTTabriz03: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee3']
    },
    totalSuccessCBSSEEVoiceMOMTTabriz04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee4']
    },
    totalSuccessCBSSEEVoiceMOMTTabriz05: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee5']
    },
    totalSuccessCBSSEEVoiceMOMTTabriz06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['tsee6']
    },

    /////////////////////////////////Shiraz///////////////////////////
    totalSuccessCBSSEEVoiceMOMT05: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessCBSSEEVoiceMOMT06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalFailedCBSSEEVoiceMOMT05: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalFailedCBSSEEVoiceMOMT06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessRateCBSSEEVoiceMOMT05: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    totalSuccessRateCBSSEEVoiceMOMT06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Shiraz']
    },
    

    totalSuccessCBSSEEVoiceMOMTShiraz01: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee1']
    },
    totalSuccessCBSSEEVoiceMOMTShiraz02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee2']
    },
    totalSuccessCBSSEEVoiceMOMTShiraz03: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee3']
    },
    totalSuccessCBSSEEVoiceMOMTShiraz04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee4']
    },
    totalSuccessCBSSEEVoiceMOMTShiraz05: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee5']
    },
    totalSuccessCBSSEEVoiceMOMTShiraz06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['ssee6']
    },

     /////////////////////////////////Mashhad///////////////////////////
     totalSuccessCBSSEEVoiceMOMT07: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessCBSSEEVoiceMOMT08: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalFailedCBSSEEVoiceMOMT07: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalFailedCBSSEEVoiceMOMT08: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessRateCBSSEEVoiceMOMT07: {
      title: null,
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: true,
      heightPerPixel: 300,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    totalSuccessRateCBSSEEVoiceMOMT08: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['Mashhad']
    },
    

    totalSuccessCBSSEEVoiceMOMTMashhad01: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee1']
    },
    totalSuccessCBSSEEVoiceMOMTMashhad02: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee2']
    },
    totalSuccessCBSSEEVoiceMOMTMashhad03: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee3']
    },
    totalSuccessCBSSEEVoiceMOMTMashhad04: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee4']
    },
    totalSuccessCBSSEEVoiceMOMTMashhad05: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee5']
    },
    totalSuccessCBSSEEVoiceMOMTMashhad06: {
      title: null,
      showTitle: false,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: false,
      showYAxisNumber: false,
      heightPerPixel: 170,
      legendPerPixel: 0,
      showInformationSection: false, 
      showOptionSection: false,
      textColorOfChart: 0xCCCCDC,
      entityToShow: ['msee6']
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
