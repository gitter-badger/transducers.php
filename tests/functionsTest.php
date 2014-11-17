<?php
namespace Transducers\Tests;

use Transducers as T;

class functionsTest extends \PHPUnit_Framework_TestCase
{
    public function testComposesFunctions()
    {
        $a = function ($x) {
            $this->assertEquals(3, $x);
            return $x + 1;
        };

        $b = function ($x) {
            $this->assertEquals(1, $x);
            return $x + 2;
        };

        $c = T\comp($a, $b);
        $this->assertEquals(4, $c(1));
    }

    public function testEnsuresReduced()
    {
        $r = T\ensure_reduced(1);
        $this->assertEquals(1, $r->value);
        $r = T\ensure_reduced($r);
        $this->assertEquals(1, $r->value);
    }

    public function testReturnsIdentity()
    {
        $this->assertEquals(1, T\identity(1));
    }

    public function testReturnsAppendXform()
    {
        $xf = T\append();
        $this->assertEquals([], $xf['init']());
        $this->assertSame([10, 1], $xf['step']([10], 1));
        $this->assertSame([10], $xf['result']([10]));
    }

    public function testReturnsStreamXform()
    {
        $xf = T\stream();
        $res = $xf['init']();
        $this->assertInternalType('resource', $res);
        $this->assertSame($res, $xf['step']($res, 'a'));
        fseek($res, 0);
        $this->assertEquals('a', stream_get_contents($res));
        $this->assertSame($res, $xf['result']($res));
        fclose($res);
    }
}