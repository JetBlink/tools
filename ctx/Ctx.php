<?php

namespace Ctx;

use PHPCtx\Ctx\Ctx as BasicCtx;

/**
 * Class Ctx
 * @property \Ctx\Service\SqlQuery\Ctx $SqlQuery
 */
class Ctx extends BasicCtx
{
    /**
     * ctx instance
     */
    protected static $ctxInstance;

    //ctx namespace
    protected $ctxNamespace = 'Ctx';
}
