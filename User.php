<?php

class User {

    public function all_users() {
        global $db;

        try {
            $result = $db->GetAll("SELECT * FROM ".USER_TABLE);
            if(empty($result)) return false;

            return $result;
        } catch (Exception $e) {
            die("Error while accessing users data");
        }

    }

}
