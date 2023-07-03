<?php
namespace Service\Book\BookAuthor;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

class BookAuthorHandler{
    
    private $bookAuthor;
    private $bookAuthors;
    private $status;

    public function __construct(){

    }

    public function persistBookAuthor($bookName, $authorName
    , $sessionId){
        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsBookAuthor('".$bookName."', '".$authorName
        ."', '".$sessionId."', @sID);";
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
                                "Class::BookAuthorHandler->persistBookAuthor Empty status returned ",
                                "Unable to register Book Author .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::BookAuthorHandler->persistBookAuthor Empty result set returned",
                            "Unable to register Book Author .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::BookAuthorHandler->persistBookAuthor Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Book Author .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassBookAuthorHandler->persistBookAuthor Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Book Author .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassBookAuthorHandler->persistBookAuthor Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Book Author .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getBookAuthors(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBookAuthor WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookAuthor(
                        $row["vBookCode"], $row["vBookName"]
                        , $row["vAuthorCode"], $row["vAuthorName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookAuthorHandler->getBookAuthors Empty resultset",
                    "BookAuthor does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookAuthorHandler->getBookAuthors Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }

    
    public function getBookAuthorByAuthorCode($sessionId, $authorCode){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBookAuthor where vAuthorCode='".$authorCode."' AND cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->bookAuthor = new BookAuthor(
                    $row["vBookCode"], $row["vBookName"]
                    , $row["vAuthorCode"], $row["vAuthorName"]
                    , $row["dDateCreated"]
                    , $row["dLastUpdate"], $row["cStatus"]);  
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookAuthorHandler->getBookAuthorByAuthorCode; Empty resultset for code '".$courseCode."'",
                    "The BookAuthor  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{ysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookAuthorHandler->getBookAuthorByAuthorCode; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookAuthor;
    }    


    
    public function getBookAuthorByBookCode($sessionId, $bookCode){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBookAuthor where vBookCode='".$bookCode."' AND cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookAuthor(
                        $row["vBookCode"], $row["vBookName"]
                        , $row["vAuthorCode"], $row["vAuthorName"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookAuthorHandler->getBookAuthors Empty resultset",
                    "BookAuthor does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookAuthorHandler->getBookAuthors Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }


    public function deleteBookAuthor($sessionId, $bookCode, $authorCode){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookAuthor('".$bookCode.
        "','".$authorCode."',1',@sID);";
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
                                "Class:BookAuthorHandler->deleteBookAuthor; Empty session id returned ",
                                "Unable to delete BookAuthor .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookAuthorHandler->deleteBookAuthor; Empty result set returned",
                            "Unable to delete BookAuthor .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookAuthorHandler->deleteBookAuthor; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookAuthor .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookAuthorHandler->deleteBookAuthor; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookAuthor .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookAuthorHandler->deleteBookAuthor; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookAuthor .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

}
?>