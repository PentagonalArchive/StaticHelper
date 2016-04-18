<?php
use Pentagonal\StaticHelper\InternalHelper;

class InternalTest extends \PHPUnit_Framework_TestCase
{
    public function testStrtoLower()
    {
        $string = 'TheUppercase';
        $arrayUppercase = array(
            'TheUppercase',
            'TheUppercaseLetter'
        );
        $this->assertNotEquals(
            $string,
            InternalHelper::strtoLower($string)
        );
        $this->assertNotEquals(
            $arrayUppercase,
            InternalHelper::strtoLower($arrayUppercase)
        );
    }

    public function testStrtoUpper()
    {
        $string = 'TheLowercase';
        $arrayLowerCase = array(
            'TheLowercase',
            'TheLowercaseLetter'
        );
        $this->assertNotEquals(
            $string,
            InternalHelper::strtoUpper($string)
        );
        $this->assertNotEquals(
            $arrayLowerCase,
            InternalHelper::strtoUpper($arrayLowerCase)
        );
    }

    public function testStrSplit()
    {
        $string = 'TestToSplitasArray';
        $this->assertArrayHasKey(
            1,
            InternalHelper::strSplit($string, 2)
        );
    }

    public function testBase64Encode()
    {
        $string = 'plain';
        $this->assertEquals(
            base64_encode($string),
            InternalHelper::base64Encode($string)
        );
    }

    public function testBase64ecode()
    {
        $string = base64_encode('plain');
        $this->assertEquals(
            base64_decode($string),
            InternalHelper::base64Decode($string)
        );
    }
}
