<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/12/18
 * Time: 11:58 AM
 */

namespace Skipper\Mines;


use Dotenv\Dotenv;

class Config
{
    protected $root;

    public function __construct(string $dir)
    {
        $this->root = $dir;
        $this->load();
    }

    protected function load()
    {
        $env = new Dotenv($this->root);
        $env->load();
    }

    public function getListenedUri(): string
    {
        return sprintf('%s:%d', getenv('LISTENED_URI'), getenv('LISTENED_PORT'));
    }
}