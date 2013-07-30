<?php
/**
 * The intranetuser controller collects necessary informations and pass them
 * to the specific classes in case of which job has to be done.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */
namespace lwIntranetuser\Controller;

class IntranetuserController extends \lw_object
{
    private $response;
    private $config;
    private $request;
    private $userObject;
    protected $db;
    private $commandHandler;
    private $queryHandler;
    private $validate;
    private $view;
    private $profileController;
    private $plugindata;
    private $pluginname;
    protected $user;


    /**
     * An instance of the registry is required and in_auth and auth instances.
     * Also \lwIntranetuser\Model\CommandHandler and \lwIntranetuser\Model\queryHandler
     * required.
     * @param object $response
     * @param object $userObject
     * @param object $db
     * @param array $plugindata
     * @param string $pluginname
     * @param object $user (user rights)
     */
    public function __construct($response, $userObject, $db, $plugindata, $pluginname, $user) 
    {
        $reg = \lw_registry::getInstance();
        $this->plugindata = $plugindata;
        $this->pluginname = $pluginname;
        $this->config = $reg->getEntry("config");
        $this->request = $reg->getEntry("request");
        $this->response = $response;
        $this->userObject = $userObject;
        $this->db = $db;
        $this->user = $user;
        $this->commandHandler = new \lwIntranetuser\Model\CommandHandler($db);
        $this->queryHandler = new \lwIntranetuser\Model\QueryHandler($db);
        $this->auth = \lw_registry::getInstance()->getEntry("auth");
    }
    
    /**
     *  Collects the specific output and data for the requested job.
     */
    public function execute()
    {     
        switch ($this->request->getAlnum("cmd")) {
            case "add":
                $newObj = new \lwIntranetuser\Model\UserObject();
                $this->profileController = new \lwProfile\Controller\ProfileController($this->response, $newObj, $this->db, $this->plugindata);
                $this->profileController->execute(false, $this->user->getData("admin"), \lw_page::getInstance()->getUrl());
                break;

            case "edit":
                $buildUserObj = new \lwIntranetuser\Model\UserObjectFactory();
                $newObj = $buildUserObj->buildUserObject($this->queryHandler->getUserById($this->request->getInt("id")));
                
                $this->profileController = new \lwProfile\Controller\ProfileController($this->response, $newObj, $this->db, $this->plugindata);
                $this->profileController->execute(true, $this->user->getData("admin"), \lw_page::getInstance()->getUrl());
                break;
            
            case "delete":
                if($this->commandHandler->deleteUserById($this->request->getInt("id"))) {
                    $this->pageReload(\lw_page::getInstance()->getUrl());
                }
                break;
            
            default:
                $allUser = $this->queryHandler->getAllUser();
                $view = new \lwIntranetuser\Views\PageOutput($this->config, $this->request, $this->db);
                $this->response->setOutputByKey("intranet",$view->render($allUser, $this->userObject->getUserData("id"), $this->user->getData("admin"),$this->plugindata));
                break;
        }
    }
}