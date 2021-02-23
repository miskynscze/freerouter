<?php
declare(strict_types=1);

namespace FreeRouter\Http;


class Redirect
{

    public const PERMANENT = 301;
    public const TEMPORARY_SEO = 301;

    private int $status;
    private string $redirectUrl;

    public function __construct($redirectTo, $status = self::TEMPORARY_SEO) {
        $this->status = $status;
        $this->redirectUrl = $redirectTo;
    }

    public function redirect(): void {
        http_send_status($this->status);
        header("Location: $this->redirectUrl");
        exit();
    }

    public function getRedirectUrl(): string {
        return $this->redirectUrl;
    }

    public function getStatus(): int {
        return $this->status;
    }
}