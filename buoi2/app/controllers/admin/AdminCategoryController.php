<?php
// app/controllers/admin/AdminCategoryController.php

class AdminCategoryController extends AdminController
{
    public function index(): void
    {
        $this->view('admin/placeholder', ['pageTitle' => 'Quản lý Danh mục']);
    }
}
