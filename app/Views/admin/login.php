<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .login-card h3 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-weight: 600;
        }
        .login-icon {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-icon i {
            font-size: 3.5rem;
            color: #667eea;
            background: #f0f4ff;
            padding: 15px;
            border-radius: 50%;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 0.3rem;
            display: block;
        }
        .form-group label i {
            margin-right: 5px;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #e1e5eb;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-primary {
            background: #667eea;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 1rem;
        }
        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #888;
            font-size: 13px;
        }
        .login-footer strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h3>Admin Login</h3>
        
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form action="<?= base_url('admin/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label><i class="bi bi-person"></i> Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Enter your username" required autofocus>
            </div>
            
            <div class="form-group">
                <label><i class="bi bi-key"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>
        
        <div class="login-footer">
            <small>Default: <strong>admin</strong> / <strong>password</strong></small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>