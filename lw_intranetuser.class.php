<?php
/**
 * This plugin shows a list of all intranet user. An logged in user can
 * see this list and edit his own profile informations. An logged admin
 * can modify the profile informations of every intranet user and can 
 * add or delete an user.
 * 
 * This plugin requires the lw_profile plugin!
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 * @subpackage lw_profile
 */

class lw_intranetuser extends lw_plugin
{
    private $in_auth;
    protected $db;
    protected $repository;
    
    /**
     * For the functionality of this plugin is it necessary to include
     * the "Autoloader" and the instances of "in_auth" and "auth"
     * objects.
     */
    public function __construct() 
    {
        parent::__construct();
        include_once(dirname(__FILE__).'/Services/Autoloader.php');
        $autoloader = new \lwIntranetuser\Services\Autoloader();
        $this->in_auth = \lw_in_auth::getInstance();
        $this->auth = \lw_registry::getInstance()->getEntry("auth");
    }
    
    /**
     * The HTML frontend output will be build for logged in user. Not logged in
     * user will be redirected to the login page. 
     * 
     * @return string
     */
    public function buildPageOutput()
    {
        $plugindata = $this->repository->plugins()->loadPluginData($this->getPluginName(),$this->params['oid']);

        if($this->request->getAlnum("cmd") == "logout"){
            $this->in_auth->logout();
            $this->pageReload(\lw_page::getInstance($plugindata["parameter"]["logout_id"])->getUrl());
        }
        
        if($this->in_auth->isLoggedIn() || $this->auth->isLoggedIn()) {
            
            $user = new \lwIntranetuser\Model\UserRightsObject();
            $user->setLoginStatusTrue();
            $user->setAdmin(false);
            
            if(intval($this->config["intranetUser"]["admin"]) === intval($this->in_auth->getUserData("intranet_id")) ) {
                $user->setAdmin(true);
            }
            if($this->auth->isLoggedIn()) {
                $user->setAdmin(true);
            }
            
            $response = \lwIntranetuser\Services\Response::getInstance();

            $controller = new \lwIntranetuser\Controller\IntranetuserController($response, $this->setUserObject(), $this->db, $plugindata, $this->getPluginName(),$user);
            $controller->execute();
            
            return $response->getOutputByKey("intranet");
        }
        
        $this->pageReload(\lw_page::getInstance($plugindata["parameter"]["logout_id"])->getUrl());
    }
    
    /**
     * Information of the logged in intranet user will be collected in this object.
     * @return \lwIntranetuser\Model\UserObject
     */
    public function setUserObject()
    {
        $inUserObject = new \lwIntranetuser\Model\UserObject();
        $inUserObject->setUserId($this->in_auth->getUserData("id"));
        $inUserObject->setUsername($this->in_auth->getUserData("name"));
        $inUserObject->setPassword($this->in_auth->getUserData("password"));
        $inUserObject->setIntranetId($this->in_auth->getUserData("intranet_id"));
        $inUserObject->setEmail($this->in_auth->getUserData("email"));
        $inUserObject->setFirstName($this->in_auth->getUserData("opt1text"));
        $inUserObject->setLastName($this->in_auth->getUserData("opt2text"));
        
        return $inUserObject;
    }
    
    /**
     * The HTML backend output will be build.
     * @return string
     */
    function getOutput() 
    {
        $backend = new \lwIntranetuser\Controller\backend($this->config,$this->request,$this->repository,$this->getPluginName(),$this->getOid(), $this->db);
        if ($this->request->getAlnum("pcmd") == "save"){
            $backend->backend_save();
        }
        return $backend->backend_view();
    }
    
    /**
     * Here will be set if the plugin-conetentbox is deleteable from a page.
     * @return boolean
     */
    function deleteEntry()
    {
        return true;
    }
}