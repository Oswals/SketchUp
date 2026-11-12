@push('styles')
    <style>
        #conversionModal .modal-content {
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            color: var(--default-color);
            background-color: white;
            padding: 20px 0;
            transition: all 0.5s;
            z-index: 997;
        }

        #modalDropzone:hover {
            border-color: #3150bc !important;
            background-color: #f0f8ff !important;
        }

        .text-primary {
            color: #3150bc !important;
        }

        .btn-primary {
            background: #3150bc;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2a44a3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(49, 80, 188, 0.3);
        }

        .form-select:focus {
            border-color: #3150bc;
            box-shadow: 0 0 0 0.25rem rgba(49, 80, 188, 0.15);
        }
    </style>
@endpush

<!-- ==================== CONVERSION MODAL ==================== -->
<div class="modal fade" id="conversionModal" tabindex="-1" aria-labelledby="conversionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0" style="background: #3150bc; color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="conversionModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    New Conversion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <!-- ✅ FILE BALANCE ALERT -->
                <div class="alert" style="background: linear-gradient(135deg, #e7f1ff 0%, #f0f7ff 100%); border: none; border-radius: 15px;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-file-earmark-check me-2" style="font-size: 1.5rem; color: #3150bc;"></i>
                            <strong style="color: #3150bc;">Available conversions:</strong>
                            <span class="ms-2" style="font-size: 1.2rem; font-weight: 700;">
                                📄 <span id="userCoinBalance">{{ auth()->user()->coin_balance ?? 0 }}</span> file(s)
                            </span>
                        </div>
                        @if(auth()->check() && auth()->user()->coin_balance < 1)
                        <button type="button" class="btn btn-sm btn-primary" onclick="openPricingFromConversion()">
                            <i class="bi bi-plus-circle me-1"></i> Buy
                        </button>
                        @endif
                    </div>
                </div>

                <form action="{{ route('conversion.convert') }}" method="POST" enctype="multipart/form-data" id="modalConversionForm">
                    @csrf

                    <!-- Upload zone -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold">SketchUp File</label>
                        </div>

                        <div class="border border-2 border-dashed rounded p-5 text-center bg-light position-relative"
                             id="modalDropzone"
                             style="min-height: 200px; cursor: pointer; transition: all 0.3s;">
                            <input type="file"
                                   name="sketchup_file"
                                   id="modalSketchupFile"
                                   class="d-none"
                                   accept=".skp"
                                   required>

                            <div id="modalDropzoneContent">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-3 d-block"></i>
                                <h5 class="mb-2">Drag & Drop</h5>
                                <p class="text-muted small mb-3">SketchUp Files (.skp)</p>
                                <p class="text-muted small mb-3">— OR —</p>
                                <button type="button" class="btn btn-primary" id="modalBrowseBtn">
                                    Browse Files
                                </button>
                            </div>

                            <div id="modalFileInfo" class="d-none">
                                <i class="bi bi-file-earmark-check fs-1 text-success mb-2 d-block"></i>
                                <p class="fw-bold mb-1" id="modalFileName"></p>
                                <p class="text-muted small" id="modalFileSize"></p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="modalRemoveFile">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Target SketchUp version -->
                    <div class="mb-4">
                        <label for="modalTargetVersion" class="form-label fw-bold">Target SketchUp Version</label>
                        <select name="target_version" id="modalTargetVersion" class="form-select" required>
                            <option value="" disabled selected>Select a version</option>
                            <option value="2017">SketchUp 2017</option>
                            <option value="2018">SketchUp 2018</option>
                            <option value="2019">SketchUp 2019</option>
                            <option value="2020">SketchUp 2020</option>
                            <option value="2021">SketchUp 2021</option>
                            <option value="2022">SketchUp 2022</option>
                            <option value="2023">SketchUp 2023</option>
                            <option value="2024">SketchUp 2024</option>
                            <option value="2025">SketchUp 2025</option>
                            <option value="2026">SketchUp 2026</option>
                        </select>
                        <small class="text-muted">Choose the version you want to convert to</small>
                    </div>

                    <!-- Submit button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="modalSubmitBtn">
                            <span id="modalSubmitText">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Convert (1 file)
                            </span>
                            <span id="modalLoadingText" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Converting...
                            </span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ==================== SCRIPTS ==================== -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalDropzone = document.getElementById('modalDropzone');
    const modalFileInput = document.getElementById('modalSketchupFile');
    const modalBrowseBtn = document.getElementById('modalBrowseBtn');
    const modalDropzoneContent = document.getElementById('modalDropzoneContent');
    const modalFileInfo = document.getElementById('modalFileInfo');
    const modalRemoveFileBtn = document.getElementById('modalRemoveFile');
    const modalConversionForm = document.getElementById('modalConversionForm');
    const modalSubmitBtn = document.getElementById('modalSubmitBtn');
    const modalSubmitText = document.getElementById('modalSubmitText');
    const modalLoadingText = document.getElementById('modalLoadingText');

    // Get user file balance (still coin_balance in backend)
    const userFileBalance = parseInt(document.getElementById('userCoinBalance')?.textContent || '0');
    console.log('📄 User file balance:', userFileBalance);

    // Open file selector
    if (modalBrowseBtn) {
        modalBrowseBtn.addEventListener('click', () => modalFileInput.click());
    }

    if (modalDropzone) {
        modalDropzone.addEventListener('click', (e) => {
            if (e.target === modalDropzone || e.target === modalDropzoneContent) {
                modalFileInput.click();
            }
        });
    }

    // Handle drag & drop
    if (modalDropzone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            modalDropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            modalDropzone.addEventListener(eventName, () => {
                modalDropzone.style.borderColor = '#3150bc';
                modalDropzone.style.backgroundColor = '#f0f8ff';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            modalDropzone.addEventListener(eventName, () => {
                modalDropzone.style.borderColor = '#dee2e6';
                modalDropzone.style.backgroundColor = '#f8f9fa';
            });
        });

        modalDropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                modalFileInput.files = files;
                handleModalFileSelect(files[0]);
            }
        });
    }

    if (modalFileInput) {
        modalFileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleModalFileSelect(e.target.files[0]);
            }
        });
    }

    function handleModalFileSelect(file) {
        const extension = file.name.split('.').pop().toLowerCase();
        if (extension !== 'skp') {
            alert('⚠️ Please select a SketchUp file (.skp)');
            modalFileInput.value = '';
            return;
        }

        const maxSize = 100 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('⚠️ File is too large. Maximum size: 100 MB');
            modalFileInput.value = '';
            return;
        }

        document.getElementById('modalFileName').textContent = file.name;
        document.getElementById('modalFileSize').textContent = formatFileSize(file.size);

        modalDropzoneContent.classList.add('d-none');
        modalFileInfo.classList.remove('d-none');
    }

    if (modalRemoveFileBtn) {
        modalRemoveFileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            modalFileInput.value = '';
            modalDropzoneContent.classList.remove('d-none');
            modalFileInfo.classList.add('d-none');
        });
    }

    // ✅ HANDLE FORM SUBMISSION
    if (modalConversionForm) {
        modalConversionForm.addEventListener('submit', function(e) {
            console.log('📤 Modal form submitted');

            // File validation
            if (!modalFileInput.files.length) {
                e.preventDefault();
                alert('⚠️ Please select a file');
                return false;
            }

            // Version validation
            const targetVersion = document.getElementById('modalTargetVersion').value;
            if (!targetVersion) {
                e.preventDefault();
                alert('⚠️ Please select a target version');
                return false;
            }

            // ✅ CHECK FILE BALANCE
            if (userFileBalance < 1) {
                e.preventDefault();
                console.log('⚠️ No files available - Opening pricing modal');

                openPricingFromConversion();

                return false;
            }

            // ✅ SUFFICIENT BALANCE - Normal submission
            console.log('✅ Sufficient files - Submitting form');
            modalSubmitBtn.disabled = true;
            modalSubmitText.classList.add('d-none');
            modalLoadingText.classList.remove('d-none');

            return true;
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Reset form when modal is closed
    const conversionModalElement = document.getElementById('conversionModal');
    if (conversionModalElement) {
        conversionModalElement.addEventListener('hidden.bs.modal', function () {
            modalConversionForm.reset();
            modalDropzoneContent.classList.remove('d-none');
            modalFileInfo.classList.add('d-none');
            modalSubmitBtn.disabled = false;
            modalSubmitText.classList.remove('d-none');
            modalLoadingText.classList.add('d-none');
        });
    }
});

// ✅ SPECIFIC FUNCTION: Open pricing from conversion modal
function openPricingFromConversion() {
    console.log('🔄 Transition: Conversion → Pricing Modal');

    // 1. Close conversion modal
    const conversionModalElement = document.getElementById('conversionModal');
    const conversionModal = bootstrap.Modal.getInstance(conversionModalElement);
    if (conversionModal) {
        conversionModal.hide();
    }

    // 2. Wait for modal to fully close
    setTimeout(() => {
        // 3. Open pricing modal
        const pricingModalElement = document.getElementById('pricingModal');
        if (pricingModalElement) {
            const pricingModal = new bootstrap.Modal(pricingModalElement, {
                backdrop: 'static',
                keyboard: true
            });
            pricingModal.show();
        } else {
            console.error('❌ Pricing modal not found');
        }
    }, 300);
}
</script>
@endpush