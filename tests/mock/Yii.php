namespace yii\base {
    class Model {
        public string $a = 'aaa';
        public function attributes(){
            return [
                'a'
            ];
        }
    }
}

namespace yii\db {
    class QueryInterface {
        public function createCommand(){
            return new \app\components\db\pgpdo\Command();
        }
    }
}

namespace app\components\db\pgpdo {
    class Command {
        public function getRawSql(){
            /** @noinspection SqlResolve */
            return 'SELECT * FROM users';
        }
    }
}
