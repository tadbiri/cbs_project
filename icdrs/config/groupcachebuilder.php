<?php

// Load libs.
require_once dirname(__DIR__, 1)."/config/localconfig.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetypeconfig.php";

$ChartGroups = [    
    [
        CBSCBPVoiceCellTotal::class,
        CBSCBPVoiceCellTotalIMS::class,
        CBSCBPVoiceCellProvinceCallMin::class,
        CBSCBPVoiceCellProvinceCallAmount::class,
        CBSCBPVoiceCellProvinceCallMinIMS::class,
        CBSCBPVoiceCellProvinceCallAmountIMS::class,
        CBSCBPVoiceRoamingCallMin::class,
        CBSCBPVoiceRoamingCallMinUAETR::class,
    ],
    [
        CBSCBPSmsCellTotal::class,
        CBSCBPSmsCellProvinceActualUsage::class,
        CBSCBPSmsCellProvinceAmount::class,
        CBSCBPSmsRoamingActualUsage::class,
    ],
    [
        CBSCBPDataCellTotal::class,
        CBSCBPDataCellProvinceActualUsage::class,
        CBSCBPDataCellProvinceAmount::class,
        CBSCBPDataCellTotalRg91::class,
        CBSCBPDataCellTotalRgKSA::class,
        CBSCBPDataRoamingActualUsage::class,
    ],
    [
        totalCAPSCBSCBPAVoice::class,
        totalCAPSCBSCBPAData::class,
        totalCAPSCBSCBPASMS::class,
        totalTPSCBSCBPAData::class,
        totalOnlineSessionCBSCBPAData::class,
        totalOnlineSessionCBSCBPAVoice::class,
        totalOnlineDelayCBSCBPAVoice::class,
        totalOnlineDelayCBSCBPAData::class,
    ],
    [
        totalFailedCBSSEEVoiceMOMT::class,
        totalSuccessCBSSEEVoiceMOMT::class,
        totalSuccessCBSSEEVoiceMOMTTehran::class,
        totalSuccessCBSSEEVoiceMOMTTabriz::class,
        totalSuccessCBSSEEVoiceMOMTShiraz::class,
        totalSuccessCBSSEEVoiceMOMTMashhad::class,
        totalFailedCBSSEEVoiceMOMTTehran::class,
        totalFailedCBSSEEVoiceMOMTTabriz::class,
        totalFailedCBSSEEVoiceMOMTShiraz::class,
        totalFailedCBSSEEVoiceMOMTMashhad::class,
    ],
    [
        totalErrorCBSSEERecMSC::class,
        totalErrorCBSSEERecMSCNokia::class,
        totalFailedCBSSEERecMSC::class,
        //totalCBSSEERecIraqMSC::class,
    ],/*
    [
        totalSuccessRateCBSSEEVoiceMOMT::class,
        totalSuccessRateCBSSEEVoiceMOMTTehran::class,
        totalSuccessRateCBSSEEVoiceMOMTTabriz::class,
        totalSuccessRateCBSSEEVoiceMOMTShiraz::class,
        totalSuccessRateCBSSEEVoiceMOMTMashhad::class,
    ],*/
    [
        totalSuccessCBSCBPData::class,
        totalFailedCBSCBPData::class,
        totalSuccessCBSCBPDataTehran::class,
        totalSuccessCBSCBPDataTabriz::class,
        totalSuccessCBSCBPDataShiraz::class,
        totalSuccessCBSCBPDataMashhad::class,
        totalFailedCBSCBPDataTehran::class,
        totalFailedCBSCBPDataTabriz::class,
        totalFailedCBSCBPDataShiraz::class,
        totalFailedCBSCBPDataMashhad::class,
        //totalSuccessRateCBSCBPData::class,
        //totalSuccessRateCBSCBPDataTehran::class,
        //totalSuccessRateCBSCBPDataTabriz::class,
        //totalSuccessRateCBSCBPDataShiraz::class,
        //totalSuccessRateCBSCBPDataMashhad::class,
    ],
    [
        totalFailedCBSCBPSMSTehran::class,
        totalSuccessCBSCBPSMS::class,
        totalSuccessCBSCBPSMSTehran::class,
        totalSuccessCBSCBPSMSShiraz::class,
        totalSuccessCBSCBPSMSMashhad::class,
        totalSuccessCBSCBPSMSTabriz::class,
        totalFailedCBSCBPSMS::class,
        totalFailedCBSCBPSMSShiraz::class,
        totalFailedCBSCBPSMSMashhad::class,
        totalFailedCBSCBPSMSTabriz::class,
        //totalSuccessRateCBSCBPSMS::class,
        //totalSuccessRateCBSCBPSMSShiraz::class,
        //totalSuccessRateCBSCBPSMSMashhad::class,
        //totalSuccessRateCBSCBPSMSTehran::class,
        //totalSuccessRateCBSCBPSMSTabriz::class,
    ],/*
    [
        totalSuccessCBSSEERecCallType::class,
        totalFailedCBSSEERecCallType::class,
        totalSuccessCBSSEERecCallTypeTehran::class,
        totalSuccessCBSSEERecCallTypeTabriz::class,
        totalSuccessCBSSEERecCallTypeShiraz::class,
        totalSuccessCBSSEERecCallTypeMashhad::class,
        totalFailedCBSSEERecCallTypeTehran::class,
        totalFailedCBSSEERecCallTypeTabriz::class,
        totalFailedCBSSEERecCallTypeShiraz::class,
        totalFailedCBSSEERecCallTypeMashhad::class,
        totalSuccessCBSSEEVoiceMGM::class,
        totalSuccessCBSSEEVoiceMGMMashhad::class,
        totalSuccessCBSSEEVoiceMGMShiraz::class,
        totalSuccessCBSSEEVoiceMGMTabriz::class,
        totalSuccessCBSSEEVoiceMGMTehran::class,
        totalFailedCBSSEEVoiceMGM::class,
        totalFailedCBSSEEVoiceMGMMashhad::class,
        totalFailedCBSSEEVoiceMGMShiraz::class,
        totalFailedCBSSEEVoiceMGMTabriz::class,
        totalFailedCBSSEEVoiceMGMTehran::class,
    ],
    [
        totalFailedCBSSEEVoiceIVRCall::class,
        totalFailedCBSSEEVoiceIVRCallMashhad::class,
        totalFailedCBSSEEVoiceIVRCallShiraz::class,
        totalFailedCBSSEEVoiceIVRCallTabriz::class,
        totalFailedCBSSEEVoiceIVRCallTehran::class,
    ],*/
    [
        totalErrorCBSSEEVoiceMOMT::class,
        totalErrorCBSSEEVoiceMOMTTehran::class,
        totalErrorCBSSEEVoiceMOMTTabriz::class,
        totalErrorCBSSEEVoiceMOMTShiraz::class,
        totalErrorCBSSEEVoiceMOMTMashhad::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee1::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee2::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee3::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee4::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee5::class,
        totalErrorCBSSEEVoiceMOMTMashhadsee6::class,
        totalErrorCBSSEEVoiceMOMTShirazsee1::class,
        totalErrorCBSSEEVoiceMOMTShirazsee2::class,
        totalErrorCBSSEEVoiceMOMTShirazsee3::class,
        totalErrorCBSSEEVoiceMOMTShirazsee4::class,
        totalErrorCBSSEEVoiceMOMTShirazsee5::class,
        totalErrorCBSSEEVoiceMOMTShirazsee6::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee1::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee2::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee3::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee4::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee5::class,
        totalErrorCBSSEEVoiceMOMTTabrizsee6::class,
        totalErrorCBSSEEVoiceMOMTTehransee1::class,
        totalErrorCBSSEEVoiceMOMTTehransee2::class,
        totalErrorCBSSEEVoiceMOMTTehransee3::class,
        totalErrorCBSSEEVoiceMOMTTehransee4::class,
        totalErrorCBSSEEVoiceMOMTTehransee5::class,
        totalErrorCBSSEEVoiceMOMTTehransee6::class,
        totalErrorCBSCBPData::class,
        totalErrorCBSCBPDataTehran::class,
        totalErrorCBSCBPDataTabriz::class,
        totalErrorCBSCBPDataShiraz::class,
        totalErrorCBSCBPDataMashhad::class,
        totalErrorCBSCBPSMS::class,
        totalErrorCBSCBPSMSTehran::class,
        totalErrorCBSCBPSMSTabriz::class,
        totalErrorCBSCBPSMSShiraz::class,
        totalErrorCBSCBPSMSMashhad::class,
    ],
];

/**
 * In here check that all charts that configured in up is exist in app.   
 * Get all configed chart.
 */
$chartNames = array_keys($ChartAverageTypeConfig);
$_chartIterated = [];
foreach($ChartGroups as $ChartGroup){
    foreach($ChartGroup as $chart){
        if(in_array($chart, $_chartIterated)){
            echo "Error: '$chart' defined twice or more than one time(s) \n";
            exit;
        }
        $_chartIterated[] = $chart;
        if(!in_array($chart, $chartNames)){
            echo "ERROR: '$chart' existed in config, but not found in App (ChartAverageTypeConfig) \n";
            exit;
        }
    }
}

?>