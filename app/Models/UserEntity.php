<?php

namespace App\Models;

class UserEntity
{
    protected $id;
    protected $name;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }

        $this->name = $data['first_name'];
        $this->lastname = $data['last_name'];
        $this->email = $data['email'];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }


}
