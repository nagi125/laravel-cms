<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /**
     * @test
     */
    public function お問い合わせページは正常に表示される()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }
}
