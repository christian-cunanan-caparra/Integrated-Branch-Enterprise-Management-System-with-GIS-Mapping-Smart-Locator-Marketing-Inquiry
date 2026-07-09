<?php

namespace App\Controllers;

use App\Models\BranchModel;

class Branch extends BaseController
{
    protected $branchModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }

        // Get all active branches
        $branches = $this->branchModel->where('status', 'active')->findAll();
        
        // Get statistics
        $stats = $this->branchModel->getStatistics();
        
        // Get branches with coordinates (for map)
        $branchesWithCoords = $this->branchModel
            ->where('status', 'active')
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->findAll();

        // Prepare data for view
        $data = [
            'title' => 'Branch Location Map',
            'username' => $this->session->get('username'),
            'branches' => $branches,
            'stats' => $stats,
            'branchesWithCoords' => $branchesWithCoords,
            'totalBranches' => count($branches)
        ];

        return view('admin/branches_map', $data);
    }

    public function search()
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $query = $this->request->getGet('q');
        
        if (empty($query)) {
            $branches = $this->branchModel->where('status', 'active')->findAll();
            return $this->response->setJSON([
                'success' => true,
                'branches' => $branches,
                'isNearby' => false,
                'nearbyMessage' => '',
                'query' => $query,
                'total' => count($branches)
            ]);
        }

        $result = $this->branchModel->searchBranches($query);

        // Ensure we have a consistent structure
        $branches = $result['branches'] ?? [];
        $isNearby = $result['isNearby'] ?? false;
        $nearbyMessage = $result['message'] ?? '';

        return $this->response->setJSON([
            'success' => true,
            'branches' => $branches,
            'isNearby' => $isNearby,
            'nearbyMessage' => $nearbyMessage,
            'query' => $query,
            'total' => count($branches)
        ]);
    }

    public function getBranch($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $branch = $this->branchModel->find($id);
        if ($branch) {
            return $this->response->setJSON(['success' => true, 'branch' => $branch]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Branch not found']);
    }

    public function getStats()
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $stats = $this->branchModel->getStatistics();
        return $this->response->setJSON(['success' => true, 'stats' => $stats]);
    }

    public function getAllCoordinates()
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $branches = $this->branchModel
            ->where('status', 'active')
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->findAll();
            
        return $this->response->setJSON(['success' => true, 'branches' => $branches]);
    }
}