<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_login_page_is_visible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke sistem');
    }

    public function test_archive_pages_are_protected(): void
    {
        $response = $this->get('/archives');

        $response->assertRedirect('/');
    }
}
