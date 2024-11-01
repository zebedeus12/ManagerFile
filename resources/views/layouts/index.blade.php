<!-- Bootstrap Toast Notification -->
@if(session('success'))
    <div class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    </script>
@endif
