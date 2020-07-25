<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>予約くん</title>

<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/css/common.css">

</head>
<body>
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/jquery-ui/jquery-ui.js"></script>
<script src="/js/common.js"></script>
<script type="text/javascript">

//////////////////////////////////////////////////////////////////
//
//  初期化処理
//
$(document).ready(function(){


    alertDlg('メッセージ', "一定時間操作が無かったため切断しました。\r\nお手数ですが再度ログインをお願いいたします",
        function()
        {
            window.location.href ='/index.php';
        }
    );
});
</script>
</body>

</html>