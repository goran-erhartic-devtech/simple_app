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
    public function getOne($id)
    {
        if ($this->connection) {
            try {
                $stmt = $this->connection->prepare("SELECT * from employees WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result = $stmt->fetch();

                $viewEmployee = new Employee();
                $viewEmployee->setName($result['Name'])->setAge($result['Age'])->setProject($result['Project'])->setDepartment($result['Department'])->setIsActive($result['isActive']);
                if (!$result) {
                    throw new \PDOException("User with ID: " . $id . " not found");
                } else {
                    return $viewEmployee;
                }
            } catch (\PDOException $e) {
                echo $e->getMessage();
                die();
            }
        } else die();
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
        $stmt = $this->connection->prepare("INSERT INTO employees (Name, Age, Project, Department, isActive) VALUES (:fname, :age, :project, :department, :isActive)");
        $stmt->execute(array(
            "fname" => $result['Name'],
            "age" => $result['Age'],
            "project" => $result['Project'],
            "department" => $result['Department'],
            "isActive" => $result['isActive']
        ));
        echo "Employee created";
    }

    public function update($id, $result)
    {
        $stmt = $this->connection->prepare('UPDATE employees SET Name = :name WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id,
            ':name' => $result['Name']
        ));
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM employees WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}