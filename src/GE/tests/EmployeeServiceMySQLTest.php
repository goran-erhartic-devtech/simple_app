<?php
///**
// * Created by PhpStorm.
// * User: goran.erhartic
// * Date: 28/3/2017
// * Time: 9:14 AM
// */
//
//use GE\Person\EmployeeServiceMySQL;
//use PHPUnit\Framework\TestCase;
//
//define('DATABASE', 'mysql');
//
//define('DB_USER', 'root'); //MySQL -> root ; MongoDB -> admin
//define('DB_PASS', 'root'); //MySQL -> root ; MongoDB -> admin
//define('DB_HOST', 'localhost');
//define('TABLE_USER', "guest.employees");
//define('DB_NAME', 'guest');
//
///**
// * Class EmployeeServiceMySQLTest
// */
//class EmployeeServiceMySQLTest extends TestCase
//{
//    private $db_instance;
//    private $employee_service;
//    private $container;
//
//    public function setUp()
//    {
//        $this->db_instance = \Database\MySqlDatabase::getInstance();
//        $this->db_instance->getConnection()->beginTransaction();
//        $this->container = new Pimple\Container();
//        $this->container['logger'] = function () {
//            return new \SimpleLogger\File('log.log');
//        };
//        $this->employee_service = new EmployeeServiceMySQL($this->db_instance, $this->container);
//
//        /*
//         * Clean up log.log file after the test
//         */
//        register_shutdown_function(function() {
//            if(file_exists('log.log')) {
//                unlink('log.log');
//            }
//        });
//    }
//
//    public function tearDown()
//    {
//        $this->db_instance->getConnection()->rollBack();
//    }
//
//    public function testGetOne()
//    {
//        $oneEmployee = $this->employee_service->getOne(1);
//        $this->assertNotEmpty($oneEmployee);
//        $this->assertLessThanOrEqual(1, sizeof($oneEmployee));
//    }
//
//    public function testGetAll()
//    {
//        $allEmployees = $this->employee_service->getAll();
//        $this->assertGreaterThan(0,sizeof($allEmployees));
//    }
//
//    public function testCreate(){
//
//        $a = $this->db_instance->getConnection()->prepare("INSERT INTO employees (Name, Age, Project, Department, isActive) VALUES (:fname, :age, :project, :department, :isActive)");
//        $a->execute(array(
//            "fname" => 'TestName',
//            "age" => 444,
//            "project" => 'asdf',
//            "department" => 'Department',
//            "isActive" => 1
//        ));
//
//        $stmt = $this->db_instance->getConnection()->prepare('SELECT * FROM employees WHERE Name = :fname');
//        $stmt->execute(array("fname" => 'TestName'));
//        $this->assertEquals(1, $stmt->rowCount());
//    }
//}
