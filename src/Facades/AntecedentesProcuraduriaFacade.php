<?php

namespace Jota\AntecededntesProcuraduria\Facades;

use Illuminate\Support\Facades\Facade;

class AntecededntesProcuraduriaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Antecedentes';
    }
}

