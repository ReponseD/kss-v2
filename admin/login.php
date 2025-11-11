<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KSS Updates Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #a05d3c 0%, #8b4d2a 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
        .form-control:focus {
            border-color: #a05d3c;
            box-shadow: 0 0 0 0.2rem rgba(160, 93, 60, 0.25);
        }
        .btn-primary {
            background-color: #a05d3c;
            border-color: #a05d3c;
        }
        .btn-primary:hover {
            background-color: #8b4d2a;
            border-color: #8b4d2a;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h3><i class="fas fa-school me-2"></i>KSS Updates Admin</h3>
            <p class="mb-0">Media Team Portal</p>
        </div>
        <div class="login-body">
            <div id="alertContainer"></div>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username or Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                const username = $('#username').val();
                const password = $('#password').val();
                
                if (!username || !password) {
                    showAlert('Please enter both username and password', 'danger');
                    return;
                }
                
                $.ajax({
                    url: '../api/auth.php?action=login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ username, password }),
                    success: function(response) {
                        if (response.error === false) {
                            showAlert('Login successful! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = 'dashboard.php';
                            }, 1000);
                        } else {
                            showAlert(response.message || 'Login failed', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON || {};
                        showAlert(response.message || 'An error occurred. Please try again.', 'danger');
                    }
                });
            });
        });
        
        function showAlert(message, type) {
            const alert = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('#alertContainer').html(alert);
        }
    </script>
</body>
</html>

