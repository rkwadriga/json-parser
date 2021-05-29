<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

class Decoder extends AbstractParser
{
    public function parse(): array|null
    {
        dd($this->source);
    }
}