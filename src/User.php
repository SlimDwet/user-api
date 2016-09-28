<?php

namespace Slimdwet;

class User {

    private $table;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->table = 'users';
    }

    /**
     * Get all users
     * @return [array] [Users list]
     */
    public function all_users() {
        try {
            $result = $this->db->GetAll("SELECT * FROM ".$this->table);
            if(empty($result)) return false;

            return $result;
        } catch (Exception $e) {
            die("Error while accessing users data");
        }
    }

    /**
     * Get an user by id
     * @param  [int] $id    [User ID]
     * @return [array]      [User datas]
     */
    public function get_user($id) {
        try {
            if(is_null($this->validate_id($id))) return null;
            $sql = "SELECT * FROM ".$this->table." WHERE id = ?";
            $sql_prepare = $this->db->Prepare($sql);
            $result = $this->db->Execute($sql_prepare, array($id));
            if(!$result->RecordCount()) return false;
            return $result->fields;
        } catch (Exception $e) {
            die("Error while getting the specified user");
        }
    }

    /**
     * Create an user
     * @param [string] $firstname [User's firstname]
     * @param [string] $lastname  [User's lastname]
     * @param [string] $birthday  [User's birthday]
     * @param [string] $email     [User's email]
     */
    public function add_user($firstname = null, $lastname = null, $birthday = null, $email = null) {
        try {
            /** Validation **/
            if(
                !isset($firstname) ||
                !isset($lastname) ||
                !isset($birthday) ||
                !isset($email)
            ) return false;

            if(!is_string($firstname) || !is_string($lastname)) return null;

            if(is_null($this->validate_date($birthday))) return null;
            if(is_null($this->validate_email($email))) return null;

            $sql = "INSERT INTO ".$this->table." (firstname, lastname, birthday, email, created, modified) VALUES (?, ?, ?, ?, ?, ?)";
            $sql_prepare = $this->db->Prepare($sql);
            $current_date = date('Y-m-d H:i:s');
            $result = $this->db->Execute($sql_prepare, array($firstname, $lastname, $birthday, $email, $current_date, $current_date));
            if(!$result) return false;

            return true;
        } catch (Exception $e) {
            die("Error while adding the user");
        }
    }

    /**
     * Update the specified user's data
     * @param  [string|int] $id    [User's ID]
     * @param  [array] $datas [User new data]
     * @return [null|boolean]        [description]
     */
    public function update_user($id = null, $datas = array()) {
        try {
            if(empty($datas)) return false;

            // Check if valid fields
            foreach ($datas as $field_name => $field_value) {
                if(!in_array($field_name, array('firstname', 'lastname', 'email', 'birthday'))) {
                    unset($datas[$field_name]);
                }
            }

            // Check if the specified user exists
            if(is_null($this->validate_id($id))) return null;
            $sql = "SELECT * FROM ".$this->table." WHERE id = ?";
            $sql_prepare = $this->db->Prepare($sql);
            $result_get_user = $this->db->Execute($sql_prepare, array($id));
            if(!$result_get_user->RecordCount()) return false;

            if(isset($datas['firstname'])) {
                if(!is_string($datas['firstname'])) return null;
            }
            if(isset($datas['lastname'])) {
                if(!is_string($datas['lastname'])) return null;
            }
            if(isset($datas['email'])) {
                if(is_null($this->validate_email($datas['email']))) return null;
            }
            if(isset($datas['birthday'])) {
                if(is_null($this->validate_date($datas['birthday']))) return null;
            }

            // Add modified date
            $datas['modified'] = date('Y-m-d H:i:s');
            $update_sql = $this->db->GetUpdateSQL($result_get_user, $datas, 'UPDATE');
            $update_result = $this->db->Execute($update_sql);
            if(!$update_result) return false;

            return true;
        } catch (Exception $e) {
            die("Error while updating the specified user");
        }

    }

    /**
     * Delete the specidied user
     * @param  [string|int] $id [User ID]
     * @return [null|boolean]     [description]
     */
    public function delete_user($id = null) {
        if(is_null($this->validate_id($id))) return null;
        try {
            $sql = "SELECT * FROM ".$this->table." WHERE id = ?";
            $sql_prepare = $this->db->Prepare($sql);
            $result_get_user = $this->db->Execute($sql_prepare, array($id));
            if(!$result_get_user->RecordCount()) return false;

            $sql = "DELETE FROM ".$this->table." WHERE id = ?";
            $sql_prepare = $this->db->Prepare($sql);
            $result_del_user = $this->db->Execute($sql_prepare, array($id));
            if(!$result_del_user) return false;

            return true;
        } catch (Exception $e) {
            die("Error while deleting the specified user");
        }

    }

    /**
     * Validate a date
     * @param  [string] $date [Date to validate]
     * @return [null|boolean]       [description]
     */
    private function validate_date($date) {
        if(preg_match("#^[0-9]{4}-[01][0-9]-[0-3][0-9]$#", $date) === 1) {
            $date_arr = explode('-', $date);
            if(!checkdate($date_arr[1], $date_arr[2], $date_arr[0])) return null;
        } else return null;

        return true;
    }

    /**
     * Validate an email
     * @param  [string] $email [Email to validate]
     * @return [null|boolean]        [description]
     */
    private function validate_email($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return null;

        return true;
    }

    /**
     * Validate an ID
     * @param  [string[int]] $id [User ID]
     * @return [null|boolean]        [description]
     */
    private function validate_id($id) {
        if(preg_match("#^[0-9]+$#", $id) === 0) return null;

        return true;
    }

}
