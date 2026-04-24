@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="error-page">
    <div class="error-container text-center">
        <div class="error-code">500</div>
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>Lỗi máy chủ nội bộ</h2>
        <p>Đã xảy ra lỗi từ phía máy chủ. Vui lòng thử lại sau.</p>
        
        @if(config('app.debug'))
            <div class="debug-info alert alert-danger">
                <strong>Debug Info:</strong><br>
                {{ $exception->getMessage() ?? 'Unknown error' }}
                @if(isset($exception))
                    <pre class="mt-2"><code>{{ $exception->getTraceAsString() }}</code></pre>
                @endif
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Đội ngũ kỹ thuật đã được thông báo và đang xử lý sự cố.
            </div>
        @endif
        
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <button onclick="location.reload()" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Thử lại
            </button>
        </div>
        
        <div class="error-reference">
            <small>Mã tham chiếu: <span id="errorId"></span></small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('errorId').innerText = 'ERR_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
</script>
@endpush

@push('styles')
<style>
.debug-info {
    text-align: left;
    margin: 20px 0;
    font-size: 12px;
    max-height: 300px;
    overflow: auto;
}
.debug-info pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    font-size: 11px;
}
.error-reference {
    margin-top: 30px;
    color: #999;
}
</style>
@endpush