@extends('layouts.app')

@section('title', 'Upload Excel File')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Excel File with IdentCodes</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('taxpayer.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="upload-area mb-4">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Drag and drop your Excel file here</h5>
                        <p class="text-muted">or click the button below to browse</p>

                        <input type="file" class="form-control @error('excel_file') is-invalid @enderror"
                               id="excel_file" name="excel_file"
                               accept=".xlsx,.xls,.csv" required>

                        @error('excel_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-upload me-2"></i>Upload and Process
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('taxpayer.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Results
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>File Format Instructions</h6>
            </div>
            <div class="card-body">
                <h6>Supported File Types:</h6>
                <ul>
                    <li>Excel files (.xlsx, .xls)</li>
                    <li>CSV files (.csv)</li>
                </ul>

                <h6 class="mt-3">Excel File Structure:</h6>
                <p>Your Excel file should contain IdentCode values in one of the following ways:</p>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Option 1: With Header Row</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>IdentCode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>206322102</td></tr>
                                    <tr><td>12345678910</td></tr>
                                    <tr><td>987654321</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Option 2: First Column (No Header)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr><td>206322102</td></tr>
                                    <tr><td>12345678910</td></tr>
                                    <tr><td>987654321</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                    <ul class="mb-0">
                        <li>The system will automatically detect column names like: <code>IdentCode</code>, <code>ident_code</code>, <code>code</code>, <code>identification_code</code>, etc.</li>
                        <li>Georgian IdentCodes should be 9 digits (legal entities) or 11 digits (individuals)</li>
                        <li>The system will automatically clean and validate the codes</li>
                        <li>Maximum file size: 10MB</li>
                        <li>Processing includes a 0.5-second delay between API calls to avoid overwhelming the service</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>Large files may take several minutes to process</li>
                        <li>Each IdentCode will be queried against the Georgian Tax Service API</li>
                        <li>Results will be automatically saved and can be exported later</li>
                        <li>Invalid IdentCodes will be skipped with error messages</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sample Excel File -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-download me-2"></i>Sample Excel File</h6>
            </div>
            <div class="card-body">
                <p>Download a sample Excel file to see the expected format:</p>
                <button type="button" class="btn btn-outline-primary" onclick="downloadSampleFile()">
                    <i class="fas fa-file-excel me-2"></i>Download Sample Excel File
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function downloadSampleFile() {
    // Create sample data
    const data = [
        ['IdentCode'],
        ['206322102'],
        ['12345678910'],
        ['987654321'],
        ['123456789'],
        ['98765432101']
    ];

    // Convert to CSV format
    const csvContent = data.map(row => row.join(',')).join('\n');

    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sample_identcodes.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>
@endsection
