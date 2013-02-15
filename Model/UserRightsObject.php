<?php
/**
 * This object contains if an user gains admin rights or not.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */
namespace lwIntranetuser\Model;

class UserRightsObject 
{
    public function __construct() 
    {
        $this->array = array(
                        "login_status"  => "",
                        "admin"         => "",
                    );
    }
    
    public function setLoginStatusTrue()
    {
        $this->array["login_status"] = true;
    }
    
    public function setLoginStatusFalse()
    {
        $this->array["login_status"] = false;
    }
    
    public function setAdmin($bool)
    {
        $this->array["admin"] = $bool;
    }
    
    public function getData($key = false)
    {
        if($key) {
            return $this->array[$key];
        } else {
            return $this->array;
        }
    }
}