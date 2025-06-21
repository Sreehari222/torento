<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            padding: 30px 40px; /* Reduced padding */
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: all 0.4s ease;
            height: auto; /* Changed from fixed height to auto */
        }

        .login-header {
            text-align: center;
            margin-bottom: 25px; /* Reduced margin */
        }

        .login-header .icon {
            font-size: 48px; /* Reduced size */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px; /* Reduced margin */
        }

        .login-header h1 {
            color: #2c3e50;
            font-size: 24px; /* Reduced size */
            font-weight: 700;
            margin-bottom: 8px; /* Reduced margin */
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 14px; /* Reduced size */
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px; /* Reduced margin */
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px; /* Reduced margin */
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px; /* Reduced size */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 14px; /* Reduced size */
        }

        .form-group input {
            width: 100%;
            padding: 14px 14px 14px 40px; /* Reduced padding */
            border: 2px solid #e8ecf0;
            border-radius: 10px; /* Reduced radius */
            font-size: 14px; /* Reduced size */
            transition: all 0.3s ease;
            background: #f8f9fb;
            color: #2c3e50;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px; /* Reduced margin */
        }

        .btn {
            width: 100%;
            padding: 14px; /* Reduced padding */
            border: none;
            border-radius: 10px; /* Reduced radius */
            font-size: 14px; /* Reduced size */
        }

        .divider {
            text-align: center;
            margin: 20px 0; /* Reduced margin */
            position: relative;
        }

        .register-link {
            font-size: 14px; /* Reduced size */
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px; /* Reduced padding */
            }

            .login-header .icon {
                font-size: 40px; /* Reduced size */
            }

            .login-header h1 {
                font-size: 22px; /* Reduced size */
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to access your admin dashboard</p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" required autocomplete="email">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="#" onclick="alert('Password reset functionality would be implemented here')">Forgot your password?</a>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>

                <div id="loginError" class="error"></div>
            </form>

            <div class="divider">
                <span>Don't have an account?</span>
            </div>

            <div class="register-link">
                <a href="#" onclick="goToRegister()">Create your admin account</a>
            </div>
        </div>
    </div>

    <script>
        // Create animated particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';

                const size = Math.random() * 5 + 2;
                const x = Math.random() * window.innerWidth;
                const y = Math.random() * window.innerHeight;
                const delay = Math.random() * 6;

                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = x + 'px';
                particle.style.top = y + 'px';
                particle.style.animationDelay = delay + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Initialize particles
        createParticles();

        // Demo user for testing
        const demoUser = {
            email: 'admin@example.com',
            password: 'admin123',
            name: 'Admin User'
        };

        // Get registered users from storage
        function getUsers() {
            const users = JSON.parse(sessionStorage.getItem('registeredUsers') || '[]');
            return [...users, demoUser]; // Include demo user
        }

        // Login form handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('loginError');

            // Show loading state
            this.classList.add('loading');

            // Simulate API call delay
            setTimeout(() => {
                const users = getUsers();
                const user = users.find(u => u.email === email && u.password === password);

                if (user) {
                    // Store current user session
                    sessionStorage.setItem('currentUser', JSON.stringify({
                        email: user.email,
                        name: user.name || 'Admin User'
                    }));

                    // Redirect to dashboard
                    goToDashboard();
                } else {
                    // Show error
                    errorDiv.textContent = 'Invalid email or password. Try admin@example.com / admin123';
                    errorDiv.style.display = 'block';

                    // Remove loading state
                    this.classList.remove('loading');
                }
            }, 1000);
        });

        // Navigation functions
        function goToRegister() {
            alert('In a real application, this would navigate to register.html');
        }

        function goToDashboard() {
            alert('Login successful! In a real application, this would redirect to dashboard.html');
        }

        // Auto-fill demo credentials on page load
        window.addEventListener('load', function() {
            document.getElementById('email').value = 'admin@example.com';
            document.getElementById('password').value = 'admin123';
        });
    </script>
</body>
</html>
