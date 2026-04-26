<?php

namespace Tests\Feature;

use Tests\TestCase;

class CsrfAndAuthTest extends TestCase
{
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
