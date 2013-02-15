<?php
/**
 * QueryHandler collects information from the database.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */
namespace lwIntranetuser\Model;

class QueryHandler 
{
    private $db;
    
    /**
     * An instance of lw_db is required.
     * @param object $db
     */
    public function __construct($db) 
    {
        $this->db = $db;
    }
    
    /**
     * Get user information for user id.
     * @param type $id
     * @return array
     */
    public function getUserById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_in_user WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect1();
    }
    
    /**
     * Get all user and their informations.
     * @return array
     */
    public function getAllUser()
    {
        $this->db->setStatement("SELECT * FROM t:lw_in_user ORDER BY name ASC ");
        return $this->db->pselect();
    }
}