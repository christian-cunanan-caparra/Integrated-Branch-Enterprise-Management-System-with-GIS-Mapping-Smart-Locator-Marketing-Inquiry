<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'region', 'branch_name', 'coverage', 'contact_person', 
        'contact_number', 'landline', 'email', 'address', 
        'latitude', 'longitude', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Search branches with nearby recommendations
    public function searchBranches($query)
    {
        if (empty($query)) {
            return [
                'branches' => $this->where('status', 'active')->findAll(),
                'isNearby' => false,
                'message' => ''
            ];
        }

        // First try exact matches
        $exactBranches = $this->where('status', 'active')
            ->groupStart()
                ->like('branch_name', $query)
                ->orLike('coverage', $query)
                ->orLike('region', $query)
                ->orLike('address', $query)
            ->groupEnd()
            ->findAll();

        // If exact matches found, return them
        if (!empty($exactBranches)) {
            return [
                'branches' => $exactBranches,
                'isNearby' => false,
                'message' => ''
            ];
        }

        // No exact matches, find nearby branches
        return $this->findNearbyBranches($query);
    }

    // Find nearby branches based on location keywords
    public function findNearbyBranches($query)
    {
        // Comprehensive location mapping for all provinces and regions in the Philippines
        $locationMap = [
            // LUZON - Regions I, II, III, IV-A, IV-B, V, CAR, NCR
            
            // Aurora - Search for Aurora province
            'aurora' => [
                'branches' => ['Quezon Province Branch', 'Cabanatuan Branch', 'Isabela Branch'],
                'message' => 'No branches in Aurora. Here are the nearest branches in nearby provinces (Quezon, Nueva Ecija, Isabela):'
            ],
            
            // Cagayan Valley - Region II
            'cagayan' => [
                'branches' => ['Isabela Branch', 'Cabanatuan Branch', 'La Union Branch'],
                'message' => 'No branches in Cagayan. Here are the nearest branches in nearby provinces (Isabela, Nueva Ecija, La Union):'
            ],
            'cagayan valley' => [
                'branches' => ['Isabela Branch', 'Cabanatuan Branch'],
                'message' => 'Showing branches in Cagayan Valley region:'
            ],
            'tuguegarao' => [
                'branches' => ['Isabela Branch', 'Cabanatuan Branch'],
                'message' => 'No branches in Tuguegarao. Here are the nearest branches:'
            ],
            
            // Ilocos Region - Region I
            'ilocos' => [
                'branches' => ['La Union Branch', 'Laoag Branch', 'Pangasinan Branch'],
                'message' => 'Showing branches in Ilocos Region:'
            ],
            'ilocos norte' => [
                'branches' => ['Laoag Branch', 'La Union Branch'],
                'message' => 'No branches in Ilocos Norte. Here are the nearest branches (Laoag, La Union):'
            ],
            'ilocos sur' => [
                'branches' => ['La Union Branch', 'Laoag Branch'],
                'message' => 'No branches in Ilocos Sur. Here are the nearest branches (La Union, Laoag):'
            ],
            'vigan' => [
                'branches' => ['La Union Branch', 'Laoag Branch'],
                'message' => 'No branches in Vigan. Here are the nearest branches:'
            ],
            
            // Cordillera Administrative Region (CAR)
            'cordillera' => [
                'branches' => ['La Union Branch', 'Pangasinan Branch', 'Isabela Branch'],
                'message' => 'Showing branches near Cordillera Region:'
            ],
            'baguio' => [
                'branches' => ['La Union Branch', 'Pangasinan Branch'],
                'message' => 'No branches in Baguio. Here are the nearest branches (La Union, Pangasinan):'
            ],
            'benguet' => [
                'branches' => ['La Union Branch', 'Pangasinan Branch'],
                'message' => 'No branches in Benguet. Here are the nearest branches (La Union, Pangasinan):'
            ],
            
            // Central Luzon - Region III
            'central luzon' => [
                'branches' => ['Pampanga Branch', 'Bulacan Branch', 'Tarlac Branch', 'Cabanatuan Branch', 'Zambales Branch'],
                'message' => 'Showing branches in Central Luzon:'
            ],
            'pampanga' => [
                'branches' => ['Pampanga Branch', 'Bulacan Branch', 'Tarlac Branch'],
                'message' => 'Showing branches in Pampanga and nearby areas:'
            ],
            'bulacan' => [
                'branches' => ['Bulacan Branch', 'Valenzuela Branch', 'Manila Branch'],
                'message' => 'Showing branches in Bulacan and nearby areas:'
            ],
            'tarlac' => [
                'branches' => ['Tarlac Branch', 'Pampanga Branch', 'Cabanatuan Branch'],
                'message' => 'Showing branches in Tarlac and nearby areas:'
            ],
            'zambales' => [
                'branches' => ['Zambales Branch', 'Pampanga Branch', 'Bataan Branch'],
                'message' => 'Showing branches in Zambales and nearby areas:'
            ],
            'bataan' => [
                'branches' => ['Bataan Branch', 'Zambales Branch', 'Pampanga Branch'],
                'message' => 'Showing branches in Bataan and nearby areas:'
            ],
            'nueva ecija' => [
                'branches' => ['Cabanatuan Branch', 'Isabela Branch', 'Tarlac Branch'],
                'message' => 'Showing branches in Nueva Ecija and nearby areas:'
            ],
            'cabanatuan' => [
                'branches' => ['Cabanatuan Branch', 'Tarlac Branch', 'Pampanga Branch'],
                'message' => 'Showing branches in Cabanatuan and nearby areas:'
            ],
            
            // CALABARZON - Region IV-A
            'calabarzon' => [
                'branches' => ['Laguna Branch', 'Cavite Branch', 'Batangas Branch', 'Quezon Province Branch', 'Antipolo Branch'],
                'message' => 'Showing branches in CALABARZON region:'
            ],
            'laguna' => [
                'branches' => ['Laguna Branch', 'Cavite Branch', 'Batangas Branch'],
                'message' => 'Showing branches in Laguna and nearby areas:'
            ],
            'cavite' => [
                'branches' => ['Cavite Branch', 'Laguna Branch', 'Batangas Branch'],
                'message' => 'Showing branches in Cavite and nearby areas:'
            ],
            'batangas' => [
                'branches' => ['Batangas Branch', 'Laguna Branch', 'Cavite Branch'],
                'message' => 'Showing branches in Batangas and nearby areas:'
            ],
            'quezon' => [
                'branches' => ['Quezon Province Branch', 'Laguna Branch', 'Cavite Branch', 'Batangas Branch'],
                'message' => 'Showing branches in Quezon Province and nearby areas:'
            ],
            'quezon province' => [
                'branches' => ['Quezon Province Branch', 'Laguna Branch', 'Cavite Branch'],
                'message' => 'Showing branches in Quezon Province and nearby areas:'
            ],
            'rizal' => [
                'branches' => ['Antipolo Branch', 'Marikina Branch', 'Pasig Branch'],
                'message' => 'Showing branches in Rizal and nearby areas:'
            ],
            'antipolo' => [
                'branches' => ['Antipolo Branch', 'Marikina Branch', 'Pasig Branch'],
                'message' => 'Showing branches in Antipolo and nearby areas:'
            ],
            
            // MIMAROPA - Region IV-B
            'mimaropa' => [
                'branches' => ['Quezon Province Branch', 'Batangas Branch', 'Laguna Branch'],
                'message' => 'Showing branches near MIMAROPA region:'
            ],
            'mindoro' => [
                'branches' => ['Quezon Province Branch', 'Batangas Branch'],
                'message' => 'No branches in Mindoro. Here are the nearest branches (Quezon, Batangas):'
            ],
            'palawan' => [
                'branches' => ['Quezon Province Branch', 'Batangas Branch'],
                'message' => 'No branches in Palawan. Here are the nearest branches (Quezon, Batangas):'
            ],
            'marinduque' => [
                'branches' => ['Quezon Province Branch', 'Batangas Branch'],
                'message' => 'No branches in Marinduque. Here are the nearest branches (Quezon, Batangas):'
            ],
            'romblon' => [
                'branches' => ['Quezon Province Branch', 'Batangas Branch'],
                'message' => 'No branches in Romblon. Here are the nearest branches (Quezon, Batangas):'
            ],
            
            // Bicol Region - Region V
            'bicol' => [
                'branches' => ['Legazpi Branch', 'Naga Branch', 'Quezon Province Branch'],
                'message' => 'Showing branches in Bicol Region:'
            ],
            'bicol region' => [
                'branches' => ['Legazpi Branch', 'Naga Branch'],
                'message' => 'Showing branches in Bicol Region:'
            ],
            'albay' => [
                'branches' => ['Legazpi Branch', 'Naga Branch'],
                'message' => 'Showing branches in Albay and nearby areas:'
            ],
            'legazpi' => [
                'branches' => ['Legazpi Branch', 'Naga Branch'],
                'message' => 'Showing branches in Legazpi and nearby areas:'
            ],
            'naga' => [
                'branches' => ['Naga Branch', 'Legazpi Branch'],
                'message' => 'Showing branches in Naga and nearby areas:'
            ],
            'cam sur' => [
                'branches' => ['Naga Branch', 'Legazpi Branch'],
                'message' => 'Showing branches in Camarines Sur and nearby areas:'
            ],
            'cam norte' => [
                'branches' => ['Naga Branch', 'Quezon Province Branch'],
                'message' => 'Showing branches in Camarines Norte and nearby areas:'
            ],
            'sorsogon' => [
                'branches' => ['Legazpi Branch', 'Naga Branch'],
                'message' => 'Showing branches in Sorsogon and nearby areas:'
            ],
            
            // Metro Manila - NCR
            'metro manila' => [
                'branches' => ['Antipolo Branch', 'Pasig Branch', 'Valenzuela Branch', 'Manila Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Metro Manila and nearby areas:'
            ],
            'ncr' => [
                'branches' => ['Antipolo Branch', 'Pasig Branch', 'Valenzuela Branch', 'Manila Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Metro Manila:'
            ],
            'manila' => [
                'branches' => ['Manila Branch', 'Pasig Branch', 'Valenzuela Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Manila and nearby areas:'
            ],
            'quezon city' => [
                'branches' => ['Valenzuela Branch', 'Manila Branch', 'Pasig Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Quezon City and nearby areas:'
            ],
            'pasig' => [
                'branches' => ['Pasig Branch', 'Marikina Branch', 'Manila Branch'],
                'message' => 'Showing branches in Pasig and nearby areas:'
            ],
            'makati' => [
                'branches' => ['Pasig Branch', 'Manila Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Makati and nearby areas:'
            ],
            'taguig' => [
                'branches' => ['Pasig Branch', 'Manila Branch', 'Marikina Branch'],
                'message' => 'Showing branches in Taguig and nearby areas:'
            ],
            
            // VISAYAS
            
            // Western Visayas - Region VI
            'western visayas' => [
                'branches' => ['Ilo Ilo Branch', 'Bacolod Branch'],
                'message' => 'Showing branches in Western Visayas:'
            ],
            'iloilo' => [
                'branches' => ['Ilo Ilo Branch', 'Bacolod Branch'],
                'message' => 'Showing branches in Iloilo and nearby areas:'
            ],
            'bacolod' => [
                'branches' => ['Bacolod Branch', 'Ilo Ilo Branch'],
                'message' => 'Showing branches in Bacolod and nearby areas:'
            ],
            'negros occidental' => [
                'branches' => ['Bacolod Branch', 'Ilo Ilo Branch'],
                'message' => 'Showing branches in Negros Occidental and nearby areas:'
            ],
            'guimaras' => [
                'branches' => ['Ilo Ilo Branch', 'Bacolod Branch'],
                'message' => 'Showing branches near Guimaras:'
            ],
            
            // Central Visayas - Region VII
            'central visayas' => [
                'branches' => ['Cebu Branch', 'Northern Cebu Area', 'Dumaguete Branch', 'Bacolod Branch'],
                'message' => 'Showing branches in Central Visayas:'
            ],
            'cebu' => [
                'branches' => ['Cebu Branch', 'Northern Cebu Area', 'Dumaguete Branch'],
                'message' => 'Showing branches in Cebu and nearby areas:'
            ],
            'cebu city' => [
                'branches' => ['Cebu Branch', 'Northern Cebu Area'],
                'message' => 'Showing branches in Cebu City and nearby areas:'
            ],
            'bohol' => [
                'branches' => ['Cebu Branch', 'Dumaguete Branch'],
                'message' => 'Showing branches near Bohol:'
            ],
            'negros oriental' => [
                'branches' => ['Dumaguete Branch', 'Cebu Branch'],
                'message' => 'Showing branches in Negros Oriental and nearby areas:'
            ],
            'dumaguete' => [
                'branches' => ['Dumaguete Branch', 'Cebu Branch'],
                'message' => 'Showing branches in Dumaguete and nearby areas:'
            ],
            'siquijor' => [
                'branches' => ['Dumaguete Branch', 'Cebu Branch'],
                'message' => 'Showing branches near Siquijor:'
            ],
            
            // Eastern Visayas - Region VIII
            'eastern visayas' => [
                'branches' => ['Samar Branch', 'Cebu Branch', 'Northern Cebu Area'],
                'message' => 'Showing branches in Eastern Visayas:'
            ],
            'samar' => [
                'branches' => ['Samar Branch', 'Northern Cebu Area', 'Cebu Branch'],
                'message' => 'Showing branches in Samar and nearby areas:'
            ],
            'leyte' => [
                'branches' => ['Samar Branch', 'Cebu Branch'],
                'message' => 'Showing branches near Leyte:'
            ],
            'tacloban' => [
                'branches' => ['Samar Branch', 'Cebu Branch'],
                'message' => 'Showing branches near Tacloban:'
            ],
            'biliran' => [
                'branches' => ['Samar Branch', 'Cebu Branch'],
                'message' => 'Showing branches near Biliran:'
            ],
            
            // MINDANAO
            
            // Zamboanga Peninsula - Region IX
            'zamboanga' => [
                'branches' => ['Zamboanga Branch', 'Iligan Branch', 'Marawi Branch'],
                'message' => 'Showing branches in Zamboanga Peninsula:'
            ],
            'zamboanga city' => [
                'branches' => ['Zamboanga Branch', 'Iligan Branch'],
                'message' => 'Showing branches in Zamboanga City and nearby areas:'
            ],
            'zamboanga del sur' => [
                'branches' => ['Zamboanga Branch', 'Iligan Branch'],
                'message' => 'Showing branches in Zamboanga del Sur and nearby areas:'
            ],
            'zamboanga del norte' => [
                'branches' => ['Zamboanga Branch', 'Iligan Branch'],
                'message' => 'Showing branches in Zamboanga del Norte and nearby areas:'
            ],
            'pagadian' => [
                'branches' => ['Zamboanga Branch', 'Iligan Branch'],
                'message' => 'Showing branches in Pagadian and nearby areas:'
            ],
            
            // Northern Mindanao - Region X
            'northern mindanao' => [
                'branches' => ['Cagayan De Oro Branch', 'Iligan Branch', 'Marawi Branch', 'Butuan Branch'],
                'message' => 'Showing branches in Northern Mindanao:'
            ],
            'cagayan de oro' => [
                'branches' => ['Cagayan De Oro Branch', 'Iligan Branch', 'Marawi Branch'],
                'message' => 'Showing branches in Cagayan De Oro and nearby areas:'
            ],
            'cdo' => [
                'branches' => ['Cagayan De Oro Branch', 'Iligan Branch', 'Marawi Branch'],
                'message' => 'Showing branches in CDO and nearby areas:'
            ],
            'iligan' => [
                'branches' => ['Iligan Branch', 'Cagayan De Oro Branch', 'Marawi Branch'],
                'message' => 'Showing branches in Iligan and nearby areas:'
            ],
            'marawi' => [
                'branches' => ['Marawi Branch', 'Iligan Branch', 'Cagayan De Oro Branch'],
                'message' => 'Showing branches in Marawi and nearby areas:'
            ],
            'bukidnon' => [
                'branches' => ['Cagayan De Oro Branch', 'Iligan Branch'],
                'message' => 'Showing branches in Bukidnon and nearby areas:'
            ],
            'camiguin' => [
                'branches' => ['Cagayan De Oro Branch', 'Iligan Branch'],
                'message' => 'Showing branches near Camiguin:'
            ],
            
            // Davao Region - Region XI
            'davao' => [
                'branches' => ['Tagum Branch', 'Gensan Branch', 'Cotabato Branch', 'Butuan Branch'],
                'message' => 'Showing branches in Davao Region:'
            ],
            'davao city' => [
                'branches' => ['Tagum Branch', 'Gensan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in Davao City and nearby areas:'
            ],
            'davao del sur' => [
                'branches' => ['Tagum Branch', 'Gensan Branch'],
                'message' => 'Showing branches in Davao del Sur and nearby areas:'
            ],
            'davao del norte' => [
                'branches' => ['Tagum Branch', 'Gensan Branch'],
                'message' => 'Showing branches in Davao del Norte and nearby areas:'
            ],
            'davao oriental' => [
                'branches' => ['Tagum Branch', 'Gensan Branch'],
                'message' => 'Showing branches in Davao Oriental and nearby areas:'
            ],
            'davao de oro' => [
                'branches' => ['Tagum Branch', 'Butuan Branch'],
                'message' => 'Showing branches in Davao de Oro and nearby areas:'
            ],
            'tagum' => [
                'branches' => ['Tagum Branch', 'Butuan Branch', 'Gensan Branch'],
                'message' => 'Showing branches in Tagum and nearby areas:'
            ],
            
            // SOCCSKSARGEN - Region XII
            'soccsksargen' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch', 'Tagum Branch'],
                'message' => 'Showing branches in SOCCSKSARGEN region:'
            ],
            'gensan' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch', 'Tagum Branch'],
                'message' => 'Showing branches in General Santos and nearby areas:'
            ],
            'general santos' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in General Santos and nearby areas:'
            ],
            'south cotabato' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in South Cotabato and nearby areas:'
            ],
            'sultan kudarat' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in Sultan Kudarat and nearby areas:'
            ],
            'sarangani' => [
                'branches' => ['Gensan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in Sarangani and nearby areas:'
            ],
            'cotabato' => [
                'branches' => ['Cotabato Branch', 'Gensan Branch', 'Tagum Branch'],
                'message' => 'Showing branches in Cotabato and nearby areas:'
            ],
            'north cotabato' => [
                'branches' => ['Cotabato Branch', 'Gensan Branch'],
                'message' => 'Showing branches in North Cotabato and nearby areas:'
            ],
            
            // Caraga - Region XIII
            'caraga' => [
                'branches' => ['Butuan Branch', 'Tagum Branch', 'Cagayan De Oro Branch'],
                'message' => 'Showing branches in Caraga Region:'
            ],
            'butuan' => [
                'branches' => ['Butuan Branch', 'Tagum Branch', 'Cagayan De Oro Branch'],
                'message' => 'Showing branches in Butuan and nearby areas:'
            ],
            'surigao' => [
                'branches' => ['Butuan Branch', 'Tagum Branch'],
                'message' => 'Showing branches in Surigao and nearby areas:'
            ],
            'agusan' => [
                'branches' => ['Butuan Branch', 'Tagum Branch'],
                'message' => 'Showing branches in Agusan and nearby areas:'
            ],
            'bislig' => [
                'branches' => ['Butuan Branch', 'Tagum Branch'],
                'message' => 'Showing branches in Bislig and nearby areas:'
            ],
            
            // Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)
            'barmm' => [
                'branches' => ['Cotabato Branch', 'Marawi Branch', 'Iligan Branch', 'Zamboanga Branch'],
                'message' => 'Showing branches in BARMM region:'
            ],
            'lanao del sur' => [
                'branches' => ['Marawi Branch', 'Iligan Branch', 'Cotabato Branch'],
                'message' => 'Showing branches in Lanao del Sur and nearby areas:'
            ],
            'maguindanao' => [
                'branches' => ['Cotabato Branch', 'Marawi Branch'],
                'message' => 'Showing branches in Maguindanao and nearby areas:'
            ],
            'basilan' => [
                'branches' => ['Zamboanga Branch', 'Cotabato Branch'],
                'message' => 'Showing branches near Basilan:'
            ],
            'sulu' => [
                'branches' => ['Zamboanga Branch', 'Cotabato Branch'],
                'message' => 'Showing branches near Sulu:'
            ],
            'tawi tawi' => [
                'branches' => ['Zamboanga Branch', 'Cotabato Branch'],
                'message' => 'Showing branches near Tawi-Tawi:'
            ],
        ];

        // Check if query matches any location keywords
        $searchQuery = strtolower(trim($query));
        $nearbyBranches = [];
        $message = 'Showing nearby branches for your search:';
        $isNearby = false;

        // First, try exact match in location map
        foreach ($locationMap as $key => $data) {
            if (stripos($searchQuery, $key) !== false) {
                $branchNames = $data['branches'];
                $message = $data['message'];
                $nearbyBranches = $this->where('status', 'active')
                    ->whereIn('branch_name', $branchNames)
                    ->findAll();
                $isNearby = true;
                break;
            }
        }

        // If no mapping found, try fuzzy matching with branch data
        if (empty($nearbyBranches)) {
            $allBranches = $this->where('status', 'active')->findAll();
            $matchedBranches = [];
            $searchWords = explode(' ', $searchQuery);
            
            foreach ($allBranches as $branch) {
                $coverage = strtolower($branch['coverage'] ?? '');
                $branchName = strtolower($branch['branch_name'] ?? '');
                $region = strtolower($branch['region'] ?? '');
                $address = strtolower($branch['address'] ?? '');
                
                // Check each word in the search query
                foreach ($searchWords as $word) {
                    if (strlen($word) > 2 && (
                        stripos($coverage, $word) !== false || 
                        stripos($branchName, $word) !== false ||
                        stripos($region, $word) !== false ||
                        stripos($address, $word) !== false
                    )) {
                        $matchedBranches[] = $branch;
                        break;
                    }
                }
            }
            
            // Remove duplicates
            $uniqueBranches = [];
            $seenIds = [];
            foreach ($matchedBranches as $branch) {
                if (!in_array($branch['id'], $seenIds)) {
                    $seenIds[] = $branch['id'];
                    $uniqueBranches[] = $branch;
                }
            }
            
            if (!empty($uniqueBranches)) {
                $nearbyBranches = array_slice($uniqueBranches, 0, 10);
                $message = 'No exact matches found. Here are the nearest branches:';
                $isNearby = true;
            }
        }

        // If still no results, return all branches as fallback
        if (empty($nearbyBranches)) {
            $nearbyBranches = $this->where('status', 'active')->limit(10)->findAll();
            $message = 'Showing all available branches:';
            $isNearby = true;
        }

        return [
            'branches' => $nearbyBranches,
            'message' => $message,
            'isNearby' => $isNearby
        ];
    }

    // Get branches with coordinates
    public function getBranchesWithCoordinates()
    {
        return $this->where('status', 'active')
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->findAll();
    }

    // Get branch statistics
    public function getStatistics()
    {
        $total = $this->where('status', 'active')->countAllResults();
        
        $regions = $this->select('region, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('region')
            ->findAll();
        
        $stats = [
            'total' => $total,
            'regions' => []
        ];
        
        foreach ($regions as $region) {
            $stats['regions'][$region['region']] = $region['count'];
        }
        
        return $stats;
    }
}