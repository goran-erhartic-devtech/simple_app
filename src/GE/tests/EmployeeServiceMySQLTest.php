<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 28/3/2017
 * Time: 9:14 AM
 */

use GE\Person\EmployeeServiceMySQL;
use PHPUnit\Framework\TestCase;

define('DATABASE', 'mysql');

define('DB_USER', 'root'); //MySQL -> root ; MongoDB -> admin
define('DB_PASS', 'root'); //MySQL -> root ; MongoDB -> admin
define('DB_HOST', 'localhost');
define('TABLE_USER', "guest.employees");
define('DB_NAME', 'guest');

/**
 * Class EmployeeServiceMySQLTest
 */
class EmployeeServiceMySQLTest extends TestCase
{
    private $database;
    private $instance;
    private $container;

    public function setUp()
    {
        $this->instance = \Database\MySqlDatabase::getInstance();
        $this->container = new Pimple\Container();
        $this->container['logger'] = function () {
            return new \SimpleLogger\File('log.log');
        };
        $this->database = new EmployeeServiceMySQL($this->instance, $this->container);
    }

    public function testGetOne()
    {
        $oneEmployee = $this->database->getOne(1);
        $this->assertNotEmpty($oneEmployee);
        $this->assertLessThanOrEqual(1, sizeof($oneEmployee));
    }

    public function testGetAll()
    {
        $allEmployees = $this->database->getAll();
        $this->assertGreaterThan(0,sizeof($allEmployees));
    }
}
