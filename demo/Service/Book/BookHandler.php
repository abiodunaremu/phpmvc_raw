<?php
namespace Service\Book;

use Lib\Database\DatabaseManager;
use Lib\ErrorReporter\ErrorReporter;
use Lib\ErrorReporter\ErrorNodeFactory;
use Lib\FileManager\FileManager;
use Lib\FileManager\FileFormatter\FileFormatterPool;
use Lib\FileManager\FileFormatter\FileFormatterFactory;
use Lib\FileManager\FileCreator\FileCreatorHandler;
use Lib\FileUploadManager\FileUploadManager;

class BookHandler{
    
    private $book;
    private $books;
    private $status;

    public function __construct(){

    }

    public function persistBook($name, $description, 
    $bookType, $bookCategory, $price, $currency, $bookUnit
    , $weight, $length, $bredth, $height, $discount
    , $fileGroupCode, $sessionId){
        $error = "";
        $response = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnInsBook('".$name."', '".$description
        ."', '".$bookType."', '".$bookCategory."', '".$price
        ."', '".$currency."', '".$bookUnit."', '"
        .$weight."', '".$length."', '".$bredth."', '".$height
        ."', '".$discount."', '"
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
                                "Class::BookHandler->persistBook Empty status returned ",
                                "Unable to register Book .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class::BookHandler->persistBook Empty result set returned",
                            "Unable to register Book .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class::BookHandler->persistBook Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to register Book .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "ClassBookHandler->persistBook Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to register Book .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "ClassBookHandler->persistBook Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to register Book .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    public function getBooks(){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE cStatus='0';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getBooks Empty resultset",
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBooks Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }


    public function getPaginatedBooks($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE cStatus='0' LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBooks Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBooks Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }




    public function getPaginatedBooksByCategory($sessionId, $size, $page, $category){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE vBookCategoryName='".$category."' AND cStatus='0' LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBooksByCategory Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBooksByCategory Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }


    public function getPaginatedBooksByName($sessionId, $size, $page, $name){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE vName LIKE '%".$name."%' AND cStatus='0' LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBooksByName Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBooksByName Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }

    public function getPaginatedBooksByNameCategory($sessionId, $size, $page, $name, $category)
    {
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE vName LIKE '%".$name."%' 
        AND vBookCategoryName='".$category."' AND cStatus='0' LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getPaginatedBooksByNameCategory Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getPaginatedBooksByNameCategory Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }



    public function getLatestBooks($sessionId, $size, $page){

        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook WHERE cStatus='0' 
        ORDER BY dLastUpdate DESC LIMIT "
        .(($size * $page)-$size).", ".$size.";";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query);

        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $this->books = [];
                for($x = 0; $x < $num_results; $x++){
                    $row = DatabaseManager::mysql_fetch_array($result);                    
                    array_push($this->books, new Book(
                        $row["vBookCode"], $row["vName"]
                        , $row["tDescription"], $row["vBookTypeName"]
                        , $row["vBookCategoryName"], $row["mPrice"]
                        , $row["vCurrencyName"], $row["iQuantity"]
                        , $row["vBookUnitName"]
                        , $row["vFileGroupID"], $row["dWeight"]
                        , $row["dLength"], $row["dBredth"]
                        , $row["dHeight"]
                        , $row["mQtyInStock"], $row["cStockStatus"]
                        , $row["vFreeShippingStatus"], $row["dDiscount"]
                        , $row["dDateCreated"]
                        , $row["dLastUpdate"], $row["cStatus"]));  
                }
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getLatestBooks Empty resultset".$query,
                    "Book does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getLatestBooks Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->books;
    }

    public function getBookById($sessionId, $bookId){
        return $this->getBook($sessionId, $bookId);
    }
    
    protected function getBook($sessionId, $bookId){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT * FROM vwBook where vBookCode='".$bookId."';";
        $row=""; $response=""; $error="";
        $result = DatabaseManager::mysql_query($query );
        if($result)
        {
            $num_results = DatabaseManager::mysql_num_rows($result);  
            if($num_results>0){
                $row = DatabaseManager::mysql_fetch_array($result);
                $this->book = new Book(
                    $row["vBookCode"], $row["vName"]
                    , $row["tDescription"], $row["vBookTypeName"]
                    , $row["vBookCategoryName"], $row["mPrice"]
                    , $row["vCurrencyName"], $row["iQuantity"]
                    , $row["vBookUnitName"]
                    , $row["vFileGroupID"], $row["dWeight"]
                    , $row["dLength"], $row["dBredth"]
                    , $row["dHeight"]
                    , $row["mQtyInStock"], $row["cStockStatus"]
                    , $row["vFreeShippingStatus"], $row["dDiscount"]
                    , $row["dDateCreated"]
                    , $row["dLastUpdate"], $row["cStatus"]); 
            }else{
                // $response = "Error! Session expired";
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "BookHandler->getBook; Empty resultset for code '".$courseCode."'",
                    "The Book  requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBook; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }    
    
    public function getBookCount(){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM vwBook WHERE cStatus='0';";
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
                    "BookHandler->getBookCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }
    
    public function getBookByCategoryCount($category){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM vwBook WHERE vBookCategoryName='".$category."' AND cStatus='0';";
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
                    "BookHandler->getBookCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }        
    
    public function getBookByNameCount($name){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM vwBook WHERE vName LIKE '%".$name."%' AND cStatus='0';";
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
                    "BookHandler->getBookCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }    
        
    
    public function getBookByNamecategoryCount($name, $category){
        
        $errorNodeFactory = new ErrorNodeFactory();
        $query = "SELECT COUNT(*) AS dCount FROM vwBook 
        WHERE vName LIKE '%".$name."%' AND vBookCategoryName='".$category."' 
        AND cStatus='0';";
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
                    "BookHandler->getBookCount; Empty resultset for code '".$courseCode."'",
                    "The Book count requested does not exist"
                );
                ErrorReporter::addNode($errorNode);                
            }
        }else{
           
           $errorNode = $errorNodeFactory->createPersistenceError(
            "BookHandler->getBookCount; Null resultst: ".DatabaseManager::mysql_error( ),
            "Internal error occured. Please try again later."
           );
           ErrorReporter::addNode($errorNode);  
        }
        return $this->book;
    }    
    
    public function updateBook($sessionId, $bookId
    , $field, $value){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBook('".$sessionId.
        "','".$bookId."','".$field."','".$value."', @sID);";
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
                                    "Class:BookHandler->updateBook; Invalid operation returned "
                                    .$row["s_id"].", for sid".$sessionId,
                                    "Unable to update Book .<br/>Please try again Later"
                                );
                                ErrorReporter::addNode($errorNode);                                 
                            }

                        } else {
                            $errorNode = $errorNodeFactory->createPersistenceError(
                                "Class:BookHandler->updateBook; Empty session id returned ",
                                "Unable to update Book .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookHandler->updateBook; Empty result set returned",
                            "Unable to update Book .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookHandler->updateBook; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to update Book .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookHandler->updateBook; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to update Book .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookHandler->updateBook; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to update Book .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }

    
    public function deleteBook($sessionId, $bookId){
        $error = "";
        $errorNodeFactory = new ErrorNodeFactory();
        $queryHolder="SET @sID=''";
        $querySelectHolder="SELECT @sID AS 's_id'";
        $query = "CALL prnUpdBook('".$bookId.
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
                                "Class:BookHandler->deleteBook; Empty session id returned ",
                                "Unable to delete Book .<br/>Please try again Later"
                            );
                            ErrorReporter::addNode($errorNode); 
                        }
                    } else {
                        $errorNode = $errorNodeFactory->createPersistenceError(
                            "Class:BookHandler->deleteBook; Empty result set returned",
                            "Unable to delete Book .<br/>Please try again Later"
                        );
                        ErrorReporter::addNode($errorNode); 
                    }
                }else{
                    $errorNode = $errorNodeFactory->createPersistenceError(
                        "Class:BookHandler->deleteBook; Null resultset: ".
                        DatabaseManager::mysql_error( ),
                        "Unable to delete Book .<br/>Please try again Later"
                    );
                    ErrorReporter::addNode($errorNode);
                }
            }else {
                $errorNode = $errorNodeFactory->createPersistenceError(
                    "Class:BookHandler->deleteBook; Query failed: ".
                    DatabaseManager::mysql_error( ),
                    "Unable to delete Book .<br/>Please try again Later"
                );
                ErrorReporter::addNode($errorNode); 
            }
        else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookHandler->deleteBook; Holder failed: ".
                DatabaseManager::mysql_error( ),
                "Unable to delete Book .<br/>Please try again Later"
            );
            ErrorReporter::addNode($errorNode); 
        }
        return $this->status;
    }
    
    public function uploadImages($sessionId
    , $fileName, $tempFileURL, $fileSize){//src_name, src_ext
        $errorNodeFactory = new ErrorNodeFactory();

        //instantiate file creator
        $fileCreatorName = "BOOK_IMAGES";
        $fileCreatorHandler = new FileCreatorHandler();
        $fileCreator = $fileCreatorHandler
        ->getFileCreatorByName($fileCreatorName);

        $validFileFormatter = [];
        $tempFile = [];
        $sourceFile = [];

        $checkInitSourceFile ="ccccccccccc";

        if(!empty(array_filter($fileName))){
            foreach($fileName as $key=>$val){
                
                //create logic files for temp and source files
                $fileNameArray = explode(".", $fileName[$key]);
                $fileExtension = $fileNameArray[sizeof($fileNameArray)-1];
                $fileName[$key] = $fileNameArray[0];
                $filePath = "";

                $tempFilePathArray = explode("\\", $tempFileURL[$key]);
                $tempFileNameFull = $tempFilePathArray[sizeof($tempFilePathArray)-1];
                $tempFileNameFullSplit = explode(".", $tempFileNameFull);

                // $tempFileExtension = $tempFileNameFullSplit[sizeof($tempFileNameFullSplit)-1];

                $tempFileExtension = sizeof($tempFileNameFullSplit) > 1 ?
                $tempFileNameFullSplit[sizeof($tempFileNameFullSplit) - 1] : "";

                $tempFileName = $tempFileNameFullSplit[0];
                $tempFilePath = str_replace($tempFileNameFull, "", $tempFileURL[$key]);

                $fileManager = new FileManager();
                $tempFile[$key] = $fileManager
                ->createLogicFile($tempFileName, $tempFileExtension
                , $tempFilePath, $fileSize[$key]);
                $sourceFile[$key] = $fileManager
                ->createLogicFile($fileName[$key], $fileExtension
                , $filePath, $fileSize[$key]);

        //instantiate file formatters
        $fileFormatterFactory = new FileFormatterFactory();
        $JPEGImageFileFormatter = $fileFormatterFactory
        ->createJPEGImageFileFormatter(null, $fileCreator);
        $fileFormatterPool = new FileFormatterPool();
        $fileFormatterPool->addFileFormatter($JPEGImageFileFormatter);

                $fileFormatterPool->setSourceFile($sourceFile[$key]);
                $fileFormater = $fileFormatterPool->getValidFileFormatter();
                $validFileFormatter[$key] = $fileFormater;
                $checkInitSourceFile = $checkInitSourceFile.$validFileFormatter[$key]->getSourceFile()->getURL().">>>";                
            }
        }
        
        //upload if valid formatter
        if($validFileFormatter !== null && $validFileFormatter != ""
        && !empty(array_filter($validFileFormatter)) && !in_array(null, $validFileFormatter)
        && !in_array("", $validFileFormatter)){
            $fileUploadManager = new FileUploadManager();
            $multipleFileUploader = $fileUploadManager->createMultipleFileUploader();
            return $multipleFileUploader->upload($sessionId
            , $tempFile, $validFileFormatter);
        } else {
            $errorNode = $errorNodeFactory->createPersistenceError(
                "Class:BookHandler->uploadImages; invalid file format: "
                .sizeof($validFileFormatter)."".$fileSize,
                "The file format is not supported for this operation .<br/>Please try again with appropriate file"
            );
            ErrorReporter::addNode($errorNode); 
        }
    }
    
}
?>