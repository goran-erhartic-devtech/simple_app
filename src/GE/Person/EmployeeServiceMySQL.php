<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 3:30 PM
 */
namespace GE\Person;

class EmployeeServiceMySQL extends \MySqlDatabase
{
    public function tryGetById($id)
    {
        $stmt = $this->connection->prepare("SELECT * from employees WHERE id = :id");
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
            $viewEmployee->setName($result['Name'])->setAge($result['Age'])->setProject($result['Project'])->setDepartment($result['Department'])->setIsActive($result['isActive']);

            return $viewEmployee;

        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAll()
    {
        $allEmployees = array();

        foreach ($this->connection->query("SELECT * from employees") as $result) {
            $viewEmployee = new Employee();
            $viewEmployee->setName($result['Name'])->setAge($result['Age'])->setProject($result['Project'])->setDepartment($result['Department'])->setIsActive($result['isActive']);
            array_push($allEmployees, $viewEmployee);
        }
        return $allEmployees;
    }

    public function create($result)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO employees (Name, Age, Project, Department, isActive) VALUES (:fname, :age, :project, :department, :isActive)");
            if ($result['Name'] === '') {
                throw new \PDOException("Name cannot be empty!");
            } elseif ($result['Age'] === '') {
                throw new \PDOException("Age cannot be empty!");
            } else {
                $stmt->execute(array(
                    "fname" => $result['Name'],
                    "age" => $result['Age'],
                    "project" => $result['Project'],
                    "department" => $result['Department'],
                    "isActive" => $result['isActive']
                ));
                echo "Employee created";
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update($id, $result)
    {
        try {
            $this->tryGetById($id);
            $stmt = $this->connection->prepare('UPDATE employees SET Name = :name, Age = :age, Project = :project, Department = :department, isActive = :isActive WHERE id = :id');
            if (empty($result['Name']) || !ctype_alpha($result['Name'])) {
                throw new \PDOException("Name must not be empty, and cannot have any numeric characters!");
            } else {
                $stmt->execute(array(
                    ':id' => $id,
                    ':name' => $result['Name'],
                    ":age" => $result['Age'],
                    ":project" => $result['Project'],
                    ":department" => $result['Department'],
                    ":isActive" => $result['isActive']
                ));
                echo "Employee updated";
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public
    function delete($id)
    {
        try {
            $this->tryGetById($id);
            $stmt = $this->connection->prepare('DELETE FROM employees WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo "Employee deleted!";
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}