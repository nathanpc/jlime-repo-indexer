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
		}
		
		?>

		<?php
		
		$index = new Index();
		$index->get_repos();
		
		print_r($index->repos);
		
		?>
	</body>
</html>