<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App\Exceptions;

use \Exception;

class EnumException extends Exception
{
    public const CODE_INVALID_VALUE = 900178654;
}