@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger no-print" role="alert">
        <div class="fw-semibold mb-1">Periksa kembali isian Anda:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
