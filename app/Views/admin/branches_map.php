<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Branch Location Map' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.Default.css" />
    
    <style>
        :root {
            --primary: #0f3b5e;
            --primary-light: #1a5276;
            --primary-dark: #0a2647;
            --secondary: #e8b931;
            --secondary-light: #f5d76e;
            --accent: #2e86de;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light-bg: #f0f4f8;
            --card-shadow: 0 8px 32px rgba(15, 59, 94, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: var(--light-bg); 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1a202c;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
        }
        .sidebar-brand {
            padding: 28px 20px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }
        .sidebar-brand .logo-icon {
            font-size: 2rem;
            color: var(--secondary);
            display: block;
            margin-bottom: 8px;
        }
        .sidebar-brand h5 {
            color: white;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }
        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 0.7rem;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: 20px 12px; }
        .sidebar-nav .nav-label {
            color: rgba(255,255,255,0.3);
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 15px 8px;
            font-weight: 600;
        }
        .sidebar-nav a {
            color: rgba(255,255,255,0.65);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: var(--transition);
            border-radius: 10px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .sidebar-nav a i { margin-right: 14px; width: 22px; font-size: 1.1rem; }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(4px);
        }
        .sidebar-nav a.active {
            background: rgba(232, 185, 49, 0.15);
            color: var(--secondary);
            box-shadow: inset 3px 0 0 var(--secondary);
        }
        .sidebar-nav a.active i { color: var(--secondary); }
        .sidebar-nav a.logout {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: 20px;
            padding-top: 20px;
            color: rgba(255,255,255,0.5);
        }
        .sidebar-nav a.logout:hover {
            color: var(--danger);
            background: rgba(231, 76, 60, 0.1);
        }

        /* Main Content */
        .main-content { padding: 25px 35px; }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 18px 28px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 28px;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .top-bar h4 {
            margin: 0;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.3rem;
        }
        .top-bar h4 i { 
            color: var(--secondary); 
            margin-right: 12px;
            background: rgba(232, 185, 49, 0.12);
            padding: 8px;
            border-radius: 10px;
            font-size: 1.2rem;
        }
        .top-bar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        .top-bar .status-badge {
            background: #d4edda;
            color: #155724;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .top-bar .status-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #28a745;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            padding: 18px 22px;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            cursor: pointer;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 14px 14px 0 0;
        }
        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, var(--primary), var(--accent)); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, var(--secondary), var(--warning)); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, var(--success), #2ecc71); }
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #9b59b6, #8e44ad); }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(15, 59, 94, 0.12);
        }
        .stat-card .stat-icon { font-size: 1.6rem; margin-bottom: 6px; display: block; }
        .stat-card .stat-number { font-size: 2rem; font-weight: 800; color: var(--primary-dark); line-height: 1.2; }
        .stat-card .stat-label { color: #718096; font-size: 0.8rem; font-weight: 500; }

        /* Search Box */
        .search-box .input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background: white;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        .search-box .input-group:focus-within {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(232, 185, 49, 0.15);
        }
        .search-box .form-control {
            border: none;
            padding: 14px 24px;
            font-size: 0.95rem;
            background: transparent;
        }
        .search-box .form-control:focus { box-shadow: none; }
        .search-box .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 28px;
            font-weight: 600;
            transition: var(--transition);
        }
        .search-box .btn:hover {
            background: var(--primary-dark);
            transform: scale(1.02);
        }
        .search-box .btn-clear {
            background: transparent;
            color: #a0aec0;
            border: none;
            padding: 14px 20px;
        }
        .search-box .btn-clear:hover { color: var(--danger); }

        /* Map */
        .map-container {
            background: white;
            border-radius: 16px;
            padding: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
        }
        #map {
            height: 520px;
            border-radius: 12px;
            width: 100%;
        }

        /* Branch List */
        .branch-list {
            max-height: 480px;
            overflow-y: auto;
            padding-right: 6px;
        }
        .branch-list::-webkit-scrollbar { width: 4px; }
        .branch-list::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .branch-list::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .branch-item {
            background: white;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.04);
            border-left-width: 4px;
            border-left-color: var(--primary);
        }
        .branch-item:hover {
            transform: translateX(6px);
            box-shadow: 0 8px 24px rgba(15, 59, 94, 0.1);
            border-color: var(--secondary);
        }
        .branch-item .name {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.95rem;
        }
        .branch-item .name i { color: var(--secondary); margin-right: 6px; }
        .branch-item .region {
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 500;
        }
        .branch-item .contact {
            font-size: 0.8rem;
            color: #4a5568;
            line-height: 1.6;
        }
        .branch-item .contact i {
            width: 18px;
            color: var(--primary);
            font-size: 0.75rem;
        }
        .branch-item .nearby-tag {
            background: rgba(39, 174, 96, 0.12);
            color: var(--success);
            padding: 2px 12px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .nearby-alert {
            background: linear-gradient(135deg, #fff9e6, #fff3cd);
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nearby-alert i { color: var(--warning); font-size: 1.3rem; }
        .nearby-alert span { color: #856404; font-weight: 500; font-size: 0.9rem; }

        /* Leaflet Popup Custom */
        .custom-popup .popup-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1rem;
        }
        .custom-popup .popup-region {
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 500;
        }
        .custom-popup .popup-detail {
            font-size: 0.8rem;
            color: #4a5568;
            margin-top: 4px;
        }
        .custom-popup .popup-detail i {
            width: 18px;
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { min-height: auto; position: relative; }
            .main-content { padding: 15px; }
            .top-bar { flex-direction: column; gap: 12px; align-items: stretch; text-align: center; }
            #map { height: 350px; }
        }
        @media (max-width: 576px) {
            .stat-card .stat-number { font-size: 1.4rem; }
            .branch-item { padding: 12px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="sidebar-brand">
                    <span class="logo-icon"><i class="bi bi-shield-fill-check"></i></span>
                    <h5>Admin Portal</h5>
                    <small>Government Management</small>
                </div>
                <div class="sidebar-nav">
                    <div class="nav-label">Main Navigation</div>
                    <a href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                  
                    <a href="#" class="active">
                        <i class="bi bi-map"></i> Branch Map
                    </a>
                                      <a href="<?= base_url('admin/products') ?>">
    <i class="bi bi-box-seam"></i> Products
</a>
  <a href="<?= base_url('admin/inquiries') ?>">
                        <i class="bi bi-chat-dots"></i> Inquiries
                    </a>
                    <a href="#">
                        <i class="bi bi-people"></i> Users
                    </a>
                    
                    <a href="<?= base_url('admin/settings') ?>">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                    <a href="<?= base_url('admin/logout') ?>" class="logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Top Bar -->
                <div class="top-bar d-flex justify-content-between align-items-center flex-wrap">
                    <h4><i class="bi bi-map"></i> Branch Location Map</h4>
                    <div class="d-flex align-items-center gap-3">
                        <span class="status-badge">
                            <span class="dot"></span> Online
                        </span>
                        <span class="user-avatar">
                            <?= strtoupper(substr($username ?? 'Admin', 0, 1)) ?>
                        </span>
                        <strong><?= $username ?? 'Admin' ?></strong>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="filterBranches('all')">
                            <span class="stat-icon" style="color: var(--primary);"><i class="bi bi-building"></i></span>
                            <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                            <div class="stat-label">Total Branches</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="filterBranches('METRO MANILA')">
                            <span class="stat-icon" style="color: var(--secondary);"><i class="bi bi-geo-alt-fill"></i></span>
                            <div class="stat-number"><?= $stats['regions']['METRO MANILA'] ?? 0 ?></div>
                            <div class="stat-label">Metro Manila</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="filterBranches('LUZON')">
                            <span class="stat-icon" style="color: var(--warning);"><i class="bi bi-tree-fill"></i></span>
                            <div class="stat-number"><?= $stats['regions']['LUZON'] ?? 0 ?></div>
                            <div class="stat-label">Luzon</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="filterBranches('VISMIN')">
                            <span class="stat-icon" style="color: #9b59b6;"><i class="bi bi-water"></i></span>
                            <div class="stat-number"><?= $stats['regions']['VISMIN'] ?? 0 ?></div>
                            <div class="stat-label">VisMin</div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search for branch, city, or address..." 
                                       autocomplete="off">
                                <button class="btn" id="searchBtn" type="button">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <button class="btn btn-clear" id="clearSearch" type="button" style="display:none;">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end d-flex align-items-center justify-content-end gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="resetMap()">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="zoomToAll()">
                            <i class="bi bi-zoom-in"></i> Show All
                        </button>
                    </div>
                </div>

                <!-- Map and List -->
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="map-container">
                            <div id="map"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="map-container" style="height: 100%;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul" style="color: var(--secondary);"></i> Branches</h6>
                                <span class="badge" id="branchCount" style="background: var(--primary);"><?= count($branches ?? []) ?></span>
                            </div>
                            
                            <div id="nearbyAlert" style="display: none;" class="nearby-alert">
                                <i class="bi bi-compass"></i>
                                <span id="nearbyMessage">Showing nearby branches</span>
                            </div>
                            
                            <div class="branch-list" id="branchList">
                                <?php if (!empty($branches)): ?>
                                    <?php foreach ($branches as $branch): ?>
                                        <div class="branch-item" data-id="<?= $branch['id'] ?>" 
                                             data-lat="<?= $branch['latitude'] ?? 0 ?>" 
                                             data-lng="<?= $branch['longitude'] ?? 0 ?>"
                                             onclick="focusBranch(<?= $branch['id'] ?>, <?= $branch['latitude'] ?? 0 ?>, <?= $branch['longitude'] ?? 0 ?>)">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="name">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                        <?= esc($branch['branch_name'] ?? 'N/A') ?>
                                                    </div>
                                                    <div class="region"><?= esc($branch['region'] ?? 'N/A') ?></div>
                                                </div>
                                                <?php if (!empty($branch['coverage'])): ?>
                                                    <span class="nearby-tag">
                                                        <i class="bi bi-map"></i> <?= substr(esc($branch['coverage']), 0, 12) ?>...
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="contact mt-1">
                                                <?php if (!empty($branch['contact_person'])): ?>
                                                    <div><i class="bi bi-person"></i> <?= esc($branch['contact_person']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($branch['contact_number'])): ?>
                                                    <div><i class="bi bi-phone"></i> <?= esc($branch['contact_number']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-building" style="font-size: 2.5rem; color: #cbd5e0;"></i>
                                        <p class="mt-2 text-muted">No branches found</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.0/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Initialize map
        let map;
        let markers = [];
        let markerCluster;
        let allBranches = <?= json_encode($branchesWithCoords ?? []) ?>;
        let currentMarkers = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            map = L.map('map').setView([12.8797, 121.7740], 6);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      
            }).addTo(map);

            // Check if we have branches with coordinates
            if (allBranches && allBranches.length > 0) {
                // Add all branches to map
                addBranchesToMap(allBranches);
            } else {
                // Show message if no branches with coordinates
                const branchList = document.getElementById('branchList');
                if (branchList) {
                    branchList.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-geo-alt" style="font-size: 3rem; color: #cbd5e0;"></i>
                            <p class="mt-2 text-muted">No location data available</p>
                        </div>
                    `;
                }
                
                // Add a simple marker at center of Philippines
                L.marker([12.8797, 121.7740]).addTo(map)
                    .bindPopup('Philippines<br><small>Add branch coordinates to see locations</small>')
                    .openPopup();
            }

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const clearSearch = document.getElementById('clearSearch');

            function performSearch() {
                const query = searchInput.value.trim();
                
                if (query.length === 0) {
                    resetMap();
                    return;
                }

                // Show loading state
                const branchList = document.getElementById('branchList');
                if (branchList) {
                    branchList.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Searching branches...</p>
                        </div>
                    `;
                }

                fetch(`<?= base_url('branch/search') ?>?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Search response:', data);
                        
                        if (data.success) {
                            // Clear existing markers
                            clearMarkers();
                            
                            if (data.branches && data.branches.length > 0) {
                                // Show nearby alert if applicable
                                const nearbyAlert = document.getElementById('nearbyAlert');
                                const nearbyMessage = document.getElementById('nearbyMessage');
                                
                                if (data.isNearby && data.nearbyMessage) {
                                    if (nearbyAlert) {
                                        nearbyAlert.style.display = 'flex';
                                        nearbyMessage.textContent = data.nearbyMessage;
                                    }
                                } else {
                                    if (nearbyAlert) nearbyAlert.style.display = 'none';
                                }
                                
                                // Add branches to map
                                addBranchesToMap(data.branches);
                                
                                // Update branch list
                                updateBranchList(data.branches, data.isNearby || false);
                                
                                // Update count
                                const branchCount = document.getElementById('branchCount');
                                if (branchCount) branchCount.textContent = data.branches.length;
                                
                                // Zoom to fit all markers
                                zoomToFit();
                                
                                if (clearSearch) clearSearch.style.display = 'block';
                            } else {
                                // No results
                                const nearbyAlert = document.getElementById('nearbyAlert');
                                if (nearbyAlert) nearbyAlert.style.display = 'none';
                                
                                const branchList = document.getElementById('branchList');
                                if (branchList) {
                                    branchList.innerHTML = `
                                        <div class="text-center py-4">
                                            <i class="bi bi-search" style="font-size: 3rem; color: #cbd5e0;"></i>
                                            <p class="mt-2 text-muted">No branches found for "${query}"</p>
                                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="resetMap()">
                                                <i class="bi bi-arrow-counterclockwise"></i> Show all branches
                                            </button>
                                        </div>
                                    `;
                                }
                                
                                const branchCount = document.getElementById('branchCount');
                                if (branchCount) branchCount.textContent = '0';
                            }
                        } else {
                            // Handle error
                            const branchList = document.getElementById('branchList');
                            if (branchList) {
                                branchList.innerHTML = `
                                    <div class="text-center py-4 text-danger">
                                        <i class="bi bi-exclamation-circle" style="font-size: 2rem;"></i>
                                        <p class="mt-2">${data.error || 'An error occurred. Please try again.'}</p>
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const branchList = document.getElementById('branchList');
                        if (branchList) {
                            branchList.innerHTML = `
                                <div class="text-center py-4 text-danger">
                                    <i class="bi bi-exclamation-circle" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Error searching branches. Please try again.</p>
                                    <small class="text-muted">${error.message || 'Network error'}</small>
                                </div>
                            `;
                        }
                    });
            }

            if (searchBtn) {
                searchBtn.addEventListener('click', performSearch);
            }
            
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    }
                });
            }

            if (clearSearch) {
                clearSearch.addEventListener('click', resetMap);
            }

            // Real-time search with debounce
            let debounceTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        if (searchInput.value.trim().length >= 2) {
                            performSearch();
                        } else if (searchInput.value.trim().length === 0) {
                            resetMap();
                        }
                    }, 500);
                });
            }
        });

        function addBranchesToMap(branches) {
            if (!branches || branches.length === 0) return;
            
            // Create marker cluster group
            markerCluster = L.markerClusterGroup({
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: true,
                zoomToBoundsOnClick: true,
                iconCreateFunction: function(cluster) {
                    return L.divIcon({
                        html: `<div style="background: #0f3b5e; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 4px 16px rgba(15, 59, 94, 0.3); border: 2px solid #e8b931;">${cluster.getChildCount()}</div>`,
                        className: 'marker-cluster-custom',
                        iconSize: L.point(40, 40)
                    });
                }
            });

            branches.forEach(branch => {
                if (branch.latitude && branch.longitude) {
                    // Create custom marker with icon
                    const marker = L.marker([parseFloat(branch.latitude), parseFloat(branch.longitude)], {
                        title: branch.branch_name
                    });

                    // Create popup content
                    const popupContent = `
                        <div class="custom-popup" style="min-width: 200px;">
                            <div class="popup-name">
                                <i class="bi bi-geo-alt-fill" style="color: #e8b931;"></i>
                                ${escapeHtml(branch.branch_name)}
                            </div>
                            <div class="popup-region">
                                <i class="bi bi-tag"></i> ${escapeHtml(branch.region || 'N/A')}
                            </div>
                            <hr style="margin: 6px 0;">
                            ${branch.address ? `<div class="popup-detail"><i class="bi bi-geo-alt"></i> ${escapeHtml(branch.address)}</div>` : ''}
                            ${branch.contact_person ? `<div class="popup-detail"><i class="bi bi-person"></i> ${escapeHtml(branch.contact_person)}</div>` : ''}
                            ${branch.contact_number ? `<div class="popup-detail"><i class="bi bi-phone"></i> ${escapeHtml(branch.contact_number)}</div>` : ''}
                            ${branch.email ? `<div class="popup-detail"><i class="bi bi-envelope"></i> ${escapeHtml(branch.email)}</div>` : ''}
                        </div>
                    `;

                    marker.bindPopup(popupContent, { maxWidth: 300 });
                    
                    // Store branch data in marker
                    marker.branchData = branch;
                    
                    markerCluster.addLayer(marker);
                    currentMarkers.push(marker);
                }
            });

            if (currentMarkers.length > 0) {
                map.addLayer(markerCluster);
            }
        }

        function clearMarkers() {
            if (markerCluster) {
                map.removeLayer(markerCluster);
            }
            currentMarkers = [];
        }

        function resetMap() {
            clearMarkers();
            
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.value = '';
            
            const nearbyAlert = document.getElementById('nearbyAlert');
            if (nearbyAlert) nearbyAlert.style.display = 'none';
            
            const clearSearch = document.getElementById('clearSearch');
            if (clearSearch) clearSearch.style.display = 'none';
            
            if (allBranches && allBranches.length > 0) {
                addBranchesToMap(allBranches);
                updateBranchList(allBranches, false);
                
                const branchCount = document.getElementById('branchCount');
                if (branchCount) branchCount.textContent = allBranches.length;
            } else {
                const branchList = document.getElementById('branchList');
                if (branchList) {
                    branchList.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-geo-alt" style="font-size: 3rem; color: #cbd5e0;"></i>
                            <p class="mt-2 text-muted">No location data available</p>
                        </div>
                    `;
                }
                
                const branchCount = document.getElementById('branchCount');
                if (branchCount) branchCount.textContent = '0';
            }
            
            zoomToFit();
        }

        function updateBranchList(branches, isNearby) {
            const list = document.getElementById('branchList');
            
            if (!branches || branches.length === 0) {
                if (list) {
                    list.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-building" style="font-size: 2rem; color: #cbd5e0;"></i>
                            <p class="mt-2 text-muted">No branches found</p>
                        </div>
                    `;
                }
                return;
            }

            let html = '';
            branches.forEach(branch => {
                html += `
                    <div class="branch-item" data-id="${branch.id}" 
                         data-lat="${branch.latitude || 0}" 
                         data-lng="${branch.longitude || 0}"
                         onclick="focusBranch(${branch.id}, ${branch.latitude || 0}, ${branch.longitude || 0})">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="name">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    ${escapeHtml(branch.branch_name)}
                                </div>
                                <div class="region">${escapeHtml(branch.region || 'N/A')}</div>
                            </div>
                            ${isNearby ? `<span class="nearby-tag"><i class="bi bi-compass"></i> Nearby</span>` : ''}
                            ${branch.coverage ? `<span class="nearby-tag"><i class="bi bi-map"></i> ${escapeHtml(branch.coverage).substring(0, 12)}...</span>` : ''}
                        </div>
                        <div class="contact mt-1">
                            ${branch.contact_person ? `<div><i class="bi bi-person"></i> ${escapeHtml(branch.contact_person)}</div>` : ''}
                            ${branch.contact_number ? `<div><i class="bi bi-phone"></i> ${escapeHtml(branch.contact_number)}</div>` : ''}
                        </div>
                    </div>
                `;
            });

            if (list) list.innerHTML = html;
        }

        function focusBranch(id, lat, lng) {
            if (lat && lng) {
                map.setView([parseFloat(lat), parseFloat(lng)], 15);
                
                // Find and open popup for this marker
                currentMarkers.forEach(marker => {
                    if (marker.branchData && marker.branchData.id === id) {
                        marker.openPopup();
                    }
                });
            }
        }

        function filterBranches(region) {
            if (region === 'all') {
                resetMap();
                return;
            }

            const filtered = allBranches.filter(b => b.region === region);
            if (filtered.length > 0) {
                clearMarkers();
                addBranchesToMap(filtered);
                updateBranchList(filtered, false);
                
                const branchCount = document.getElementById('branchCount');
                if (branchCount) branchCount.textContent = filtered.length;
                
                const searchInput = document.getElementById('searchInput');
                if (searchInput) searchInput.value = region;
                
                const clearSearch = document.getElementById('clearSearch');
                if (clearSearch) clearSearch.style.display = 'block';
                
                zoomToFit();
            }
        }

        function zoomToFit() {
            if (currentMarkers.length > 0) {
                const group = L.featureGroup(currentMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                map.setView([12.8797, 121.7740], 6);
            }
        }

        function zoomToAll() {
            if (currentMarkers.length > 0) {
                zoomToFit();
            } else {
                map.setView([12.8797, 121.7740], 6);
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>