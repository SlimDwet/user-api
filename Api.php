<?php

class Api {

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function get_all() {
        $this->user->all_users();
    }

}
