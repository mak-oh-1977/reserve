<?php include_once(__DIR__ . "/../common/common_head.php"); ?>


<style type="text/css">

</style>
<title>予約くん</title>

</head>
<body>

<?php include(__DIR__ . "/../common/header.php"); ?>

<div class="container">

	<h2>設定</h2>

	<div class="panel-body">
        <div id="info">
			<div class="form-group">
				<label>アカウント</label><br>
				<span name="UserID"></span>
			</div>
			<div class="form-group">
				<label>担当者名</label>
				<input type="text" name="UserName" style="width:15em; ime-mode:disabled" class="form-control" maxlength="20">
			</div>
			<div class="form-group">
				<label>連絡用メールアドレス</label>
				<input type="textarea" name="Email" style="width:80vw; ime-mode:disabled" class="form-control" maxlength="200" placeholder="カンマで区切って複数登録可能">
			</div>
			<div class="form-group">
				<label>パスワード</label>
				<input type="password" name="Pass" style="width:20em" class="form-control" maxlength="20" placeholder="6文字以上">
			</div>
			<div class="form-group">
				<label>確認のため上のパスワードと<br>同じものを入力してください</label>
				<input type="password" name="ConfPass" style="width:20em" class="form-control" maxlength="20">
			</div>
		</table>
		<button type="button" id="regist" class="btn  btn-primary btn-lg">登 録</button>

	</div>
</div>

<!-- JSライブラリ -->
<?php include_once(__DIR__ . "/../common/common_js.php"); ?>

<script type="text/javascript">
$(document).ready(function(){

	Api('110_user', 'user_data', null, 
		function(ret){
			SetToDom("#info", ret);
        }
    );

});


//------------------------------------------------------------------------
//
// 登録
//
$("#regist").click(function(){
	var parm = GetFromDom("#info");

	Api('110_user', 'user_save', parm, 
			function(ret){
				alertDlg("ユーザー情報変更", "更新しました。ログアウトします。", function(){
					window.location.href = "/index.php";
                });
			},
			function(ret){
				alertDlg("ユーザー情報変更", ret['msg'] + "\n" + ret['info']);
			}
	);



});
</script>


</body>
</html>
