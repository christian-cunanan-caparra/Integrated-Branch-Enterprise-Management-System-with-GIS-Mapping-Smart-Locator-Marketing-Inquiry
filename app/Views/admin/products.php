<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Product Management' ?></title>
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
            cursor: pointer;
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
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, var(--danger), #e74c3c); }
        
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
        .stat-card:nth-child(4) .stat-icon { 
            color: var(--danger); 
            background: rgba(231, 76, 60, 0.08);
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

        /* Search & Filter */
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
            padding: 12px 24px;
            font-size: 0.95rem;
            background: transparent;
        }
        .search-box .form-control:focus { box-shadow: none; }
        .search-box .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            transition: var(--transition);
        }
        .search-box .btn:hover {
            background: var(--primary-dark);
            transform: scale(1.02);
        }
        .filter-select {
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            padding: 12px 20px;
            background: white;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        .filter-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(232, 185, 49, 0.15);
            outline: none;
        }

        /* Product Table */
        .table-container {
            background: white;
            border-radius: 16px;
            padding: 0;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .table-responsive {
            padding: 0;
        }
        .table {
            margin: 0;
            font-size: 0.9rem;
        }
        .table thead {
            background: var(--primary-dark);
            color: white;
        }
        .table thead th {
            padding: 14px 20px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f4f8;
        }
        .table tbody tr:hover {
            background: #f8fafc;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .badge-status {
            padding: 5px 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-status.approved {
            background: #d4edda;
            color: #155724;
        }
        .badge-status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge-status.disapproved {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-status.evaluation {
            background: #cce5ff;
            color: #004085;
        }
        .badge-status.checked {
            background: #e8daef;
            color: #6c3483;
        }
        .badge-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .badge-status.approved .dot { background: #28a745; }
        .badge-status.pending .dot { background: #ffc107; }
        .badge-status.disapproved .dot { background: #dc3545; }
        .badge-status.evaluation .dot { background: #007bff; }
        .badge-status.checked .dot { background: #6c3483; }

        /* Product Name */
        .product-name {
            font-weight: 600;
            color: var(--primary-dark);
        }
        .product-name small {
            font-weight: 400;
            color: #718096;
            font-size: 0.75rem;
            display: block;
        }
        .product-fda {
            font-size: 0.8rem;
            color: #4a5568;
            font-family: monospace;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 20px;
        }
        .no-results i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 16px;
        }
        .no-results h5 {
            color: var(--primary-dark);
            font-weight: 700;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px 24px;
            background: white;
            border-top: 1px solid #f0f4f8;
            border-radius: 0 0 16px 16px;
        }
        .pagination {
            margin: 0;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            color: var(--primary-dark);
            font-weight: 500;
            transition: var(--transition);
            background: transparent;
        }
        .pagination .page-item .page-link:hover {
            background: rgba(15, 59, 94, 0.06);
            color: var(--primary);
        }
        .pagination .page-item.active .page-link {
            background: var(--primary);
            color: white;
            border-radius: 8px;
        }
        .pagination .page-item.disabled .page-link {
            color: #cbd5e0;
            cursor: not-allowed;
        }
        .pagination .page-item .page-link i {
            font-size: 0.8rem;
        }
        .pagination-info {
            color: #718096;
            font-size: 0.85rem;
        }
        .pagination-info strong {
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { min-height: auto; position: relative; }
            .main-content { padding: 15px; }
            .top-bar { flex-direction: column; gap: 12px; align-items: stretch; text-align: center; }
            .top-bar .user-info { justify-content: center; }
            .search-box { width: 100%; }
            .filter-select { width: 100%; margin-top: 10px; }
        }
        @media (max-width: 576px) {
            .stat-card .stat-number { font-size: 1.6rem; }
            .stat-card { padding: 16px; }
            .table thead th, .table tbody td { padding: 10px 12px; font-size: 0.8rem; }
            .pagination-wrapper { padding: 15px; }
            .pagination .page-item .page-link { padding: 6px 12px; font-size: 0.8rem; }
        }

        /* Animation */
        .fade-in {
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
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
                    <a href="<?= base_url('admin/branches') ?>">
                        <i class="bi bi-geo-alt"></i> Branches
                    </a>
                    <a href="<?= base_url('admin/branches-map') ?>">
                        <i class="bi bi-map"></i> Branch Map
                    </a>
                    <a href="<?= base_url('admin/products') ?>" class="active">
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
                    <h4><i class="bi bi-box-seam"></i> Product Management</h4>
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
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/products') ?>'">
                            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="stat-number"><?= $statusCounts['total'] ?? 0 ?></div>
                            <div class="stat-label">Total Products</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/products?status=approved') ?>'">
                            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="stat-number" style="color: var(--success);"><?= $statusCounts['approved'] ?? 0 ?></div>
                            <div class="stat-label">Approved</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/products?status=pending') ?>'">
                            <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
                            <div class="stat-number" style="color: var(--warning);"><?= $statusCounts['pending'] ?? 0 ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/products?status=disapproved') ?>'">
                            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                            <div class="stat-number" style="color: var(--danger);"><?= $statusCounts['disapproved'] ?? 0 ?></div>
                            <div class="stat-label">Disapproved</div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search products by flavor, FDA registration, importer..." 
                                       autocomplete="off" value="<?= $search ?? '' ?>">
                                <button class="btn" id="searchBtn" type="button">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <?php if (!empty($search) || !empty($statusFilter)): ?>
                                    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg"></i> Clear
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="filter-select w-100" id="statusFilter" onchange="window.location.href='<?= base_url('admin/products') ?>?status='+this.value">
                            <option value="all" <?= ($statusFilter ?? 'all') == 'all' ? 'selected' : '' ?>>All Status</option>
                            <option value="approved" <?= ($statusFilter ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="pending" <?= ($statusFilter ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="disapproved" <?= ($statusFilter ?? '') == 'disapproved' ? 'selected' : '' ?>>Disapproved</option>
                            <option value="evaluation" <?= ($statusFilter ?? '') == 'evaluation' ? 'selected' : '' ?>>Evaluation</option>
                            <option value="checking" <?= ($statusFilter ?? '') == 'checking' ? 'selected' : '' ?>>Checking</option>
                        </select>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-container fade-in">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Flavor / Product</th>
                                    <th>Importer</th>
                                    <th>Manufacturer</th>
                                    <th>FDA Registration</th>
                                    <th>Validity Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php 
                                    $startNumber = (($currentPage ?? 1) - 1) * ($perPage ?? 10) + 1;
                                    foreach ($products as $index => $product): 
                                    ?>
                                        <tr>
                                            <td><?= $startNumber + $index ?></td>
                                            <td>
                                                <div class="product-name">
                                                    <?= esc($product['flavor'] ?? 'N/A') ?>
                                                    <?php if (!empty($product['remarks'])): ?>
                                                        <small><i class="bi bi-info-circle"></i> <?= esc($product['remarks']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= esc($product['importer'] ?? 'N/A') ?></td>
                                            <td><?= esc($product['manufacturer'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="product-fda"><?= esc($product['fda_registration_no'] ?? 'N/A') ?></span>
                                                <?php if (!empty($product['fda_no'])): ?>
                                                    <br><small class="text-muted">FDA No: <?= esc($product['fda_no']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($product['validity_date'])): ?>
                                                    <?= date('M d, Y', strtotime($product['validity_date'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                // Determine status properly
                                                $statusText = 'Pending';
                                                $statusClass = 'pending';
                                                
                                                if ($product['approved_with_cpr'] == 1) {
                                                    $statusText = 'Approved';
                                                    $statusClass = 'approved';
                                                } elseif (!empty($product['status'])) {
                                                    $statusLower = strtolower($product['status']);
                                                    if ($statusLower == 'approved') {
                                                        $statusText = 'Approved';
                                                        $statusClass = 'approved';
                                                    } elseif ($statusLower == 'disapproved') {
                                                        $statusText = 'Disapproved';
                                                        $statusClass = 'disapproved';
                                                    } elseif ($statusLower == 'evaluation') {
                                                        $statusText = 'Evaluation';
                                                        $statusClass = 'evaluation';
                                                    } elseif ($statusLower == 'checking') {
                                                        $statusText = 'Checking';
                                                        $statusClass = 'checked';
                                                    } elseif ($statusLower == 'pending') {
                                                        $statusText = 'Pending';
                                                        $statusClass = 'pending';
                                                    } else {
                                                        $statusText = ucfirst($statusLower);
                                                        $statusClass = 'pending';
                                                    }
                                                } elseif ($product['not_approved_yet'] == 1) {
                                                    $statusText = 'Pending';
                                                    $statusClass = 'pending';
                                                }
                                                ?>
                                                <span class="badge-status <?= $statusClass ?>">
                                                    <span class="dot"></span> <?= $statusText ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="no-results">
                                                <i class="bi bi-box-seam"></i>
                                                <h5>No products found</h5>
                                                <p class="text-muted">Try adjusting your search or filter criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (($totalPages ?? 0) > 1): ?>
                        <div class="pagination-wrapper">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="pagination-info">
                                        Showing <strong><?= count($products ?? []) ?></strong> of <strong><?= $totalProducts ?? 0 ?></strong> products
                                        <?php if (!empty($search)): ?>
                                            for "<strong><?= esc($search) ?></strong>"
                                        <?php endif; ?>
                                        <?php if (!empty($statusFilter) && $statusFilter !== 'all'): ?>
                                            with status "<strong><?= ucfirst($statusFilter) ?></strong>"
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <li class="page-item <?= ($currentPage ?? 1) <= 1 ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= base_url('admin/products?page=' . (($currentPage ?? 1) - 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($statusFilter) && $statusFilter !== 'all' ? '&status=' . urlencode($statusFilter) : '')) ?>">
                                                    <i class="bi bi-chevron-left"></i>
                                                </a>
                                            </li>
                                            
                                            <?php 
                                            $startPage = max(1, ($currentPage ?? 1) - 2);
                                            $endPage = min($totalPages ?? 1, ($currentPage ?? 1) + 2);
                                            
                                            if ($startPage > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?= base_url('admin/products?page=1' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($statusFilter) && $statusFilter !== 'all' ? '&status=' . urlencode($statusFilter) : '')) ?>">1</a>
                                                </li>
                                                <?php if ($startPage > 2): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                                <li class="page-item <?= ($i == ($currentPage ?? 1)) ? 'active' : '' ?>">
                                                    <a class="page-link" href="<?= base_url('admin/products?page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($statusFilter) && $statusFilter !== 'all' ? '&status=' . urlencode($statusFilter) : '')) ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($endPage < ($totalPages ?? 1)): ?>
                                                <?php if ($endPage < ($totalPages ?? 1) - 1): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?= base_url('admin/products?page=' . ($totalPages ?? 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($statusFilter) && $statusFilter !== 'all' ? '&status=' . urlencode($statusFilter) : '')) ?>"><?= $totalPages ?? 1 ?></a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <li class="page-item <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="<?= base_url('admin/products?page=' . (($currentPage ?? 1) + 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($statusFilter) && $statusFilter !== 'all' ? '&status=' . urlencode($statusFilter) : '')) ?>">
                                                    <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const statusFilter = document.getElementById('statusFilter');

            function performSearch() {
                const query = searchInput.value.trim();
                const status = statusFilter.value;
                let url = '<?= base_url('admin/products') ?>?';
                if (query) url += 'search=' + encodeURIComponent(query) + '&';
                if (status && status !== 'all') url += 'status=' + encodeURIComponent(status);
                window.location.href = url;
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
        });
    </script>
</body>
</html>