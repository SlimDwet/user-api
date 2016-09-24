<?php

use Slimdwet\User as User;

class Api {

    private $db;
    private $user;

    public function __construct($db, User $user) {
        $this->db = $db;
        $this->user = $user;
    }

    /**
     * Show all users
     * @return [type] [description]
     */
    public function get_all() {
        $result $this->user->all_users();
        if(!$result) echo json_encode(array());
            else echo json_encode($result);
    }

    /**
     * Show the specified user datas
     * @return [type] [description]
     */
    public function get() {
        if(!isset($_GET['id'])) die("User's ID missing");
        $id = $_GET['id'];
        $result = $this->user->get_user($id);
        switch ($result) {
            case false:
            case null:
                die("This user doesn't exists");
                break;
            default:
                echo json_encode($result);
                break;
        }
    }

    /**
     * Save new user
     */
    public function add() {
        if(empty($_POST)) die("No data detected");
        
        extract($_POST);
        if(!isset($lastname)) die("User's lastname missing");
        if(!isset($firstname)) die("User's firstname missing");
        if(!isset($birthday)) die("User's birthday missing");
        if(!isset($email)) die("User's email missing");

        $result = $this->user->add_user($firstname, $lastname, $birthday, $email);
        switch ($result) {
            case null:
                echo json_encode(array('message' => 'User datas are invalid. Please check all fields format'));
                break;
            case false:
                echo json_encode(array('message' => "An error occurend while saving user's data"));
                break;
            default:
                echo json_encode(array('message' => "User saved with success"));
                break;
        }
    }

}
