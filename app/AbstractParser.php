<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

abstract class AbstractParser
{
    public function __construct(
        protected string|array $source
    ) {}

    abstract public function parse(): array|string|null;
}