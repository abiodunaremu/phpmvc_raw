<?php
namespace Lib\ResponseBuilder;

class JsonHandler{
    function adjustJsonString($value){
        return str_replace("\'","\\\'",str_replace("\"","\\\"",str_replace("\n","\\n",$value)));
    }

    function adjustSQLString($value){
        return str_replace("'","\'",str_replace("\"","\\\"",$value));
    }

    function quoteString($value){
        return "\"".$value."\"";
    }
}
?>