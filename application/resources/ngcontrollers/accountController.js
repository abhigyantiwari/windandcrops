windandcrops.controller('accountController', function($scope, $http, $location){

	$scope.showloginErr = false;
	$scope.showSignupErr = false;
	$scope.showPage = false;

	$scope.checkLoginStatus = function(){
		try{
		$http.get("auth/logged").then(function(response){
			if(response["data"].logged == "true"){
				$location.path('/profile');
			} else{
				$scope.showPage = true;
			}
		}, function(error){
			console.log(error);
            $scope.userTrouble = true;
		})
	} catch(e){
		console.log(e.message);
            $scope.userTrouble = true;
	}
	}
	$scope.checkLoginStatus();

	$scope.loginFormSubmit = function(){
		var aintEmpty = $scope.log_email.length > 1 && $scope.log_password.length > 1;
		if(aintEmpty){
			$http.post('auth/login', {
				"email":$scope.log_email,
				"password":$scope.log_password,
				"captcha":null//grecaptcha.getResponse()
			}).then(function(response){
					if(response["data"].loginStatus == "success"){
						localStorage.setItem("wandcUserData", JSON.stringify(response["data"]));
						$location.path('profile');
					} else{
						$scope.showLoginErr = true;
						$scope.loginErr = "Wrong Credentials!";
					}
					
			}, function(response){
					$scope.showloginErr = true;
					console.log(response);
           			$scope.userTrouble = true;
			})
		}
	} 
	$scope.signupFormSubmit = function(){
		var aintEmpty = $scope.reg_email.length > 1 && $scope.reg_password.length > 1 && $scope.fullname.length > 1;
		if(aintEmpty){
			$http.post('auth/signup', {
				"fullname":$scope.fullname,
				"email":$scope.reg_email,
				"password":$scope.reg_password,
				"captcha":null//grecaptcha.getResponse()
			}, {
				headers:{
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				}
			}).then(function(response, status){
				
				$scope.regStatus = response["data"].signupStatus;
				switch ($scope.regStatus) {
					case "empty":
						$scope.signupErr = "Empty fields! Fill form correctly.";
						break;
					case "exists":
						$scope.signupErr = "Sorry email already has been taken!.";
						break;
					case "failed":
						$scope.signupErr = "Sorry Server Issues, try again later!";
						break;
					case "registered":
						$scope.signupErr = "Successfully registered. Please login to continue!";
						break;
					default:
						alert("Server Issues, please try again later!");
						break;
				}
				$scope.showSignupErr = true;
			}, function(response){
				alert("Server Issues, please try again later!")
				console.log(JSON.stringify(response));
			})
		}
	}
})