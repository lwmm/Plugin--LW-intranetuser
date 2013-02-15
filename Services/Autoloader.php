<?php
/**
 * This class is an autoloader which load the required file,
 * depending on namespace and classname.
 * The combination of namespace and classname have to be the 
 * same as in your project directory.
 * 
 * @author Andreas Eckhoff <andreas.eckhoff@logic-works.de>
 * @package lw_intranetuser
 */

namespace lwIntranetuser\Services;

class Autoloader
{
    /**
     * A new autoloader will be registered in the system.
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }
    
    /**
     * Depending on the class name will be the php file loaded.
     * @param string $className
     */
    private function loader($className) 
    {
        $array = explode("\\", $className);
        
        switch ($array[0]) {
            case "lwProfile":
                $path = dirname(__FILE__).'/../../lw_profile';
                $filename = str_replace('lwProfile', $path, $className);
                break;

            case "LwI18n":
                $path = dirname(__FILE__).'/../../lw_i18n';
                $filename = str_replace('LwI18n', $path, $className);
                break;
            
            default:
                $path = dirname(__FILE__).'/..';
                $filename = str_replace('lwIntranetuser', $path, $className);
                break;
        }
        
        $filename = str_replace('\\', '/', $filename).'.php';
        if (is_file($filename)) {
            //echo $filename." exists<br>";
            include_once($filename);
        }
    }
}