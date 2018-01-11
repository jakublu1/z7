<html>
	<head>
		<title>
			Jakub Lula
		</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
	<body>
		<?php
		require_once('functions.php');
		if (loggedin()) {
			include('page.php');
		} else {
			include('login.php');
		}
		?>
	</body>
</html>
