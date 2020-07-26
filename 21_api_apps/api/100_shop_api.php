<?php
require_once __DIR__ . "/../Classes/PHPExcel.php";
require_once __DIR__ . "/../Classes/PHPExcel/IOFactory.php";

//////////////////////////////////////////////////////////////////////////
//
//  店舗情報ＡＰＩ
//
class C100_shop_api extends api
{

  //////////////////////////////////////////////////////////////////////////
  //
  //	検体状態リスト
  //
  function init_item($res, $p)
  {
    //県コード
    $sql = "select distinct PrefCode Code, Pref Value from 009m_prefcity Code";
    $ret = $this->dbSelect($sql);
    $res['pref'] = $ret['rows'];

    $res['res'] = 'OK';

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	検体一覧
  //
  function shop_list($res, $p)
  {

    $sql = "
      From 100t_shop s 
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
        ShopId, Name, Post, Address1, Address2, 
        Tel, Email, Url, Start, 
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
      $query[] = "s.PrefCode = {$p['PrefCode']}";
    }
    if ($p['text'] != null) {
      $query[] = "concat(s.Name, Kana, s.Address1, s.Address2, s.Tel) collate utf8_unicode_ci Like '%" . $p['text'] . "%'";
    }

    if (count($query) > 0) {
      $sql .= "where " . implode(' and ', $query);
    }

    return $sql;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	グループ詳細情報リスト
  //
  function shop_detail_item($res, $p)
  {
    //県コード
    $sql = "select distinct PrefCode Code, Pref Value from 009m_prefcity Code";
    $ret = $this->dbSelect($sql);
    $res['pref'] = $ret['rows'];

    $res['res'] = 'OK';

    return $res;
  }
  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function shop_detail($res, $parm)
  {
    $sql = "
      select
        ShopId, Name, Post, PrefCode, CityCode, Address1, Address2, 
        Tel, Email, Url, InService, Appeal, Status
      from
          100t_shop
      where
          ShopId = ?
      ";

    $ret = $this->dbSelect($sql, [$parm['ShopId']]);

    $res = $ret['rows'][0];
    $res['res'] = 'OK';
    return $res;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function shop_save($res, $p)
  {
    $this->dbTrans();

    if ($p['ShopId'] == '') {
      $sql = "Select ifnull((Max(ShopId) + 1), 100000) From 100t_shop";

      $ret = $this->dbOne($sql);
      $shop_id = $ret['value'];

      $sql = "
        INSERT INTO `reserve`.`100t_shop`
        (`ShopId`, `Name`, `Post`, `Address1`, `Address2`, `Tel`, `Email`,
        `Url`, `MapUrl`,  `InService`, `Appeal`, `Status`, `Start`,
        `UpdUserId`, `UpdDateTime`)
        VALUES
        (?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, now(), 
        ?, now())
      ";

      $parm = [
        $shop_id, $p['Name'], $p['Post'], $p['Address1'], $p['Address2'], $p['Tel'], $p['Email'],
        $p['Url'], $p['MapUrl'], $p['InService'], $p['Appeal'], $p['Status'],
        $this->user_id
      ];

      $ret = $this->dbExec($sql, $parm);
    
      $res['ShopId'] = $shop_id;
    } else {

      $sql = "
        UPDATE `reserve`.`100t_shop`
        SET
        `Name` = ?, `Post` = ?, `Address1` = ?, `Address2` = ?,
        `Tel` = ?, `Email` = ?, `Url` = ?, `MapUrl` = ?,
        `InService` = ?, `Appeal` = ?, `Status` = ?, `Start` = ?,
        `UpdUserId` = ?, `UpdDateTime` = now()
        WHERE `ShopId` = ?
        ";

      $parm = [
        $p['Name'], $p['Post'], $p['Address1'], $p['Address2'], $p['Tel'], $p['Email'],
        $p['Url'], $p['MapUrl'], $p['InService'], $p['Appeal'], $p['Status'], $p['StartDate'],
        $this->user_id,
        $p['ShopId']
      ];


      $ret = $this->dbExec($sql, $parm);

      $res['ShopId'] = $p['ShopId'];
    }

    $this->dbCommit();

    $res['res'] = 'OK';


    return $res;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //	唾液性情リスト
  //
  function get_address_by_zip($res, $p)
  {
    // 呼び出したURLの'?'以降の'callback='で指定された文字列を取得する
    // 指定がなければ'jsonp'というコールバック関数名で返す
    // ZipCloudのAPI用のアドレス文字列を生成
    //    $url = "http://zipcloud.ibsnet.co.jp/api/search?zipcode=" .$p['zipcode'];
    $url = "https://postal-codes-jp.azurewebsites.net/api/PostalCodes/" . $p['zipcode'];
    // テキストデータを読み込む (HTTP通信)
    $json = file_get_contents($url);

    $data = json_decode($json);
    // 文字化けしないようにUTF-8に変換
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    // 取得した文字列をそのまま返す

    $data = json_decode($json);

    log::debug(print_r($data, TRUE));

    $r = $data[0];

    log::debug(print_r($r, TRUE));

    log::debug($r->name);

    return [
      'res' => 'OK', 'Address1' => $r->city->name . $r->name,
      'PrefCode' => $r->city->pref->code,
      'Ken' => $r->city->pref->name,
      'CityCode' => $r->city->code,
    ];
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
    $sql = "delete from 100t_shop where ShopId = ?";
    $ret = $this->dbExec($sql, [$p['ShopId']]);

    $this->OpeLog(api::OP_TYPE_DEL, "Delete Shop {$p['ShopId']}");

    $this->dbCommit();

    $res['res'] = 'OK';
    return $res;
  }
}
