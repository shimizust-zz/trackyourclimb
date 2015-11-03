<?php
include "../core/bootstrap.php";

class ClimbingAreaService {
	
	protected $climbingAreaDAO;
	
	public function __construct() {
		$this->climbingAreaDAO = new ClimbingAreaDAO();
	}
	
	public function getCountryCode($climbingAreaID) {
		/*
		 * Return the country code for the specified climbing area
		 */
		return $this->getClimbingAreaProperties($climbingAreaID)["countryCode"];
	}
				
	public function getClimbingAreaProperties($climbingAreaID) {
		$selectKeyValue = ["gymid", $climbingAreaID];
		return $this->climbingAreaDAO->getClimbingAreaProperty($selectKeyValue)[0];
	}
				
}