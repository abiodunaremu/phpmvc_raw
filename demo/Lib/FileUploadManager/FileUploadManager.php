<?php
namespace Lib\FileUploadManager;

use Lib\FileUploadManager\SingleFileUploader;
use Lib\FileUploadManager\MultipleFileUploader;

class FileUploadManager{

    function createSingleFileUploader(){
        return new SingleFileUploader();
    }
    function createMultipleFileUploader(){
        return new MultipleFileUploader();
    }

}

?>