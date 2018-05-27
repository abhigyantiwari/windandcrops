<?php 
require('vendor/autoload.php');
use \Firebase\JWT\JWT;

class Auth extends CI_Controller{

	private $loginRestData;
	private $signupRestData;
	private $secretSignCheckHash = "waaaa! #&*# casino royale; a great moovee! $$$";
	
	public function __construct(){
		parent::__construct();
		header("Content-type: application/json");
		// Contain RESTful data
		$this->signupRestData = array();
		$this->loginRestData = array();
		$this->load->helper('url');
		$this->load->model('auth_model');
		$this->load->model('data_model');
	}
	public function index(){

	}

	public function getpopdata(){
		$popData = $this->data_model->get_population();
		echo json_encode($popData);
	}

	public function logged(){
		header("Content-type: application/json");
		$tokExist = "false";
		if( isset($_COOKIE['jwtk']) && $this->verifyJWT()){
			$tokExist = "true";
			$userdat = $this->data_model->get_user_data($_COOKIE['jwtk']);
			echo json_encode(
				array( 
					"logged" => $tokExist,
					"user_dat" => $userdat
				)
			);
		} else{
			echo json_encode(array("logged" => $tokExist));
		}
	}

	public function verifyJWT(){
		$verified = true;
		try{
			JWT::decode($_COOKIE['jwtk'], $this->secretSignCheckHash, array('HS256'));
			if(!$this->auth_model->exists_logged_user($_COOKIE['jwtk'])){
			$verified = false;		
		};
		} catch(Exception $e){
			unset($_COOKIE['jwtk']);
			setcookie('jwtk', "", time()-86400*60, '/', null, FALSE, TRUE);
			$verified = false;
		}
		
		return $verified;
	}

	public function login(){
		// Prevent if user modifies JS and logs in
		if(isset($_COOKIE['jwtk']) && $this->verifyJWT()){
			return;
		}

		$postJSON = file_get_contents("php://input");
		$post = json_decode($postJSON);
		$email = htmlspecialchars(trim($post->email));
		$password = htmlspecialchars($post->password);

		// try{
		// 	$verifyCaptchaResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LdGdEUUAAAAAJ6bPyyYRZhGJZeTwXuF8oiDNqRy&response='.$post->captcha);
  //       $responseCaptchaData = json_decode($verifyCaptchaResponse);
  //   	} catch(Exception $e){
  //   		$this->loginRestData['loginStatus'] = '500';
  //   		$this->signupRestData['signupStatus'] = '500';
  //   	}

        // if($responseCaptchaData->success){
        // 	$this->loginRestData['captcha'] = 'solved';
        // } else{
        // 	$this->loginRestData['captcha'] = 'failed';
        // }

		if(empty($email) || empty($password)){
			$this->_outRestData('empty');
		} else if($this->auth_model->find_user($email, $password)){
			$jwtData = array(
				"iat" => time(),
				"jti" => base64_encode(openssl_random_pseudo_bytes(16)),
				"iss" => site_url(),
				"data" => array(
					//Fixed size secret hash used for preventing jwt theft
					"secrethash" => hash('sha256', $_SERVER['HTTP_USER_AGENT'])
				)
			);
			$jwt = jwt::encode($jwtData, $this->secretSignCheckHash, 'HS256');
			setcookie('jwtk', $jwt, time()+86400*30, '/', null, FALSE, TRUE);

			$this->loginRestData['jwt'] = ($jwt);
			$this->loginRestData['loginStatus'] = "success";
			
			if(!$this->auth_model->add_logged_user($email, $jwt)){
				$this->_outRestData('failed');
				return;
			}
			
			$this->loginRestData['userdata'] = $this->data_model->get_user_data($jwt);
			$this->_outRestData($this->loginRestData['loginStatus'], $this->loginRestData['jwt'], $this->loginRestData['userdata']);
		} else{
			$this->_outRestData("failed");
		}

	}

	public function _outRestData($loginStatus = '', $jwt = '', $userdata = ''){
		
		$restArr = array(
			"loginStatus" => $loginStatus,
			"tok" => $jwt,
			"userdata" => $userdata
		);

		echo json_encode($restArr);
	}

	public function logout(){
		$this->auth_model->delete_logged_user($_COOKIE['jwtk']);
		unset($_COOKIE['jwtk']);
		setcookie('jwtk', "", time()-86400*60, '/', null, FALSE, TRUE);
	}

	public function signup(){
		
		$postJSON = file_get_contents("php://input");
		$post = json_decode($postJSON);
		$fullname = htmlspecialchars(trim($post->fullname));
		$email = htmlspecialchars(trim($post->email));
		$password = htmlspecialchars($post->password);

		// $verifyCaptchaResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lf3zE4UAAAAAIlbcArMt7EDOAlXSyOs7-CyFH5Q&response='.$post->captcha);
  //       $responseCaptchaData = json_decode($verifyCaptchaResponse);

  //       if($responseCaptchaData->success){
  //       	$this->signupRestData['captcha'] = 'solved';
  //       } else{
  //       	$this->signupRestData['captcha'] = 'failed';
  //       }


		if(empty($email) || empty($password) || empty($fullname)){
			$this->signupRestData['signupStatus'] = 'empty';
		} else if($this->auth_model->email_used($email)){
			$this->signupRestData['signupStatus'] = "exists";
		} else{
			if($this->auth_model->register_user($fullname, $email, $password)){
				$this->signupRestData['signupStatus'] = "registered";
			} else{
				$this->signupRestData['signupStatus'] = "failed";
			}
		}
		echo json_encode($this->signupRestData);
	}
}