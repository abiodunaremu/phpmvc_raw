<?php
namespace Lib\FileManager\FileFormatter\Image;

use Lib\FileManager\FileFormatter\FileFormatter;

class JPEGImageFileFormtter implements FileFormtter{

    public function isValid(){
        if(strtoupper($this->getExtension()) === 'PNG'){
            return true;
        }else{
            return false;
        }
    }
}