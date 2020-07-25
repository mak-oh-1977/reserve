<?php include_once(__DIR__ . '/../common/common_head.php'); ?>

<style type="text/css">
  span[name=Status] {
    font-size: 0.8em;
  }

  th span[name=Cnt] {
    font-size: 2em;
    color: #0B0B61;
  }

  td.Status {
    font-size: 0.7em;
  }

  th {
    width: 2em;
  }
</style>

<?php include(__DIR__ . "/../common/common_body.php"); ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
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
        <div class="col-12">
          <div class="card card-primary card-outline" id="not_arrival">
            <div class="card-header">
              <h3 class="card-title">Summary</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>

            <div class="card-body">
              <!-- Info boxes -->
              <div class="row">
                <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                  <div class="card card-primary" id="not_arrival">
                    <div class="card-header">
                    予約件数
                    </div>
                    <div class="card-body">
                      <div class="h3">総数</div>

                    </div>
                  </div>
                </div>


              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </content>

</div>

<?php include_once(__DIR__ . '/../common/common_footer.php'); ?>


<script type="text/javascript">
  //////////////////////////////////////////////////////////////////
  //
  //  初期化処理
  //
  $(document).ready(function() {



  });


</script>

</body>

</html>