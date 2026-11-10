<?php
// app/controllers/admin/AdminProductController.php

class AdminProductController extends AdminController
{
    public function index(): void
    {
        $this->view('admin/placeholder', ['pageTitle' => 'Quản lý Sản phẩm']);
    }
}
