<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'flavor', 'importer', 'manufacturer', 'fda_registration_no',
        'fda_no', 'registered_date', 'issued_date', 'validity_date',
        'approved_with_cpr', 'approved_without_cpr', 'not_approved_yet',
        'status', 'task', 'last_modify', 'remarks'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get products with filters and pagination
    public function getProducts($search = null, $status = null, $perPage = 10, $page = 1)
    {
        $builder = $this->where('flavor IS NOT NULL')->where('flavor !=', '');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('flavor', $search)
                ->orLike('fda_registration_no', $search)
                ->orLike('importer', $search)
                ->orLike('manufacturer', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        if (!empty($status) && $status !== 'all') {
            $builder->where('status', $status);
        }

        // Get total count for pagination
        $total = $builder->countAllResults(false);
        
        // Get paginated results
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

    // Get status counts - FIXED
    public function getStatusCounts()
    {
        // Total products (excluding empty rows)
        $total = $this->where('flavor IS NOT NULL')->where('flavor !=', '')->countAllResults();
        
        // Approved: products with approved_with_cpr = 1
        $approved = $this->where('flavor IS NOT NULL')->where('flavor !=', '')
            ->where('approved_with_cpr', 1)->countAllResults();
        
        // Pending: products that are NOT approved yet (not_approved_yet = 1) 
        // OR status is 'PENDING' or 'pending'
        $pending = $this->where('flavor IS NOT NULL')->where('flavor !=', '')
            ->groupStart()
                ->where('not_approved_yet', 1)
                ->orWhere('LOWER(status)', 'pending')
                ->orWhere('LOWER(status)', 'evaluation')
            ->groupEnd()
            ->countAllResults();
        
        // Disapproved: products with status = 'DISAPPROVED' or 'disapproved'
        $disapproved = $this->where('flavor IS NOT NULL')->where('flavor !=', '')
            ->where('LOWER(status)', 'disapproved')
            ->countAllResults();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'disapproved' => $disapproved
        ];
    }

    // Get unique importers
    public function getImporters()
    {
        return $this->select('importer')
            ->where('flavor IS NOT NULL')
            ->where('flavor !=', '')
            ->distinct()
            ->findAll();
    }
}