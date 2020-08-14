<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// alternative to include that avoid indexing by phpStorm
eval(file_get_contents(__DIR__ . "/../mock/Yii.php"));

final class DumperYiiTest extends TestCase
{
    private Faydaen\Dumper $dumper;
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->dumper = new Faydaen\Dumper();
        parent::__construct($name, $data, $dataName);
    }

    public function testDumpModel(): void
    {
        $entity = new yii\base\Model();

        $expected = '<span style="">yii\base\Model::class</span> {<br>'.PHP_EOL;
        $expected .= '&nbsp;&nbsp;&nbsp;&nbsp;$a = <span style="color:#D67F1D">\'aaa\'</span>;<br>'.PHP_EOL;
        $expected .= '};<br>'.PHP_EOL;

        $this->expectOutputString($expected);
        $this->dumper->dump($entity);
    }

    public function testDumpCommand(): void
    {
        $command = new \app\components\db\pgpdo\Command();
        $expected = '<span style="font-weight:bold;">SELECT * FROM users</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump($command);
    }

    public function testDumpQuery(): void
    {
        // so we will avoid phpstorm error "cannot instantiate interface"
        $query = null;
        $eval = '$query = new yii\db\QueryInterface();';
        eval($eval);

        $expected = '<span style="font-weight:bold;">SELECT * FROM users</span><br>' . PHP_EOL;
        $this->expectOutputString($expected);
        $this->dumper->dump($query);
    }
}

