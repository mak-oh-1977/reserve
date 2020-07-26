<?php

//////////////////////////////////////////////////////////////////////////
//
//  請求処理ＡＰＩ
//
class C000_common_api extends api
{

  //////////////////////////////////////////////////////////////////////////
  //
  //	ログイン
  //
  function login($res, $p)
  {
    $login_id = $p["LoginId"];
    $pass = $p["pass"];
    if (strpos($_SERVER['SERVER_NAME'], 'reserve.com') === FALSE && strlen($login_id) == 0) {
      $a = file(__DIR__ . '/../../debug.txt');
      $login_id = trim(str_replace(PHP_EOL, '', $a[0]));
      $pass = trim(str_replace(PHP_EOL, '', $a[1]));
    }

    $this->dbTrans();

    $ret = $this->check_user_id($login_id, $pass);
    if ($ret['res'] != 'OK')
      return $ret;

    $ret = $this->get_user_info($ret['UserId']);
    if ($ret['res'] != 'OK')
      return $ret;

    $ret = $this->get_right_info($ret['UserId']);
    if ($ret['res'] != 'OK')
      return $ret;

    log::debug(print_r($_SERVER, TRUE));

    $res['res'] = 'OK';
    
    $res['location'] = "./000_system/000_dashboard.php";

    log::debug(print_r($_SESSION, TRUE));

    $this->dbCommit();

    return $res;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //	アカウント確認
  //
  function check_user_id($id, $pass)
  {
    log::func($id);

    if (strlen($id) > 0 && strlen($pass) > 0) {
      $ip = $_SERVER["HTTP_X_REAL_IP"];

      $res = $this->dbOne('select UserId from 010m_user where LoginId = ? and Pass = ?', [$id, $pass]);
      if ($res['value'] == '') {
        $res['res'] = 'NG';
        $res['msg'] = "ユーザID または パスワードが違います。";

        log::info($res['msg'] . " {$id} {$ip}");

        $this->OpeLog(api::OP_TYPE_SEL, "{$id} Login failure from {$ip}");

        $this->dbCommit();

        return $res;
      }
      log::debug(print_r($res, TRUE));

      $res = ['res' => 'OK', 'UserId' => $res['value']];
    } else {
      throw new AppException("ユーザID または パスワードが入力されていません。");
    }

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	ユーザー情報取得
  //
  function get_user_info($id)
  {
    log::func("{$id}");

    $sql = "
          Select
              UserId, UserType, UserName, 
              case when ExpireDate > now() then '' else ExpireDate end as ExpireDate
          From
              010m_user
          Where
              UserId = ?
      ";

    $ret = $this->dbSelect($sql, [$id]);


    $row = $ret['rows'][0];
    if (!$row) {
      throw new AppException("ユーザID または パスワードが違います。");
    }

    $_SESSION = [];
    //セッションに格納し、画面遷移
    $_SESSION['UserId']     = $row['UserId'];
    $_SESSION['UserType']     = $row['UserType'];
    $_SESSION['UserName']     = $row['UserName'];
    $_SESSION['ExpireDate']   = $row['ExpireDate'];


    return ['res' => 'OK'];
  }
  //////////////////////////////////////////////////////////////////////////
  //
  //	アカウント確認
  //
  function get_right_info($id)
  {
    $sql = "
        SELECT f.FunctionId
        FROM
            010m_user u inner join 001m_role r on u.RoleId = r.RoleId
                    inner join 002m_permission p on r.RoleId = p.RoleId
                    inner join 003m_function f on p.FunctionId = f.FunctionId
        where
            UserId = ?
    ";

    $ret = $this->dbSelect($sql, [$id]);

    $_SESSION['function'] = [];

    foreach ($ret['rows'] as $r) {
      $_SESSION['function'][] = $r['FunctionId'];
    }
    return $ret;
  }


}
