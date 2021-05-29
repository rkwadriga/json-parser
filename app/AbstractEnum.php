<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

use App\Exceptions\EnumException;
use \ReflectionClass;

abstract class AbstractEnum
{
    protected array $values;

    /**
     * @param string $value
     * @throws EnumException
     */
    public function __construct(
        protected string $value,
    ) {
        $reflection = new ReflectionClass($this);
        $this->values = $reflection->getConstants();
        if (!in_array($value, $this->values)) {
            $valuesString = implode('", "', '"' . $this->values . '"');
            throw new EnumException("Invalid enum value. Only {$valuesString} allowed, {$value} given", EnumException::CODE_INVALID_VALUE);
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}