<?php
/**
 * All config for charts available in here.
 * 
 * Key in bellow arrays come from className of charts.
 */
$ChartAverageTypeConfig = [];

//////////// Iraq MSC //////////////
/*
$ChartAverageTypeConfig['totalCBSSEERecIraqMSC'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];*/

//////////// Roaming SMS //////////////
$ChartAverageTypeConfig['CBSCBPSmsRoamingActualUsage'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// Roaming Data //////////////
$ChartAverageTypeConfig['CBSCBPDataRoamingActualUsage'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// Roaming Voice //////////////
$ChartAverageTypeConfig['CBSCBPVoiceRoamingCallMin'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPVoiceRoamingCallMinUAETR'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];


//////////// Iran Voice //////////////
$ChartAverageTypeConfig['CBSCBPVoiceCellTotal'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['CBSCBPVoiceCellTotalIMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['CBSCBPVoiceCellProvinceCallMin'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['CBSCBPVoiceCellProvinceCallAmount'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['CBSCBPVoiceCellProvinceCallMinIMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['CBSCBPVoiceCellProvinceCallAmountIMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// Iran Data /////////////
$ChartAverageTypeConfig['CBSCBPDataCellTotal'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPDataCellProvinceActualUsage'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPDataCellProvinceAmount'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPDataCellTotalRg91'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPDataCellTotalRgKSA'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// Iran SMS /////////////
$ChartAverageTypeConfig['CBSCBPSmsCellTotal'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPSmsCellProvinceActualUsage'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['CBSCBPSmsCellProvinceAmount'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS CBPA Voice CAPS //////////////

$ChartAverageTypeConfig['totalCAPSCBSCBPAVoice'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalCAPSCBSCBPAData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalCAPSCBSCBPASMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalTPSCBSCBPAData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];


$ChartAverageTypeConfig['totalOnlineSessionCBSCBPAData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalOnlineSessionCBSCBPAVoice'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalOnlineDelayCBSCBPAVoice'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalOnlineDelayCBSCBPAData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];


//////////// Traffic CBS SEE Voice MOMT //////////////

$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMOMT'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMOMT'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMOMTTehran'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMOMTTabriz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMOMTShiraz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMOMTMashhad'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMOMTTehran'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMOMTTabriz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMOMTShiraz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMOMTMashhad'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS MSC Signaling //////////////
$ChartAverageTypeConfig['totalErrorCBSSEERecMSC'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEERecMSCNokia'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSSEERecMSC'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
/*
//////////// Success Rate CBS SEE Voice MOMT //////////////

$ChartAverageTypeConfig['totalSuccessRateCBSSEEVoiceMOMT'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSSEEVoiceMOMTTehran'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSSEEVoiceMOMTTabriz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSSEEVoiceMOMTShiraz'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSSEEVoiceMOMTMashhad'] = [
    ChartAverageType::OneHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];*/

//////////// CBS CBP Data //////////////
$ChartAverageTypeConfig['totalSuccessCBSCBPData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth,
    ChartAverageType::OneMonth

];
$ChartAverageTypeConfig['totalFailedCBSCBPData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSCBPDataTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSCBPDataTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSCBPDataShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSCBPDataMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSCBPDataTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSCBPDataTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSCBPDataShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSCBPDataMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS CBP Data Success Rate //////////////
/*
$ChartAverageTypeConfig['totalSuccessRateCBSCBPData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSCBPDataTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSCBPDataTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSCBPDataShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessRateCBSCBPDataMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
*/
//////////// CBS CBP SMS Traffic //////////////

$ChartAverageTypeConfig['totalSuccessCBSCBPSMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSCBPSMSTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSCBPSMSShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSCBPSMSMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSCBPSMSTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSCBPSMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSCBPSMSTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSCBPSMSShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSCBPSMSMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSCBPSMSTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

/*
//////////// CBS CBP SMS SuccessRate //////////////

$ChartAverageTypeConfig['totalSuccessRateCBSCBPSMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSCBPSMSShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSCBPSMSMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSCBPSMSTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessRateCBSCBPSMSTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS CBP REC Call Type Traffic //////////////

$ChartAverageTypeConfig['totalSuccessCBSSEERecCallType'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEERecCallType'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEERecCallTypeTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEERecCallTypeTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEERecCallTypeShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEERecCallTypeMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEERecCallTypeTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEERecCallTypeTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEERecCallTypeShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEERecCallTypeMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];


//////////// MGM CBS SEE Voice MOMT //////////////

$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMGM'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMGMMashhad'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMGMShiraz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMGMTabriz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceMGMTehran'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMGM'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMGMMashhad'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMGMShiraz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMGMTabriz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceMGMTehran'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS SEE Voice IVR //////////////

$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceIVRCall'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceIVRCall'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceIVRCallMashhad'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceIVRCallShiraz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceIVRCallTabriz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalSuccessCBSSEEVoiceIVRCallTehran'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceIVRCallMashhad'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceIVRCallShiraz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceIVRCallTabriz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalFailedCBSSEEVoiceIVRCallTehran'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];*/

//////////// Error CBS SEE Voice MOMT //////////////

$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMT'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehran'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabriz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShiraz'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhad'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee1'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee2'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee3'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee4'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee5'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTMashhadsee6'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee1'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee2'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee3'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee4'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee5'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTShirazsee6'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee1'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee2'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee3'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee4'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee5'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTabrizsee6'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee1'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee2'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee3'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee4'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee5'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSSEEVoiceMOMTTehransee6'] = [
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

//////////// CBS CBP Data Error //////////////
$ChartAverageTypeConfig['totalErrorCBSCBPData'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalErrorCBSCBPDataTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPDataTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPDataShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPDataMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];


//////////// CBS CBP SMS Error //////////////
$ChartAverageTypeConfig['totalErrorCBSCBPSMS'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];

$ChartAverageTypeConfig['totalErrorCBSCBPSMSTehran'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPSMSTabriz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPSMSShiraz'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
$ChartAverageTypeConfig['totalErrorCBSCBPSMSMashhad'] = [
    ChartAverageType::TwoHour,
    ChartAverageType::OneDay,
    ChartAverageType::ThreeDays,
    ChartAverageType::OneWeek,
    ChartAverageType::OneMonth
];
