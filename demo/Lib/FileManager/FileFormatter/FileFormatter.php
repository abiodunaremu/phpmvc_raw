<?php
namespace Lib\FileManager\FileFormatter;

interface FileFormatter{
    
    function setSourceFile($sourceFile);
    function getSourceFile();
    function setTempPath($path); //in
    function getTempPath();
    function setFileType($fileType); //in
    function getFileType();
    function setFileCreator($fileCreator); //in
    function getFileCreator();
    function isInitialized();
    function isValid();
}
?>