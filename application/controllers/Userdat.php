<?php 
require('vendor/autoload.php');
use \Firebase\JWT\JWT;

class Userdat extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('data_model');
	}
	public function index(){

	}
	public function weather_data(){
		$lon = $this->input->get('lon');
		$lat = $this->input->get('lat');

		header("Content-type: application/json");
		$wDat = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&units=metric&appid=d0140333c4865b5d39c8dee07f821433");
		echo ($wDat);
	}

	public function weather_forecast(){
		$lon = $this->input->get('lon');
		$lat = $this->input->get('lat');

		header("Content-type: application/json");
		$wDat = file_get_contents("http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$lon."&units=metric&appid=d0140333c4865b5d39c8dee07f821433");
		echo ($wDat);
	}

	public function rev_geocode(){
		$lon = $this->input->get('lon');
		$lat = $this->input->get('lat');

		header("Content-type: application/json");
		$wDat = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&key=AIzaSyA1fNxjPlULmQrgrriqTpvu9iESkYFF6WA");
		echo ($wDat);	
	}

	public function popdata(){
		header("Content-type: application/json");
		$city = $this->input->get('city');
		$popdata = $this->data_model->getPopData($city);
		echo json_encode($popdata);
	}
	
}