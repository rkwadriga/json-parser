<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

class Json
{
    public static function decode(string $jsonString): mixed
    {
        $parser = new Decoder($jsonString);
        return $parser->convert();
    }
}