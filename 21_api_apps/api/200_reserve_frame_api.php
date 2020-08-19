<?php

//////////////////////////////////////////////////////////////////////////
//
//  予約枠情報ＡＰＩ
//
class C200_reserve_frame_api extends api
{

  //////////////////////////////////////////////////////////////////////////
  //
  //	一覧
  //
  function list_reserve($res, $p)
  {
    $sql = "
      select 
        EventId, Start, End, 
        case when DATE_FORMAT(Start, '%H%i') = '0000' and End is null then 1 else 0 end AllDay,
        Memo 
      from 200t_reserve_frame 
      where StaffId = ? and ((Start >= ? and End < ?) or (Start >=? and End is null))
      ";
    $res = $this->dbSelect($sql, [$p['StaffId'], $p['Start'], $p['End'], $p['Start']]);

    return $res;
  }
  //////////////////////////////////////////////////////////////////////////
  //
  //	追加
  //
  function add_reserve($res, $p)
  {
    $this->dbTrans();

    $sql = "select ifnull(max(EventId), 0) + 1 from 200t_reserve_frame where StaffId = ?";
    $ret = $this->dbOne($sql, [$p['StaffId']]);
    $eventid = $ret['value'];

    $sql = "insert into 200t_reserve_frame values(?, ?, ?, ?, ?)";
    $ret = $this->dbExec($sql, [$p['StaffId'], $eventid, $p['Start'], $p['End'], $p['Memo']]);

    $res['res'] = 'OK';
    $res['EventId'] = $eventid;
    
    $this->dbCommit();

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	変更
  //
  function update_reserve($res, $p)
  {
    $this->dbTrans();

    $sql = "update 200t_reserve_frame set Start = ?, End = ? where StaffId = ? and EventId = ?";
    $ret = $this->dbExec($sql, [$p['Start'], $p['End'], $p['StaffId'], $p['EventId']]);

    $res['res'] = 'OK';
    
    $this->dbCommit();
    
    return $res;
  }

}
