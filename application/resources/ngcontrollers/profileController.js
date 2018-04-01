windandcrops.controller('profileController', ['$scope', '$http', '$location', function($scope, $http, $location) {

    $scope.showProfile = true;
    $scope.locateUser = false;
    $scope.geoLocErrMsg = "";
    $scope.geoLocErr = false;
    $scope.dashActive = false;
    $scope.locationPickerActive = false;
    $scope.locData = null;
    $scope.place = '';
    $scope.popdata = null;
    //If active shows user trouble
    $scope.userTrouble = false;
    $scope.lat = 22.59;
    $scope.lon = 79.96;

    $scope.checkLoginStatus = function() {
        try {
            $http.get("auth/logged").then(function(response) {
                if (response["data"].logged == "true") {
                    try {
                        if (localStorage.getItem("wandcUserData")) {
                            $scope.profileData = JSON.parse(localStorage.getItem("wandcUserData"));
                        } else {
                            localStorage.setItem("wandcUserData", JSON.stringify(response.data));
                            $scope.profileData = JSON.parse(localStorage.getItem("wandcUserData"));
                        }
                        console.log($scope.profileData);
                    } catch (e) {
                        console.log(e.message);
                        $scope.userTrouble = true;
                    }
                    // User already picked location...
                    if (localStorage.getItem("wandcLoc")) {
                        $scope.showDash();
                    } else {
                        $scope.askForLocation();
                    }
                    return;
                }
                if (response["data"].logged == "false") {
                    localStorage.removeItem('wandcLoc');
                    localStorage.removeItem('wandcUserData');
                    localStorage.removeItem('locArea');
                    $location.path('/');
                }
            }, function(error) {
                console.log(error);
                $scope.userTrouble = true;
                $location.path('/');
            })

        } catch (e) {
            console.log(e.message);
            $scope.userTrouble = true;
        }
    }
    $scope.checkLoginStatus();

    $scope.logout = function() {
        try {
            $http.get('auth/logout').then(function() {
                localStorage.removeItem('wandcUserData');
                localStorage.removeItem('wandcLoc');
                localStorage.clear();
                $location.path('/')
            }, function(error) {
                console.log(error);
                $scope.userTrouble = true;
            });
        } catch (e) {
            console.log(e.message);
            $scope.userTrouble = true;
        }
    }

    $scope.askForLocation = function() {
        $scope.locateUser = true;
    }
    $scope.storeLoc = function() {
        localStorage.setItem("wandcLoc", JSON.stringify({
            lat: $scope.lat,
            long: $scope.lon
        }));

        $scope.showDash();
    }

    //Got user location store in localstorage
    $scope.foundPos = function(pos) {
        try {
            $scope.lat = pos.coords.latitude;
            $scope.lon = pos.coords.longitude;
            $scope.storeLoc();
        } catch (e) {
            console.log(e.message);
            $scope.userTrouble = true;
        }
    }

    //Failed to get locaction / denied
    $scope.foundPosFail = function() {
        try {
            $scope.$apply(function() {
                $scope.geoLocErr = true;
            });
            $scope.geoLocErrMsg = "Unable to get current position! Try using map picker.";
        } catch (e) {
            console.log(e.message);
            $scope.userTrouble = true;
        }
    }

    //Gets location using gps
    $scope.getGeolocation = function() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition($scope.foundPos, $scope.foundPosFail);
        } else {
            $scope.geoLocErr = true;
            $scope.geoLocErrMsg = "Sorry Geolocation is not supported! You can try using map picker.";
        }
    }

    //Shows google maps to pick location
    $scope.pickLocation = function() {
        try {
            $scope.locateUser = false;
            $scope.locationPickerActive = true;
            $('#locater').locationpicker({
                location: {
                    latitude: 22.59,
                    longitude: 79.96
                },
                
                inputBinding: {
                    locationNameInput: $('#placeNameSource')
                },
                enableAutocomplete: true,
                autocompleteOptions: {
                    types: ['(cities)'],
                    componentRestrictions: {
                        country: 'in'
                    }
                },
                zoom: 6,
                onchanged: function(currLocation, r, m) {
                    $scope.lat = currLocation.latitude;
                    $scope.lon = currLocation.longitude;
                }
            });
        } catch (e) {
            console.log(e.message);
            $scope.userTrouble = true;
        }
    }



    $scope.showDash = function() {
        $scope.locData = JSON.parse(localStorage.getItem("wandcLoc"));
        $scope.lat = $scope.locData.lat;
        $scope.lon = $scope.locData.long;
        $scope.placename = $scope.locData.place;
        $scope.locateUser = false;
        $scope.locationPickerActive = false;
        try {
            console.log($scope.lat + " " + $scope.lon);
            $http.get('userdat/weather_data?lon=' + $scope.lon + "&lat=" + $scope.lat).then(function(response) {
                $scope.weather = (response.data);

                $http.get("userdat/popdata?city=" + $scope.weather.name).then(function(response) {
                    $scope.popdata = response.data;
                }, function(err) {
                    console.log(err);
                })

            }, function(err) {
                console.log(err);
                $scope.userTrouble = true;
            })
        } catch (e) {
            console.log(err);
            $scope.userTrouble = true;
        }
        $scope.dashActive = true;
    }

}]);