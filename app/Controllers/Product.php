<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10; // Show 10 products per page

        $result = $this->productModel->getProducts($search, $status, $perPage, $page);
        $statusCounts = $this->productModel->getStatusCounts();
        $importers = $this->productModel->getImporters();

        $data = [
            'title' => 'Product Management',
            'username' => $this->session->get('username'),
            'products' => $result['data'],
            'totalProducts' => $result['total'],
            'perPage' => $result['perPage'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'statusCounts' => $statusCounts,
            'importers' => $importers,
            'search' => $search,
            'statusFilter' => $status
        ];

        return view('admin/products', $data);
    }

    public function getProduct($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $product = $this->productModel->find($id);
        if ($product) {
            return $this->response->setJSON(['success' => true, 'product' => $product]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
    }

    public function getStats()
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $stats = $this->productModel->getStatusCounts();
        return $this->response->setJSON(['success' => true, 'stats' => $stats]);
    }
}