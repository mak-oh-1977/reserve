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
    $userID = $p["userID"];
    $pass = $p["pass"];
    if (strpos($_SERVER['SERVER_NAME'], 'reserve.com') === FALSE && strlen($userID) == 0) {
      $a = file(__DIR__ . '/../../debug.txt');
      $userID = trim(str_replace(PHP_EOL, '', $a[0]));
      $pass = trim(str_replace(PHP_EOL, '', $a[1]));
    }

    $this->dbTrans();

    $ret = $this->check_user_id($userID, $pass);
    if ($ret['res'] != 'OK')
      return $ret;

    $ret = $this->get_user_info($userID, $ret['userDiv']);
    if ($ret['res'] != 'OK')
      return $ret;

    $ret = $this->get_right_info($userID);
    if ($ret['res'] != 'OK')
      return $ret;

    log::debug(print_r($_SERVER, TRUE));


    //var_dump(mb_convert_encoding($row['UserName'], "UTF-8"));

    $ip = $_SERVER["HTTP_X_REAL_IP"];

    $sql = "update 032m_user set LastLoginTime = now(),  LastLoginIp = ?, LastLoginInfo = ? where UserID = ? ";
    $ret = $this->dbExec($sql, [$ip, $_SERVER['HTTP_USER_AGENT'], $userID]);
    if ($ret['res'] == 'NG') {
      log::info('ログイン情報の書き込みに失敗');
      return $ret;
    }

    $this->OpeLog(api::OP_TYPE_SEL, "Login from {$ip}");

    log::debug(print_r($_SESSION, TRUE));

    $v  = $_SESSION['Version'] == "" ? ("") : ($_SESSION['Version'] . "/");
    //                header( "Location: {$v}menu.php" );
    Log::info("goto " . $v);

    $res['res'] = 'OK';
    $res['location'] = "v0/menu.php";


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

      $res = $this->dbOne('select UserDiv from 032m_user where UserId = ? and Pass = ?', [$id, $pass]);
      if ($res['value'] == '') {
        $res['res'] = 'NG';
        $res['msg'] = "ユーザID または パスワードが違います。";

        log::info($res['msg'] . " {$id} {$ip}");

        $this->OpeLog(api::OP_TYPE_SEL, "{$id} Login failure from {$ip}");

        $this->dbCommit();

        return $res;
      }
      log::debug(print_r($res, TRUE));

      $res = ['res' => 'OK', 'userDiv' => $res['value']];
    } else {
      $res['res'] = 'NG';
      $res['msg'] = "ユーザID または パスワードが入力されていません。";
    }

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	ユーザー情報取得
  //
  function get_user_info($id, $div)
  {
    log::func("{$id}, {$div}");


    $sql = "
        Select
          U.UserId, U.UserDiv, U.UserName, 
          case when U.ExpireDate > now() then '' else U.ExpireDate end as ExpireDate
        From
          032m_user AS U
        Where
          U.UserID = ?
      ";

    $ret = $this->dbSelect($sql, [$id]);
    $row = $ret['rows'][0];
    if (!$row) {
      $res['res'] = "NG";
      $res['msg'] = "ユーザID または パスワードが違います。";

      return $res;
    }

    if ($row['EnableFlg'] == 0) {
      $res['msg'] = "このアカウントは現在停止中です。システム管理者にお問い合わせください";
      $res['res'] = "NG";

      return $res;
    }

    $_SESSION = [];
    //セッションに格納し、画面遷移
    $_SESSION['userID']     = $row['UserId'];
    $_SESSION['userDiv']     = $row['UserDiv'];
    $_SESSION['userName']     = $row['UserName'];
    $_SESSION['ExpireDate']   = $row['ExpireDate'];
    $_SESSION['version']     = $row['Version'];

    log::debug(print_r($_SESSION, TRUE));
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
            032m_user u inner join 001m_role r on u.RoleId = r.RoleId
                    inner join 002m_permission p on r.RoleId = p.RoleId
                    inner join 003m_function f on p.FunctionId = f.FunctionId
        where
            UserId = ?
    ";

    $ret = $this->dbSelect($sql, [$id]);
    if ($ret['res'] == 'NG') {
      log::info('失敗しました。');
      return $ret;
    }

    $_SESSION['function'] = [];

    foreach ($ret['rows'] as $r) {
      $_SESSION['function'][] = $r['FunctionId'];
    }
    return $ret;
  }


}
