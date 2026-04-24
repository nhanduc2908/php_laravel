@extends('layouts.app')

@section('title', 'Two Factor Authentication')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-3 mt-5">
                <div class="card-header bg-secondary text-white text-center py-3 rounded-top">
                    <h4 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Xác thực hai yếu tố
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <p class="text-muted mb-4">
                        Nhập mã xác thực từ ứng dụng Google Authenticator của bạn.
                    </p>
                    
                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">
                                <i class="fas fa-qrcode me-1"></i> Mã xác thực
                            </label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" placeholder="123456" required autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Xác thực
                            </button>
                        </div>
                        
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Mất mã? 
                                <a href="{{ route('2fa.recovery') }}" class="text-decoration-none">
                                    Sử dụng mã dự phòng
                                </a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto focus and format OTP input
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
</script>
@endpush