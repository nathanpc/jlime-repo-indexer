<?php

$repo     = htmlentities($_GET["repo"]);
$cat      = htmlentities($_GET["cat"]);
$repo_url = build_repo_url($repo, $cat);

function build_repo_url($repository, $category) {
	$repo_base_url = "http://www.jlime.com/downloads/repository";
	// TODO: Implement feed-lagacy too.
	return "$repo_base_url/$repository/feed/$category/Packages";
}

print $repo_url;


?>