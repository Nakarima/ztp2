<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\Calculator;

class Test extends TestCase
{
    public function testEmptyString()
    {
        $kata = new Calculator();

        $result = $kata->add("");

        $this->assertEquals(0, $result);
    }

    public function testOne()
    {
        $kata = new Calculator();

        $result = $kata->add("1");

        $this->assertEquals(1, $result);
    }

    public function testTwo()
    {
        $kata = new Calculator();

        $result = $kata->add("1,2");

        $this->assertEquals(3, $result);
    }

    public function testLonger()
    {
        $kata = new Calculator();

        $result = $kata->add("999");

        $this->assertEquals(999, $result);
    }

    public function testALot()
    {
        $kata = new Calculator();

        $result = $kata->add("1,1,1,1,1,1,1");

        $this->assertEquals(7, $result);
    }

    public function testALot2()
    {
        $kata = new Calculator();

        $result = $kata->add("10,10,10,10,10,10,12");

        $this->assertEquals(72, $result);
    }

    public function testNewLine()
    {
        $kata = new Calculator();

        $result = $kata->add("1\n2,3");

        $this->assertEquals(6, $result);
    }

    public function testNegatives()
    {
        $kata = new Calculator();

        $this->expectExceptionMessage("negatives not allowed");

        $result = $kata->add("1\n2,-3");
    }

    public function testCustomDelimiter()
    {
        $kata = new Calculator();

        $result = $kata->add("//;\n2;3");

        $this->assertEquals(5, $result);
    }

    public function testIgnoringBiggerThan1000()
    {
        $kata = new Calculator();

        $result = $kata->add("1001,2");

        $this->assertEquals(2, $result);
    }

    public function testCustomLongDelimiter()
    {
        $kata = new Calculator();

        $result = $kata->add("//***\n2***3***3");

        $this->assertEquals(8, $result);
    }

}
