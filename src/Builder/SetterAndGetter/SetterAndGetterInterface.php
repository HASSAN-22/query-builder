<?php

namespace App\Builder\SetterAndGetter;

/**
 * @property static array $data;
 */
interface SetterAndGetterInterface
{
    public static function get();

    public static function set($data);

    public static function empty();
}