<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 28/3/2017
 * Time: 9:14 AM
 */

use GE\Person\EmployeeServiceMySQL;
use GE\Person\EmployeeServiceMongo;
use PHPUnit\Framework\TestCase;

/**
 * Class EmployeeServiceTest
 */
class EmployeeServiceTest extends TestCase
{
    private $db_instance;
    private $employee_service;
    private $container;

    /**
     * Include database constants
     */
    public static function setUpBeforeClass()
    {
        include __DIR__ . '/../../../database/config.php';
    }

    public function setUp()
    {
        $this->db_instance = \Database\Database::getInstance();
        $this->container = new Pimple\Container();
        $this->container['logger'] = function () {
            return new \SimpleLogger\File('log.log');
        };

        if (DATABASE == 'mysql') {
            $this->employee_service = new EmployeeServiceMySQL($this->db_instance, $this->container);
        } elseif (DATABASE == 'mongodb') {
            $this->employee_service = new EmployeeServiceMongo($this->db_instance, $this->container);
        }
        /*
         * Clean up log.log file after the test
         */
//        register_shutdown_function(function () {
//            if (file_exists('log.log')) {
//                unlink('log.log');
//            }
//        });
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

    public function testCreate()
    {
        $mockArr = array('Name' => 'TestEmployee', 'Age' => 666, 'Project' => 'PrOj', 'Department' => 'DePPP', 'isActive' => 1);
        $this->assertTrue($this->employee_service->create($mockArr));
    }

    public function testDelete(){
        if (DATABASE == 'mysql') {
            $testEmployeeId = $this->db_instance->getConnection()->lastInsertId();
            $this->assertTrue($this->employee_service->delete($testEmployeeId));
        }elseif (DATABASE == 'mongodb') {
            $query = new \MongoDB\Driver\Query([], ['sort' => ['id' => -1], 'limit' => 1]);
            $rows = $this->db_instance->getConnection()->executeQuery(TABLE_USER, $query);
            $lastId = 0;
            foreach ($rows as $res) {
                $lastId = $res->id;
            }
            $this->assertTrue($this->employee_service->delete($lastId));
        }
    }
}
