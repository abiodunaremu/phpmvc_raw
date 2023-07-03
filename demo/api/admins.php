<?php

function admins_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0){
        //register a admin
        $_POST['firstname'] = $decoded['firstname'];
        
        $_POST['fullname'] = $decoded['fullname'];
        $_POST['username'] = $decoded['username'];
        $_POST['password'] = $decoded['password'];
        $_POST['description'] = $decoded['description'];
        $_POST['address'] = $decoded['address'];
        $_POST['country'] = $decoded['country'];
        $_POST['phonenumber'] = $decoded['phonenumber'];
        $_POST['email'] = $decoded['email'];
        $_POST['filegroupid'] = $decoded['filegroupid'];
        
        include './../Controller/newadmin.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') 
        === 0 && sizeof($params) === $index_entity+2){
        //retrieve admin information
        $_GET['adminid'] = $params[sizeof($params)-1];
        include './../Controller/getadmindetails.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET')
     === 0      
    && sizeof($params) === $index_entity+4
    && strcasecmp($params[sizeof($params)-2], 'search') === 0){
        //retrieve user information
        $_GET['criteria'] = $params[sizeof($params)-1];
        include './../Controller/searchusers.php';
    }else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') === 0){
        //reset user password
        $_PUT['email'] = $decoded['email'];
        $_PUT['phonenumber'] = $decoded['phonenumber'];
        include './../Controller/resetpassword.php';
    }else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') === 0
    && sizeof($params) === $index_entity+3
    && strcasecmp($params[sizeof($params)-1], 'profilepicture') === 0){
        //upload profile picture
        $_PUT['sessionid'] = $decoded['sessionid'];
        include './../Controller/uploadprofilepicture.php';
    }else{            
        $_POST['source'] = "users";
        include './../Controller/showinvalidrequest.php';
    }
}
?>