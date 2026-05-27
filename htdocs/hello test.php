<?php
use PHPUnit\Framework\TestCase;
require_once ("hello.php");
final class HelloTest extends TestCase
{
    public function testttttttHallo(): void
    {
        $input = "Mikolaj";
        $result = hallooo($input);
        $this->assertSame($result, "Hallo, Mikolaj!");
    }
}