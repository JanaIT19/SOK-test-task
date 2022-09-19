<?php

namespace Helpers;

include('Config.php');

use Config;
use mysqli;

class SqlHelper
{
    /**
     * @var mysqli
     */
    private $connection;

    public function __construct()
    {
        $config = new Config();
        $this->createConnection();
    }

    /**
     * @return void
     */
    public function createConnection()
    {
        $connection = new mysqli(
            Config::SQL_HOST,
            Config::SQL_USERNAME,
            Config::SQL_PASSWORD,
            Config::SQL_SCHEMA
        );

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $this->connection = $connection;
    }

    /**
     * @param string $username
     * @return bool|null
     */
    public function getUserByUsername(string $username)
    {
        $query = $this->connection->prepare(
            sprintf('SELECT * FROM %s WHERE username=?', Config::SQL_TABLE_USERS)
        );

        $query->bind_param('s',$username);
        $query->execute();

        $result = $query->get_result();

        return $result->fetch_assoc();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getCategoryById(int $id)
    {
        $query = $this->connection->prepare(
            sprintf('SELECT * FROM %s WHERE id=?', Config::SQL_TABLE_CATEGORIES)
        );

        $query->bind_param('i',$id);
        $query->execute();

        $result = $query->get_result();

        return $result->fetch_assoc();
    }

    /**
     * @param string $title
     * @param string $description
     * @param int $parent_id
     * @return bool
     */
    public function insertCategory(string $title, string $description, int $parent_id=0)
    {
        $query = $this->connection->prepare(
            sprintf('INSERT INTO %s (parent_id, title, description) VALUES (?, ?, ?)', Config::SQL_TABLE_CATEGORIES)
        );

        $query->bind_param('iss', $parent_id, $title, $description);
        $query->execute();

        $id = $this->connection->insert_id;

        return $id;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $description
     * @return bool
     */
    public function updateCategory(int $id, string $title, string $description)
    {
        $query = $this->connection->prepare(
            sprintf('UPDATE %s SET title=?, description=? WHERE id=?', Config::SQL_TABLE_CATEGORIES)
        );

        $query->bind_param('ssi', $title, $description, $id);
        $query->execute();

        $result = $query->get_result();

        return $result;
    }


    /**
     * @param int $id
     * @return void
     */
    public function deleteCategoryById(int $id)
    {
        $query = $this->connection->prepare(
            sprintf('DELETE FROM %s WHERE id=?', Config::SQL_TABLE_CATEGORIES)
        );

        $query->bind_param('i',$id);
        $query->execute();
    }

    /**
     * @return array|null
     */
    public function getAllCategories()
    {
        $query = $this->connection->prepare(
            sprintf('SELECT * FROM %s', Config::SQL_TABLE_CATEGORIES)
        );

        $query->execute();

        $result = $query->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}