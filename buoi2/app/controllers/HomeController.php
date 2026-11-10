<?php
// app/controllers/HomeController.php

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'pageTitle' => 'Trang chủ - Cửa hàng trực tuyến'
        ]);
    }
}
