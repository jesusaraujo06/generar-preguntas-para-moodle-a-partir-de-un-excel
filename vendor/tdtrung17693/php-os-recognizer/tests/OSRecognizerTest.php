<?php
use TdTrung\OSRecognizer\OSRecognizer;


class OSRecognizerTest extends \PHPUnit_Framework_TestCase
{
    private $recognizer;

    protected function setUp()
    {
        $this->recognizer = new OSRecognizer();
    }

    public function testRecognizerGetTheRightPlatform()
    {
        foreach ($this->recognizer->getSupportedOS() as $os) {
            if (stripos(PHP_OS, $os) !== false) {
                $this->assertEquals($os, $this->recognizer->getPlatform());
                break;
            }
        }
    }

    public function testRecognizerCanGetTheRelease()
    {
        $this->assertTrue(is_string($this->recognizer->getRelease()));
    }
}
