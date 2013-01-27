<html>
	<head>
		<meta charset="utf-8">
		<title>JLime Repositories</title>
		
		<!-- jQuery (TODO: Use the cloud link instead of the local one) -->
		<script src="libs/jquery.js" type="text/javascript"></script>
		
		<!-- Bootstrap (TODO: Use the cloud link instead of the local one) -->
		<link href="libs/normalize.css" rel="stylesheet" type="text/css">
		<link href="libs/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
		<script src="libs/bootstrap/js/bootstrap.js" type="text/javascript"></script>
		
		<link href="css/main.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="#">JLime Repositories</a>
					
					<ul class="nav">
						<li class="active"><a href="#">Home</a></li>
						<li class="divider-vertical"></li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="container content">
			<ul>
				<?php
				
				require_once "./db_settings.php";
				
				// PDO Stuff.
				$pdo_string = "mysql:host=" . HOSTNAME . ";dbname=" . DB;
				$db = new PDO($pdo_string, USERNAME, PASSWORD);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// Query the repos.
				$query = $db->prepare("SELECT * FROM repos");
				$query->execute();
				
				while ($repo = $query->fetch(PDO::FETCH_ASSOC)) {
					$name = $repo["name"];
					echo "<li><a href=\"list.php?type=categories&repo=$name\">" . ucfirst($name) . "</a></li>";
				}
				
				?>
			</ul>
		</div>
	</body>
</html>