<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Nokuex Staff Panel</title>
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
            background-color: #f5f5f5;
        }
        
        .navbar {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-warning {
            background-color: var(--accent-color);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">Nokuex Chat</div>
        <div>
            <a href="{{ route('core.dashboard') }}" class="btn btn-warning">Back to Dashboard</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>