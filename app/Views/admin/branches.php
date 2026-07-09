<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Branch Management' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }

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
            position: relative;
        }
        .sidebar-nav a i { 
            margin-right: 14px; 
            width: 22px; 
            font-size: 1.1rem;
            transition: var(--transition);
        }
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
        .top-bar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
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
            padding: 20px 24px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
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
            border-radius: 16px 16px 0 0;
        }
        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, var(--primary), var(--accent)); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, var(--success), #2ecc71); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, var(--warning), #f1c40f); }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(15, 59, 94, 0.12);
        }
        .stat-card .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
            display: inline-block;
            padding: 10px;
            border-radius: 12px;
        }
        .stat-card:nth-child(1) .stat-icon { 
            color: var(--primary); 
            background: rgba(15, 59, 94, 0.08);
        }
        .stat-card:nth-child(2) .stat-icon { 
            color: var(--success); 
            background: rgba(39, 174, 96, 0.08);
        }
        .stat-card:nth-child(3) .stat-icon { 
            color: var(--warning); 
            background: rgba(243, 156, 18, 0.08);
        }
        .stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1.2;
        }
        .stat-card .stat-label {
            color: #718096;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .stat-card .stat-trend {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 50px;
            background: #d4edda;
            color: #155724;
        }

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
        .search-box .form-control:focus {
            box-shadow: none;
        }
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

        /* Region Header */
        .region-header {
            background: white;
            padding: 16px 24px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .region-header h5 {
            margin: 0;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.05rem;
        }
        .region-header h5 i { color: var(--secondary); margin-right: 10px; }
        .region-header .badge {
            background: var(--primary);
            color: white;
            padding: 4px 14px;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Branch Card */
        .branch-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }
        .branch-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            border-radius: 14px 0 0 14px;
        }
        .branch-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(15, 59, 94, 0.12);
        }
        .branch-card .branch-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.05rem;
        }
        .branch-card .branch-name i { color: var(--secondary); margin-right: 8px; }
        .branch-card .branch-region {
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(15, 59, 94, 0.06);
            padding: 2px 12px;
            border-radius: 50px;
            display: inline-block;
        }
        .branch-card .branch-contact {
            color: #4a5568;
            font-size: 0.85rem;
            line-height: 1.8;
        }
        .branch-card .branch-contact i {
            width: 22px;
            color: var(--primary);
            font-size: 0.9rem;
        }
        .branch-card .badge-coverage {
            background: rgba(232, 185, 49, 0.12);
            color: var(--primary-dark);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .branch-card .nearby-tag {
            background: rgba(39, 174, 96, 0.12);
            color: var(--success);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Nearby Alert */
        .nearby-alert {
            background: linear-gradient(135deg, #fff9e6, #fff3cd);
            border: 1px solid #ffc107;
            border-radius: 14px;
            padding: 16px 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nearby-alert i {
            color: var(--warning);
            font-size: 1.5rem;
        }
        .nearby-alert span {
            color: #856404;
            font-weight: 500;
        }

        /* Loading Spinner */
        .loading-spinner {
            text-align: center;
            padding: 60px 20px;
        }
        .loading-spinner .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e2e8f0;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
        }
        .no-results i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 20px;
        }
        .no-results h5 {
            color: var(--primary-dark);
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { min-height: auto; position: relative; }
            .main-content { padding: 15px; }
            .top-bar { flex-direction: column; gap: 12px; align-items: stretch; text-align: center; }
            .top-bar .user-info { justify-content: center; }
            .search-box { max-width: 100%; }
        }
        @media (max-width: 576px) {
            .stat-card .stat-number { font-size: 1.6rem; }
            .branch-card { padding: 16px; }
            .region-header { flex-direction: column; gap: 8px; text-align: center; }
        }

        /* Fade In Animation */
        .fade-in {
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                    
                    <a href="<?= base_url('admin/branches-map') ?>">
                        <i class="bi bi-map"></i> Branch Map
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
                    <h4><i class="bi bi-geo-alt"></i> Branch Management</h4>
                    <div class="user-info">
                        <span class="status-badge">
                            <span class="dot"></span> Online
                        </span>
                        <span class="user-avatar">
                            <?= strtoupper(substr($username ?? 'Admin', 0, 1)) ?>
                        </span>
                        <strong><?= $username ?? 'Admin' ?></strong>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-building"></i></div>
                            <div class="stat-number"><?= $totalBranches ?? 0 ?></div>
                            <div class="stat-label">Total Branches</div>
                            <small class="stat-trend"><i class="bi bi-arrow-up"></i> Active</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-globe"></i></div>
                            <div class="stat-number"><?= count($regions ?? []) ?></div>
                            <div class="stat-label">Regions Covered</div>
                            <small class="stat-trend"><i class="bi bi-check-circle"></i> Nationwide</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                            <div class="stat-number"><?= $totalBranches ?? 0 ?></div>
                            <div class="stat-label">Active Branches</div>
                            <small class="stat-trend"><i class="bi bi-arrow-up"></i> Operational</small>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search branches by name, region, or coverage..." 
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
                    <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Type a location to find branches
                        </small>
                    </div>
                </div>

                <!-- Results Container -->
                <div id="resultsContainer">
                    <!-- Nearby Search Alert -->
                    <div id="nearbyAlert" style="display: none;" class="nearby-alert">
                        <i class="bi bi-compass"></i>
                        <span id="nearbyMessage">Showing nearby branches for your search</span>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;">
                        <div class="spinner"></div>
                        <p class="text-muted">Searching branches...</p>
                    </div>

                    <!-- Branches Display -->
                    <div id="branchesDisplay">
                        <?php if (!empty($groupedBranches)): ?>
                            <?php foreach ($groupedBranches as $region => $branches): ?>
                                <div class="region-header">
                                    <h5>
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <?= esc($region) ?>
                                    </h5>
                                    <span class="badge"><?= count($branches) ?> Branches</span>
                                </div>
                                <div class="row mb-4">
                                    <?php foreach ($branches as $branch): ?>
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="branch-card fade-in">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <div class="branch-name">
                                                            <i class="bi bi-building"></i>
                                                            <?= esc($branch['branch_name']) ?>
                                                        </div>
                                                        <div class="branch-region mt-1">
                                                            <?= esc($branch['region'] ?? 'N/A') ?>
                                                        </div>
                                                    </div>
                                                    <span class="badge-coverage">
                                                        <i class="bi bi-map"></i> Coverage
                                                    </span>
                                                </div>
                                                
                                                <div class="branch-contact mt-2">
                                                    <?php if (!empty($branch['coverage'])): ?>
                                                        <div><i class="bi bi-map"></i> <?= esc($branch['coverage']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($branch['contact_person'])): ?>
                                                        <div><i class="bi bi-person"></i> <?= esc($branch['contact_person']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($branch['contact_number'])): ?>
                                                        <div><i class="bi bi-phone"></i> <?= esc($branch['contact_number']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($branch['email'])): ?>
                                                        <div><i class="bi bi-envelope"></i> <?= esc($branch['email']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($branch['address'])): ?>
                                                        <div><i class="bi bi-geo-alt"></i> <?= esc($branch['address']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-results">
                                <i class="bi bi-building"></i>
                                <h5>No branches found</h5>
                                <p class="text-muted">Please add branches to get started.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- No Results -->
                    <div id="noResults" style="display: none;" class="no-results">
                        <i class="bi bi-search"></i>
                        <h5>No branches found</h5>
                        <p class="text-muted" id="noResultsMessage">Try searching with a different keyword.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const clearSearch = document.getElementById('clearSearch');
            const resultsContainer = document.getElementById('branchesDisplay');
            const noResults = document.getElementById('noResults');
            const nearbyAlert = document.getElementById('nearbyAlert');
            const nearbyMessage = document.getElementById('nearbyMessage');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const originalContent = resultsContainer ? resultsContainer.innerHTML : '';

            function performSearch() {
                const query = searchInput.value.trim();
                
                if (query.length === 0) {
                    if (resultsContainer) {
                        resultsContainer.innerHTML = originalContent;
                    }
                    if (noResults) noResults.style.display = 'none';
                    if (nearbyAlert) nearbyAlert.style.display = 'none';
                    if (clearSearch) clearSearch.style.display = 'none';
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    return;
                }

                // Show loading
                if (loadingSpinner) loadingSpinner.style.display = 'block';
                if (resultsContainer) resultsContainer.style.display = 'none';
                if (noResults) noResults.style.display = 'none';
                if (nearbyAlert) nearbyAlert.style.display = 'none';

                // AJAX request
                fetch(`<?= base_url('branch/search') ?>?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                        if (resultsContainer) resultsContainer.style.display = 'block';

                        if (data.success && data.branches && data.branches.length > 0) {
                            // Show results
                            displayBranches(data.branches, data.isNearby || false, data.query);
                            
                            // Show nearby alert if applicable
                            if (data.isNearby && data.nearbyMessage) {
                                if (nearbyAlert) {
                                    nearbyAlert.style.display = 'flex';
                                    nearbyMessage.textContent = data.nearbyMessage;
                                }
                            } else {
                                if (nearbyAlert) nearbyAlert.style.display = 'none';
                            }
                            
                            if (noResults) noResults.style.display = 'none';
                            if (clearSearch) clearSearch.style.display = 'block';
                        } else {
                            // No results
                            if (resultsContainer) resultsContainer.innerHTML = '';
                            if (noResults) {
                                noResults.style.display = 'block';
                                document.getElementById('noResultsMessage').textContent = 
                                    `No branches found for "${query}". Try searching with a different keyword.`;
                            }
                            if (nearbyAlert) nearbyAlert.style.display = 'none';
                            if (clearSearch) clearSearch.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                        if (resultsContainer) resultsContainer.style.display = 'block';
                        if (noResults) {
                            noResults.style.display = 'block';
                            document.getElementById('noResultsMessage').textContent = 
                                'An error occurred while searching. Please try again.';
                        }
                    });
            }

            function displayBranches(branches, isNearby, query) {
                if (!resultsContainer) return;
                
                let html = '';
                
                if (isNearby) {
                    html = `<div class="row">`;
                    branches.forEach(branch => {
                        html += `
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="branch-card fade-in" style="border-left: 4px solid #27ae60;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="branch-name">
                                                <i class="bi bi-building"></i>
                                                ${escapeHtml(branch.branch_name)}
                                            </div>
                                            <div class="branch-region mt-1">
                                                ${escapeHtml(branch.region || 'N/A')}
                                            </div>
                                        </div>
                                        <span class="nearby-tag">
                                            <i class="bi bi-compass"></i> Nearby
                                        </span>
                                    </div>
                                    <div class="branch-contact mt-2">
                                        ${branch.coverage ? `<div><i class="bi bi-map"></i> ${escapeHtml(branch.coverage)}</div>` : ''}
                                        ${branch.contact_person ? `<div><i class="bi bi-person"></i> ${escapeHtml(branch.contact_person)}</div>` : ''}
                                        ${branch.contact_number ? `<div><i class="bi bi-phone"></i> ${escapeHtml(branch.contact_number)}</div>` : ''}
                                        ${branch.email ? `<div><i class="bi bi-envelope"></i> ${escapeHtml(branch.email)}</div>` : ''}
                                        ${branch.address ? `<div><i class="bi bi-geo-alt"></i> ${escapeHtml(branch.address)}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += `</div>`;
                } else {
                    // Group by region for exact matches
                    const grouped = {};
                    branches.forEach(branch => {
                        const region = branch.region || 'Uncategorized';
                        if (!grouped[region]) grouped[region] = [];
                        grouped[region].push(branch);
                    });

                    for (const [region, regionBranches] of Object.entries(grouped)) {
                        html += `
                            <div class="region-header">
                                <h5>
                                    <i class="bi bi-geo-alt-fill"></i>
                                    ${escapeHtml(region)}
                                </h5>
                                <span class="badge">${regionBranches.length} Branches</span>
                            </div>
                            <div class="row mb-4">
                        `;
                        regionBranches.forEach(branch => {
                            html += `
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="branch-card fade-in">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="branch-name">
                                                    <i class="bi bi-building"></i>
                                                    ${escapeHtml(branch.branch_name)}
                                                </div>
                                                <div class="branch-region mt-1">
                                                    ${escapeHtml(branch.region || 'N/A')}
                                                </div>
                                            </div>
                                            <span class="badge-coverage">
                                                <i class="bi bi-map"></i> Coverage
                                            </span>
                                        </div>
                                        <div class="branch-contact mt-2">
                                            ${branch.coverage ? `<div><i class="bi bi-map"></i> ${escapeHtml(branch.coverage)}</div>` : ''}
                                            ${branch.contact_person ? `<div><i class="bi bi-person"></i> ${escapeHtml(branch.contact_person)}</div>` : ''}
                                            ${branch.contact_number ? `<div><i class="bi bi-phone"></i> ${escapeHtml(branch.contact_number)}</div>` : ''}
                                            ${branch.email ? `<div><i class="bi bi-envelope"></i> ${escapeHtml(branch.email)}</div>` : ''}
                                            ${branch.address ? `<div><i class="bi bi-geo-alt"></i> ${escapeHtml(branch.address)}</div>` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += `</div>`;
                    }
                }

                resultsContainer.innerHTML = html;
            }

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Event listeners
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
                clearSearch.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (resultsContainer) resultsContainer.innerHTML = originalContent;
                    if (noResults) noResults.style.display = 'none';
                    if (nearbyAlert) nearbyAlert.style.display = 'none';
                    if (clearSearch) clearSearch.style.display = 'none';
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (resultsContainer) resultsContainer.style.display = 'block';
                });
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
                            if (clearSearch) clearSearch.click();
                        }
                    }, 500);
                });
            }
        });
    </script>
</body>
</html>