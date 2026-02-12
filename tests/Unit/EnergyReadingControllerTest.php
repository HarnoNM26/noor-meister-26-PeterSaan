<?php

namespace Tests\Unit;

use Tests\TestCase;

class EnergyReadingControllerTest extends TestCase
{
    public function test_invalid_dates_return_an_error()
    {
        $res = $this->post('/api/sync/prices', []);
    }
}
