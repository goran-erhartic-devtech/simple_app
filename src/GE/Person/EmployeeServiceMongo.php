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
use MongoDB\Driver\WriteConcern;

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
                $viewEmployee
                    ->setName($result->name)
                    ->setAge($result->age)
                    ->setProject($result->project)
                    ->setDepartment($result->department)
                    ->setIsActive($result->isActive);
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
            $employee = $this->tryGetById($query, $id);

            $viewEmployee = new Employee();
            $viewEmployee
                ->setName($employee[0]->name)
                ->setAge($employee[0]->age)
                ->setProject($employee[0]->project)
                ->setDepartment($employee[0]->department)
                ->setIsActive($employee[0]->isActive);
            return $viewEmployee;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function create($result)
    {
        try {
            if ($result['Name'] == '' || $result['Age'] == '') {
                throw new \Exception("Name and/or Age cannot be blank");
            } else {
                //Auto-increment ID
                $query = new Query([], ['sort' => ['id' => -1], 'limit' => 1]);
                $rows = $this->db->executeQuery(TABLE_USER, $query);
                $lastId = 0;
                foreach ($rows as $res) {
                    $lastId = $res->id;
                }
                $newId = $lastId + 1;

                $write = new BulkWrite();
                $write->insert([
                    'id' => $newId,
                    'name' => $result['Name'],
                    'age' => $result['Age'],
                    'project' => $result['Project'],
                    'department' => $result['Department'],
                    'isActive' => $result['isActive']]);
                $this->db->executeBulkWrite(TABLE_USER, $write);
                echo "New user has been created";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $query = new Query(['id' => intval($id)]);
            $this->tryGetById($query, $id);

            $delete = new BulkWrite();
            $delete->delete(['id' => intval($id)]);
            $this->db->executeBulkWrite(TABLE_USER, $delete);

            echo "User with ID: $id has been deleted";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update($id, $info)
    {
        try {
            $query = new Query(['id' => intval($id)]);
            $employee = $this->tryGetById($query, $id);

            $viewEmployee = new Employee();
            $viewEmployee
                ->setName($employee[0]->name)
                ->setAge($employee[0]->age)
                ->setProject($employee[0]->project)
                ->setDepartment($employee[0]->department)
                ->setIsActive($employee[0]->isActive);

            $update = new BulkWrite;
            $update->update(['id' => intval($id)], ['$set' => [
                'name' => $info['Name'] ? $info['Name'] : $viewEmployee->getName(),
                'age' => $info['Age'] ? $info['Age'] : $viewEmployee->getAge(),
                'project' => $info['Project'] ? $info['Project'] : $viewEmployee->getProject(),
                'department' => $info['Department'] ? $info['Department'] : $viewEmployee->getDepartment(),
                'isActive' => $info['isActive'] ? $info['isActive'] : $viewEmployee->getIsActive()
            ]]);
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
            $this->db->executeBulkWrite(TABLE_USER, $update, $writeConcern);
            echo "User with ID: $id has been updated";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $query
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function tryGetById($query, $id)
    {
        $check = $this->db->executeQuery(TABLE_USER, $query)->toArray();
        if (sizeof($check) < 1) {
            throw new \Exception("Cannot find user with this ID: $id");
        } else {
            return $check;
        }
    }
}