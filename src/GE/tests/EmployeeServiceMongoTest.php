<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 29/3/2017
 * Time: 9:47 AM
 */

use GE\Person\EmployeeServiceMongo;
use PHPUnit\Framework\TestCase;

define('DATABASE', 'mongodb');

define('DB_USER', 'admin'); //MySQL -> root ; MongoDB -> admin
define('DB_PASS', 'admin'); //MySQL -> root ; MongoDB -> admin
define('DB_HOST', 'localhost');
define('TABLE_USER', "guest.employees");
define('DB_NAME', 'guest');

class EmployeeServiceMongoTest extends TestCase
{

    public $db_instance;
    public $container;
    public $employee_service;

    public function setUp()
    {
        $this->db_instance = \Database\MongoDatabase::getInstance();
        $this->container = new Pimple\Container();
        $this->container['logger'] = function () {
            return new \SimpleLogger\File('log.log');
        };
        $this->employee_service = new EmployeeServiceMongo($this->db_instance, $this->container);

        /*
         * Clean up log.log file after the test
         */
        register_shutdown_function(function () {
            if (file_exists('log.log')) {
                unlink('log.log');
            }
        });
    }

    public function testGetOne()
    {
        $oneEmployee = $this->employee_service->getOne(1);
        $this->assertNotEmpty($oneEmployee);
        $this->assertLessThanOrEqual(1, sizeof($oneEmployee));
    }

    public function testGetAll()
    {
        $allEmployees = $this->employee_service->getAll();
        $this->assertGreaterThan(0, sizeof($allEmployees));
    }

    public function testCreateAndDelete()
    {

        //Auto-increment ID
        $query = new \MongoDB\Driver\Query([], ['sort' => ['id' => -1], 'limit' => 1]);
        $rows = $this->db_instance->getConnection()->executeQuery(TABLE_USER, $query);
        $lastId = 0;
        foreach ($rows as $res) {
            $lastId = $res->id;
        }
        $newId = $lastId + 1;

        //create mock employee
        $write = new \MongoDB\Driver\BulkWrite();
        $write->insert([
            'id' => $newId,
            'name' => 'Name',
            'age' => 'Age',
            'project' => 'Project',
            'department' => 'Department',
            'isActive' => 'isActive']);
        $this->db_instance->getConnection()->executeBulkWrite(TABLE_USER, $write);
        $this->assertEquals(1, $write->count());

        //delete mock employee
        $delete = new \MongoDB\Driver\BulkWrite();
        $delete->delete(['id' => intval($newId)]);
        $this->db_instance->getConnection()->executeBulkWrite(TABLE_USER, $delete);
        $this->assertEquals(1, $delete->count());
    }
}