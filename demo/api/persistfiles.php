<?php

function persistfiles_api($params, $decoded, $index_entity){    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') === 0
    && sizeof($params) === $index_entity+2){            
        $_GET['sessionid'] = $decoded['sessionid'];
        $_GET['persistfileid'] = $params[sizeof($params)-1];
        include './../Controller/getpersistfiledetails.php';
    } else if($params[sizeof($params)-1] === "image"
    && sizeof($params) === $index_entity+3){            
        $_GET['persistfileid'] = $params[sizeof($params)-2];
        include './../Controller/getimagefile.php';
    } else if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') === 0
    && $params[sizeof($params)-2] === "group"
    && sizeof($params) === $index_entity+4){            
        $_GET['groupid'] = $params[sizeof($params)-1];
        include './../Controller/getallpersistfiles.php';
    } else {            
        $_POST['source'] = "persistfiles";
        include './../Controller/showinvalidrequest.php';
    }
}

?>