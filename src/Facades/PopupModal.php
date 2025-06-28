<?php

namespace Maystro\FilamentPopupModal\Facades;

use Illuminate\Support\Facades\Facade;

class PopupModal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Maystro\FilamentPopupModal\PopupModal::class;
    }
}
