<?php
	require_once("Rest.inc.php");
	const PROD_JSON_PATH = "product_sample.json";
	class API extends REST {
		public $data = "";
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
	
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi($filepath,$pricerange='',$color='',$category='',$sortby=''){
			$func = strtolower(trim(str_replace("/[\\]/g","",$_REQUEST['action'])));
			if((int)method_exists($this,$func) > 0) {
				$result = $this->$func($filepath,$pricerange,$color,$category,$sortby);
				$this->response($result,200);
			}else {
				$this->response('Invalid Request',404);				// If the method not exist with in this class, response would be "Page not found".
			}
		}
	}
	
	// Initiate Library
	$view = file_get_contents("php://input");
    $customList =  json_decode($view);
    $pricerange = isset($customList->pricerange)?$customList->pricerange:'';
    $color = isset($customList->color)?$customList->color:'';
    $category = isset($customList->category)?$customList->category:'';
    $sortby = isset($customList->sortby)?$customList->sortby:'';
	$api = new API;
	if(isset($_REQUEST['action']) && $_REQUEST['action']!=''){
		$api->processApi(PROD_JSON_PATH,$pricerange,$color,$category,$sortby);
	}else{
		$api->response('Invalid Request',404);
	}
?>