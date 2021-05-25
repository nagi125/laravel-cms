<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;

class TopControllerTest extends TestCase
{
    /**
     * @test
     */
    public function トップページは正常に表示される()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
