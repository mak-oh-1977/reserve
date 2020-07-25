<!DOCTYPE html>
<html lang="ja">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>予約くん</title>

<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script type="text/javascript" src="./js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="./js/bootstrap.min.js"></script>

</head>

<body>

<div class="container container-fluid" align="center">
<div align="center">
</div>

<div style="max-width:400px; margin-top:5rem">
	<div class="panel panel-success" style="margin-left:20px; margin-right:20px">
		<div class="panel-heading">
			<center><h4>予約くん</h4></center>
		</div>
		<div class="panel-body">
			<form action="" method="POST" name="login">
				<div class="form-group" align="left">
					<label>ユーザＩＤ</label>
					<input type="email" name="userID" class="form-control" style="ime-mode:disabled" maxlength="12" autofocus />
				</div>
				<div class="form-group" align="left">
					<label>パスワード</label>
					<input type="password" name="pass" class="form-control" value="" maxlength="50"/>
                    <div style="text-align:right; margin:5px 0 0 0">
                        <label for="js-passcheck">パスワードを表示する</label>
                        <input type="checkbox" id="js-passcheck"/>
                    </div>

				</div>
				<input type="button" name="login" value="ログイン" class="btn btn-success form-control">
			</form>
            <br />
                <div id="stat">
                </div>
                <div id="message" style="color:red;text-align:left">
                    <noscript>
                    <span style="font-size:1.5em">JavaScriptを有効にする必要があります</span>
                    </noscript>
                    <b></b>
                </div>
            <br>

		</div>
	</div>


</div>
</div>

</body>

<script src="./js/common.js"></script>
<script type="text/javascript">
//////////////////////////////////////////////////////////////////////////
//
//
//
$(document).ready(function(){

});

$(function() {
    var password  = 'input[name=pass]';
    var passcheck = '#js-passcheck';
    
    $(passcheck).change(function() {
        if ($(this).prop('checked')) {
            $(password).attr('type','text');
        } else {
            $(password).attr('type','password');
        }
    });
});

//////////////////////////////////////////////////////////////////////////
//
//
//
function login()
{
	var p = GetFromDom("form[name=login]");
    $('#message b').text('');
    $('#stat').text('ログイン中...');
	Api('000_common', 'login', p, 
		function(ret){
			location.href = ret['location'];
        },
		function(ret){
			$('#message b').text(ret['msg']);
		}
    );
}

//////////////////////////////////////////////////////////////////////////
//
//
//
$('input[name=login]').click(function(){
	login();
});

//////////////////////////////////////////////////////////////////////////
//
//
//
$('input').keypress(function(e){
	if (e.keyCode == 13)
		login();
});


</script>
</html>
