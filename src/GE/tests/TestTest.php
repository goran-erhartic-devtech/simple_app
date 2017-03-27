<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 27/3/2017
 * Time: 2:57 PM
 */
use PHPUnit\Framework\TestCase;


class TestTest extends TestCase
{
    public function testThis()
    {
        $a = "igor";
        $b = "igor";

        $this::assertEquals($a, $b);

        // run the test
        // phpunit ../../src/GE/tests/TestTest.php
    }


}