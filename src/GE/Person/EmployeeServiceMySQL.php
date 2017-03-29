<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 3:30 PM
 */
namespace GE\Person;

use Database\DatabaseInterface;

class EmployeeServiceMySQL implements EmployeeInterface
{
    private $db;
    private $logger;

    /**
     * EmployeeServiceMySQL constructor.
     * @param DatabaseInterface $db_instance
     * @param $container
     */
    public function __construct(DatabaseInterface $db_instance, $container)
    {
        $this->db = $db_instance->getConnection();
        $this->logger = $container['logger'];
    }

    /**
     * @param $id
     * @return bool|Employee
     */
    public function getOne($id)
    {
        try {
            $result = $this->tryGetById($id);

            $viewEmployee = new Employee();
            $viewEmployee
                ->setName($result['Name'])
                ->setAge($result['Age'])
                ->setProject($result['Project'])
                ->setDepartment($result['Department'])
                ->setIsActive($result['isActive']);

            $this->logger->info("[MySQL] - Returned employee {$result['Name']} by ID: #{$id}");
            return $viewEmployee;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->logger->error("[MySQL] - Failed to get user by this ID: #{$id}");
            return false;
        }
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $allEmployees = array();
        $stmt = $this->db->prepare("SELECT * from employees");
        $stmt->execute();

        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $result) {
            $viewEmployee = new Employee();
            $viewEmployee
                ->setName($result['Name'])
                ->setAge($result['Age'])
                ->setProject($result['Project'])
                ->setDepartment($result['Department'])
                ->setIsActive($result['isActive']);
            array_push($allEmployees, $viewEmployee);
        }
        $this->logger->info('[MySQL] - Returned all Employees from DB');
        return $allEmployees;
    }

    /**
     * @param $result
     * @return bool
     */
    public function create($result)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO employees (Name, Age, Project, Department, isActive) VALUES (:fname, :age, :project, :department, :isActive)");

            $stmt->execute(array(
                "fname" => $result['Name'] ? $result['Name'] : null,
                "age" => $result['Age'] ? $result['Age'] : null,
                "project" => $result['Project'],
                "department" => $result['Department'],
                "isActive" => $result['isActive']
            ));
            echo "Employee created";
            $this->logger->info("[MySQL] - Created new employee {$result['Name']}");
            return true;
        } catch (\PDOException $e) {
            $this->logger->error("[MySQL] - Failed to create new employee");
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $id
     * @param $result
     * @return bool
     */
    public function update($id, $result)
    {
        try {
            $existingEmployee = $this->tryGetById($id);
            $stmt = $this->db->prepare('UPDATE employees SET Name = :name, Age = :age, Project = :project, Department = :department, isActive = :isActive WHERE id = :id');

            $stmt->execute(array(
                ':id' => $id,
                ':name' => $result['Name'] ? $result['Name'] : $existingEmployee['Name'],
                ":age" => $result['Age'] ? $result['Age'] : $existingEmployee['Age'],
                ":project" => $result['Project'] ? $result['Project'] : $existingEmployee['Project'],
                ":department" => $result['Department'] ? $result['Department'] : $existingEmployee['Department'],
                ":isActive" => $result['isActive'] ? $result['isActive'] : $existingEmployee['isActive']
            ));
            echo "Employee updated";
            $this->logger->info("[MySQL] - Employee with ID: #{$id} has been updated");
            return true;
        } catch (\PDOException $e) {
            $this->logger->error("[MySQL] - Failed to update employee with ID: #{$id}");
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
            $this->tryGetById($id);
            $stmt = $this->db->prepare('DELETE FROM employees WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->logger->info("[MySQL] - Deleted employee with ID: #{$id}");
            echo "Employee deleted!";
            return true;
        } catch (\PDOException $e) {
            $this->logger->error("[MySQL] - Failed to delete user with ID: #{$id}");
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \PDOException
     */
    private function tryGetById($id)
    {
        $stmt = $this->db->prepare("SELECT * from employees WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            throw new \PDOException("User with ID: " . $id . " not found");
        }
    }
}