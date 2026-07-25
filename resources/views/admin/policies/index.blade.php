@extends('layouts.admin')
@section('title', 'Policy Pages')
@section('page')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Policy Pages</h3>
            <small class="text-muted">
                Manage Privacy Policy, Terms & Conditions, Refund Policy and Cookie Policy.
            </small>
        </div>

        <button
            form="policyForm"
            type="submit"
            class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            Save Changes
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form
        id="policyForm"
        action="{{ route('admin.policies.update') }}"
        method="POST">

        @csrf

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="row g-0">

                    <!-- Left Side -->
                    <div class="col-md-3 border-end">

                        <div
                            class="nav flex-column nav-pills p-3"
                            id="policy-tabs"
                            role="tablist">

                            <button
                                type="button"
                                class="nav-link active mb-2"
                                data-bs-toggle="pill"
                                data-bs-target="#privacy">
                                🔒 Privacy Policy
                            </button>

                            <button
                                type="button"
                                class="nav-link mb-2"
                                data-bs-toggle="pill"
                                data-bs-target="#terms">
                                📄 Terms & Conditions
                            </button>

                            <button
                                type="button"
                                class="nav-link mb-2"
                                data-bs-toggle="pill"
                                data-bs-target="#refund">
                                💰 Refund Policy
                            </button>

                            <button
                                type="button"
                                class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#cookies">
                                🍪 Cookie Policy
                            </button>

                        </div>

                    </div>

                    <!-- Right Side -->
                    <div class="col-md-9">

                        <div class="tab-content p-4">

                            <!-- Privacy Tab -->
                            <div
                                class="tab-pane fade show active"
                                id="privacy">
                                @php
                                    $privacy = $policies['privacy'] ?? null;
                                @endphp

                                @if($privacy)
                                    <input type="hidden"
                                           name="policies[{{ $privacy->id }}][id]"
                                           value="{{ $privacy->id }}">
                                    @include('admin.policies.partials.form', ['policy' => $privacy])
                                @endif
                            </div>

                            <!-- Terms Tab -->
                            <div
                                class="tab-pane fade"
                                id="terms">
                                @php
                                    $terms = $policies['terms'] ?? null;
                                @endphp

                                @if($terms)
                                    <input type="hidden"
                                           name="policies[{{ $terms->id }}][id]"
                                           value="{{ $terms->id }}">
                                    @include('admin.policies.partials.form', ['policy' => $terms])
                                @endif
                            </div>

                            <!-- Refund Tab -->
                            <div
                                class="tab-pane fade"
                                id="refund">
                                @php
                                    $refund = $policies['refund'] ?? null;
                                @endphp

                                @if($refund)
                                    <input type="hidden"
                                           name="policies[{{ $refund->id }}][id]"
                                           value="{{ $refund->id }}">
                                    @include('admin.policies.partials.form', ['policy' => $refund])
                                @endif
                            </div>

                            <!-- Cookies Tab -->
                            <div
                                class="tab-pane fade"
                                id="cookies">
                                @php
                                    $cookies = $policies['cookies'] ?? null;
                                @endphp

                                @if($cookies)
                                    <input type="hidden"
                                           name="policies[{{ $cookies->id }}][id]"
                                           value="{{ $cookies->id }}">
                                    @include('admin.policies.partials.form', ['policy' => $cookies])
                                @endif
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Store editor instances
    let editors = {};

    // Function to initialize CKEditor
    function initEditor(editorElement) {
        // Skip if already initialized
        if (!editorElement || editors[editorElement.id]) return;
        
        ClassicEditor
            .create(editorElement, {
                // Optional: Customize toolbar
                toolbar: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'undo',
                    'redo'
                ]
            })
            .then(editor => {
                editors[editorElement.id] = editor;
                console.log('Editor initialized for:', editorElement.id);
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    }

    // Initialize editors in active tab only
    document.querySelectorAll('.tab-pane.active .editor').forEach(editor => {
        // Give unique ID if not present
        if (!editor.id) {
            editor.id = 'editor_' + Math.random().toString(36).substr(2, 9);
        }
        initEditor(editor);
    });

    // When tab is shown, initialize its editors
    document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function (e) {
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            if (targetPane) {
                targetPane.querySelectorAll('.editor').forEach(editor => {
                    if (!editor.id) {
                        editor.id = 'editor_' + Math.random().toString(36).substr(2, 9);
                    }
                    initEditor(editor);
                });
            }
        });
    });
});
</script>
@endsection