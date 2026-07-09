<?php

namespace App\Models;

use CodeIgniter\Model;

class InquiryModel extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'full_name', 'city', 'province', 'contact_number', 
        'email', 'latitude', 'longitude', 'suggested_branch_id', 
        'suggested_branch_name', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get inquiries with filters
    public function getInquiries($search = null, $status = null, $perPage = 10, $page = 1)
    {
        $builder = $this;

        if (!empty($search)) {
            $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('city', $search)
                ->orLike('province', $search)
                ->orLike('contact_number', $search)
                ->orLike('suggested_branch_name', $search)
                ->groupEnd();
        }

        if (!empty($status) && $status !== 'all') {
            $builder->where('status', $status);
        }

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        $results = $builder->orderBy('id', 'DESC')->limit($perPage, $offset)->findAll();

        return [
            'data' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => (int)$page,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    // Get status counts
    public function getStatusCounts()
    {
        $total = $this->countAllResults();
        $pending = $this->where('status', 'pending')->countAllResults();
        $contacted = $this->where('status', 'contacted')->countAllResults();
        $resolved = $this->where('status', 'resolved')->countAllResults();

        return [
            'total' => $total,
            'pending' => $pending,
            'contacted' => $contacted,
            'resolved' => $resolved
        ];
    }
}