<html>
	<head>
		<meta charset="utf-8">
		<title>JLime Repository Indexer</title>
	</head>
	<body>
		<?php

		require_once "./indexer.php";
		require_once "../db_settings.php";
		
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
					// Populate $repos.
					array_push($this->repos, array(
						"name" => $repo["name"],
						"categories" => explode("|", $repo["categories"])
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
		
		$index = new Index();
		$index->get_repos();
		
		// Clean the table for population.
		$index->clean_repos_table();

		for ($i = 0; $i < count($index->repos); $i++) {
			$repo = $index->repos[$i];

			for ($j = 0; $j < count($repo["categories"]); $j++) {
				$indexer = new Indexer($repo["name"], $repo["categories"][$j]);

				$packages = $indexer->get_packages();
				$index->update_repo($repo["name"], $repo["categories"][$j], $packages);
			}
		}

		?>
	</body>
</html>