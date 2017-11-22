<?php

namespace App\Models;

class UserMapper extends Mapper
{
    public function getUsers() {
        $sql = "SELECT id, first_name, last_name, email
            FROM users";
        $stmt = $this->db->query($sql);

        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new UserEntity($row);
        }
        return $results;
    }

    /**
     * Get one file by its ID
     *
     * @param int $file_id The ID of the file
     * @return FileEntity  The File
     */
    public function getUserById($user_id) {
        $sql = "SELECT id, first_name
            FROM users
            WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["user_id" => $user_id]);

        if( $result ) {
            return new UserEntity($stmt->fetch());
        }

    }

    public function save(UserEntity $user, $user_data = []) {
        $sql = "INSERT into users
            (first_name, last_name) VALUES
            (:first_name, :last_name)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "first_name" => $user_data['first_name'],
            "last_name" => $user_data['last_name'],
        ]);

        if( !$result ) {
            throw new Exception("could not save record");
        }
    }
}
