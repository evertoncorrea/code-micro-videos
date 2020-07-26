<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertUuid(string $string, string $message = '') : void {
        $pattern = "/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i";
        if ($message=='') {
            $message = "Failed asserting that {$string} is a UUID";
        }
        $this->assertRegExp($pattern, $string, $message);
    }
}
