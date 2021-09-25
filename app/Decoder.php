<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace App;

use App\Exceptions\DecodeException;

class Decoder extends AbstractParser
{
    private const OBJECT_START = '{';
    private const OBJECT_END = '}';
    private const ARRAY_START = '[';
    private const ARRAY_END = ']';
    private const STRING_CHAR = '"';
    private const DECIMAL_CHAR = '.';
    private const SEPARATE_CHAR = ',';
    private const PARAM_CHAR = ':';
    private const NULL_VAL = 'NULL';

    private array $chars = [
        self::OBJECT_START => self::OBJECT_END,
        self::ARRAY_START => self::ARRAY_END,
    ];

    protected function parse(): mixed
    {
        $source = '{"param1":"Value 1","param2":"Value 2"}';
        $source = '{"param1":"Value 1","param2":"Value 2","param3":3,"param4":4.14,"param5":[1,2,3]}';
        $source = '{"array":[1,2,3]}';

        dd($this->parseStringRecursive($source));
    }

    private function parseStringRecursive(string $str): mixed
    {
        // Remove spaces and string braking from the start and end of string
        $str = trim($str);

        // Get the first and the last chars
        [$firstChar, $lastChar] = [substr($str, 0, 1), substr($str, -1)];

        // Exit condition 1 (string scalar):
        if ($firstChar === self::STRING_CHAR) {
            //If string like '"name":<value>' - recursively parse the "value" part and return an associative array
            $paramsValueSeparator = self::STRING_CHAR . self::PARAM_CHAR;
            preg_match("/^{$firstChar}(.+){$paramsValueSeparator}(.+)$/", $str, $matches);
            if (preg_match("/^{$firstChar}(.+){$paramsValueSeparator}(.+)$/", $str, $matches)) {
                // Init result array and fill it by recursively parsed values
                $subStringParts = $this->explode($matches[2]);
                // @Recursion
                $result = [$matches[1] => $this->parseStringRecursive(array_shift($subStringParts))];
                foreach ($subStringParts as $subStringPart) {
                    // @Recursion
                    $result = array_merge($result, (array)$this->parseStringRecursive($subStringPart));
                }

                // Return result
                return $result;
            }

            // Check the end char - th should be a "close string" char
            if ($lastChar !== self::STRING_CHAR) {
                throw new DecodeException("Invalid string closing char: \"$lastChar\"", DecodeException::INVALID_SOURCE);
            }
            return substr($str, 1, -1);
        }

        // Exit condition 2 (non-string scalar): if first char is not an "open array" or "open object" char
        if (!isset($this->chars[$firstChar])) {
            // Numeric - integer or float
            if (is_numeric($str)) {
                return str_contains($str, self::DECIMAL_CHAR) !== false ? floatval($str) : intval($str);
            }
            // Null or boolean
            return strtoupper($str) === self::NULL_VAL ? null : boolval($str);
        }

        // Check closing char - it should be compare with opening char
        if ($lastChar !== $this->chars[$firstChar]) {
            $typeString = $firstChar === self::OBJECT_START ? 'object' : 'array';
            throw new DecodeException("Invalid {$typeString} closing char: \"$lastChar\"", DecodeException::INVALID_SOURCE);
        }

        // Init result - in non-scalar case it always will be array
        $result = [];

        // Fill the result array by recursively parsed values
        foreach ($this->explode(substr($str, 1, -1)) as $subStringPart) {
            // @Recursion
            $result = array_merge($result, (array)$this->parseStringRecursive($subStringPart));
        }

        // Return result
        return $result;
    }

    private function explode(string $str): array
    {
        // @todo - make a correct exploding by separator
        return explode(self::SEPARATE_CHAR, $str);
    }

    protected function sourceType(): SourceTypeEnum
    {
        return new SourceTypeEnum(SourceTypeEnum::STRING);
    }
}