<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 28/3/2017
 * Time: 9:04 AM
 */

use PHPUnit\Framework\TestCase;

define('Employee', 'GE\Person\Employee');

/**
 * Class EmployeeTest
 */
class EmployeeTest extends TestCase
{

    public function testIfClassHasAttributes()
    {
        $this->assertClassHasAttribute('name', Employee);
        $this->assertClassHasAttribute('age', Employee);
        $this->assertClassHasAttribute('project', Employee);
        $this->assertClassHasAttribute('department', Employee);
        $this->assertClassHasAttribute('is_active', Employee);
    }
}
