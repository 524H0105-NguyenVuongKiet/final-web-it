<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Khai báo biến user để nhận dữ liệu từ Controller gửi qua
    public function __construct(public $user)
    {
    }

    /**
     * Thiết lập tiêu đề email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kích hoạt tài khoản Note App của bạn',
        );
    }

    /**
     * Thiết lập giao diện và dữ liệu truyền vào email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify', // Sẽ trỏ tới resources/views/emails/verify.blade.php
            with: [
                'name' => $this->user->display_name,
                'url' => url('/api/verify/' . $this->user->activation_token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}