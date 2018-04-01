windandcrops.controller('splashController', function($scope) {

    //Splash Screen Code
    var i = 0;
    var txt = "Get Location based Weather Predictions to grow Crops!";
    var speed = 140;

    function typeWrite() {
        if (i < txt.length) {
            try{
                document.getElementById("splash-msg").innerHTML += txt.charAt(i);
                i++;
                setTimeout(typeWrite, speed);
            } catch(e){
                return;
            }
        }
    }
    typeWrite();

})