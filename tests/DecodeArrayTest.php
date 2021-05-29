<?php declare(strict_types=1);
/**
 * Created 2021-05-29
 * Author Dmitry Kushneriov
 */

namespace Tests;

use App\Json;

/**
 * Command ./run DecodeArrayTest
 */
class DecodeArrayTest extends AbstractTest
{
    public function testSimpleArray(): void
    {
        $this->assertNull(Json::decode($this->simpleArrayJson));
    }
}