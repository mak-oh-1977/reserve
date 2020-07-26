<?php include_once(__DIR__ . '/../common/common_head.php'); ?>

<style type="text/css">
  td {
    white-space: nowrap;
  }

  select {
    height: 26px;
  }

  .cont_req {
    color: #ff0000;
    font-size: 1.2em;
  }

  #kind li.nav-item {
    width: 15%;
  }

  #seikyubiDlg tr td {
    width: 200px;
  }

  #seikyubiDlg tr:hover td {
    cursor: pointer;
    background-color: #FFE9D6;
  }

  #shiharaibiDlg tr td {
    width: 200px;
  }

  #shiharaibiDlg tr:hover td {
    cursor: pointer;
    background-color: #FFE9D6;
  }

  /* div.tab-pane {
    height:48em;
}
div.sub-tab-page {
    height:40em;
} */
  div.ctrl-btn {
    float: right;
    margin: 10px 50px 0 0;
  }

  div#account-base {
    height: 30em;
    overflow-y: scroll;
  }

  table#account tbody tr:hover {
    cursor: pointer;
    background-color: #FFE9D6;
  }


  .inp-err {
    background-color: #ffeeee;
    border: 1px solid #ff0000;
  }

  .inp-warn {
    background-color: #fdffc1;
    border: 1px solid #b5ba27;
  }

  .err-msg {
    color: #ff5555;
  }

  .tab-page .table tbody th {
    text-align: right;
  }

  #AccountDlg .table th {
    text-align: right;
  }

  select.ui-datepicker-year {
    color: #303030;
  }

  select.ui-datepicker-month {
    color: #303030;
  }

  #houjinSelDlg table tbody tr:hover {
    cursor: pointer;
    background-color: #FFE9D6;

  }

  #SearchResultDlg table tbody tr:hover {
    cursor: pointer;
    background-color: #FFE9D6;

  }
</style>

<?php include(__DIR__ . "/../common/common_body.php"); ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">店舗情報</h1>
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
                              <option value="0">停止中</option>
                              <option value="1" selected>運用中</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label>店舗ID</label>
                            <input type="tel" name="ShopId" class="form-control" maxlength="20" readonly>
                          </div>

                          <div class="form-group">
                            <label>店舗名</label>
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
                          <div class="form-group">
                            <label>郵便番号</label>

                            <div class="input-group">
                              <input type="tel" name="Post" class="form-control" maxlength="8">
                              <span class="input-group-append">
                                <button type="button" id="zipsearch" class="btn btn-info">検索</button>
                              </span>
                            </div>
                          </div>

                          <div class="form-group zip-search">
                            <label>都道府県</label>
                            <select name="PrefCode" class="form-control form-control-sm">
                            </select>
                          </div>

                          <div class="form-group zip-search">
                            <label>住所</label>
                            <!-- <td class="tips" title="契約書にそのまま出力されます" colspan="2"> -->
                            <input type="text" name="Address1" class="form-control">
                            <input type="text" name="Address2" class="form-control" placeholder="建物名等">
                          </div>

                          <div class="form-group">
                            <label>電話番号</label>
                            <input type="tel" name="Tel" class="form-control">
                          </div>

                          <div class="form-group">
                            <label>E-mail</label>
                            <input type="tel" name="Email" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>URL</label>

                            <div class="input-group">
                              <input type="tel" name="Url" class="form-control">
                              <span class="input-group-append">
                                <button type="button" id="hpopen" class="btn btn-info">確認</button>
                              </span>
                            </div>
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
                            <label>営業時間・休日</label>

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


    get_item_values();

  });
  //------------------------------------------------------------------------
  //
  //*	各項目の値を取得
  //
  function get_item_values() {
    Api('100_shop/shop_detail_item', null,
      function(ret) {

        $.each(ret['pref'], function(index, value) {
          $opt = $('<option>').text(value['Value']).val(value['Code']);
          $('select[name=PrefCode]').append($opt);
        });

        if (getParm('shop_id') != "") {
          detail_data(getParm('shop_id'));
        }
      }
    );

  }

  //------------------------------------------------------------------------
  //
  //*	値取得
  //
  function detail_data(shop_id) {
    var param = {
      ShopId: shop_id,
    };

    Api('100_shop/shop_detail', param,
      function(ret) {

        SetToDom('#tabs-1', ret);

      }
    );
  }

  //------------------------------------------------------------------------
  //
  //*	郵便番号から住所検索
  //
  $('#zipsearch').click(function() {

    var p = {
      'zipcode': $('#tabs-1 input[name=Post]').val()
    }
    Api('100_shop/get_address_by_zip', p,
      function(ret) {
        SetToDom('#tabs-1', ret);
      }
    );

  })

  //------------------------------------------------------------------------
  //
  //登録ボタンクリック
  //
  $('#save').click(function() {
    confirmDlg("店舗情報", "登録しますか？",
      function() {

        var param = {};
        param = GetFromDom('#tabs-1');
        param['Ken'] = $('#tabs-1 :input[name=PrefCode] option:selected').text();

        Api('100_shop/shop_save', param,
          function(ret) {

            $('#ShopId').val(ret['ShopId']);
            alertDlg('保存', '保存しました', function() {
              history.replaceState('', '', "/100_shop/101_shop_d.php?shop_id=" + ret['ShopId'])

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

    Api('100_shop/delete_check', p,
      function(ret) {
        confirmDlg("店舗情報", "削除します、よろしいですか？",
          function() {
            Api('100_shop/delete_shop', p,
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