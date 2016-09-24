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
     */
    public function get_all() {
        $result = $this->user->all_users();
        if(!$result) echo json_encode(array());
            else echo json_encode($result);
    }

    /**
     * Show the specified user data
     */
    public function get() {
        if(!isset($_GET['id'])) die("User's ID missing");
        $result = $this->user->get_user($_GET['id']);
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
     * Create an user
     */
    public function add() {
        if(empty($_POST)) die("No post data detected");

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

    /**
     * Update the specified user data
     */
    public function update() {
        if(!isset($_GET['id'])) die("User's ID missing");
        if(empty($_POST)) die("No post data detected");

        $result = $this->user->update_user($_GET['id'], $_POST);
        switch ($result) {
            case null:
                echo json_encode(array('message' => 'User datas are invalid. Please check all fields format'));
                break;
            case false:
                echo json_encode(array('message' => "An error occurend while updating user's data"));
                break;
            default:
                echo json_encode(array('message' => "User updated with success"));
                break;
        }
    }

    /**
     * Delete the specidied user
     */
    public function delete() {
        if(!isset($_GET['id'])) die("User's ID missing");
        $result = $this->user->delete_user($_GET['id']);
        switch ($result) {
            case null:
                echo json_encode(array('message' => 'Invalid user ID'));
                break;
            case false:
                echo json_encode(array('message' => "An error occurend while deleting user"));
                break;
            default:
                echo json_encode(array('message' => "User deleted with success"));
                break;
        }
    }

}
