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
  //	一覧
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
