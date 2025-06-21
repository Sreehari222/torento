<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .password-strength {
            height: 5px;
            margin-top: 5px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
<div class="bg-light py-3 py-md-5 vh-100">
  <div class="container h-100">
    <div class="row justify-content-md-center h-100">
      <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
          <div class="row">
            <div class="col-12">
              <div class="mb-5">
                <h3>Create Admin Account</h3>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
              </div>
            </div>
          </div>
          <form action="{{ route('admin.register') }}" method="POST">
            @csrf
            <div class="row gy-3 gy-md-4 overflow-hidden">
              <div class="col-12">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" id="name" placeholder="John Doe"
                       value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="col-12">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" id="email" placeholder="admin@example.com"
                       value="{{ old('email') }}" required>
                @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="col-12">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" class="form-control @error('password') is-invalid @enderror"
                         name="password" id="password" required
                         oninput="checkPasswordStrength(this.value)">
                  <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fa fa-eye"></i>
                  </button>
                  @error('password')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="password-strength mt-2">
                  <div class="password-strength-bar" id="password-strength-bar"></div>
                </div>
                <small class="text-muted">Password must be at least 8 characters long</small>
              </div>
              <div class="col-12">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" class="form-control"
                         name="password_confirmation" id="password_confirmation" required>
                  <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input @error('terms') is-invalid @enderror"
                         type="checkbox" name="terms" id="terms" required>
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                  </label>
                  @error('terms')
                  <div class="invalid-feedback">
                    You must accept the terms and conditions
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12">
                <div class="d-grid">
                  <button class="btn btn-lg btn-primary" type="submit">Register</button>
                </div>
              </div>
            </div>
          </form>
          <div class="row">
            <div class="col-12">
              <hr class="mt-5 mb-4 border-secondary-subtle">
              <div class="d-flex gap-2 gap-md-4 justify-content-center">
                <p class="mb-0">Already have an account?</p>
                <a href="{{ route('admin.login') }}" class="link-primary text-decoration-none">Sign in</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Admin Account Agreement</h6>
        <p>By creating an admin account, you agree to:</p>
        <ol>
          <li>Use the admin privileges responsibly and only for legitimate administrative purposes</li>
          <li>Keep your login credentials secure and not share them with others</li>
          <li>Comply with all applicable laws and regulations</li>
          <li>Not attempt to access data or areas outside of your authorized permissions</li>
          <li>Report any security incidents or suspicious activities immediately</li>
        </ol>
        <p>Violation of these terms may result in account termination and legal action.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const input = this.parentElement.querySelector('input');
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Password strength indicator
  function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('password-strength-bar');
    let strength = 0;

    // Length check
    if (password.length >= 8) strength += 20;
    if (password.length >= 12) strength += 20;

    // Character type checks
    if (password.match(/[a-z]/)) strength += 20;
    if (password.match(/[A-Z]/)) strength += 20;
    if (password.match(/[0-9]/)) strength += 10;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 10;

    // Update the strength bar
    strengthBar.style.width = strength + '%';

    // Update the color
    if (strength < 40) {
      strengthBar.style.backgroundColor = '#dc3545'; // Red
    } else if (strength < 70) {
      strengthBar.style.backgroundColor = '#fd7e14'; // Orange
    } else if (strength < 90) {
      strengthBar.style.backgroundColor = '#ffc107'; // Yellow
    } else {
      strengthBar.style.backgroundColor = '#28a745'; // Green
    }
  }

  // Auto-dismiss alerts after 5 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);
</script>
</body>
</html>
