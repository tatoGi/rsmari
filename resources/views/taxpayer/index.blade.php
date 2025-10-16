@extends('layouts.app')

@section('title', 'Tax Payer Results')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar me-2"></i>Tax Payer Query Results</h2>
            <div>
                <a href="{{ route('taxpayer.upload') }}" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i>Upload Excel
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download me-1"></i>Export Results
                </button>
                @if($results->count() > 0)
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearModal">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                @endif
            </div>
        </div>

        <!-- Single Query Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Single Query</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('taxpayer.single') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label for="ident_code" class="form-label">Identification Code (IdentCode)</label>
                        <input type="text" class="form-control" id="ident_code" name="ident_code"
                               placeholder="Enter 9 or 11 digit identification code (e.g., 206322102)"
                               pattern="[0-9]{9,11}" required>
                        <div class="form-text">Enter Georgian taxpayer identification code (9 digits for legal entities, 11 digits for individuals)</div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Statistics -->
        @if($results->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Queries</h6>
                                    <h3>{{ $results->total() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Successful</h6>
                                    <h3>{{ $results->where('response_status', 'success')->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Errors</h6>
                                    <h3>{{ $results->where('response_status', 'error')->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Success Rate</h6>
                                    <h3>{{ $results->count() > 0 ? round(($results->where('response_status', 'success')->count() / $results->count()) * 100, 1) : 0 }}%</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-percentage fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Query Results</h5>
            </div>
            <div class="card-body">
                @if($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>IdentCode</th>
                                    <th>Status</th>
                                    <th>Full Name</th>
                                    <th>Registered Subject</th>
                                    <th>VAT Payer</th>
                                    <th>Additional Status</th>
                                    <th>Query Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td><code>{{ $result->ident_code }}</code></td>
                                        <td>{{ $result->status ?: '-' }}</td>
                                        <td>{{ $result->full_name ?: '-' }}</td>
                                        <td>{{ $result->registered_subject ?: '-' }}</td>
                                        <td>{{ $result->vat_payer ?: '-' }}</td>
                                        <td>{{ $result->additional_status ?: '-' }}</td>
                                        <td>
                                            @if($result->response_status === 'success')
                                                <span class="badge bg-success">Success</span>
                                            @else
                                                <span class="badge bg-danger">Error</span>
                                            @endif
                                        </td>
                                        <td>{{ $result->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $result->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $result->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Details for {{ $result->ident_code }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($result->response_status === 'success')
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Status:</strong> {{ $result->status ?: '-' }}<br>
                                                                <strong>Registered Subject:</strong> {{ $result->registered_subject ?: '-' }}<br>
                                                                <strong>Full Name:</strong> {{ $result->full_name ?: '-' }}<br>
                                                                <strong>Start Date:</strong> {{ $result->start_date ?: '-' }}<br>
                                                                <strong>VAT Payer:</strong> {{ $result->vat_payer ?: '-' }}<br>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Mortgage:</strong> {{ $result->mortgage ?: '-' }}<br>
                                                                <strong>Sequestration:</strong> {{ $result->sequestration ?: '-' }}<br>
                                                                <strong>Additional Status:</strong> {{ $result->additional_status ?: '-' }}<br>
                                                                <strong>Non-Resident:</strong> {{ $result->non_resident ?: '-' }}<br>
                                                                <strong>Query Date:</strong> {{ $result->created_at->format('Y-m-d H:i:s') }}<br>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-danger">
                                                            <strong>Error:</strong> {{ $result->error_message ?: 'Unknown error occurred' }}
                                                        </div>
                                                    @endif

                                                    @if($result->raw_response)
                                                        <hr>
                                                        <h6>Raw API Response:</h6>
                                                        <pre class="bg-light p-3 rounded"><code>{{ $result->raw_response }}</code></pre>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $results->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No results found</h5>
                        <p class="text-muted">Upload an Excel file or perform a single query to get started.</p>
                        <a href="{{ route('taxpayer.upload') }}" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i>Upload Excel File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('taxpayer.export') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">Export Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Filter</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Results</option>
                            <option value="success">Successful Only</option>
                            <option value="error">Errors Only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-1"></i>Export to Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Clear All Modal -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Clear All Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete all tax payer query results? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('taxpayer.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
