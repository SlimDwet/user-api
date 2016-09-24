<?php

// Include AdoDB
require_once dirname(dirname(__FILE__)).'/adodb5/adodb.inc.php';
require_once dirname(dirname(__FILE__)).'/adodb5/adodb-exceptions.inc.php';

use Slimdwet\User as User;

class UserTest extends PHPUnit_Framework_TestCase {

    private $db;

    public function __construct() {
        // Database connection
        try {
            $this->db = ADONewConnection('mysql');
            $this->db->Connect('localhost', 'root', 'root', 'user-api');
            $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        } catch(Exception $e) {
            die("\r\nError while database connection\r\n");
        }
    }

    public function testGetUser() {
        $user = new User($this->db);
        $this->assertEquals(null, $user->get_user("babar"));
        $this->assertEquals(null, $user->get_user('1" OR 1=1'));
        $this->assertEquals(null, $user->get_user("1' OR 1=1"));
        // $this->assertEquals(true, $user->get_user("1"));
        // $this->assertNotEmpty(true, $user->get_user(1));
        $this->assertEquals(false, $user->get_user('1 UNION SELECT * FROM `users` WHERE 1'));
        $this->assertEquals(false, $user->get_user('1 UNION SELECT * FROM users WHERE 1'));
    }

    public function testAddUser() {
        $user = new User($this->db);
        $this->assertEquals(false, $user->add_user());
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '10-03-1990', false));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '10-03-1990', null));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '10-03-1990', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '1990-06-29', 'admin@mailserver1'));
        $this->assertEquals(true, $user->add_user('Hector', 'DUPONT', '1990-06-29', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '1990-06-29', 123));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', '1990-04-16 13:59:05', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user('Hector', 'DUPONT', 'NOW()', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user('Hector', '<script type="text/javascript">alert("faille")</script>', 'NOW()', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user(13, '<script type="text/javascript">alert("faille")</script>', 'NOW()', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user(array('bibi', 'balou13', 49), '<script type="text/javascript">alert("faille")</script>', 'NOW()', 'hector.dupont@gmail.com'));
        $this->assertEquals(false, $user->add_user(array('bibi', 'balou13', 49), 'DUPONT', '1990-06-29', 'hector.dupont@gmail.com'));
        $this->assertEquals(null, $user->add_user('Basile', 'Kévin', '1989-05-14', '"); SELECT * FROM users #"'));
        $this->assertEquals(null, $user->add_user('Basile', 'Kévin', '1989-05-14', "'); TRUNCATE TABLE users; #'"));
    }

    public function testUpdateUser(){
        $user = new User($this->db);
        $this->assertEquals(false, $user->update_user());
        $this->assertEquals(false, $user->update_user('bibi', array()));
        $this->assertEquals(false, $user->update_user(1, array('lastname' => 'Duval', 'age' => 30)));
        $this->assertEquals(true, $user->update_user(1, array('email' => 'hector.duval@gmail.com')));
        $this->assertEquals(null, $user->update_user(1, array('email' => 'hector.duval@yahoo')));
        $this->assertEquals(true, $user->update_user(1, array('birthday' => '1984-06-25')));
        $this->assertEquals(null, $user->update_user(1, array('birthday' => 62)));
        $this->assertEquals(true, $user->update_user(1, array('firstname' => 'Paul"; TRUNCATE TABLE users')));
    }

    public function testDeleteUser() {
        $user = new User($this->db);
        $this->assertEquals(false, $user->delete_user());
        $this->assertEquals(null, $user->delete_user('1"; TRUNCATE TABLE users;'));
    }

}
