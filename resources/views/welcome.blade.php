<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            .hero-section {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .feature-card {
                transition: all 0.3s ease;
                border: none;
                border-radius: 15px;
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
            }
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.2);
            }
            .btn-custom {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 50px;
                padding: 12px 30px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
            }
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>
    <body class="d-flex align-items-center">
        <div class="container">
            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end mb-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif

            <!-- Hero Section -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="hero-section p-5 text-center">
                        <h1 class="display-4 fw-bold text-primary mb-4">
                            <i class="fas fa-chart-line me-3"></i>
                            Georgian Tax Service Integration
                        </h1>
                        <p class="lead text-muted mb-5">
                            Streamline your tax information queries with our comprehensive Georgian Revenue Service integration platform. Upload Excel files with multiple IdentCodes and get detailed taxpayer information instantly.
                        </p>

                        <div class="d-flex justify-content-center gap-3 mb-5">
                            <a href="{{ route('taxpayer.index') }}" class="btn btn-custom btn-lg text-white">
                                <i class="fas fa-rocket me-2"></i>Get Started
                            </a>
                            <a href="{{ route('taxpayer.upload') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-upload me-2"></i>Upload Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-file-excel fa-3x"></i>
                            </div>
                            <h5 class="card-title fw-bold">Excel File Processing</h5>
                            <p class="card-text text-muted">
                                Upload Excel files containing multiple Georgian IdentCodes and process them in bulk. Supports .xlsx, .xls, and .csv formats.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-success mb-3">
                                <i class="fas fa-sync-alt fa-3x"></i>
                            </div>
                            <h5 class="card-title fw-bold">Real-time API Integration</h5>
                            <p class="card-text text-muted">
                                Direct integration with Georgian Revenue Service API (xdata.rs.ge) for real-time taxpayer information retrieval.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-info mb-3">
                                <i class="fas fa-download fa-3x"></i>
                            </div>
                            <h5 class="card-title fw-bold">Export Results</h5>
                            <p class="card-text text-muted">
                                Export all query results to Excel format with comprehensive taxpayer information and status details.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="row mt-5">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-info-circle me-2"></i>Supported Information
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success me-2"></i>Taxpayer Status</li>
                                <li><i class="fas fa-check text-success me-2"></i>Registered Subject Type</li>
                                <li><i class="fas fa-check text-success me-2"></i>Full Name</li>
                                <li><i class="fas fa-check text-success me-2"></i>Registration Date</li>
                                <li><i class="fas fa-check text-success me-2"></i>VAT Payer Status</li>
                                <li><i class="fas fa-check text-success me-2"></i>Mortgage Information</li>
                                <li><i class="fas fa-check text-success me-2"></i>Sequestration Details</li>
                                <li><i class="fas fa-check text-success me-2"></i>Additional Status</li>
                                <li><i class="fas fa-check text-success me-2"></i>Residence Status</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-shield-alt me-2"></i>Key Features
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success me-2"></i>Bulk Processing</li>
                                <li><i class="fas fa-check text-success me-2"></i>Error Handling & Validation</li>
                                <li><i class="fas fa-check text-success me-2"></i>Progress Tracking</li>
                                <li><i class="fas fa-check text-success me-2"></i>Detailed Result Storage</li>
                                <li><i class="fas fa-check text-success me-2"></i>Export to Excel</li>
                                <li><i class="fas fa-check text-success me-2"></i>Search & Filter Results</li>
                                <li><i class="fas fa-check text-success me-2"></i>Single Query Support</li>
                                <li><i class="fas fa-check text-success me-2"></i>Responsive Design</li>
                                <li><i class="fas fa-check text-success me-2"></i>API Rate Limiting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Start -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-rocket me-2"></i>Quick Start Guide
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-upload fa-2x"></i>
                                    </div>
                                    <h6>1. Upload Excel</h6>
                                    <small class="text-muted">Upload your Excel file with IdentCodes</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-cogs fa-2x"></i>
                                    </div>
                                    <h6>2. Process</h6>
                                    <small class="text-muted">System queries Georgian Tax Service API</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-eye fa-2x"></i>
                                    </div>
                                    <h6>3. Review Results</h6>
                                    <small class="text-muted">View detailed taxpayer information</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-download fa-2x"></i>
                                    </div>
                                    <h6>4. Export</h6>
                                    <small class="text-muted">Download results as Excel file</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
