<?php

namespace App\Controllers;

use App\Models\InquiryModel;
use App\Models\BranchModel;
use App\Models\ProductModel;

class Admin extends BaseController
{
    protected $inquiryModel;
    protected $branchModel;
    protected $productModel;

    public function __construct()
    {
        $this->inquiryModel = new InquiryModel();
        $this->branchModel = new BranchModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }
        
        // Get counts for statistics
        $totalInquiries = $this->inquiryModel->countAllResults();
        $totalBranches = $this->branchModel->where('status', 'active')->countAllResults();
        $totalProducts = $this->productModel->where('flavor IS NOT NULL')->where('flavor !=', '')->countAllResults();
        
        // Get inquiry status counts
        $pendingInquiries = $this->inquiryModel->where('status', 'pending')->countAllResults();
        $contactedInquiries = $this->inquiryModel->where('status', 'contacted')->countAllResults();
        $resolvedInquiries = $this->inquiryModel->where('status', 'resolved')->countAllResults();
        
        // Get branch region counts
        $branchRegions = $this->branchModel->select('region, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('region')
            ->findAll();
        
        // Get daily inquiries (last 7 days)
        $dailyInquiries = $this->getDailyInquiries();
        
        // Get weekly inquiries (last 4 weeks)
        $weeklyInquiries = $this->getWeeklyInquiries();
        
        // Get monthly inquiries (last 6 months)
        $monthlyInquiries = $this->getMonthlyInquiries();
        
        // Get yearly inquiries (last 5 years)
        $yearlyInquiries = $this->getYearlyInquiries();
        
        $data = [
            'title' => 'Admin Dashboard',
            'username' => $this->session->get('username'),
            'email' => $this->session->get('email'),
            'totalInquiries' => $totalInquiries,
            'totalBranches' => $totalBranches,
            'totalProducts' => $totalProducts,
            'totalAdmins' => 1,
            'pendingInquiries' => $pendingInquiries,
            'contactedInquiries' => $contactedInquiries,
            'resolvedInquiries' => $resolvedInquiries,
            'branchRegions' => $branchRegions,
            'dailyInquiries' => $dailyInquiries,
            'weeklyInquiries' => $weeklyInquiries,
            'monthlyInquiries' => $monthlyInquiries,
            'yearlyInquiries' => $yearlyInquiries
        ];
        
        return view('admin/dashboard', $data);
    }

    private function getDailyInquiries()
    {
        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = $this->inquiryModel->where('DATE(created_at)', $date)->countAllResults();
            $result[] = [
                'date' => date('D', strtotime($date)),
                'count' => $count
            ];
        }
        return $result;
    }

    private function getWeeklyInquiries()
    {
        $result = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = date('Y-m-d', strtotime("-$i weeks"));
            $weekEnd = date('Y-m-d', strtotime("+6 days", strtotime($weekStart)));
            $count = $this->inquiryModel
                ->where('created_at >=', $weekStart . ' 00:00:00')
                ->where('created_at <=', $weekEnd . ' 23:59:59')
                ->countAllResults();
            $result[] = [
                'week' => 'Week ' . (4 - $i),
                'count' => $count
            ];
        }
        return $result;
    }

    private function getMonthlyInquiries()
    {
        $result = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $this->inquiryModel
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                ->countAllResults();
            $result[] = [
                'month' => date('M', strtotime($month . '-01')),
                'count' => $count
            ];
        }
        return $result;
    }

    private function getYearlyInquiries()
    {
        $result = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = date('Y', strtotime("-$i years"));
            $count = $this->inquiryModel
                ->where('YEAR(created_at)', $year)
                ->countAllResults();
            $result[] = [
                'year' => $year,
                'count' => $count
            ];
        }
        return $result;
    }
}