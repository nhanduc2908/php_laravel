@extends('layouts.app')

@section('title', '403 - Forbidden')

@section('content')
<div class="error-page">
    <div class="error-container text-center">
        <div class="error-code">403</div>
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h2>Không có quyền truy cập</h2>
        <p>Bạn không có quyền để truy cập vào trang này.</p>
        
        @if(isset($message))
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> {{ $message }}
            </div>
        @endif
        
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <div class="contact-support">
            <p>Bạn cần hỗ trợ? <a href="mailto:support@security.com">Liên hệ Admin</a></p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.alert-warning {
    background-color: #fff3cd;
    border-color: #ffecb5;
    color: #856404;
    padding: 10px;
    border-radius: 8px;
    margin: 20px 0;
}
.contact-support {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    font-size: 14px;
}
</style>
@endpush