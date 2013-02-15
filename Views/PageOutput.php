<?php
/**
 * This class prepares the html frontend output. The template
 * will be set with the required informations.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */

namespace lwIntranetuser\Views;

class PageOutput 
{
    private $view;
    private $config;
    private $request;
    
    /**
     * The constructor requires the config,request and db object and
     * it's necessary to get a new instance of lw_view object with the
     * ProfileForm.tpl.phtml.
     * 
     * @param object $config
     * @param object $request
     * @param object $db
     */
    public function __construct($config, $request, $db) 
    {
        $this->db = $db;
        $this->request = $request;
        $this->config = $config;
        $this->view = new \lw_view(dirname(__FILE__).'/Templates/IntranetuserTable.tpl.phtml');
    }
    
    /**
     * The html template will be filled with the required information
     * and the constructed html will be returned.
     * 
     * @param array $userArray
     * @param int $logedInUserId
     * @param bool $admin
     * @param array $plugindata
     * @return string
     */
    public function render($userArray, $logedInUserId, $admin, $plugindata)
    {
        foreach($userArray as $key => $user) {
            if(!empty($user["last_login"])) $userArray[$key]["last_login"] = $this->intDateToNiceDate($user["last_login"]);
            if(!empty($user["lw_first_date"]))  $userArray[$key]["lw_first_date"] = $this->intDateToNiceDate($user["lw_first_date"]);
        }

        if($this->request->getInt("result")) {
            $this->view->jqUI       = $this->config["url"]["media"] . "jquery/jquery.min.js";
            $this->view->response   = $this->request->getInt("result");
        } else {
             $this->view->response  = 0;
        }
        $this->view->show           = $plugindata["parameter"]["show"];
        $this->view->urlPluginCSS   = $this->config["url"]["resource"] . "plugins/lw_intranetuser/Assets/Css/LwIntranetuser.css";
        $this->view->urlCSS         = $this->config["url"]["media"] . "/bootstrap/css/bootstrap.min.css";
        $this->view->urlJS          = $this->config["url"]["media"] . "/bootstrap/js/bootstrap.min.js";
        $this->view->url            = \lw_page::getInstance()->getUrl();
        $this->view->userId         = $logedInUserId;
        $this->view->admin          = $admin;
        $this->view->userArray      = $userArray;
     
        if(isset($plugindata["parameter"]["selectedLang"])) {
            $qH = new \LwI18n\Model\queryHandler($this->db);
            $result = $qH->getAllEntriesForCategoryAndLang("lw_intranetuser", $plugindata["parameter"]["selectedLang"]);
            $temp = array();
            foreach($result as $value) {
                $temp[$value["lw_key"]] = $value["text"];
            }
            $this->view->lang       = $temp;
        } else {
            $this->view->lang       = $this->fillPlaceHolderWithSelectedLanguage("de");
        }
        
        return $this->view->render();
    }
    
    /**
     * The saved int date from the database will be convertet to a normal date format.
     * @param string $temp
     * @return string
     */
    public function intDateToNiceDate($temp)
    {
        $year = substr($temp, 0, 4);
        $month = substr($temp, 4, 2);
        $day = substr($temp, 6, 2);
        $h = substr($temp, 8, 2);
        $m = substr($temp, 10, 2);
        $s = substr($temp, 12, 2);
        
        $a = $day . "." . $month . "." . $year . " " . $h . ":" . $m . ":" . $s;
        return $a;
    }
    
    /**
     * All placholders will be set with the output text. 
     * The backend.php of this package will collect
     * this informations as base information.
     * 
     * @param string $lang
     * @return array
     */
    public function fillPlaceHolderWithSelectedLanguage($lang)
    {
        $languageDE = array(
            "add_user"  => "Nutzer hinzuf&uuml;gen",
            "logout"    => "Logout",
            "id"        => "Id",
            "username"  => "Benutzername",
            "email"     => "Email",
            "firstname" => "Vorname",
            "lastname"  => "Nachname",
            "last_login"=> "Letzter Besuch",
            "first_date"=> "Registriert am",
            "delete"    => "Diesen Benutzer wirklich l&ouml;schen ?"
        );
        
        $languageEN = array(
            "add_user"  => "Add user",
            "logout"    => "Logout",
            "id"        => "Id",
            "username"  => "Username",
            "email"     => "Email",
            "firstname" => "Firstname",
            "lastname"  => "Lastname",
            "last_login"=> "Last visit",
            "first_date"=> "registered at",
            "delete"    => "Realy delete this user ?"
        );
        
        if($lang == "de") {
            return $languageDE;
        } else {
            return $languageEN;
        }
    }
}