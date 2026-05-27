<?php
use PHPUnit\Framework\TestCase;
require_once ("hello.php");
final class HelloTest extends TestCase
{
    public function testHallo(): void
    {
        $input = "Mikolaj";
        $result = hallo($input);
        $this->assertSame($result, "Hallo, Mikolaj!");
    }
}