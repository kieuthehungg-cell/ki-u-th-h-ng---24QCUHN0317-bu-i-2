<?php
// public/index.php
declare(strict_types=1);

// Khoi tao session truoc khi bat ky output nao xuat hien
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dang ky Autoloader nap dong cac class trong he thong
spl_autoload_register(function (string $class) {
    $searchDirectories = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/controllers/admin/' . $class . '.php',
    ];

    foreach ($searchDirectories as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Nap tap tin ham tien ich
require_once __DIR__ . '/../app/core/helpers.php';

// Phan tich URL (Routing)
$url = $_GET['url'] ?? '';

if ($url === '') {
    // Neu mod_rewrite khong truyen qua ?url=, tu boc tach qua REQUEST_URI hoac PATH_INFO
    if (!empty($_SERVER['PATH_INFO'])) {
        $url = $_SERVER['PATH_INFO'];
    } else {
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($requestUri, $scriptDir)) {
            $url = substr($requestUri, strlen($scriptDir));
        } else {
            $url = $requestUri;
        }
    }
}

// Loai bo tien to index.php va dau gach cheo thua
$url = preg_replace('#^/?index\.php/?#i', '', $url);
$url = trim($url, '/');

// Dieu phoi yeu cau (Dispatch Route)
switch ($url) {
    case '':
    case 'home':
        (new HomeController())->index();
        break;

    case 'auth/register':
        (new AuthController())->register();
        break;

    case 'auth/login':
        (new AuthController())->login();
        break;

    case 'auth/logout':
        (new AuthController())->logout();
        break;

    case 'account/profile':
        (new AccountController())->profile();
        break;

    case 'admin':
    case 'admin/dashboard':
        (new AdminDashboardController())->index();
        break;

    case 'admin/auth/login':
        (new AdminAuthController())->login();
        break;

    case 'admin/auth/logout':
        (new AdminAuthController())->logout();
        break;

    case 'admin/user':
        (new AdminUserController())->index();
        break;

    case 'admin/user/role':
        (new AdminUserController())->role();
        break;

    case 'admin/user/status':
        (new AdminUserController())->status();
        break;

    case 'admin/category':
        (new AdminCategoryController())->index();
        break;

    case 'admin/product':
        (new AdminProductController())->index();
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/../app/views/errors/404.php';
        break;
}
