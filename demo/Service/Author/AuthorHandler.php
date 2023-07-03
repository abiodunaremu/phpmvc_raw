<?php
namespace Service\Author;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;

use Lib\FileManager\FileManager;
use Lib\FileManager\FileFormatter\FileFormatterPool;
use Lib\FileManager\FileFormatter\FileFormatterFactory;
use Lib\FileManager\FileCreator\FileCreatorHandler;
use Lib\FileUploadManager\FileUploadManager;

class AuthorHandler{
    
    private $author;
    private $authors;
    private $status;

    public function __construct(){

    }

    public function persistAuthor($name, $description,
    $address, $phoneNumber, $email, $fileGroupCode, $sessionId){

        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsAuthor('".$name."', '".$description
        ."', '".$address."', '".$phoneNumber."', '".$email."','"
        .$fileGroupCode."', '".$sessionId."', @sID);";
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
                                "Class::AuthorHandler->persistAuthor Empty status returned ",
                                "Unable to register Author .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::AuthorHandler->persistAuthor Empty result set returned",
                            "Unable to register Author .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::AuthorHandler->persistAuthor Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Author .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassAuthorHandler->persistAuthor Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Author .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassAuthorHandler->persistAuthor Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Author .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getAuthors(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Author WHERE cStatus='0' 
        ORDER BY dCreatedDate ;";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->authors = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->authors, new Author(
                        $row["vAuthorCode"], $row["vName"]
                        , $row["vEmail"]
                        , $row["tDescription"]
                        , $row["vAddress"]
                        , $row["vPhonenumber"]
                        , $row["vFileGroupID"]
                        , $row["dCreatedDate"]
                        , $row["dLastUpdate"], $row["cStatus"])); 
                        
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "AuthorHandler->getAuthors Empty resultset",
                    "Author does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "AuthorHandler->getAuthors Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->authors;
    }

    public function getAuthorById($sessionId, $authorId){
        return $this->getAuthor($sessionId, $authorId);
    }
    
    protected function getAuthor($sessionId, $authorId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Author where vAuthorCode='".$authorId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->author = new Author(
                    $row["vAuthorCode"], $row["vName"]
                    , $row["vEmail"]
                    , $row["tDescription"]
                    , $row["vAddress"]
                    , $row["vPhonenumber"]
                    , $row["vFileGroupID"]
                    , $row["dCreatedDate"]
                    , $row["dLastUpdate"], $row["cStatus"]);  
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "AuthorHandler->getAuthor; Empty resultset for code '".$courseCode."'",
                    "The Author  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{ysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "AuthorHandler->getAuthor; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->author;
    }    

    public function getPaginatedAuthors($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM Author WHERE cStatus='0'
         ORDER BY dCreatedDate  LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->authors = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->authors, new Author(
                        $row["vAuthorCode"], $row["vName"]
                        , $row["vEmail"]
                        , $row["tDescription"]
                        , $row["vAddress"]
                        , $row["vPhonenumber"]
                        , $row["vFileGroupID"]
                        , $row["dCreatedDate"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBookCategories Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBookCategories Null resultst: "
            .DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->authors;
    }

    public function getAuthorCount(){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM Author WHERE cStatus='0';";
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
                    "BookHandler->getAuthorCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getAuthorCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }


    public function updateAuthor($sessionId, $authorId
    , $field, $value){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdAuthor('".$sessionId.
        "','".$authorId."','".$field."','".$value."', @sID);";
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
                                    "Class:AuthorHandler->updateAuthor; Invalid operation returned "
                                    .$row["s_id"].", for sid".$sessionId,
                                    "Unable to update Author .<br/>Please try again Later"
                                );
                                ErrorReporter::addNode($errorNode);                                 
                            }

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:AuthorHandler->updateAuthor; Empty session id returned ",
                                "Unable to update Author .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:AuthorHandler->updateAuthor; Empty result set returned",
                            "Unable to update Author .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:AuthorHandler->updateAuthor; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to update Author .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:AuthorHandler->updateAuthor; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to update Author .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:AuthorHandler->updateAuthor; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to update Author .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    
    public function deleteAuthor($sessionId, $authorId){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdAuthor('".$authorId.
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
                                "Class:AuthorHandler->deleteAuthor; Empty session id returned ",
                                "Unable to delete Author .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:AuthorHandler->deleteAuthor; Empty result set returned",
                            "Unable to delete Author .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:AuthorHandler->deleteAuthor; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete Author .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:AuthorHandler->deleteAuthor; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete Author .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:AuthorHandler->deleteAuthor; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete Author .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function uploadImage($sessionId
    , $fileName, $tempFileURL, $fileSize){//src_name, src_ext
        $errorNodeFactory = new ErrorNodeFactory();
        //instantiate file creator
        $fileCreatorName = "AUTHOR_IMAGE";
        $fileCreatorHandler = new FileCreatorHandler();
        $fileCreator = $fileCreatorHandler
        ->getFileCreatorByName($fileCreatorName);

        //create logic files for temp and source files
        $fileNameArray = explode(".", $fileName);
        $fileExtension = $fileNameArray[sizeof($fileNameArray)-1];
        $fileName = $fileNameArray[0];
        $filePath = "";

        $tempFilePathArray = explode("\\", $tempFileURL);
        $tempFileNameFull = $tempFilePathArray[sizeof($tempFilePathArray)-1];
        $tempFileNameFullSplit = explode(".", $tempFileNameFull);

        
        $tempFileExtension = sizeof($tempFileNameFullSplit) > 1 ?
         $tempFileNameFullSplit[sizeof($tempFileNameFullSplit)-1] : "";
         
        $tempFileName = $tempFileNameFullSplit[0];
        $tempFilePath = str_replace($tempFileNameFull, "", $tempFileURL);

        $fileManager = new FileManager();
        $tempFile = $fileManager
        ->createLogicFile($tempFileName, $tempFileExtension
        , $tempFilePath, $fileSize);
        $sourceFile = $fileManager
        ->createLogicFile($fileName, $fileExtension
        , $filePath, $fileSize);

        //instantiate file formatters and upload with valid formatter
        $fileFormatterFactory = new FileFormatterFactory();
        $JPEGImageFileFormatter = $fileFormatterFactory
        ->createJPEGImageFileFormatter($sourceFile, $fileCreator);

        $fileFormatterPool = new FileFormatterPool();
        $fileFormatterPool->addFileFormatter($JPEGImageFileFormatter);
        $fileFormatterPool->setSourceFile($sourceFile);
        $validFileFormatter = $fileFormatterPool->getValidFileFormatter();

        if($validFileFormatter !== null && $validFileFormatter != ""){
            $fileUploadManager = new FileUploadManager();
            $singleFileUploader = $fileUploadManager->createSingleFileUploader();
            return $singleFileUploader->upload($sessionId
            , $tempFile, $validFileFormatter);
        } else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:AuthorsHandler->uploadImage; invalid file format: ".
                DatabaseManager::mysql_error( ),
                "The file format is not supported for this operation .<br/>Please try again with appropriate file"
            );
            ErrorReporter::addNode($errorNode); 
        }
    }
}
?>