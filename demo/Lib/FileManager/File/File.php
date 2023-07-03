<?php
namespace Lib\FileManager\File;

interface File{
    function setName($name);
    function getName();
    function setPath($path);
    function getPath();
    function setSize($size);
    function getSize();
    function setExtension($extension);
    function getExtension();
    functIon getURL();
}
?>