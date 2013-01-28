<html>
	<head>
		<meta charset="utf-8">
		<title>Jlime Repositories</title>

		<!-- jQuery (TODO: Use the cloud link instead of the local one) -->
		<script src="libs/jquery.js" type="text/javascript"></script>

		<!-- Bootstrap (TODO: Use the cloud link instead of the local one) -->
		<link href="libs/normalize.css" rel="stylesheet" type="text/css">
		<link href="libs/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
		<script src="libs/bootstrap/js/bootstrap.js" type="text/javascript"></script>

		<link href="css/main.css" rel="stylesheet" type="text/css">
		<script src="js/main.js" type="text/javascript"></script>
	</head>
	<body>
		<?php

		// Request variables.
		$req_type = htmlentities($_GET["type"]);
		$req_repo = htmlentities($_GET["repo"]);
		$req_cat = "";

		if (!empty($_GET["cat"])) {
			$req_cat = htmlentities($_GET["cat"]);
		}

		?>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">Jlime Repositories</a>

					<ul class="nav">
						<li class="active"><a href="#">Home</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container content">
			<table class="table table-condensed table-hover">
				<thead>
					<tr>
					<?php if ($req_type == "categories") { ?>
						<th>Category</th>
					<?php } else if ($req_type == "packages") { ?>
						<th>Name</th>
						<th>Version</th>
					<?php } ?>
					</tr>
				</thead>
				
				<tbody>
					<?php
					
					require_once "./db_settings.php";
					
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
						
						$categories = $repo["categories"];
						if (!empty($categories)) {
							$categories = explode("|", $categories);

							for ($i = 0; $i < count($categories); $i++) {
								$curr_category = $categories[$i];
								echo "<tr onclick=\"window.location = 'list.php?type=packages&repo=$name&cat=$curr_category'\">\n<td>$curr_category</td>\n</tr>\n\n";
							}
						}
					} else if ($req_type == "packages") {
						// Query the packages for the categories on the repo.
						$query = $db->prepare("SELECT * FROM packages WHERE repo = :repo AND category = :category");
						$query->execute(array(
							":repo" => $req_repo,
							":category" => $req_cat
						));
					
						while ($raw_package = $query->fetch(PDO::FETCH_ASSOC)) {
							$raw_json = $raw_package["json"];
							$package = json_decode($raw_json);
							$name = $package->Package;
							$version = $package->Version;

							$raw_json = str_replace("'", "\\\"", $raw_json);
							echo "<tr onclick='show_details($raw_json);'>\n<td>$name</td>\n<td>$version</td>\n</tr>";
						}
					}
					
					?>
				</tbody>
			</table>
		</div>
		
		<div id="details" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3></h3>
				<h4></h4>
			</div>

			<div class="modal-body">
				
			</div>

			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<!--<a href="#" class="btn btn-primary">Download</a>-->
			</div>
		</div>
	</body>
</html>