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
          <h1 class="m-0 text-dark">スタッフ情報</h1>
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

          <div class="card card-info card-tabs">
            <div class="card-header p-0 pt-1">
              <input type="hidden" id="GroupId">
              <ul class="nav nav-tabs" role="tablist" id="kind">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" href="#tabs-1" idx=1>基本情報</a></li>
              </ul>

            </div>

            <div class="card-body" id="tabs">
              <div class="tab-content">
                <div id="tabs-1" class="tab-pane fade active show">
                  <div class="cont_req">（※は必須）</div>
                  <div class="row">

                    <div class="col-lg-5">
                      <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">基本</h3>
                        </div>
                        <div class="card-body">
                          <div class="form-group">
                            <label>状態</label>
                            <select name="Status" class="form-control">
                              <option value="0">退店</option>
                              <option value="1" selected>入店</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label>店舗ID</label>
                            <input type="tel" name="ShopId" class="form-control" maxlength="20" readonly>
                          </div>
                          <div class="form-group">
                            <label>スタッフID</label>
                            <input type="tel" name="StaffId" class="form-control" maxlength="20" readonly>
                          </div>

                          <div class="form-group">
                            <label>氏名</label>
                            <input type="text" name="Name" class="form-control">
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="col-lg-7">
                      <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">連絡先</h3>
                        </div>
                        <div class="card-body">

                          <div class="form-group zip-search">
                            <label>住所</label>
                            <!-- <td class="tips" title="契約書にそのまま出力されます" colspan="2"> -->
                            <input type="text" name="Address" class="form-control">
                          </div>

                          <div class="form-group">
                            <label>電話番号</label>
                            <input type="tel" name="Tel" class="form-control">
                          </div>

                          <div class="form-group">
                            <label>E-mail</label>
                            <input type="tel" name="Email" class="form-control">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <div class="card card-info">
                        <div class="card-header">
                          <h3 class="card-title">情報</h3>
                        </div>
                        <div class="card-body">
                          <div class="form-group">
                            <label>出勤予定</label>

                            <div class="input-group">
                              <input type="text" name="InService" class="form-control">
                            </div>
                          </div>
                          <div class="form-group">
                            <label>アピールポイント</label>

                            <div class="input-group">
                              <textarea name="Appeal" class="form-control"></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <button id="save" class="btn btn-primary btn-lg"><i class="fas fa-cloud-upload-alt"></i>保存</button>
              <button id="delete" class="btn btn-danger btn-lg"><i class="fas fa-trash-alt"></i>削除</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </content>

</div>


<?php include_once(__DIR__ . '/../common/common_footer.php'); ?>

<script type="text/javascript">
  $(document).ready(function() {

    if (getParm('staff_id') != "") {
      detail_data(getParm('staff_id'));
    }
    else
    {
      $('#tabs-1 input[name=ShopId]').val(getParm('shop_id'));
    }


  });

  //------------------------------------------------------------------------
  //
  //*	値取得
  //
  function detail_data(staff_id) {
    var param = {
      StaffId: staff_id,
    };

    Api('110_staff/staff_detail', param,
      function(ret) {

        SetToDom('#tabs-1', ret);

      }
    );
  }


  //------------------------------------------------------------------------
  //
  //登録ボタンクリック
  //
  $('#save').click(function() {
    confirmDlg("スタッフ情報", "登録しますか？",
      function() {

        var param = {};
        param = GetFromDom('#tabs-1');

        Api('110_staff/staff_save', param,
          function(ret) {

            $('#StaffId').val(ret['StaffId']);
            alertDlg('保存', '保存しました', function() {
              history.replaceState('', '', "/100_shop/111_staff_d.php?staff_id=" + ret['StaffId'])

            });
          }
        );

      }
    );

  });


  //------------------------------------------------------------------------
  //
  //
  //
  $('button#hpopen').click(function() {
    $input = $(this).parent().prev('input');
    window.open($($input).val());
  })

  //------------------------------------------------------------------------
  //
  //
  //
  $("#delete").click(function() {

    var p = GetFromDom("#tabs-1");

    Api('110_staff/delete_check', p,
      function(ret) {
        confirmDlg("スタッフ情報", "削除します、よろしいですか？",
          function() {
            Api('110_staff/delete_sstaff', p,
              function(ret) {
                alertDlg("削除", "完了しました。", function() {
                  window.close();
                });
              });

          }
        );
      }
    );
    return false;
  });
</script>



</body>

</html>