<?php

namespace Tests\Feature\Controller;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /**
     * @test
     */
    public function お問い合わせページは正常に表示される()
    {
        $res = $this->get(route('contact.index'));
        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function お問い合わせのサンキューページは正常に表示される()
    {
        $res = $this->get(route('contact.thanks'));
        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function お問い合わせ処理は正常に動作する()
    {
        $postData = [
          'name'  => '野末 テスト',
          'email' => 'nagi125.dev@gmail.com',
          'body'  => 'test',
        ];

        Mail::fake();

        $res = $this->from(route('contact.index'))->post(route('contact.submit'), $postData);

        Mail::assertSent(ContactMail::class, 1);

        $res->assertRedirect(route('contact.thanks'));
    }
}
