<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 10:56 AM
 */
namespace GE\Person;

interface EmployeeInterface
{
    public function getAll();
    public function getOne($id);
    public function create($result);
    public function update($id, $result);
    public function delete($id);
}