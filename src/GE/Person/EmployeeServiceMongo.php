<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 15/3/2017
 * Time: 3:40 PM
 */

namespace GE\Person;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;

class EmployeeServiceMongo
{
    public function __construct()
    {
        $connection = \MongoDatabase::getInstance();
        $this->db = $connection->getConnection();
    }

    public function getAll()
    {
        try {
            $query = new Query([]);
            $check = $this->db->executeQuery(TABLE_USER, $query)->toArray();
            if (sizeof($check) < 1) {
                throw new \Exception("Database empty!");
            }

            $rows = $this->db->executeQuery(TABLE_USER, $query);

            $allEmployees = array();
            foreach ($rows as $result) {
                $viewEmployee = new Employee();
                $viewEmployee->setName($result->name)->setAge($result->age)->setProject($result->project)->setDepartment($result->department)->setIsActive($result->isActive);
                array_push($allEmployees, $viewEmployee);
            }
            return $allEmployees;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getOne($id)
    {
        try {
            $query = new Query(['id' => intval($id)]);
            $this->tryGetById($query, $id);

            $rows = $this->db->executeQuery(TABLE_USER, $query);
            $viewEmployee = new Employee();
            foreach ($rows as $result) {
                $viewEmployee->setName($result->name)->setAge($result->age)->setProject($result->project)->setDepartment($result->department)->setIsActive($result->isActive);
            }
            return $viewEmployee;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function create($result)
    {
        //Auto-increment ID
        $query = new Query([],['sort' => ['id' => -1], 'limit' => 1]);
        $rows = $this->db->executeQuery(TABLE_USER, $query);
        $lastId = 0;
        foreach ($rows as $res) {
            $lastId = $res->id;
        }
        $newId = $lastId+1;

        $write = new BulkWrite();
        $write->insert(['id' => $newId, 'name' => $result['Name'], 'age' => $result['Age'], 'project' => $result['Project'], 'department' => $result['Department'], 'isActive' => $result['isActive']]);
        $this->db->executeBulkWrite(TABLE_USER, $write);
    }

    public function delete($id){
        try {
            $query = new Query(['id' => intval($id)]);
            $this->tryGetById($query, $id);

            $delete = new BulkWrite();
            $delete->delete(['id' => intval($id)]);
            $this->db->executeBulkWrite(TABLE_USER, $delete);

            echo "User with ID: $id has been deleted";
        }catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * @param $query
     * @param $id
     * @throws \Exception
     */
    public function tryGetById($query, $id)
    {
        $check = $this->db->executeQuery(TABLE_USER, $query)->toArray();
        if (sizeof($check) < 1) {
            throw new \Exception("Cannot find user with this ID: $id");
        }
    }
}