
<!DOCTYPE html>
<html>
<head>
  <title>CBS MSC Traffic</title>

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
    require_once dirname(__DIR__)."/config/localconfig.php";
    require_once FullCommonPath."/modules/main_menu.php" ;
    
    require_once dirname(__DIR__)."/config/chartaveragetype.php";
    require_once dirname(__DIR__)."/config/chartaveragetypeconfig.php";
    require_once dirname(__DIR__)."/config/layoutrender.php";
    
    $layoutRender = new LayoutRender();
    $layoutRender->showLayoutBox = false;
  ?>
  
</head>
<body>
<?php Main_menu(); ?>

<div class="container">
  <div class="chart-main-box" style='padding-left:46px; padding-right:5px;'>
    <h3 class='chart-title'>CBS MSC Traffic</h3>
    <div class="d-flex row">
      <div class="col-md-6 col-md">
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalErrorCBSSEERecMSCNokia', ChartAverageType::OneDay); ?>
        </div>
      </div> 
      <div class="col-md-6 col-md">
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
          <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
        </div>
        <div class="col-md-12 col-md">
         <?php $layoutRender->initChart('totalFailedCBSSEERecMSC', ChartAverageType::OneDay); ?>
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
    totalErrorCBSSEERecMSC01: {
      title: "Tehran Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Beheshti B','Kazemian B','Rahahan C','Tohid B']
    },
    totalErrorCBSSEERecMSC02: {
      title: "Tabriz Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Tabriz A','Tabriz B','Tabriz C']
    },
    totalErrorCBSSEERecMSC03: {
      title: "Transit Huawei Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['TAhvaz A','TBabol A','Tbeheshti B','THamedan A','TRahahan B','TTabriz A']
    },
    totalErrorCBSSEERecMSC04: {
      title: "Ahvaz Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Ahvaz A','Ahvaz B','Ahvaz C']
    },
    totalErrorCBSSEERecMSC05: {
      title: "Mazandaran Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Sari A','Noshahr A','Babol A']
    },
    totalErrorCBSSEERecMSC06: {
      title: "Gilan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Lahijan A','Rasht B']
    },
    totalErrorCBSSEERecMSC07: {
      title: "Orumieh Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Orumieh A','Orumieh B']
    },
    totalErrorCBSSEERecMSC08: {
      title: "Hamedan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Hamedan A','Hamedan B']
    },
    totalErrorCBSSEERecMSC09: {
      title: "Sanandaj Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Sanandaj A']
    },
    totalErrorCBSSEERecMSC10: {
      title: "Semnan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Semnan B']
    },
    totalErrorCBSSEERecMSC11: {
      title: "Zanjan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Zanjan A']
    },
    totalErrorCBSSEERecMSC12: {
      title: "Qazvin Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Qazvin A']
    },
    totalErrorCBSSEERecMSC13: {
      title: "Golestan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Golestan A']
    },
    totalErrorCBSSEERecMSC14: {
      title: "Kermanshah Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Kermanshah A']
    },
    totalErrorCBSSEERecMSC15: {
      title: "Khoramabad Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Khoramabad A']
    },
    totalErrorCBSSEERecMSC16: {
      title: "ILAM Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['ILAM A']
    },
    totalErrorCBSSEERecMSC17: {
      title: "Arak Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Arak B']
    },
    totalErrorCBSSEERecMSC18: {
      title: "Ardabil Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Ardabil A']
    },

    //////////////////NOKIA///////////////////////////////////
    totalErrorCBSSEERecMSCNokia01: {
      title: "Mashhad Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Mashhad A','Mashhad B','Mashhad C','Mashhad D']
    },
    totalErrorCBSSEERecMSCNokia02: {
      title: "Esfehan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Esfehan A','Esfehan B','Esfehan C','Esfehan D']
    },
    totalErrorCBSSEERecMSCNokia03: {
      title: "Shiraz Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Shiraz B','Shiraz C','Shiraz D']
    },
    totalErrorCBSSEERecMSCNokia04: {
      title: "Transit Nokia Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['TEsfehan A','TMashhad A','TMashhad B','TShiraz A']
    },
    totalErrorCBSSEERecMSCNokia05: {
      title: "Yazd Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Yazd A','Yazd B']
    },
    totalErrorCBSSEERecMSCNokia06: {
      title: "Kerman Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Kerman A','Kerman B']
    },
    totalErrorCBSSEERecMSCNokia07: {
      title: "BandarAbas Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['BandarAbas A','BandarAbas B']
    },
    totalErrorCBSSEERecMSCNokia08: {
      title: "Zahedan Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Zahedan B']
    },
    totalErrorCBSSEERecMSCNokia09: {
      title: "Yasoj Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Yasoj A']
    },
    totalErrorCBSSEERecMSCNokia10: {
      title: "ShahrKord Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['ShahrKord A']
    },
    totalErrorCBSSEERecMSCNokia11: {
      title: "Qom Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Qom A']
    },
    totalErrorCBSSEERecMSCNokia12: {
      title: "Birjand Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Birjand A']
    },
    totalErrorCBSSEERecMSCNokia13: {
      title: "Bojnord Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Bojnord A']
    },
    totalErrorCBSSEERecMSCNokia14: {
      title: "Boshehr Success",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Boshehr A']
    },
    
    ///////////////CBS MSC Tarffic Failed////////////
    totalFailedCBSSEERecMSC01: {
      title: "Tehran Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Beheshti B','Kazemian B','Rahahan C','Tohid B']
    },
    totalFailedCBSSEERecMSC02: {
      title: "Mashhad Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Mashhad A','Mashhad B','Mashhad C','Mashhad D']
    },
    totalFailedCBSSEERecMSC03: {
      title: "Esfehan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Esfehan A','Esfehan B','Esfehan C','Esfehan D']
    },
    totalFailedCBSSEERecMSC04: {
      title: "Tabriz Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Tabriz A','Tabriz B','Tabriz C']
    },
    totalFailedCBSSEERecMSC05: {
      title: "Shiraz Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Shiraz B','Shiraz C','Shiraz D']
    },
    totalFailedCBSSEERecMSC06: {
      title: "Transit Huawei Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['TAhvaz A','TBabol A','Tbeheshti B','THamedan A','TRahahan B','TTabriz A']
    },
    totalFailedCBSSEERecMSC07: {
      title: "Transit Nokia Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['TEsfehan A','TMashhad A','TMashhad B','TShiraz A']
    },
    totalFailedCBSSEERecMSC08: {
      title: "Ahvaz Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Ahvaz A','Ahvaz B','Ahvaz C']
    },
    totalFailedCBSSEERecMSC09: {
      title: "Mazandaran Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Sari A','Noshahr A','Babol A']
    },
    totalFailedCBSSEERecMSC10: {
      title: "Yazd Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Yazd A','Yazd B']
    },
    totalFailedCBSSEERecMSC11: {
      title: "Kerman Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Kerman A','Kerman B']
    },
    totalFailedCBSSEERecMSC12: {
      title: "Gilan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Lahijan A','Rasht B']
    },
    totalFailedCBSSEERecMSC13: {
      title: "Orumieh Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Orumieh A','Orumieh B']
    },
    totalFailedCBSSEERecMSC14: {
      title: "Hamedan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Hamedan A','Hamedan B']
    },
    totalFailedCBSSEERecMSC15: {
      title: "BandarAbas Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['BandarAbas A','BandarAbas B']
    },
    totalFailedCBSSEERecMSC16: {
      title: "Sanandaj Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Sanandaj A']
    },
    totalFailedCBSSEERecMSC17: {
      title: "Semnan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Semnan B']
    },
    totalFailedCBSSEERecMSC18: {
      title: "Zanjan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Zanjan A']
    },
    totalFailedCBSSEERecMSC19: {
      title: "Qazvin Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Qazvin A']
    },
    totalFailedCBSSEERecMSC20: {
      title: "Golestan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Golestan A']
    },
    totalFailedCBSSEERecMSC21: {
      title: "Zahedan Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Zahedan B']
    },
    totalFailedCBSSEERecMSC22: {
      title: "Kermanshah Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Kermanshah A']
    },
    totalFailedCBSSEERecMSC23: {
      title: "Yasoj Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Yasoj A']
    },
    totalFailedCBSSEERecMSC24: {
      title: "ShahrKord Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['ShahrKord A']
    },
    totalFailedCBSSEERecMSC25: {
      title: "Khoramabad Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Khoramabad A']
    },
    totalFailedCBSSEERecMSC26: {
      title: "ILAM Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['ILAM A']
    },
    totalFailedCBSSEERecMSC27: {
      title: "Arak Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Arak B']
    },
    totalFailedCBSSEERecMSC28: {
      title: "Ardabil Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Ardabil A']
    },
    totalFailedCBSSEERecMSC29: {
      title: "Qom Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Qom A']
    },
    totalFailedCBSSEERecMSC30: {
      title: "Birjand Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Birjand A']
    },
    totalFailedCBSSEERecMSC31: {
      title: "Bojnord Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Bojnord A']
    },
    totalFailedCBSSEERecMSC32: {
      title: "Boshehr Failed",
      showTitle: true,
      descriptionPlace: DescriptionPlace.IN_TOOLTIP,
      showLegend: true,
      showYAxisNumber: true,
      heightPerPixel: 350,
      legendPerPixel: 18,
      showInformationSection: false, 
      showOptionSection: true,
      textColorOfChart: 0x838383,
      entityToShow: ['Boshehr A']
    },

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
