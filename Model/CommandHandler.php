<?php
/**
 * CommandHanlder.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_intranetuser
 */
namespace lwIntranetuser\Model;

class CommandHandler 
{
    private $db;
    
    public function __construct($db) 
    {
        $this->db = $db;
    }
    
    public function deleteUserById($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_in_user WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }
}