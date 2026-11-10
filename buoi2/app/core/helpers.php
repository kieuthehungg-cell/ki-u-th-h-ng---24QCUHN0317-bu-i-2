<?php
// app/core/helpers.php

// ---------------------------------------------------------------
// Ham tien ich co ban (Buoi 1)
// ---------------------------------------------------------------

function e(?string $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function base_url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $dir = dirname($scriptName);
        $base = ($dir === '/' || $dir === '\\') ? '' : rtrim($dir, '/');
    }
    $path = ltrim($path, '/');
    return $path === '' ? ($base === '' ? '/' : $base . '/') : $base . '/' . $path;
}

function asset(string $path = ''): string
{
    return base_url(ltrim($path, '/'));
}

function format_vnd(float|int $amount): string
{
    return number_format($amount, 0, ',', '.') . ' đ';
}

function product_image_url(?string $image): string
{
    if (empty($image)) {
        return asset('images/no-image.png');
    }
    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        return $image;
    }
    return asset('uploads/' . ltrim($image, '/'));
}

// ---------------------------------------------------------------
// Bo sung Buoi 2: chong CSRF va thong bao flash (session-based)
// ---------------------------------------------------------------
/**
* Sinh (hoac tai su dung) token CSRF luu trong session.
*/
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
* Tra ve input hidden de nhung vao MOI form POST.
*/
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
* Doi chieu token gui len voi token trong session.
* Goi o DAU moi ham xu ly POST, truoc khi dong cham den du lieu.
*/
function verify_csrf(string $token): void
{
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        die('Phien lam viec da het han hoac yeu cau khong hop le. Vui long tai lai trang va thu lai.');
    }
}

/**
* Thong bao flash: hien thi MOT LAN roi tu xoa.
* - Goi flash('success', 'Noi dung') de LUU thong bao truoc khi redirect.
* - Goi flash('success') (khong co $message) de LAY va xoa thong bao.
*/
function flash(string $key, ?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = ['message' => $message, 'type' => $type];
        return null;
    }
    if (!empty($_SESSION['flash'][$key])) {
        $data = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $data;
    }
    return null;
}
