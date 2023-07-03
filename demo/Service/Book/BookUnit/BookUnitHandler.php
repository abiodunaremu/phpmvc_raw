<?php
namespace Service\Book\BookUnit;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class BookUnitHandler{
    
    private $bookUnit;
    private $bookUnits;
    private $status;

    public function __construct(){

    }

    public function persistBookUnit($name, $sessionId){
        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsBookUnit('".$name."', '"
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
                                "Class::BookUnitHandler->persistBookUnit Empty status returned ",
                                "Unable to register Book Type .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::BookUnitHandler->persistBookUnit Empty result set returned",
                            "Unable to register Book Type .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::BookUnitHandler->persistBookUnit Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Book Type .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassBookUnitHandler->persistBookUnit Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Book Type .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassBookUnitHandler->persistBookUnit Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Book Type .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getBookUnits(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookUnit WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookUnits = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookUnits, new BookUnit(
                        $row["vBookUnitCode"], $row["vName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookUnitHandler->getBookUnits Empty resultset",
                    "BookUnit does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookUnitHandler->getBookUnits Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookUnits;
    }

    public function getBookUnitById($sessionId, $bookTypeId){
        return $this->getBookUnit($sessionId, $bookTypeId);
    }
    
    protected function getBookUnit($sessionId, $bookTypeId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookUnit where vBookUnitCode='".$bookTypeId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->bookUnit = new BookUnit(
                    $row["vBookUnitCode"], $row["vName"]
                    , $row["dDateCreated"]
                    , $row["dLastUpdate"], $row["cStatus"]);  
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookUnitHandler->getBookUnit; Empty resultset for code '".$courseCode."'",
                    "The BookUnit  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{ysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookUnitHandler->getBookUnit; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookUnit;
    }    

    public function getPaginatedBookUnits($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookUnit WHERE cStatus='0' 
        ORDER BY dDateCreated LIMIT "
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
                    array_push($this->bookCategorys, new BookUnit(
                        $row["vBookUnitCode"], $row["vName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBookUnits Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBookUnits Null resultst: "
            .DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }

    public function getBookUnitCount(){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM BookUnit WHERE cStatus='0';";
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
                    "BookHandler->getBookUnitCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookUnitCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }
    


    public function updateBookUnit($sessionId, $bookUnitId
    , $field, $value){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookUnit('".$sessionId.
        "','".$bookUnitId."','".$field."','".$value."', @sID);";
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
                                    "Class:BookUnitHandler->updateBookUnit; Invalid operation returned "
                                    .$row["s_id"].", for sid".$sessionId,
                                    "Unable to delete BookUnit .<br/>Please try again Later"
                                );
                                ErrorReporter::addNode($errorNode);                                 
                            }

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:BookUnitHandler->deleteBookUnit; Empty session id returned ",
                                "Unable to delete BookUnit .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookUnitHandler->deleteBookUnit; Empty result set returned",
                            "Unable to delete BookUnit .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookUnitHandler->deleteBookUnit; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookUnit .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookUnitHandler->deleteBookUnit; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookUnit .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookUnitHandler->deleteBookUnit; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookUnit .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }


    public function deleteBookUnit($sessionId, $bookTypeId){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookUnit('".$bookTypeId.
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
                                "Class:BookUnitHandler->deleteBookUnit; Empty session id returned ",
                                "Unable to delete BookUnit .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookUnitHandler->deleteBookUnit; Empty result set returned",
                            "Unable to delete BookUnit .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookUnitHandler->deleteBookUnit; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookUnit .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookUnitHandler->deleteBookUnit; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookUnit .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookUnitHandler->deleteBookUnit; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookUnit .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

}
?>