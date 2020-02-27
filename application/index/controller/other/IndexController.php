<?php
namespace app\index\controller\other;

use app\common\exception\JsonException;
use app\common\enums\ErrorCode;
use app\common\vo\ResultVo;

class IndexController
{
    public function index()
    {

        echo phpinfo();

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
