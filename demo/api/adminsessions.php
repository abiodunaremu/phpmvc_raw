<?php

function adminsessions_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //login admin session
        $_POST['username'] = $decoded['username'];
        $_POST['password'] = $decoded['password'];
        $_POST['cookiecode'] = $decoded['cookiecode'];
        include './../Controller/loginadmin.php';

    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') === 0){
        //Logout user session
        $_PUT['sessionid'] = $decoded['sessionid'];
        include './../Controller/logoutadmin.php';
    } else {
        $_POST['source'] = "adminsessions";
        include './../Controller/showinvalidrequest.php';
    }
}

?>