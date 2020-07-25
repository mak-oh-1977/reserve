<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarEexample">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="../000_menu/000_menu.php">予約</a>
		</div>

		<div class="collapse navbar-collapse" id="navbarEexample">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
		           <a href="#" class="dropdown-toggle" data-toggle="dropdown">ようこそ <?php echo $groupName . " " . $userName ?> さん。<span class="caret"></span></a>

					<ul class="dropdown-menu">
						<li>
							<a href="../000_menu/001_setting.php">設定</a>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="if(window.confirm('ログアウトしますか？')){location.href = '../../index.php';}">ログアウト</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>