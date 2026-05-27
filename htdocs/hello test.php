<?php
use PHPUnit\Framework\TestCase;
require_once ("hello.php");
final class HelloTest extends TestCase
{
    public function testHalloooo(): void
    {
        $input = "Mikolaj";
        $result = halloooo($input);
        $this->assertSame($result, "Hallo, Mikolaj!");
    }
}