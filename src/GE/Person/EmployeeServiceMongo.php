<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 15/3/2017
 * Time: 3:40 PM
 */

namespace GE\Person;

use Database\DatabaseInterface;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

class EmployeeServiceMongo implements EmployeeInterface
{
    private $db;
    private $logger;

    /**
     * EmployeeServiceMongo constructor.
     * @param DatabaseInterface $db_instance
     * @param $container
     */
    public function __construct(DatabaseInterface $db_instance, $container)
    {
        $this->db = $db_instance->getConnection();
        $this->logger = $container['logger'];
    }

    /**
     * @return array|bool
     */
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
            $this->logger->info('[MongoDB] - Returned all Employees from DB');
            return $allEmployees;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->logger->error('[MongoDB] - Failed to get all employees from empty database');
            return false;
        }
    }

    /**
     * @param $id
     * @return bool|Employee
     */
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
            $this->logger->info("[MongoDB] - Returned employee {$employee[0]->name} by ID: #{$id}");
            return $viewEmployee;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->logger->error("[MongoDB] - Failed to get user by this ID: #{$id}");
            return false;
        }
    }

    /**
     * @param $result
     * @return bool
     */
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
                $this->logger->info("[MongoDB] - Created new employee {$result['Name']} with ID: #{$newId}");
                echo "New user has been created";
                return true;
            }
        } catch (\Exception $e) {
            $this->logger->error("[MongoDB] - Failed to create new employee");
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            $query = new Query(['id' => intval($id)]);
            $this->tryGetById($query, $id);

            $delete = new BulkWrite();
            $delete->delete(['id' => intval($id)]);
            $this->db->executeBulkWrite(TABLE_USER, $delete);

            $this->logger->info("[MongoDB] - Deleted employee with ID: #{$id}");
            echo "User with ID: #{$id} has been deleted";
            return true;
        } catch (\Exception $e) {
            $this->logger->error("[MongoDB] - Failed to delete user with ID: #{$id}");
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $id
     * @param $info
     * @return bool
     */
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

            $this->logger->info("[MongoDB] - Employee with ID: #{$id} has been updated");
            echo "User with ID: $id has been updated";
            return true;
        } catch (\Exception $e) {
            $this->logger->error("[MongoDB] - Failed to update employee with ID: #{$id}");
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $query
     * @param $id
     * @return array
     * @throws \Exception
     */

    private function tryGetById($query, $id)
    {
        $check = $this->db->executeQuery(TABLE_USER, $query)->toArray();
        if (sizeof($check) < 1) {
            throw new \Exception("Cannot find user with this ID: $id");
        } else {
            return $check;
        }
    }
}