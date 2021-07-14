<?php

//include_once realpath(__DIR__ . '/../..').'/include/checkpermission.php';
require_once realpath(__DIR__ . '/../..') . '/class/SystemMessage.php';
require_once realpath(__DIR__ . '/../..') . '/class/Installation.class.php';
require_once realpath(__DIR__ . '/../..') . '/class/Helper.php';
include_once realpath(__DIR__ . '/../..') . '/include/checksession.php';
require_once realpath(__DIR__ . '/../..') . '/include/settings.php';
require_once realpath(__DIR__ . '/../..') . '/class/UserLog.class.php';
$Installation = new Installation();
$message = New SysetemMessage($LANGUAGE);
$User_Log = new User_Log();

$data =array();
$point_id = Helper::Request("point_id", true);
$data['point_id'] = $point_id;
$data['status_type_id'] = Helper::Request("status_type_id", true);
$data['installation_user_id'] = $USERID;

$LogData = array();
$LogData["USER_ID"] = $USERID;
$LogData["TIMESTAMP"] = date("Y-m-d H:i:s");
$LogData["MODULE_ID"] = 3;
$LogData["OPERATION_ID"] = 3;
$LogData["KEY_DATA"] = $point_id;
$LogData["NEW_DATA"] = json_encode($data);
$LogData["OLD_DATA"] = $User_Log->GetJsonData("service_point", ["point_id"=>$point_id]);
$LogData["CRUD_OPERATION_ID"] = 2;
$LogData["TABLE_NAME"] = "service_point";
$LogData["RECORD_ID"] = $point_id;
$User_Log->AddRecord($LogData);


$result = $Installation -> EditPointInstallationStatus($data);

if($result != false){
    //print $result;
    $filter["service_point.point_id"] = $point_id;
    $ServicePoint = $Installation->GetServicePoint($filter);
    print $ServicePoint[0]["installation_status_id"];
} else {
	//echo $Installation->Message;
	//error
	$message -> AddMessage($Installation->State, $Installation->Message);
	//$message -> PrintJsonMessage();
}
?>
