<?php
namespace Lib\ErrorReporter;

interface ErrorIterator{
    // static function setHeadNode();
    static function getHeadNode();
    // static function setTailNode();
    static function getTailNode();
    static function getNodeCount();
}
?>