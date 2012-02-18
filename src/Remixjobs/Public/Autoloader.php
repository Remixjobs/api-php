<?php

class Remixjobs_Public_Autoloader
{
    static public function register()
    {
        if (!class_exists('Zend_Loader')) {
            self::loadZend();
        }

        set_include_path(implode(PATH_SEPARATOR, array(
            get_include_path(),
            dirname(__FILE__) . '/../../'
        )));

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Remixjobs_Public_');
    }

    static public function loadZend()
    {
        set_include_path(implode(PATH_SEPARATOR, array(
            get_include_path(),
            dirname(__FILE__) . '/../../../vendor/ZendFramework/library'
        )));

        require_once 'Zend/Loader/Autoloader.php';

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Zend_');
    }
}

