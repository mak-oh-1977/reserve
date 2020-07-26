<?php
require_once __DIR__ . "/../Classes/PHPExcel.php";
require_once __DIR__ . "/../Classes/PHPExcel/IOFactory.php";

//////////////////////////////////////////////////////////////////////////
//
//  スタッフ情報ＡＰＩ
//
class C110_staff_api extends api
{

  //////////////////////////////////////////////////////////////////////////
  //
  //	一覧
  //
  function staff_list($res, $p)
  {

    $sql = "
      From 110t_staff s 
        inner join 010m_user u on s.UpdUserId = u.UserId
      ";
    $sql .= $this->get_query_sql($p);

    $sql .= " order by ShopId ";

    if ($p['offset'] <= 0) {
      $res = $this->dbOne("select count(*) " . $sql);

      $cnt = $res['value'];
    }

    $cols = "
      select 
        StaffId, ShopId, Name, Address, Tel, Email, 
        u.UserName UpdUserName, 
        date_format(s.UpdDateTime, '%y-%m-%d %H:%i') UpdDateTime
        ";
    if ($p['offset'] >= 0) {
      $sql .= " limit 50 offset " . $p['offset'];
    }
    $res = $this->dbSelect($cols . $sql);

    $res['all_cnt'] = $cnt;

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	検索用ＳＱＬ作成
  //
  function get_query_sql($p)
  {
    $query = [];

    //検索条件
    if ($p['Pref'] != null) {
      $query[] = "G.PrefCode = {$p['PrefCode']}";
    }
    if ($p['text'] != null) {
      $query[] = "concat(s.Name, Kana, s.Address, s.Tel) collate utf8_unicode_ci Like '%" . $p['text'] . "%'";
    }

    if (count($query) > 0) {
      $sql .= "where " . implode(' and ', $query);
    }

    return $sql;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function staff_detail($res, $parm)
  {
    $sql = "
      select
        ShopId, Name, Address, Tel, Email, InService, Appeal, Status
      from
        110t_staff
      where
        StaffId = ?
      ";

    $ret = $this->dbSelect($sql, [$parm['StaffId']]);

    $res = $ret['rows'][0];
    $res['res'] = 'OK';
    return $res;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function staff_save($res, $p)
  {
    $this->dbTrans();

    if ($p['StaffId'] == '') {
      $sql = "Select ifnull((Max(StaffId) + 1), 100000) From 110t_staff";

      $ret = $this->dbOne($sql);
      $staff_id = $ret['value'];

      $sql = "
        INSERT INTO `reserve`.`110t_staff`
        (`StaffId`, `ShopId`, `Name`, `Address`, `Tel`, `Email`,
        `InService`, `Appeal`, `Status`, 
        `UpdUserId`, `UpdDateTime`)
        VALUES
        (?, ?, ?, ?, ?, ?,
        ?, ?, ?, 
        ?, now())
      ";

      $parm = [
        $staff_id, $p['ShopId'], $p['Name'], $p['Address'], $p['Tel'], $p['Email'],
        $p['InService'], $p['Appeal'], $p['Status'],
        $this->user_id
      ];

      $ret = $this->dbExec($sql, $parm);
    
      $res['StaffId'] = $staff_id;
    } else {

      $sql = "
        UPDATE `reserve`.`110t_staff`
        SET
        `ShopId` = ?, `Name` = ?, `Address` = ?, `Tel` = ?, `Email` = ?, 
        `InService` = ?, `Appeal` = ?, `Status` = ?, 
        `UpdUserId` = ?, `UpdDateTime` = now()
        WHERE `StaffId` = ?
        ";

      $parm = [
        $p['ShopId'], $p['Name'], $p['Address'], $p['Tel'], $p['Email'],
        $p['InService'], $p['Appeal'], $p['Status'], 
        $this->user_id,
        $p['StaffId']
      ];


      $ret = $this->dbExec($sql, $parm);

      $res['StaffId'] = $p['StaffId'];
    }

    $this->dbCommit();

    $res['res'] = 'OK';


    return $res;
  }



  //////////////////////////////////////////////////////////////////////////
  //
  //	契約先集計
  //
  function delete_check($res, $p)
  {

    $res['res'] = "OK";

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	施設の削除
  //
  function delete_shop($res, $p)
  {
    $this->dbTrans();

    //グループ
    $sql = "delete from 110t_staff where StaffId = ?";
    $ret = $this->dbExec($sql, [$p['StaffId']]);

    $this->OpeLog(api::OP_TYPE_DEL, "Delete Staff {$p['StaffId']}");

    $this->dbCommit();

    $res['res'] = 'OK';
    return $res;
  }
}
