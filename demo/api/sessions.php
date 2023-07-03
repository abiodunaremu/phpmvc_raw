<?php

function sessions_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //login user session
        $_POST['username'] = $decoded['username'];
        $_POST['password'] = $decoded['password'];
        include './../Controller/loginuser.php';

    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') === 0){
        //Logout user session
        $_PUT['sessionid'] = $decoded['sessionid'];
        include './../Controller/logoutuser.php';
    } else {
        $_POST['source'] = "sessions";
        include './../Controller/showinvalidrequest.php';
    }
}

?>