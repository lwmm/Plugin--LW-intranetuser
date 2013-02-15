<?php
/**
 * This class is factory for user objects.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */
namespace lwIntranetuser\Model;

class UserObjectFactory 
{
    private $newUObj;   
    
    /**
     * Create a new instance of \lwIntranetuser\Model\UserObject()
     */
    public function __construct() 
    {
        $this->newUObj = new \lwIntranetuser\Model\UserObject();
    }
    
    /**
     * A new user object will be set.
     * @param array $dataArray
     * @return object \lwIntranetuser\Model\UserObject()
     */
    public function buildUserObject($dataArray)
    {
            $this->newUObj->setUserId($dataArray["id"]);
            $this->newUObj->setIntranetId($dataArray["intranet_id"]);
            $this->newUObj->setPassword($dataArray["password"]);
            $this->newUObj->setUsername($dataArray["name"]);
            $this->newUObj->setFirstName($dataArray["opt1text"]);
            $this->newUObj->setLastName($dataArray["opt2text"]);
            $this->newUObj->setEmail($dataArray["email"]);
        
        return $this->newUObj;
    }
}