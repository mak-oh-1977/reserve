<?php
 /*  * Apiクラス(abstract)
 * Aoi は一切の出力を行わない
 *
 * @package Api
 */
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');


include_once(__DIR__ . "/../Classes/api.php");

session_start();

$parm = $_POST;

if (empty($parm)) {

  $parm = json_decode(file_get_contents('php://input'), true);
}

$res['res'] = "NG";


if (!isset($parm["MOD"])) {
  $parm = json_decode(file_get_contents('php://input'), true);
  if (!isset($parm["MOD"])) {
    $res['msg'] = "not set MOD " . print_r($parm, TRUE);

    log::error("not set MOD " . print_r($parm, TRUE));

    SendResponse($res);
    return;
  }
}


if ($parm['MOD'] != '000_common' && $parm['MOD'] != 'login' && $_SERVER['HTTP_HOST'] != 'localhost') {

  if (!isset($_SESSION['userName'])) {
    log::error("login error", $_SESSION['userName']);

    $res['res'] = 'TO';
    SendResponse($res);

    return;
  }
}

if ($parm['MOD'] == '000_common' && $parm['CMD'] == 'login') {
  session_destroy();
  session_start();

  $_SESSION['userID'] = 'login';
  $_SESSION['userDiv'] = '0';
  $_SESSION['groupID'] = '0';
}


$mod = $parm["MOD"];

$file = __DIR__ . "/../api/" . $mod . "_api.php";

$ret = include_once($file);
if (!$ret) {
  $res['res'] = 'NG';
  $res['msg'] = 'moudle not found';
  log::error("include error:" . $file);
  SendResponse($res);
  return;
}

$cmd = $parm['CMD'];
$res['res'] = 'NG';

$class = "C" . $mod . "_api";
$api = new $class($_SESSION['userID'], $_SESSION['userDiv'], $_SESSION['groupID'], $_SESSION['OpCompanyId'], $_SESSION);

if ($parm['LOG'] == true)
  log::api($class . ":" . $cmd, $parm);
else
  log::api($class . ":" . $cmd . "");



if (!method_exists($api, $cmd)) {
  $res['res'] = 'NG';
  $res['msg'] = 'function not found';
  log::error("api call error:" . $class . ":" . $cmd);
  SendResponse($res);
  return;
}
if ($parm['JOB'] == false || getenv('ENV') == "dbg") {

  try {
    $res = $api->$cmd(['res' => 'OK'], $parm['param']);
  } catch (DbException $ex) {
    $res = ['res' => 'DBERR', 'msg' => $ex->getResMessage()];
  } catch (SlvException $ex) {
    $res = ['res' => 'NG', 'msg' => $ex->getResMessage()];
  }

  SendResponse($res);
} else {
  $res['res'] = 'OK';
  SendResponseClose($res);

  $start = date('H:i');
  log::info('start JOBID:' . $start . ' ***************************************************');

  try {
    $res = $api->$cmd(['res' => 'OK'], $parm['param']);
  } catch (DbException $ex) {
    $res = ['res' => 'DBERR', 'msg' => $ex->getResMessage()];
  } catch (SlvException $ex) {
    $res = ['res' => 'NG', 'msg' => $ex->getResMessage()];
  }

  log::info('end JOBID:' . $jobid . ' ***************************************************');

  $msg .= "$start に{$_SESSION['userName']}に頼まれた $cmd が終わったおー\n";
  $msg .= "HOST：" . $_SERVER['HTTP_HOST'] . " $cmd \n";
  $msg .= "結果 : {$res['res']}\n{$res['msg']}";

  $api->ChatSend($msg);


}

return;

//////////////////////////////////////////////////////////////////////////
//
//
//
function SendResponse($arr)
{
  if ($arr['res'] == 'NG')
    Log::error("json res ng:" . $arr['msg']);

  if ($arr['res'] == 'DBERR')
    Log::error("db err ng:" . $arr['msg']);

  $encode = json_encode($arr);

  header("Content-Type: text/html; charset=UTF-8");

  echo $encode;   //JSONを出力
}



