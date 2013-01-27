<?php

$repo     = htmlentities($_GET["repo"]);
$cat      = htmlentities($_GET["cat"]);
$repo_url = build_repo_url($repo, $cat);

function build_repo_url($repository, $category) {
	$repo_base_url = "http://www.jlime.com/downloads/repository";
	// TODO: Implement feed-lagacy too.
	return "$repo_base_url/$repository/feed/$category/Packages";
}

function parse_packages($file) {
	// Gets an array with raw plain text from each package description.
	$package_arr = explode("\n\n", $file);

	// Pops the last element if its empty.
	if (empty($package_arr[count($package_arr) - 1])) {
		array_pop($package_arr);
	}
	
	// Parse each package description.
	for ($i = 0; $i < count($package_arr); $i++) {
		$curr_package = explode("\n", $package_arr[$i]);
		$package_arr[$i] = array();

		for ($j = 0; $j < count($curr_package); $j++) {
			$desc_arr = explode(": ", $curr_package[$j], 2);
			$package_arr[$i][$desc_arr[0]] = $desc_arr[1];
		}
	}
	
	return $package_arr;
}

$packages_file = file_get_contents($repo_url);
//header("Content-Type: text/plain");
header("Content-Type: application/json");
echo json_encode(parse_packages($packages_file));
//print_r(parse_packages($packages_file));

?>