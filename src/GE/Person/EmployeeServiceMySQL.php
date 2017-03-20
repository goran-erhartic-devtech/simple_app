<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 3:30 PM
 */
namespace GE\Person;

class EmployeeServiceMySQL implements EmployeeInterface
{
    private $db;
    private $logger;

    public function __construct($container)
    {
        $connection = \MySqlDatabase::getInstance();
        $this->db = $connection->getConnection();
        $this->logger = $container['logger'];
    }

    public function tryGetById($id)
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
}