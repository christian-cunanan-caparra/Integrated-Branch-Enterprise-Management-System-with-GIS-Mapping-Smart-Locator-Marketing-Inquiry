<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #9b59b6, #8e44ad); }
        
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(15, 59, 94, 0.12);
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
            color: #9b59b6; 
            background: rgba(155, 89, 182, 0.08);
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
            margin-top: 4px;
        }
        .stat-card .stat-trend {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 8px;
        }
        .stat-card .stat-trend.up {
            background: #d4edda;
            color: #155724;
        }
        .stat-card .stat-trend.down {
            background: #f8d7da;
            color: #721c24;
        }
        .stat-card .stat-trend i { margin-right: 4px; }

        /* Chart Cards */
        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            height: 100%;
        }
        .chart-card .chart-title {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.95rem;
            margin-bottom: 16px;
        }
        .chart-card .chart-title i {
            color: var(--secondary);
            margin-right: 8px;
        }
        .chart-container {
            position: relative;
            height: 250px;
        }
        .chart-container.pie-chart {
            height: 220px;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            position: relative;
        }
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(232, 185, 49, 0.05);
            border-radius: 50%;
        }
        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(232, 185, 49, 0.03);
            border-radius: 50%;
        }
        .welcome-card .card-body { 
            padding: 30px 35px;
            position: relative;
            z-index: 1;
        }
        .welcome-card .welcome-icon {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 10px;
            display: block;
        }
        .welcome-card h5 { 
            font-size: 1.3rem; 
            margin-bottom: 10px;
            font-weight: 700;
        }
        .welcome-card h5 i { 
            color: var(--secondary); 
            margin-right: 10px;
        }
        .welcome-card p {
            opacity: 0.9;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .welcome-card .welcome-divider {
            background: rgba(255,255,255,0.15);
            height: 1px;
            margin: 14px 0;
        }
        .welcome-card .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .welcome-card .quick-actions .btn {
            border-radius: 50px;
            padding: 6px 18px;
            font-weight: 500;
            font-size: 0.8rem;
            transition: var(--transition);
        }
        .welcome-card .quick-actions .btn-outline-light:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(232, 185, 49, 0.3);
        }

        .fade-in {
            animation: fadeInUp 0.6s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-delay-1 { animation-delay: 0.1s; }
        .fade-in-delay-2 { animation-delay: 0.2s; }
        .fade-in-delay-3 { animation-delay: 0.3s; }
        .fade-in-delay-4 { animation-delay: 0.4s; }

        @media (max-width: 992px) {
            .sidebar { min-height: auto; position: relative; }
            .main-content { padding: 15px; }
            .top-bar { flex-direction: column; gap: 12px; align-items: stretch; text-align: center; }
            .top-bar .user-info { justify-content: center; }
            .welcome-card .card-body { padding: 25px; }
            .chart-container { height: 200px; }
            .chart-container.pie-chart { height: 180px; }
        }
        @media (max-width: 576px) {
            .stat-card .stat-number { font-size: 1.6rem; }
            .stat-card { padding: 16px; }
            .welcome-card .card-body { padding: 20px; }
            .welcome-card h5 { font-size: 1.1rem; }
            .chart-container { height: 180px; }
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
                    <a href="<?= base_url('admin/dashboard') ?>" class="active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    
                    <a href="<?= base_url('admin/branches-map') ?>">
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
                    <h4><i class="bi bi-speedometer2"></i> Dashboard</h4>
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

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3 fade-in fade-in-delay-1">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/inquiries') ?>'">
                            <div class="stat-icon"><i class="bi bi-chat-dots"></i></div>
                            <div class="stat-number"><?= $totalInquiries ?? 0 ?></div>
                            <div class="stat-label">Total Inquiries</div>
                            <span class="stat-trend up"><i class="bi bi-arrow-up"></i> New inquiries</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3 fade-in fade-in-delay-2">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/branches') ?>'">
                            <div class="stat-icon"><i class="bi bi-building"></i></div>
                            <div class="stat-number"><?= $totalBranches ?? 0 ?></div>
                            <div class="stat-label">Total Branches</div>
                            <span class="stat-trend up"><i class="bi bi-arrow-up"></i> Active branches</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3 fade-in fade-in-delay-3">
                        <div class="stat-card" onclick="window.location.href='<?= base_url('admin/products') ?>'">
                            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="stat-number"><?= $totalProducts ?? 0 ?></div>
                            <div class="stat-label">Total Products</div>
                            <span class="stat-trend up"><i class="bi bi-arrow-up"></i> Registered products</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3 fade-in fade-in-delay-4">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
                            <div class="stat-number"><?= $totalAdmins ?? 1 ?></div>
                            <div class="stat-label">Administrators</div>
                            <span class="stat-trend up"><i class="bi bi-check-circle"></i> All active</span>
                        </div>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="welcome-card fade-in">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <span class="welcome-icon"><i class="bi bi-hand-thumbs-up"></i></span>
                                        <h5><i class="bi bi-person-circle"></i> Welcome Back, <?= $username ?? 'Admin' ?>!</h5>
                                        <p>
                                            You are logged in as an administrator. 
                                            <?php if(isset($email)): ?>
                                                <br><i class="bi bi-envelope"></i> <?= $email ?>
                                            <?php endif; ?>
                                        </p>
                                        <div class="welcome-divider"></div>
                                        <p style="opacity: 0.8; font-size: 0.9rem;">
                                            <i class="bi bi-info-circle"></i> 
                                            This is your admin dashboard. You can manage your application from here.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="quick-actions">
                                            <a href="<?= base_url('admin/branches') ?>" class="btn btn-outline-light">
                                                <i class="bi bi-geo-alt"></i> Branches
                                            </a>
                                            <a href="<?= base_url('admin/inquiries') ?>" class="btn btn-outline-light">
                                                <i class="bi bi-chat-dots"></i> Inquiries
                                            </a>
                                            <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-light">
                                                <i class="bi bi-box-seam"></i> Products
                                            </a>
                                            <a href="<?= base_url('admin/settings') ?>" class="btn btn-outline-light">
                                                <i class="bi bi-gear"></i> Settings
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 1 - Bar Charts -->
                <div class="row mb-4">
                    <div class="col-lg-6 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i> Daily Inquiries (Last 7 Days)</div>
                            <div class="chart-container">
                                <canvas id="dailyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i> Weekly Inquiries (Last 4 Weeks)</div>
                            <div class="chart-container">
                                <canvas id="weeklyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 - Bar Chart & Pie Chart -->
                <div class="row mb-4">
                    <div class="col-lg-6 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i> Monthly Inquiries (Last 6 Months)</div>
                            <div class="chart-container">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-pie-chart-fill"></i> Inquiry Status</div>
                            <div class="chart-container pie-chart">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-pie-chart-fill"></i> Branches by Region</div>
                            <div class="chart-container pie-chart">
                                <canvas id="regionPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yearly Chart -->
                <div class="row mb-4">
                    <div class="col-lg-12 mb-3 fade-in">
                        <div class="chart-card">
                            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i> Yearly Inquiries (Last 5 Years)</div>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="yearlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Color palette
            const colors = {
                primary: '#0f3b5e',
                secondary: '#e8b931',
                success: '#27ae60',
                danger: '#e74c3c',
                warning: '#f39c12',
                info: '#2e86de',
                purple: '#9b59b6',
                lightBlue: '#85c1e9',
                lightGreen: '#82e0aa',
                lightOrange: '#f5b041',
                lightPurple: '#bb8fce'
            };

            // ========== DAILY CHART ==========
            const dailyData = <?= json_encode($dailyInquiries ?? []) ?>;
            new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: {
                    labels: dailyData.map(d => d.date),
                    datasets: [{
                        label: 'Inquiries',
                        data: dailyData.map(d => d.count),
                        backgroundColor: dailyData.map(d => d.count > 0 ? colors.primary : '#e2e8f0'),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // ========== WEEKLY CHART ==========
            const weeklyData = <?= json_encode($weeklyInquiries ?? []) ?>;
            new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: {
                    labels: weeklyData.map(d => d.week),
                    datasets: [{
                        label: 'Inquiries',
                        data: weeklyData.map(d => d.count),
                        backgroundColor: weeklyData.map((d, i) => 
                            d.count > 0 ? ['#0f3b5e', '#1a5276', '#2e86de', '#85c1e9'][i] : '#e2e8f0'
                        ),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // ========== MONTHLY CHART ==========
            const monthlyData = <?= json_encode($monthlyInquiries ?? []) ?>;
            new Chart(document.getElementById('monthlyChart'), {
                type: 'bar',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [{
                        label: 'Inquiries',
                        data: monthlyData.map(d => d.count),
                        backgroundColor: monthlyData.map((d, i) => 
                            d.count > 0 ? ['#27ae60', '#2ecc71', '#82e0aa', '#f39c12', '#f5b041', '#e8b931'][i] : '#e2e8f0'
                        ),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // ========== YEARLY CHART ==========
            const yearlyData = <?= json_encode($yearlyInquiries ?? []) ?>;
            new Chart(document.getElementById('yearlyChart'), {
                type: 'line',
                data: {
                    labels: yearlyData.map(d => d.year),
                    datasets: [{
                        label: 'Inquiries',
                        data: yearlyData.map(d => d.count),
                        backgroundColor: 'rgba(15, 59, 94, 0.1)',
                        borderColor: colors.primary,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: colors.secondary,
                        pointBorderColor: colors.primary,
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // ========== STATUS PIE CHART ==========
            const statusData = {
                labels: ['Pending', 'Contacted', 'Resolved'],
                data: [
                    <?= $pendingInquiries ?? 0 ?>,
                    <?= $contactedInquiries ?? 0 ?>,
                    <?= $resolvedInquiries ?? 0 ?>
                ],
                colors: [colors.warning, colors.info, colors.success]
            };
            new Chart(document.getElementById('statusPieChart'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.data,
                        backgroundColor: statusData.colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                usePointStyle: true,
                                font: { size: 11 }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // ========== REGION PIE CHART ==========
            const regionData = <?= json_encode($branchRegions ?? []) ?>;
            const regionColors = ['#0f3b5e', '#e8b931', '#27ae60', '#e74c3c', '#2e86de', '#9b59b6', '#f39c12', '#1abc9c'];
            new Chart(document.getElementById('regionPieChart'), {
                type: 'doughnut',
                data: {
                    labels: regionData.map(r => r.region),
                    datasets: [{
                        data: regionData.map(r => r.count),
                        backgroundColor: regionColors.slice(0, regionData.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                usePointStyle: true,
                                font: { size: 10 }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        });
    </script>
</body>
</html>