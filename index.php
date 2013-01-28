<!DOCTYPE html>
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
		
		require_once "./db_settings.php";
		
		$repos = array();
		
		// PDO Stuff.
		$pdo_string = "mysql:host=" . HOSTNAME . ";dbname=" . DB;
		$db = new PDO($pdo_string, USERNAME, PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Query the repos.
		$query = $db->prepare("SELECT * FROM repos");
		$query->execute();
		
		while ($repo = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($repos, $repo);
		}
		
		?>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">Jlime Repositories</a>
					
					<ul class="nav">
						<li class="active"><a href="#">Home</a></li>
						<li class="divider-vertical"></li>
					</ul>
					
					<form class="navbar-search pull-left">
						<input id="search" type="text" class="search-query" placeholder="Search" onkeypress="search_keypress(event);">
					</form>
					
					<div class="btn-group" id="search-dropdown">
						<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
							Repositories
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu">
							<?php
							
							foreach ($repos as $repo) {
								$name = $repo["name"];
								echo "<li><a href=\"#\" onclick=\"search_package('$name');\">" . ucfirst($name) . "</a></li>\n";
							}
							
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container content">
			<table class="table table-condensed table-hover">
				<thead>
					<tr>
						<th>Repository</th>
					</tr>
				</thead>
				
				<tbody>
				<?php

				foreach ($repos as $repo) {
					$name = $repo["name"];
					
					if ($name != "shrek") {
						echo "<tr onclick=\"window.location = 'list.php?type=categories&repo=$name';\"><td><a href=\"#\">" . ucfirst($name) . "</a></td></tr>";
					} else {
						echo "<tr onclick=\"window.location = 'list.php?type=packages&repo=$name';\"><td><a href=\"#\">" . ucfirst($name) . "</a></td></tr>";
					}
				}
				
				?>
				</tbody>
			</table>
		</div>
	</body>
</html>