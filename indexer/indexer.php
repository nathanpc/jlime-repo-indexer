<?php

class Indexer {
	public $repository;
	public $category;
	public $repo_url;
	public $packages_file;
	
	public function __construct($repository, $category) {
		$this->repository = $repository;
		$this->category = $category;
		$this->repo_url = $this->build_repo_url($repository, $category);
	}

	public static function build_repo_url($repository, $category) {
		$repo_base_url = "http://www.jlime.com/downloads/repository";
		$repo_url = "$repo_base_url/$repository";
		
		if (!empty($category)) {
			$repo_url .= "/feed/$category/Packages";
		}
		
		return "$repo_url";
	}

	private function parse_packages($file) {
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
	
	public function get_packages() {
		$this->packages_file = file_get_contents($this->repo_url);
		return $this->parse_packages($this->packages_file);
	}
}

?>