<?php include_once(__DIR__ . '/../common/common_head.php'); ?>

<style type="text/css">
</style>

<?php include(__DIR__ . "/../common/common_body.php"); ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">スタッフ一覧</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div>
  </div>
  <!-- /.content-header -->

  <content>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">絞り込み条件を指定</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-9" id="query">

                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="col-form-label-sm">氏名</label>
                        <input type="text" name="text" class="form-control form-control-sm">
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-3 d-flex align-items-end">
                  <div class="box">
                    <br><button name="search" class="btn btn-info btn-lg"><i class="fas fa-filter"></i>絞込</button>
                    <button name="clear" class="btn btn-secondary btn-lg"><i class="fas fa-eraser"></i>クリア</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </content>
  <content>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <p style="float:left"><span id="list_cnt"></span>件</p>
          <button name="new" class="btn btn-success btn-md" style="float:right"><i class="fas fa-plus"></i>新規登録</button>
        </div>
        <div class="card-body table-responsive p-0" id="list-base">

          <table class="table table-hover table-head-fixed" id="tbl-list">
            <thead>
              <tr>
                <th>
                  <div class="row">
                    <div class="col-sm-1 th">
                      スタッフID
                    </div>
                    <div class="col-sm-2 th">
                      氏名
                    </div>
                    <div class="col-sm-3 th">
                      電話番号/メール
                    </div>
                    <div class="col-sm-1 th">
                      入店日
                    </div>
                    <div class="col-sm-1 th">
                      更新者/更新日時
                    </div>
                    <div class="col-sm-1 th">
                    </div>

                  </div>

                </th>
              </tr>
              <tr style="display:none">
                <td>
                  <div class="row">
                    <div class="col-sm-1 detail">
                      <span name="StaffId"></span><br>

                    </div>
                    <div class="col-sm-2 detail">
                      <span name="Name"></span><br>
                    </div>
                    <div class="col-sm-3">
                      <span name="Tel"></span><br>
                      <span name="Email"></span><br><br>
                      <a class="Url" target="_blank"><span></span></a>
                    </div>
                    <div class="col-sm-1">
                      <span name="Start"></span><br>
                    </div>
                    <div class="col-sm-1">
                      <span name="UpdUserName"></span><br>
                      <span name="UpdDateTime"></span><br>
                    </div>
                    <div class="col-sm-1">
                      <button name="staff" class="btn btn-info btn-md"><i class="fas fa-people"></i>シフト</button>
                    </div>
                    <div class="col-sm-12" style="background-color:#EFFBF8">
                      <span name="Memo"></span>
                    </div>

                  </div>

                </td>

              </tr>

            </thead>
            <tbody></tbody>
          </table>

        </div>
      </div>
    </div>

</div>


<?php include_once(__DIR__ . '/../common/common_footer.php'); ?>

<script type="text/javascript">
  //////////////////////////////////////////////////////////////////
  //
  //
  //
  $(document).ready(function() {

    $('#tbl-list').attr('offset', 0);

    tbl_list();

  });


  //////////////////////////////////////////////////////////////////////////
  //
  //	検索
  //
  $('button[name=search]').click(function() {
    search();

  })

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  $('input[name=groupID]').on('keypress', function(e) {
    if (e.keyCode != 13)
      return;

    search();
  });

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function search(e) {

    $('#tbl-list').attr('offset', 0);

    tbl_list();
  }
  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  $('input[name=text]').on('keypress', function(e) {
    if (e.keyCode != 13)
      return;

    search();
  });

  //////////////////////////////////////////////////////////////////////////
  //
  //	検索条件のクリア
  //
  $('button[name=clear]').click(function() {
    ClearDom('#query');

    $('select[name=SearchDateType]').val('1');
  })

  //////////////////////////////////////////////////////////////////////////
  //
  //  新規ボタン押下
  //
  $("button[name='new']").click(function() {
    window.open("./111_staff_d.php?shop_id=" + getParm('shop_id'));
    return false;
  })



  //------------------------------------------------------------------------
  //
  // 一覧読み込み
  //
  function tbl_list() {
    $("#tbl-list").attr('processing', 1);
    var offset = parseInt($("#tbl-list").attr('offset'));

    // if (offset < 0)
    //     return;

    var p = GetFromDom('#query');
    p['offset'] = offset;

    if (offset <= 0)
      $('#tbl-list tbody').empty();

    Api('110_staff/staff_list', p,
      function(ret) {

        var cnt = ret['rows'].length;

        if (ret['all_cnt'] != null)
          $('#list_cnt').text(ret['all_cnt']);

        $.each(ret['rows'], function(index, r) {
          var $tr = $('#tbl-list thead tr:nth-child(2)').clone();
          $tr.attr('StaffId', r['StaffId']);

          SetToDom($tr, r);

          $('#tbl-list tbody').append($tr);

        });

        if (cnt > 0) {
          $('#tbl-list').attr('offset', offset + cnt);
        } else {
          $('#tbl-list').attr('offset', -1);

        }

        $('#tbl-list tbody tr').show();

        $("#tbl-list").attr('processing', 0);

        RecalcTableSize();
      }
    );

  }



  //////////////////////////////////////////////////////////////////////////
  //
  //	スクロール、リサイズの表示（一覧の順次読み込み）
  //
  $(window).on("resize", RecalcTableSize);

  $('button[data-card-widget=collapse]').click(function() {
    setInterval(RecalcTableSize, 1000);
  });


  //////////////////////////////////////////////////////////////////////////
  //
  //	一覧テーブルサイズ再設定
  //
  function RecalcTableSize() {
    var h = $(window).height() - $('#list-base').offset().top - 30;
    if (h > 0) {
      $('#list-base').css('height', h);
    }
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //	一覧テーブルのリサイズスクロール
  //
  $('#list-base').on("load scroll resize", function() {

    if (($('#tbl-list').height() * 0.7) < $('#list-base').scrollTop()) {

      if ($("#tbl-list").attr('offset') != -1) {
        if ($('#tbl-list').attr('processing') != 1)
          tbl_list();
      }

    }

  });

  //////////////////////////////////////////////////////////////////////////
  //
  //  詳細情報
  //
  $(document).on('click', '#tbl-list div.detail', function() {

    var id = $(this).closest('tr').attr('StaffId')
    window.open("../100_shop/111_staff_d.php?staff_id=" + id, '_blank');

  })

  //////////////////////////////////////////////////////////////////////////
  //
  //	一覧テーブルのリサイズスクロール
  //
  $('#tbl-list').on("click", "button[name=staff]", function() {
    var id = $(this).closest('tr').attr('ShopId')
    window.open("102_staff.php?shop_id=" + id, '_blank');


  });
</script>



</body>

</html>