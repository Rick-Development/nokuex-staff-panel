<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nokuex Staff Panel</title>
    <style>
        :root {
            --primary-color: #292D50;
            --secondary-color: #5B8040;
            --accent-color: #DE811D;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color), #1a1f38);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 2rem;
            text-align: center;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .tagline {
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(41, 45, 80, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #1a1f38;
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            display: none;
        }
        
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }
            
            .logo {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">Nokuex</div>
            <div class="tagline">Staff Panel Login</div>
            
            <div id="error-message" class="alert alert-error"></div>

            <form id="login-form">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                </div>
                
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="margin: 0; font-weight: normal;">Remember me</label>
                </div>
                
                <button type="submit" class="btn btn-primary" id="login-btn">
                    Sign In
                </button>
            </form>
            
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
                <div style="font-size: 0.8rem; color: #666;">
                    Demo Credentials:<br>
                    <!-- admin@nokuex.com / password<br> -->
                    manager@nokuex.com / password<br>
                    staff@nokuex.com / password
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('login-btn');
            const errorDiv = document.getElementById('error-message');
            const originalText = btn.textContent;
            
            btn.textContent = 'Signing in...';
            btn.classList.add('loading');
            errorDiv.style.display = 'none';
            
            const formData = new FormData(this);
            
            fetch('{{ route("core.login.post") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            })
            .finally(() => {
                btn.textContent = originalText;
                btn.classList.remove('loading');
            });
        });
        
        // Clear error when user starts typing
        document.getElementById('email').addEventListener('input', function() {
            document.getElementById('error-message').style.display = 'none';
        });
        
        document.getElementById('password').addEventListener('input', function() {
            document.getElementById('error-message').style.display = 'none';
        });
    </script>
</body>
</html>