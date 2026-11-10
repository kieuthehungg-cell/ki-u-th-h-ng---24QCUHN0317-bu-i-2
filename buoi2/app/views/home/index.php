<div class="p-5 mb-4 bg-light rounded-3 shadow-sm text-center">
    <div class="container-fluid py-4">
        <h1 class="display-5 fw-bold text-primary">Chào mừng đến với ShopOnline</h1>
        <p class="col-md-8 fs-5 mx-auto text-muted">
            Hệ thống thực hành thương mại điện tử MVC PHP thuần. Buổi 2: Quản lý tài khoản, xác thực, phân quyền (RBAC) và bảo mật chống XSS/CSRF.
        </p>
        <div class="d-flex justify-content-center gap-2 mt-4">
            <?php if (Auth::check()): ?>
                <span class="badge bg-success p-2 fs-6">Đã đăng nhập: <?= e(Auth::user()['full_name']) ?> (Vai trò: <?= e(Auth::role()) ?>)</span>
                <?php if (in_array(Auth::role(), ['admin', 'staff'], true)): ?>
                    <a href="<?= e(base_url('admin/dashboard')) ?>" class="btn btn-dark">Đến trang quản trị</a>
                <?php else: ?>
                    <a href="<?= e(base_url('account/profile')) ?>" class="btn btn-outline-primary">Hồ sơ cá nhân</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= e(base_url('auth/login')) ?>" class="btn btn-primary px-4">Đăng nhập</a>
                <a href="<?= e(base_url('auth/register')) ?>" class="btn btn-outline-primary px-4">Đăng ký tài khoản</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="fs-1 text-primary mb-3">🛡️</div>
                <h5 class="card-title fw-bold">Bảo mật cao</h5>
                <p class="card-text text-muted">Mật khẩu được mã hoá bằng thuật toán bcrypt (<code>password_hash</code>). Chống tấn công CSRF bằng token ngẫu nhiên và XSS bằng <code>e()</code>.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="fs-1 text-success mb-3">👥</div>
                <h5 class="card-title fw-bold">Phân quyền chặt chẽ</h5>
                <p class="card-text text-muted">Hệ thống phân quyền 3 cấp độ: <code>admin</code> (toàn quyền), <code>staff</code> (quản lý), và <code>customer</code> (khách hàng).</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div class="fs-1 text-warning mb-3">⚙️</div>
                <h5 class="card-title fw-bold">Kiến trúc MVC chuẩn</h5>
                <p class="card-text text-muted">Tổ chức mã nguồn rõ ràng theo mô hình MVC, cơ chế routing tập trung, Base Controller, Base Model và Layout template.</p>
            </div>
        </div>
    </div>
</div>
