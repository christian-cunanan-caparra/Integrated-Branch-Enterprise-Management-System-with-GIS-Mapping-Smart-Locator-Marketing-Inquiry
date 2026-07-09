<?php

namespace App\Controllers;

use App\Models\InquiryModel;
use App\Models\BranchModel;
use App\Models\CityModel;

class Inquiry extends BaseController
{
    protected $inquiryModel;
    protected $branchModel;
    protected $cityModel;

    public function __construct()
    {
        $this->inquiryModel = new InquiryModel();
        $this->branchModel = new BranchModel();
        $this->cityModel = new CityModel();
    }

    // Public form page
    public function index()
    {
        $branches = $this->branchModel->where('status', 'active')->findAll();
        
        $data = [
            'title' => 'Inquiry Form',
            'branches' => $branches
        ];

        return view('inquiry/form', $data);
    }

// Search cities (AJAX)
public function searchCities()
{
    $query = $this->request->getGet('q');
    $province = $this->request->getGet('province');
    $limit = $this->request->getGet('limit') ?? 100;
    
    $cities = $this->cityModel->searchCities($query, $province, $limit);
    
    return $this->response->setJSON([
        'success' => true,
        'cities' => $cities
    ]);
}


// Get cities by province (AJAX)
public function getCitiesByProvince()
{
    $province = $this->request->getGet('province');
    
    if (empty($province)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Province is required',
            'cities' => []
        ]);
    }
    
    $cities = $this->cityModel->getCitiesByProvince($province);
    
    return $this->response->setJSON([
        'success' => true,
        'cities' => $cities
    ]);
}

    // Get provinces (AJAX)
    public function getProvinces()
    {
        $provinces = $this->cityModel->getProvinces();
        
        return $this->response->setJSON([
            'success' => true,
            'provinces' => $provinces
        ]);
    }

// Submit inquiry
public function submit()
{
    log_message('debug', 'Inquiry submission started');
    
    try {
        // Get POST data
        $fullName = $this->request->getPost('full_name');
        $city = $this->request->getPost('city');
        $province = $this->request->getPost('province');
        $contactNumber = $this->request->getPost('contact_number');
        $email = $this->request->getPost('email');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $cityId = $this->request->getPost('city_id');
        $suggestedBranchId = $this->request->getPost('suggested_branch_id');
        $suggestedBranchName = $this->request->getPost('suggested_branch_name');

        // Validate
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[2]|max_length[100]',
            'city' => 'required|min_length[2]|max_length[100]',
            'province' => 'required|min_length[2]|max_length[100]',
            'contact_number' => 'required|min_length[7]|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // If branch not found from hidden inputs, find it
        if (empty($suggestedBranchId) || empty($suggestedBranchName)) {
            $suggestedBranch = $this->findNearestBranch($city, $province);
            $suggestedBranchId = $suggestedBranch ? $suggestedBranch['id'] : null;
            $suggestedBranchName = $suggestedBranch ? $suggestedBranch['branch_name'] : null;
        } else {
            // Get full branch details
            $suggestedBranch = $this->branchModel->find($suggestedBranchId);
        }

        // Get coordinates from city
        if ($cityId) {
            $cityData = $this->cityModel->find($cityId);
            if ($cityData) {
                $latitude = $cityData['latitude'] ?? $latitude;
                $longitude = $cityData['longitude'] ?? $longitude;
            }
        }

        // Prepare data for insertion
        $data = [
            'full_name' => $fullName,
            'city' => $city,
            'province' => $province,
            'contact_number' => $contactNumber,
            'email' => $email,
            'latitude' => $latitude ?: null,
            'longitude' => $longitude ?: null,
            'suggested_branch_id' => $suggestedBranchId,
            'suggested_branch_name' => $suggestedBranchName,
            'status' => 'pending'
        ];

        log_message('debug', 'Data to insert: ' . print_r($data, true));

        // Insert inquiry
        $inquiryId = $this->inquiryModel->insert($data);

        if ($inquiryId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inquiry submitted successfully!',
                'inquiry_id' => $inquiryId,
                'suggested_branch' => $suggestedBranch
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to submit inquiry. Please try again.'
            ]);
        }
    } catch (\Exception $e) {
        log_message('error', 'Inquiry submission error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    // Find nearest branch based on city/province
    private function findNearestBranch($city, $province)
    {
        try {
            $searchQuery = trim($city . ' ' . $province);
            $result = $this->branchModel->searchBranches($searchQuery);
            
            // Check if result has 'branches' key
            if (isset($result['branches']) && !empty($result['branches'])) {
                return $result['branches'][0];
            }
            
            // Check if result is a direct array of branches
            if (is_array($result) && !empty($result) && isset($result[0])) {
                return $result[0];
            }
            
            // Check if result is a single branch
            if (is_array($result) && !empty($result) && !isset($result[0]) && isset($result['id'])) {
                return $result;
            }
            
            // Fallback: get first active branch
            $branches = $this->branchModel->where('status', 'active')->limit(1)->findAll();
            return !empty($branches) ? $branches[0] : null;
        } catch (\Exception $e) {
            log_message('error', 'findNearestBranch error: ' . $e->getMessage());
            return null;
        }
    }

    // Auto-suggest branch based on city/province (AJAX)
    public function suggestBranch()
    {
        try {
            $city = $this->request->getGet('city');
            $province = $this->request->getGet('province');
            $latitude = $this->request->getGet('latitude');
            $longitude = $this->request->getGet('longitude');

            if (empty($city) && empty($province) && empty($latitude)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No location data provided',
                    'branches' => []
                ]);
            }

            $query = trim($city . ' ' . $province);
            $result = $this->branchModel->searchBranches($query);
            
            $branches = [];
            $isNearby = false;
            $message = '';

            if (isset($result['branches']) && !empty($result['branches'])) {
                $branches = $result['branches'];
                $isNearby = $result['isNearby'] ?? false;
                $message = $result['message'] ?? '';
            } elseif (is_array($result) && !empty($result) && isset($result[0])) {
                $branches = $result;
            } elseif (is_array($result) && !empty($result) && isset($result['id'])) {
                $branches = [$result];
            }

            return $this->response->setJSON([
                'success' => true,
                'branches' => $branches,
                'isNearby' => $isNearby,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            log_message('error', 'suggestBranch error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
                'branches' => []
            ]);
        }
    }

    // Admin: View all inquiries
    public function adminIndex()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $result = $this->inquiryModel->getInquiries($search, $status, $perPage, $page);
        $statusCounts = $this->inquiryModel->getStatusCounts();

        $data = [
            'title' => 'Inquiries Management',
            'username' => $this->session->get('username'),
            'inquiries' => $result['data'],
            'totalInquiries' => $result['total'],
            'perPage' => $result['perPage'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'statusCounts' => $statusCounts,
            'search' => $search,
            'statusFilter' => $status
        ];

        return view('admin/inquiries', $data);
    }

    // Admin: Update inquiry status
    public function updateStatus($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['pending', 'contacted', 'resolved'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $updated = $this->inquiryModel->update($id, ['status' => $status]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
    }

    // Admin: Get inquiry details
    public function getInquiry($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $inquiry = $this->inquiryModel->find($id);
        if ($inquiry) {
            return $this->response->setJSON(['success' => true, 'inquiry' => $inquiry]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Inquiry not found']);
    }
}