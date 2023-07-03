<?php
namespace Service\Book\BookType;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class BookTypeHandler{
    
    private $bookType;
    private $bookTypes;
    private $status;

    public function __construct(){

    }

    public function persistBookType($name, $sessionId){
        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsBookType('".$name."', '"
        .$sessionId."', @sID);";
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);
                    if($num_results>0) {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["s_id"],"")!=0) {
                            $this->status = $row["s_id"];

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class::BookTypeHandler->persistBookType Empty status returned ",
                                "Unable to register Book Type .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::BookTypeHandler->persistBookType Empty result set returned",
                            "Unable to register Book Type .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::BookTypeHandler->persistBookType Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Book Type .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassBookTypeHandler->persistBookType Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Book Type .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassBookTypeHandler->persistBookType Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Book Type .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getBookTypes(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookType WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookTypes = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookTypes, new BookType(
                        $row["vBookTypeCode"], $row["vName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookTypeHandler->getBookTypes Empty resultset",
                    "BookType does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookTypeHandler->getBookTypes Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookTypes;
    }


    public function getPaginatedBookTypes($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookType WHERE cStatus='0' ORDER BY dDateCreated LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookType(
                        $row["vBookTypeCode"], $row["vName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBookTypes Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBookTypes Null resultst: "
            .DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }

    public function getBookTypeCount(){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM BookType WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->book = $row["dCount"];
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getBookTypeCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookTypeCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }
    
    public function getBookTypesWithBook(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookType WHERE vBookTypeCode 
        IN (SELECT vBookTypeCode FROM Book WHERE cStatus='0') AND cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookTypes = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookTypes, new BookType(
                        $row["vBookTypeCode"], $row["vName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookTypeHandler->getBookTypes Empty resultset",
                    "BookType does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookTypeHandler->getBookTypes Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookTypes;
    }

    public function getBookTypeById($sessionId, $bookTypeId){
        return $this->getBookType($sessionId, $bookTypeId);
    }
    
    protected function getBookType($sessionId, $bookTypeId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookType where vBookTypeCode='".$bookTypeId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->bookType = new BookType(
                    $row["vBookTypeCode"], $row["vName"]
                    , $row["dDateCreated"]
                    , $row["dLastUpdate"], $row["cStatus"]);  
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookTypeHandler->getBookType; Empty resultset for code '".$courseCode."'",
                    "The BookType  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookTypeHandler->getBookType; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookType;
    }    

    public function updateBookType($sessionId, $bookTypeId
    , $field, $value){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookType('".$sessionId.
        "','".$bookTypeId."','".$field."','".$value."', @sID);";
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);
                    if($num_results>0) {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["s_id"],"")!=0) {
                            if($row["s_id"] === "OK"){
                            $this->status = $row["s_id"];
                            }else{
                                $errorNode = $errorNodeFactory->createPersistenceError(
                                    "Class:BookTypeHandler->updateBookType; Invalid operation returned "
                                    .$row["s_id"].", for sid".$sessionId,
                                    "Unable to delete BookType .<br/>Please try again Later"
                                );
                                ErrorReporter::addNode($errorNode);                                 
                            }

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:BookTypeHandler->deleteBookType; Empty session id returned ",
                                "Unable to delete BookType .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookTypeHandler->deleteBookType; Empty result set returned",
                            "Unable to delete BookType .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookTypeHandler->deleteBookType; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookType .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookTypeHandler->deleteBookType; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookType .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookTypeHandler->deleteBookType; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookType .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function deleteBookType($sessionId, $bookTypeId){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookType('".$bookTypeId.
        "','',1',@sID);";
        if(DatabaseManager::mysql_query($queryHolder ))
            if(DatabaseManager::mysql_query($query ))
            {
                $result = DatabaseManager::mysql_query($querySelectHolder );
                if($result)
                {
                    $num_results = DatabaseManager::mysql_num_rows($result);
                    if($num_results>0) {            
                        $row = DatabaseManager::mysql_fetch_array($result);
                        if(strcmp($row["s_id"],"")!=0) {
                            $this->status = $row["s_id"];

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:BookTypeHandler->deleteBookType; Empty session id returned ",
                                "Unable to delete BookType .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookTypeHandler->deleteBookType; Empty result set returned",
                            "Unable to delete BookType .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookTypeHandler->deleteBookType; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookType .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookTypeHandler->deleteBookType; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookType .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookTypeHandler->deleteBookType; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookType .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

}
?>