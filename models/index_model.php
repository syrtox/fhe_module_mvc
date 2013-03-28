<?php

/**
 * primary model of the index
 */
class Index_Model extends DPLoad {

    public function __construct() {
        parent::__construct();
    }
//----------------------------------------------------------------------------------------------------------------------
    /**
     *
     * @return Array.
     */
    public function getEntries() {

        return $this->db->select("SELECT benutzernam FROM account");
    }
//----------------------------------------------------------------------------------------------------------------------
    /**
     * return a searched entry
     *
     * @param int $id Id of the searched entry
     * @return Array 
     */
    public function getEntry($id) {
        if (array_key_exists($id, $this->entries)) {
            return self::$entries[$id];
        } else {
            return null;
        }
    }

}

?>