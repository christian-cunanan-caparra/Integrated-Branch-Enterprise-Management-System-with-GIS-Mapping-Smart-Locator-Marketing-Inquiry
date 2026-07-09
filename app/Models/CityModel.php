<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    protected $allowedFields = ['city_name', 'province_name', 'region', 'latitude', 'longitude'];
    protected $returnType = 'array';

    // Search cities by name or province with optional province filter
    public function searchCities($query = null, $province = null, $limit = 100)
    {
        $builder = $this;

        if (!empty($query)) {
            $builder->like('city_name', $query);
        }

        if (!empty($province)) {
            $builder->where('province_name', $province);
        }

        return $builder->orderBy('city_name', 'ASC')->limit($limit)->findAll();
    }

    // Get cities by province (exact match)
    public function getCitiesByProvince($province)
    {
        return $this->where('province_name', $province)
            ->orderBy('city_name', 'ASC')
            ->findAll();
    }

    // Get distinct provinces
    public function getProvinces()
    {
        return $this->select('province_name')
            ->distinct()
            ->orderBy('province_name', 'ASC')
            ->findAll();
    }

    // Get regions
    public function getRegions()
    {
        return $this->select('region')
            ->distinct()
            ->orderBy('region', 'ASC')
            ->findAll();
    }
}