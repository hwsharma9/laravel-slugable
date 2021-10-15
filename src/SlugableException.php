<?php

namespace Harsh\Slug;

use Exception;

class SlugableException extends Exception
{
    public static function slugFieldNotFound($error)
    {
        return new static($error);
    }
}