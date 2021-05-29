<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

abstract class AbstractParser
{
    public function __construct(
        protected string|array $source,
    ) {}

    public function convert(): mixed
    {
        // If source type do ton match with current parser expected source type -
        //  let's assume it's doesn't need to be parsed
        if (gettype($this->source) != $this->sourceType()) {
            return $this->source;
        }
        return $this->parse();
    }

    abstract protected function parse(): mixed;

    abstract protected function sourceType(): SourceTypeEnum;
}