<footer class="bg-light text-center text-muted py-3 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-md-start">
                <small>
                    &copy; {{ date('Y') }} {{ config('app.name') }}. 
                    All rights reserved.
                </small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>
                    <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                    &nbsp;|&nbsp;
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    &nbsp;|&nbsp;
                    <a href="#" class="text-muted text-decoration-none">Contact</a>
                </small>
            </div>
        </div>
        <div class="mt-2">
            <small>
                <i class="fas fa-shield-alt text-success"></i>
                Version {{ config('app.version', '1.0.0') }}
            </small>
        </div>
    </div>
</footer>

<style>
footer {
    border-top: 1px solid #dee2e6;
    margin-top: auto;
}

footer a:hover {
    color: #0d6efd !important;
}
</style>