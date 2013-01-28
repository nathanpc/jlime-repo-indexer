<html>
	<head>
		<meta charset="utf-8">
		<title>Jlime Repository Indexer</title>
	</head>
	<body>
		<?php

		require_once "./indexer.php";
		require_once "../db_settings.php";
		require_once "./pass.php";
		
		class Index {
			private $db;
			public $repos = array();

			public function __construct() {
				// Setup PDO.
				$pdo_string = "mysql:host=" . HOSTNAME . ";dbname=" . DB;
				$this->db = new PDO($pdo_string, USERNAME, PASSWORD);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			
			public function get_repos() {
				// Query!
				$query = $this->db->prepare("SELECT * FROM repos");
				$query->execute();

				while ($repo = $query->fetch(PDO::FETCH_ASSOC)) {
					$categories = $repo["categories"];

					if (!empty($categories)) {
						$categories = explode("|", $repo["categories"]);
					}

					// Populate $repos.
					array_push($this->repos, array(
						"name" => $repo["name"],
						"categories" => $categories
					));
				}
			}
			
			public function clean_repos_table() {
				$this->db->query("TRUNCATE TABLE packages");
			}
			
			public function update_repo($repo, $category, $packages) {
				for ($i = 0; $i < count($packages); $i++) {
					$insert_query = $this->db->prepare("INSERT INTO packages (repo, category, json) VALUES (:repo, :category, :json)");
					$insert_query->execute(array(
						":repo" => $repo,
						":category" => $category,
						":json" => json_encode($packages[$i])
					));
				}
			}
		}
		
		?>

		<?php
		
		if (!empty($_GET["pass"]) && htmlentities($_GET["pass"]) == INDEX_PASSWORD) {
			$index = new Index();
			$index->get_repos();
			echo "<p>Got the repos</p>";
			
			// Clean the table for population.
			$index->clean_repos_table();
			echo "<p>Cleaned the tables</p>";

			for ($i = 0; $i < count($index->repos); $i++) {
				$repo = $index->repos[$i];
				echo "<p>Fetching repo: " . $repo["name"] . "</p>";
	
				for ($j = 0; $j < count($repo["categories"]); $j++) {
					$indexer = new Indexer($repo["name"], $repo["categories"][$j]);
	
					$packages = $indexer->get_packages();
					$index->update_repo($repo["name"], $repo["categories"][$j], $packages);
					echo "<p>Indexed " . $repo["name"] . "->" . $repo["categories"][$j] . "</p>";
				}
			}
		} else {
			echo "<h1>The password you entered is incorrect.</h1>";
		}

		?>
	</body>
</html>