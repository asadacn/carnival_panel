<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testBulkPreviewCanAcceptExpiredSelectedMonthsRequest()
    {
        $response = $this->getJson('/bulk_sms/preview?client_status=expired_selected_months&months[]=1&months[]=4');

        $response->assertStatus(401);
    }

    public function testBulkPreviewCanAcceptActiveGroupRequest()
    {
        $response = $this->getJson('/bulk_sms/preview?client_status=active');

        $response->assertStatus(401);
    }
}
