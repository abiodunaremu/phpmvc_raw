<?php
namespace Service\Book\BookCategory;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\FileManager\FileManager;
use Lib\FileManager\FileFormatter\FileFormatterPool;
use Lib\FileManager\FileFormatter\FileFormatterFactory;
use Lib\FileManager\FileCreator\FileCreatorHandler;
use Lib\FileUploadManager\FileUploadManager;

class BookCategoryHandler{
    
    private $bookCategory;
    private $bookCategorys;
    private $status;

    public function __construct(){

    }

    public function persistBookCategory($name, $description, 
    $fileGroupCode, $sessionId){
        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsBookCategory('".$name."', '".$description
        ."', '".$fileGroupCode."', '".$sessionId."', @sID);";
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
                                "Class::BookCategoryHandler->persistBookCategory Empty status returned ",
                                "Unable to register Book Category .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::BookCategoryHandler->persistBookCategory Empty result set returned",
                            "Unable to register Book Category .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::BookCategoryHandler->persistBookCategory Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Book Category .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassBookCategoryHandler->persistBookCategory Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Book Category .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassBookCategoryHandler->persistBookCategory Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Book Category .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getBookCategorys(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookCategory WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookCategory(
                        $row["vBookCategoryCode"], $row["vCategoryName"]
                        , $row["tDescription"]
                        , $row["vImageGroupID"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookCategoryHandler->getBookCategorys Empty resultset",
                    "BookCategory does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookCategoryHandler->getBookCategorys Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }

    public function getPaginatedBookCategories($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookCategory WHERE cStatus='0'
         ORDER BY dDateCreated  LIMIT "
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
                    array_push($this->bookCategorys, new BookCategory(
                        $row["vBookCategoryCode"], $row["vCategoryName"]
                        , $row["tDescription"]
                        , $row["vImageGroupID"]
                        , $row["dDateCreated"]
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
        return $this->bookCategorys;
    }

    public function getBookCategoryCount(){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM BookCategory WHERE cStatus='0';";
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
                    "BookHandler->getBookCategoryCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookCategoryCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }
    
    public function getBookCategoryById($sessionId, $bookCategoryId){
        return $this->getBookCategory($sessionId, $bookCategoryId);
    }
    
    protected function getBookCategory($sessionId, $bookCategoryId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookCategory where vBookCategoryCode='".$bookCategoryId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->bookCategory = new BookCategory(
                    $row["vBookCategoryCode"], $row["vCategoryName"]
                    , $row["tDescription"]
                    , $row["vImageGroupID"]
                    , $row["dDateCreated"]
                    , $row["dLastUpdate"], $row["cStatus"]);  
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookCategoryHandler->getBookCategory; Empty resultset for code '".$courseCode."'",
                    "The BookCategory  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{ysql_error( );
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookCategoryHandler->getBookCategory; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategory;
    }    
    
    public function getBookCategoriesByBookType($sessionId, $bookTypeId){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookCategory WHERE vBookCategoryCode 
        IN (SELECT vBookCategoryCode FROM Book WHERE vBookTypeCode='".$bookTypeId."'
        AND cStatus='0') AND cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookCategory(
                        $row["vBookCategoryCode"], $row["vCategoryName"]
                        , $row["tDescription"]
                        , $row["vImageGroupID"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookCategoryHandler->getBookCategoriesbybooktype Empty resultset",
                    "BookCategory does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookCategoryHandler->getBookCategoriesbybooktype Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }
    
    public function getBookCategoriesWithBook(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM BookCategory WHERE vBookCategoryCode 
        IN (SELECT vBookCategoryCode FROM Book WHERE cStatus='0') AND cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->bookCategorys = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->bookCategorys, new BookCategory(
                        $row["vBookCategoryCode"], $row["vCategoryName"]
                        , $row["tDescription"]
                        , $row["vImageGroupID"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookCategoryHandler->getBookCategoriesbybooktype Empty resultset",
                    "BookCategory does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookCategoryHandler->getBookCategoriesbybooktype Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->bookCategorys;
    }

    public function updateBookCategory($sessionId, $bookCategoryId
    , $field, $value){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBookCategory('".$sessionId.
        "','".$bookCategoryId."','".$field."','".$value."', @sID);";
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
                                    "Class:BookCategoryHandler->updateBookCategory; Invalid operation returned "
                                    .$row["s_id"].", for sid".$sessionId,
                                    "Unable to delete BookCategory .<br/>Please try again Later"
                                );
                                ErrorReporter::addNode($errorNode);                                 
                            }

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:BookCategoryHandler->deleteBookCategory; Empty session id returned ",
                                "Unable to delete BookCategory .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookCategoryHandler->deleteBookCategory; Empty result set returned",
                            "Unable to delete BookCategory .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookCategoryHandler->deleteBookCategory; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete BookCategory .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookCategoryHandler->deleteBookCategory; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete BookCategory .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookCategoryHandler->deleteBookCategory; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete BookCategory .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    
    
    public function uploadImage($sessionId
    , $fileName, $tempFileURL, $fileSize){//src_name, src_ext
        $errorNodeFactory = new ErrorNodeFactory();
        //instantiate file creator
        $fileCreatorName = "BOOK_CATEGORY_IMAGE";
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
                "Class:BookCategoryHandler->uploadImage; invalid file format: ".
                DatabaseManager::mysql_error( ),
                "The file format is not supported for this operation .<br/>Please try again with appropriate file"
            );
            ErrorReporter::addNode($errorNode); 
        }
    }
}
?>