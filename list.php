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
					</ul>
				</div>
			</div>
		</div>

		<div class="container content">
			<ul>
				<?php
				
				require_once "./db_settings.php";
				
				// Request variables.
				$req_type = htmlentities($_GET["type"]);
				$req_repo = htmlentities($_GET["repo"]);
				if (!empty($_GET["cat"])) {
					$req_cat = htmlentities($_GET["cat"]);
				}
				
				// PDO Stuff.
				$pdo_string = "mysql:host=" . HOSTNAME . ";dbname=" . DB;
				$db = new PDO($pdo_string, USERNAME, PASSWORD);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				if ($req_type == "categories") {
					// Query the categories for the repo.
					$query = $db->prepare("SELECT * FROM repos WHERE name = :name");
					$query->execute(array(
						":name" => $req_repo
					));
					
					$repo = $query->fetch(PDO::FETCH_ASSOC);
					$name = $repo["name"];
					$categories = explode("|", $repo["categories"]);
					
					for ($i = 0; $i < count($categories); $i++) {
						$curr_category = $categories[$i];
						echo "<li><a href=\"list.php?type=packages&repo=$name&cat=$curr_category\">$curr_category</a></li>";
					}
				} else if ($req_type == "packages") {
					// Query the packages for the categories on the repo.
					$query = $db->prepare("SELECT * FROM packages WHERE repo = :repo AND category = :category");
					$query->execute(array(
						":repo" => $req_repo,
						":category" => $req_cat
					));

					while ($raw_package = $query->fetch(PDO::FETCH_ASSOC)) {
						$package = json_decode($raw_package["json"]);
						$name = $package->Package;
						echo "<li><a href=\"#$name\">$name</a></li>";
					}
				}
				
				?>
			</ul>
		</div>
	</body>
</html>