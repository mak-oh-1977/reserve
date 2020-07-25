<?php include_once(__DIR__ . "/../common/common_head.php"); ?>


<style type="text/css">

a.btn {
	margin:1em 0 0 0;
}

a.btn-info {
	/* width:20em; */
	height:7em;
}

img {
	height:4.5em;
}

#news_cnt {
	background-color:#ff0000;
	border-radius:40px;
	margin:0 0 0 10px;
	padding: 5px 10px 5px 10px;
	color:#ffffff;
	display: none;
}
</style>

<title>予約</title>

</head>
<body>

<?php include(__DIR__ . "/../common/header.php"); ?>
<div class="container">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-6">
				<a class="btn btn-default btn-lg btn-block" href="../030_news/030_news_list.php">お知らせ<span id="news_cnt"></span></a>
			</div>
		</div>
	</div>

	<br>

</div>

<!-- JSライブラリ -->
<?php include_once(__DIR__ . "/../common/common_js.php"); ?>

<script type="text/javascript">
//------------------------------------------------------------------------
//
// 初期表示
//
$(document).ready(function(){

});
</script>

</body>
</html>
