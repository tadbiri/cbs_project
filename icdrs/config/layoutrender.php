<?php
class LayoutRender{
  private $initedCharts = [];
  public $javascriptObject = [];
  public function initJavaScriptFunction(){
    echo json_encode($this->javascriptObject);
  }
  public function initJavaScriptFunctionDict(){
    $i=0;
    $result = [];
    foreach($this->javascriptObject as $chart){
      $result[$chart['chartName']] = $i; 
      $i++;
    }
    echo json_encode($result);
  }
  public function handleErrorInitChart($chartNameJsObjectIndex, $message){
    if($chartNameJsObjectIndex != null){
      $this->javascriptObject[$chartNameJsObjectIndex]['status'] = false;
      $this->javascriptObject[$chartNameJsObjectIndex]['errorMessage'] = $message;
    }
    echo "<h4 style='color: red;'>$message</h5>";
  }
  /**
  * Get a new name for a chart.
  * This function get a chart name and append a index to it to keep unified chart name law.
  * 
  * @param array $initedChart a list for inited chart.
  * #param strnig $chartName name for new chart that want to added layout.
  */
  function generateChartName(&$initedChart, $chartName){
    $result = array_map(function($item){
        return substr($item, 0, strlen($item)-2);
    }, $initedChart);
    sort($result);
    $chartCountMap = array_count_values($result);
    if(!isset($chartCountMap[$chartName])){
        $newChartName = $chartName."01";
        $initedChart[] = $newChartName;
        return $newChartName;
    }
    $lastIndexOfChart = (int) $chartCountMap[$chartName];
    $lastIndexOfChart++;
    $newChartName = $chartName.sprintf("%02d", $lastIndexOfChart);
    $initedChart[] = $newChartName;
    return $newChartName;
  }
  /**
   * param string @chartname chart name
   * param class ChartAverageType set for default chart type.
   */
  public function initChart($chartName, $chartAverageType){
    // Check that it is analytic chart or not.
    $isAnalytic = false;
    if(count(explode("-", $chartName)) == 2){
      if(explode("-", $chartName)[1] == 'analytic'){
        $isAnalytic = true;
        $chartName = explode("-", $chartName)[0];
      }
    }
    $pureChartName = $chartName;
    $chartName = $this->generateChartName($this->initedCharts, $chartName);
    /**
     * Init javascript object of chart.
     * Get index of inited object.
     */
    $this->javascriptObject[] = [
      'chartName'=>$chartName,
      'pureChartName'=>$pureChartName,
      'status'=>true,
      'errorMessage'=>'',
      'chartAverageTypePrevious'=>[],
      'chartAverageType'=>[],
      'timerHandler'=>null,
      'chartObject'=>null,
      'graphTagIdPrefix'=>'',
      'graphTagId'=>'',
      'chartBoxHeightPerPixel'=>'',
      'chartLengendHeightPerPixel'=>'',
      'graphType'=>'',
      'isAnalytic'=>$isAnalytic
    ];
    $_chartNameJsObjectIndex = array_search($chartName, array_column($this->javascriptObject, 'chartName'));
    
    // Check that chart is exist.
    global $ChartAverageTypeConfig;
    if(!is_numeric(array_search($pureChartName, array_keys($ChartAverageTypeConfig)))){
      $this->handleErrorInitChart($_chartNameJsObjectIndex, "Error: $chartName not exist in app.");
      return;
    }
    // Load chart lib and get instance of them.
    require_once dirname(__DIR__, 1)."/api/".$pureChartName.".php";
    $chartInstance = new $pureChartName();
    $_graphHolderTagSeq = 0;
    $_graphPrefix = $chartName."_holder_";
    $_graphHolderTag_Id = $_graphPrefix.$_graphHolderTagSeq;
    $this->javascriptObject[$_chartNameJsObjectIndex]['graphTagIdPrefix'] = $_graphPrefix;
    $this->javascriptObject[$_chartNameJsObjectIndex]['graphTagId'] = $_graphHolderTag_Id;
    $this->javascriptObject[$_chartNameJsObjectIndex]['chartBoxHeightPerPixel'] = $chartInstance->chartBoxHeightPerPixel;
    $this->javascriptObject[$_chartNameJsObjectIndex]['chartLengendHeightPerPixel'] = $chartInstance->chartLengendHeightPerPixel;
    $this->javascriptObject[$_chartNameJsObjectIndex]['graphType'] = $chartInstance->graphType;
    // Make HTML section.
    $html = "
    <div class='chart-box'>
      <h3 class='chart-title' data-chart-name='$chartName'>$chartInstance->chartTitle</h3>
      <div class='chart-holder' data-chart-name='$chartName'>
        <div class='chart-style-div chart-item chart-item-$chartName' data-chart-name='$chartName' 
             data-prefix-id='$_graphPrefix' data-seq='$_graphHolderTagSeq' id='$_graphHolderTag_Id'>
        </div>
      </div>
      <div class='d-flex'>
        <!-- Blocks of change data options. -->";
    $html .= "<div class='chart-check-box'>";
    $_averageTypes = $ChartAverageTypeConfig[$pureChartName];
    
    foreach ($_averageTypes as $_averageType) {
      $_typeObject = json_encode($_averageType); 
      $_checkedStatus = '';
      if(($chartAverageType['name'] == $_averageType['name'])){
        $this->javascriptObject[$_chartNameJsObjectIndex]['chartAverageType'] = $_averageType;        
        $this->javascriptObject[$_chartNameJsObjectIndex]['chartAverageTypePrevious'] = $_averageType;
        $_checkedStatus = "checked='null'";
      }
      $html .= "
          <div class='form-check option-section-".$chartName."'>
            <input class='form-check-input' type='radio' name='$chartName' id='".$chartName.'-'.$_averageType['name']."' data-type='$_typeObject' $_checkedStatus>
            <label class='form-check-label' for='".$chartName.'-'.$_averageType['name']."'>".$_averageType['label']."</label>
          </div>
          ";
    }
    $html .= "</div>";
    $chartDurationTagId = $chartName."-chart-duration";
    $chartRuntimeMessageTagId = $chartName."-chart-runtime-message";
    $chartNextUpdateTagId = $chartName."-chart-next-update";
    $html .= "
        <div class='m-l-20 information-section-".$chartName."'>        
          <div class='form-check' id='$chartDurationTagId'>
            <label class='form-check-label'>Chart Duration: </label>
            <span class='form-check-label m-l-10 font12'>NaN</span>
          </div>
          <div class='form-check chart-next-update' id='$chartNextUpdateTagId'>
            <label class='form-check-label'>Time to next update: </label>
            <span class='form-check-label m-l-10 font12'>NaN</span>
          </div>
          <div class='form-check hide' id='$chartRuntimeMessageTagId'>
            <label class='form-check-label colorRed'>Error: </label>
            <span class='form-check-label m-l-10 colorRed font12'></span>
          </div>
        <div>";
    
    $html .= "</div></div></div></div>";
    echo $html;
  }
}