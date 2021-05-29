<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App\Exceptions;

use \Exception;

class ParsingException extends Exception
{
    public const INVALID_SOURCE = 8001876643;
}