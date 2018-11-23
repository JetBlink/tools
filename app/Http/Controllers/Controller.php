<?php

namespace App\Http\Controllers;

use Ctx\Ctx;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Ctx
     */
    protected $ctx;

    public function __construct()
    {
        $this->ctx = Ctx::getInstance();
    }
}
