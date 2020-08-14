<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class DumperTest extends TestCase
{

    private Faydaen\Dumper $dumper;
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->dumper = new Faydaen\Dumper();
        parent::__construct($name, $data, $dataName);
    }

    public function testDumpInt(): void
    {
        $expected = '<span style="color:#0000FF">45</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(45);
    }

    public function testDumpFloat(): void
    {
        $expected = '<span style="color:#0000FF">12.2f</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(12.2);
    }

    public function testDumpString(): void
    {
        $expected = '<span style="color:#D67F1D">\'string\'</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump('string');
    }

    public function testDumpBoolTrue(): void
    {
        $expected = '<span style="color:#C04E19">true</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(true);
    }

    public function testDumpBoolFalse(): void
    {
        $expected = '<span style="color:#C04E19">false</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(false);
    }

    public function testDumpNull(): void
    {
        $expected = '<span style="color:#C04E19">null</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(null);
    }

    public function testDumpAssociate(): void
    {
        $expected ='[<br>'.PHP_EOL;
        $expected .='&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'a\'</span> => <span style="color:#D67F1D">\'aaa\'</span>,<br>'.PHP_EOL;
        $expected .='&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'b\'</span> => <span style="color:#D67F1D">\'bbb\'</span>,<br>'.PHP_EOL;
        $expected .='&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'c\'</span> => <span style="color:#D67F1D">\'ccc\'</span><br>'.PHP_EOL;
        $expected .='];<br>'.PHP_EOL;

        $this->expectOutputString($expected);
        $array = ['a'=>'aaa','b'=>'bbb','c'=>'ccc'];
        $this->dumper->dump($array);
    }

    public function testDumpArrayEmpty(): void
    {
        $expected = '<span style="">[ ]</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $array = [];
        $this->dumper->dump($array);
    }

    public function testDumpArrayFlat(): void
    {
        $expected = '[<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0000FF">1</span>,<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0000FF">2</span>,<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0000FF">3</span><br>'.PHP_EOL;
        $expected .= '];<br>'.PHP_EOL;

        $this->expectOutputString($expected);
        $array = [1,2,3];
        $this->dumper->dump($array);
    }

    public function testDumpObject(): void
    {
        $expected = '<span style="">SomeClass::class</span> {<br>'. PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;$a = <span style="color:#D67F1D">\'aaa\'</span>;<br>'. PHP_EOL;
        $expected .= '};<br>' . PHP_EOL;

        $this->expectOutputString($expected);
        $this->dumper->dump(new SomeClass());
    }

    public function testDumpEndSign(): void {
        $expected = '[<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;<span style="">SomeClassWithArray::class</span> {<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$a = [<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'aaa\'</span>,<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'bbb\'</span><br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;];<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;},<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#D67F1D">\'value\'</span><br>'.PHP_EOL;
        $expected .= '];<br>'.PHP_EOL;

        $this->expectOutputString($expected);

        $array = [new SomeClassWithArray(), 'value'];

        $this->dumper->dump($array);
    }

    public function testDumpClassName(): void {
        $expected = '[<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;[<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="">SomeClass::class</span><br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;],<br>'.PHP_EOL;
        $expected .= '];<br>'.PHP_EOL;

        $this->expectOutputString($expected);
        $this->dumper->dump([[new SomeClass()]]);
    }

    public function testDumpUnknown(): void {
        $expected = '<span style="color:#997229">resource</span><br>'.PHP_EOL;
        $context = stream_context_create();
        $this->expectOutputString($expected);
        $this->dumper->dump($context);
    }

    public function testDumpComment(): void {
        $expected = '<b>comment</b><br><span style="color:#0000FF">1</span><br>'.PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump(1, 'comment');
    }
}

class SomeClass {
    public string $a;

    public function __construct()
    {
        $this->a = 'aaa';
    }
}

class SomeClassWithArray {
    public array $a;

    public function __construct()
    {
        $this->a = ['aaa','bbb'];
    }
}

