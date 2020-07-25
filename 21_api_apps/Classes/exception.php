<?php
//////////////////////////////////////////////////////////////////////////
//
//
//
abstract class ApiException extends Exception
{

  public abstract function getLogMessage();
  public abstract function getResMessage();
}

class DbException extends ApiException
{

  public function getLogMessage()
  {
    $t = parent::getTrace();
    $log = "\nTrace------------------------------------------\n";

    foreach ($t as $r => $v) {
      $log .= "{$v['file']}({$v['line']}):{$v['function']}\n";
    }
    $log .= "\nMessage -----------------------------------------------\n" . parent::getMessage();
    $log .= "\nLast arg -----------------------------------------------\n" . print_r($t[0]['args'], TRUE);

    return $log;
  }

  public function getResMessage()
  {
    return self::getLogMessage();
  }
}
//////////////////////////////////////////////////////////////////////////
//
//
//
class AppException extends ApiException
{

  public function getLogMessage()
  {
    $t = parent::getTrace();
    $log = "\nTrace------------------------------------------\n";

    foreach ($t as $r => $v) {
      $log .= "{$v['file']}({$v['line']}):{$v['function']}\n";
    }
    $log .= "\nMessage -----------------------------------------------\n" . parent::getMessage();

    return $log;
  }

  public function getResMessage()
  {
    return parent::getMessage();
  }
}

?>