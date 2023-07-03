<?php
namespace Lib\FileUploadManager;

interface FileUploader{
    function upload($sessionId
    , $sourceLogicFile, $fileFormatter);
}

?>