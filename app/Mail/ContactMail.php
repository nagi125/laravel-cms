<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected array $validated;

    /**
     * ContactMail constructor.
     * @param  array  $validated
     */
    public function __construct(array $validated)
    {
        $this->validated = $validated;
    }

    /**
     * @return ContactMail
     */
    public function build()
    {
        $validated = $this->validated;

        $data = [
            'validated' => $validated,
        ];

        return $this->from('admin@example.com', 'テスト窓口')
            ->to($validated['email'])
            ->bcc('admin@example.com')
            ->subject('【自動返信】お問い合わせ完了のお知らせ')
            ->text('emails.contact', $data);
    }
}
