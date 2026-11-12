@extends('frontend.layout.app')

@section('content')

<!-- Success Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11000;">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Conversion Successful!</strong> Your file is ready.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@if(session('modern_version_info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'info',
            title: 'Format de fichier moderne',
            html: `{{ session('modern_version_info') }}`,
            confirmButtonText: 'Compris',
            confirmButtonColor: '#3085d6'
        });
    });
</script>
@endif

<!-- Conversion Details Section -->
<section id="conversion-details" class="section" style="padding-bottom: 80px; background: #ffffff;">
    <div class="container-fluid">
        <div class="row">

            <!-- Left Sidebar -->
            <div class="col-lg-3 col-xl-2 order-2 order-lg-1">
                <div class="sidebar-container" style="position: sticky; top: 100px;">

                    <!-- Usage Card -->
                    <div class="card border-0 shadow-sm mb-3">


                        @php
                            $conversionsEffectuees = $conversions->count();
                            $conversionsRestantes = auth()->user()->coin_balance ?? 0;
                            $isUnlimited = $conversionsRestantes >= 9999;
                            $totalConversions = $isUnlimited ? $conversionsEffectuees : ($conversionsEffectuees + $conversionsRestantes);
                            $percentage = (!$isUnlimited && $totalConversions > 0) ? ($conversionsEffectuees / $totalConversions) * 100 : 0;
                        @endphp

                        <div class="card-body text-center py-4" style="background: linear-gradient(135deg, #3951bcff 0%, #194ec7ff 100%); color: white; border-radius: 10px;">
                            <h6 class="mb-3 fw-semibold" style="color: #f5f7faff;">Conversions</h6>
                            <div class="mb-3">
                                <span style="font-size: 2.5rem; font-weight: bold;">{{ $conversionsEffectuees }}</span>
                                @if($isUnlimited)
                                    <span style="font-size: 1.6rem; filter: brightness(0) invert(1); font-weight: bold"> /Unlimited</span>
                                @else
                                    <span style="font-size: 1.2rem;"> / {{ $totalConversions }}</span>
                                @endif
                            </div>
                            @if(!$isUnlimited)
                            <div class="progress mb-3" style="height: 8px; background: rgba(255,255,255,0.3);">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;"></div>
                            </div>
                            @endif
                            <p class="small mb-3 opacity-75">recent conversions</p>
                            @if(!$isUnlimited && $conversionsEffectuees == $totalConversions)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#pricingModal" class="btn btn-light btn-sm w-100 fw-semibold">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Buy More Conversions
                                </a>
                            @else
                                <a href="#hero-conversion" class="btn btn-light btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-repeat me-2"></i>
                                    New Conversion
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Shortcuts -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Shortcuts</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="#conversion-table" class="d-flex align-items-center text-decoration-none text-dark">
                                        <i class="bi bi-grid-3x3-gap me-3 fs-5" style="color: #3150bf;"></i>
                                        <span>Recent Files</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none text-dark">
                                        <i class="bi bi-house-door me-3 text-warning fs-5"></i>
                                        <span>Home</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10 order-1 order-lg-2">

                <!-- Hero Conversion Section - Format Paysage Réduit -->
                <section id="hero-conversion" style="padding: 50px 0; background: transparent;">
                    <div class="container" style="max-width: 1800px;">
                        <!-- Card de conversion -->
                        <div class="conversion-card card border-0 shadow-sm" style="background: light-blue; border-radius: 20px;">
                            <div class="card-body" style="padding: 2rem;">

                                <!-- Titre principal -->
                                <div class="text-center mb-3">
                                    <h3 class="d-flex align-items-center justify-content-center flex-wrap" style="color: #333; font-size: 1.3rem; margin-bottom: 0;">
                                        <i class="bi bi-file-earmark-arrow-up fs-3 me-2" style="color: #3150BF;"></i>
                                        <span>Downconvert <span style="background: linear-gradient(135deg, #3150bf 0%, #4a6fd5 100%); color: white; padding: 0.2rem 0.6rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(49, 80, 191, 0.3);">SketchUp</span> files from newer versions to older ones</span>
                                    </h3>
                                </div>

                                <form action="{{ route('conversion.convert') }}" method="POST" enctype="multipart/form-data" id="conversionForm">
                                    @csrf

                                    <!-- Layout Paysage: 2 colonnes -->
                                    <div class="row g-3">

                                        <!-- Colonne Gauche: Upload Zone -->
                                        <div class="col-lg-7">
                                            <label class="form-label fw-bold mb-2" style="color: #333; font-size: 0.9rem;">SketchUp File</label>

                                            <!-- Zone de drag & drop -->
                                            <div class="border border-2 border-dashed rounded p-3 text-center position-relative"
                                                 id="dropzone"
                                                 style="min-height: 280px; cursor: pointer; transition: all 0.3s; border-color: #dee2e6 !important; background: #f8f9fa; border-radius: 15px; display: flex; flex-direction: column; justify-content: center;">
                                                <input type="file"
                                                       name="sketchup_file"
                                                       id="sketchupFile"
                                                       class="d-none"
                                                       accept=".skp"
                                                       required>

                                                <div id="dropzoneContent">
                                                    <i class="bi bi-cloud-arrow-up mb-2 d-block" style="color: #3150BF; font-size: 3rem;"></i>
                                                    <h5 class="mb-2" style="color: #333; font-size: 1rem;">Drag & Drop</h5>
                                                    <p class="small mb-2" style="color: #666; font-size: 0.85rem;">SketchUp Files (.skp)</p>
                                                    <p class="small mb-2" style="color: #999;">— OR —</p>
                                                    <button type="button" class="btn btn-primary btn-sm" id="browseBtn" style="background: linear-gradient(135deg, #3150BF 0%, #3150BF 100%); border: none; box-shadow: 0 4px 15px rgba(49, 80, 191, 0.3); padding: 0.5rem 1.5rem; border-radius: 10px; font-weight: 600; transition: all 0.3s;">
                                                        Browse Files
                                                    </button>
                                                </div>

                                                <div id="fileInfo" class="d-none">
                                                    <i class="bi bi-file-earmark-check mb-2 d-block" style="color: #3150BF; font-size: 3rem;"></i>
                                                    <p class="fw-bold mb-1" id="fileName" style="color: #333; font-size: 0.9rem;"></p>
                                                    <p class="small" id="fileSize" style="color: #666; font-size: 0.85rem;"></p>
                                                    <button type="button" class="btn btn-sm mt-2" id="removeFile" style="border: 1px solid #dee2e6; color: #dc3545; background: #f8f9fa; transition: all 0.3s;">
                                                        <i class="bi bi-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Colonne Droite: Options & Submit -->
                                        <div class="col-lg-5">
                                            <div class="h-100 d-flex flex-column justify-content-between">

                                                <!-- Version Target -->
                                                <div class="mb-3">
                                                    <label for="targetVersion" class="form-label fw-bold" style="color: #333; font-size: 0.95rem;">
                                                        <i class="bi bi-arrow-down-circle me-2" style="color: #3150BF;"></i>
                                                        Target SketchUp Version
                                                    </label>
                                                    <select name="target_version" id="targetVersion" class="form-select form-select-lg" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 0.75rem 1rem; color: #333; font-weight: 500; background: white; font-size: 0.95rem;">
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
                                                    <small class="d-block mt-2" style="color: #666; font-size: 0.85rem;">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Choose the version to convert to
                                                    </small>
                                                </div>

                                                <!-- Alert de balance -->
                                                <div class="alert mb-3" style="background: linear-gradient(135deg, #e7f1ff 0%, #f0f7ff 100%); border: none; border-radius: 12px; padding: 0.9rem;">
                                                    <div class="d-flex align-items-center justifConversionsy-content-between">
                                                        <div>
                                                            <i class="bi bi-file-earmark-check me-2" style="font-size: 1.3rem; color: #3150bc;"></i>
                                                            <strong style="color: #3150bc; font-size: 0.9rem;">Available conversions:</strong>
                                                            <span class="ms-2" style="font-size: 1.1rem; font-weight: 700; color: #3150bc;">
                                                                @if(auth()->user()->coin_balance >= 9999)
                                                                    <span id="userCoinBalance"> ♾️ Unlimited </span>
                                                                @else
                                                                    <span id="userCoinBalance">{{ auth()->user()->coin_balance ?? 0 }}</span> file(s)
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if(auth()->check() && auth()->user()->coin_balance < 1)
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pricingModal" style="font-size: 0.85rem; padding: 0.4rem 1rem; border-radius: 8px;">
                                                            <i class="bi bi-plus-circle me-1"></i> Buy
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Messages d'erreur -->
                                                @if($errors->any())
                                                <div class="alert mb-3" style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; border-radius: 12px; padding: 0.75rem; font-size: 0.9rem;">
                                                    <i class="bi bi-exclamation-circle me-2" style="color: #ffc107;"></i>
                                                    <strong>Error!</strong>
                                                    <ul class="mb-0 mt-1" style="font-size: 0.85rem;">
                                                        @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

                                                <!-- Bouton de soumission -->
                                                <div class="d-grid mb-3">
                                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" style="background: linear-gradient(135deg, #3150BF 0%, #3150BF 100%); border: none; box-shadow: 0 5px 20px rgba(49, 80, 191, 0.35); border-radius: 12px; padding: 0.9rem 2rem; font-weight: 600; font-size: 1.05rem; transition: all 0.3s;">
                                                        <span id="submitText">
                                                            <i class="bi bi-arrow-repeat me-2"></i>
                                                            Convert Now
                                                        </span>
                                                        <span id="loadingText" class="d-none">
                                                            <span class="spinner-border spinner-border-sm me-2"></span>
                                                            Processing...
                                                        </span>
                                                    </button>
                                                </div>

                                                <!-- Note légale -->
                                                <p class="text-center small mb-0" style="color: #666; font-size: 0.78rem; line-height: 1.4;">
                                                    By using this service, you accept our
                                                    <a href="" target="_blank" style="color: #3150BF; font-weight: 500; text-decoration: none;">terms of use</a> and
                                                    <a href="" target="_blank" style="color: #3150BF; font-weight: 500; text-decoration: none;">privacy policy</a>.
                                                </p>

                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section Title -->
                <div class="section-title mb-4 mt-5">
                    <h2 class="mb-2">Conversion History</h2>
                    <p class="text-muted">View and download your converted files</p>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($conversions->isEmpty())
                    <!-- Message if no conversion -->
                    <div class="col-12">
                        <div class="alert text-center py-5" style="background: linear-gradient(135deg, rgba(57,81,188,0.1) 0%, rgba(25,78,199,0.1) 100%); border: 2px dashed #3951bcff; border-radius: 20px;">
                            <i class="bi bi-inbox fs-1 d-block mb-3" style="color: #3951bcff;"></i>
                            <h4 style="color: #3951bcff;">No recent conversions</h4>
                            <p class="mb-4 text-muted">You haven't converted any files yet or your conversions have expired.</p>
                            <a href="#hero-conversion" class="btn btn-lg" style="background: linear-gradient(135deg, #3150bf 0%, #3150bf 100%); color: white; border: none; box-shadow: 0 4px 15px #3150bf;">
                                <i class="bi bi-plus-circle me-2"></i>
                                New Conversion
                            </a>
                        </div>
                    </div>
                @else

                <!-- Conversions Table -->
                <div class="card shadow-sm border-0 mb-4" id="conversion-table">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-down me-2" style="color: #3150bf;"></i>
                                    <span>Converted Files ({{ $conversions->count() }})</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <div class="btn-group btn-group-sm">
                                    @if($conversionsEffectuees === $totalConversions)
                                        <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pricingModal" href="#">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Buy More Conversions
                                        </a>
                                    @else
                                        <a type="button" class="btn btn-primary" href="#hero-conversion">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            New Conversion
                                        </a>
                                        <a type="button" data-bs-toggle="modal" data-bs-target="#pricingModal" href="#" class="btn btn-outline-primary">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Buy More Conversions
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="conversionTable" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;">File</th>
                                        <th style="width: 12%;">Date</th>
                                        <th style="width: 10%;">Source</th>
                                        <th style="width: 10%;">Target</th>
                                        <th style="width: 10%;">Size</th>
                                        <th style="width: 8%;">Duration</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 10%;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($conversions as $conversion)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text me-3 fs-4" style="color: #3150bf;"></i>
                                                <div>
                                                    <div class="fw-semibold text-break" title="{{ $conversion->original_filename }}">
                                                        {{ Str::limit($conversion->original_filename, 30) }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-arrow-right me-1"></i>
                                                        {{ Str::limit($conversion->converted_filename, 30) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $conversion->created_at->format('m/d/Y') }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $conversion->created_at->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                                {{ $conversion->source_version }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                                                {{ $conversion->target_version }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $conversion->formatted_converted_size }}</div>
                                            <small class="text-muted">{{ $conversion->formatted_original_size }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="bi bi-stopwatch me-1"></i>
                                                {{ $conversion->conversion_duration }}s
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Downloaded
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">

                                                @if($conversion->isDownloadable())
                                                    <a href="{{ route('conversion.download', $conversion->id) }}"
                                                    class="btn btn-primary"
                                                    title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @else
                                                    <span class="btn btn-outline-secondary disabled"
                                                        title="File expired after 3 days"
                                                        data-bs-toggle="tooltip">
                                                        <i class="bi bi-clock-history"></i>
                                                    </span>
                                                @endif

                                                <form action="{{ route('conversion.delete', $conversion->id) }}"
                                                    method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                            <p class="text-muted mb-3">No conversions available</p>
                                            <a href="#hero-conversion" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>
                                                New Conversion
                                            </a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @endif
            </div>

        </div>
    </div>
</section>

<!-- Features Section -->
<section id="result-features" class="featured-services section" style="padding: 80px 0; background: #f8f9fa;">
    <div class="container">
        <div class="row gy-4">
            <div class="col-xl-4 col-lg-6">
                <div class="service-item d-flex h-100" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                    <div class="icon flex-shrink-0 rounded-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin-right: 20px; font-size: 2rem; background: rgba(21, 101, 192, 0.1); color: #3150bf;">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h4 class="title mb-3" style="font-size: 1.1rem; font-weight: 600; color: #333;">Important Information</h4>
                        <p class="description mb-0" style="font-size: 0.95rem; color: #666; line-height: 1.6;">Files are kept for 7 days then automatically deleted to ensure your privacy.</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="service-item d-flex h-100" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                    <div class="icon flex-shrink-0 rounded-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin-right: 20px; font-size: 2rem; background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h4 class="title mb-3" style="font-size: 1.1rem; font-weight: 600; color: #333;">Security & Privacy</h4>
                        <p class="description mb-0" style="font-size: 0.95rem; color: #666; line-height: 1.6;">Your files are processed securely with SSL encryption. We don't keep any personal data.</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="service-item d-flex h-100" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                    <div class="icon flex-shrink-0 rounded-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin-right: 20px; font-size: 2rem; background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <div>
                        <h4 class="title mb-3" style="font-size: 1.1rem; font-weight: 600; color: #333;">Speed & Performance</h4>
                        <p class="description mb-0" style="font-size: 0.95rem; color: #666; line-height: 1.6;">Instant conversion optimized for the best possible performance. Unlimited downloads.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MODAL PRICING (Minimal) ==================== -->
<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-bottom" style="background: #3150bc; color: white;">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="pricingModalLabel">Buy Files</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <!-- Trust badge -->
                <div class="text-center mb-4">
                    <div class="trust-badge-modal">
                        <span class="stars">★★★★★</span>
                        <strong>+7,500 users</strong> already trust DownConvert
                    </div>
                </div>

                <!-- Info utilisateur -->
                @auth
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wallet2 fs-4 me-3" style="color: #3150bc;"></i>
                            <div>
                                <strong>Current balance: {{ auth()->user()->display_balance }}</strong>
                                <p class="mb-0 small text-muted">1 file = 1 conversion • No expiration • Secure payment</p>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Packages -->
                <div class="row g-3 justify-content-center">

                    <!-- Package 1 File -->
                    <div class="col-md-5">
                        <div class="card h-100 border text-center">
                            <div class="card-body p-4">
                                <i class="bi bi-file-earmark fs-1 mb-3 d-block" style="color: #3150bc;"></i>
                                <h5 class="fw-bold mb-2">1 File</h5>
                                <p class="text-muted small mb-3">For one-time needs</p>
                                <div class="mb-3">
                                    <span class="fs-2 fw-bold" style="color: #3150bc;">$0.90</span>
                                </div>
                                @auth
                                    <a href="{{ route('coins.checkout', 1) }}" class="btn btn-outline-primary w-100">Buy Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Buy</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Package Unlimited -->
                    <div class="col-md-5">
                        <div class="card h-100 border-primary text-center position-relative" style="border-width: 2px;">
                            <span class="promo-ribbon">Save 70%</span>
                            <div class="card-body p-4">
                                <span class="badge bg-primary mb-2">⭐ Best Value</span>
                                <i class="bi bi-infinity fs-1 mb-3 d-block" style="color: #3150bc;"></i>
                                <h5 class="fw-bold mb-2">Unlimited</h5>
                                <p class="text-muted small mb-3">For professionals</p>
                                <div class="mb-2">
                                    <span class="fs-2 fw-bold" style="color: #3150bc;">$19.90</span>
                                </div>
                                <div class="small text-muted mb-3">One-time • Never expires</div>
                                @auth
                                    <a href="{{ route('coins.checkout', 2) }}" class="btn btn-primary w-100">Buy Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to Buy</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Message informatif -->
                <div class="text-center text-muted mt-4 small">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Secure payment by Stripe &nbsp;•&nbsp;
                    <i class="bi bi-lock-fill text-success me-1"></i>
                    No subscription, no hidden fees
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #pricingModal .card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        #pricingModal .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(49, 80, 188, 0.15);
        }

        #pricingModal .border-primary {
            border-color: #3150bc !important;
        }

        #pricingModal .btn-primary {
            background: #3150bc;
            border-color: #3150bc;
            transition: all 0.3s ease;
        }

        #pricingModal .btn-primary:hover {
            background: #2a44a3;
            border-color: #2a44a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(49, 80, 188, 0.3);
        }

        #pricingModal .btn-outline-primary {
            color: #3150bc;
            border-color: #3150bc;
            transition: all 0.3s ease;
        }

        #pricingModal .btn-outline-primary:hover {
            background: #3150bc;
            border-color: #3150bc;
            color: white;
            transform: translateY(-2px);
        }

        #pricingModal .badge.bg-primary {
            background: #3150bc !important;
        }

        /* Trust badge dans le modal */
        #pricingModal .trust-badge-modal {
            display: inline-block;
            background: #f8f9ff;
            border: 1px solid #e0e4f5;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            color: #333;
        }
        #pricingModal .trust-badge-modal .stars {
            color: #ffb400;
            margin-right: 6px;
        }

        /* Ruban promo sur la carte Unlimited */
        #pricingModal .promo-ribbon {
            position: absolute;
            top: -12px;
            right: 15px;
            background: #ff6b35;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            box-shadow: 0 3px 8px rgba(255, 107, 53, 0.4);
            z-index: 2;
        }
    </style>
@endpush

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .header {
        color: var(--default-color);
        background-color: white;
        padding: 20px 0;
        transition: all 0.5s;
        z-index: 997;
    }

    .sidebar-container {
        padding-right: 15px;
    }

    .sidebar-container .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .sidebar-container .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .sidebar-container a {
        transition: all 0.3s ease;
        padding: 8px 10px;
        border-radius: 8px;
    }

    .sidebar-container a:hover {
        background-color: #f8f9fa;
        padding-left: 15px;
    }

    .toast {
        min-width: 350px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    #conversion-details {
        min-height: 60vh;
        padding-top: 220px;
    }

    /* Hero Conversion Section */
    #hero-conversion #dropzone:hover {
        border-color: #3150bf !important;
        background: #f0f8ff !important;
        transform: scale(1.01);
    }

    #hero-conversion .form-control:focus,
    #hero-conversion .form-select:focus {
        border-color: #3150bf;
        box-shadow: 0 0 0 0.25rem rgba(21, 101, 192, 0.15);
    }

    #hero-conversion #browseBtn:hover {
        background: linear-gradient(135deg, #194ec7ff 0%, #3951bcff 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 80, 191, 0.4) !important;
    }

    #hero-conversion #submitBtn:hover {
        background: linear-gradient(135deg, #194ec7ff 0%, #3951bcff 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 80, 191, 0.4) !important;
    }

    #hero-conversion #removeFile:hover {
        background: #fff5f5 !important;
        border-color: #dc3545 !important;
    }

    /* Table Styles */
    .table thead th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        background-color: #f8f9fa;
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.002);
    }

    .badge {
        font-weight: 500;
        font-size: 0.875rem;
        letter-spacing: 0.3px;
    }

    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12) !important;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding: 5px 15px;
        border: 2px solid #e9ecef;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3150bf;
        box-shadow: 0 0 0 0.25rem rgba(21, 101, 192, 0.15);
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 5px;
    }

    html {
        scroll-behavior: smooth;
    }

    #dropzone {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #targetVersion {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233150BF' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px;
        padding-right: 2.5rem;
    }

    .section-title h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .sidebar-container {
            position: relative !important;
            top: auto !important;
            margin-bottom: 30px;
        }

        #conversion-details {
            min-height: 60vh;
            padding-top: 170px;
        }

        #hero-conversion {
            padding: 30px 0 !important;
        }

        #hero-conversion .card-body {
            padding: 1.5rem !important;
        }

        #hero-conversion h3 {
            font-size: 1.1rem !important;
        }

        #dropzone {
            min-height: 220px !important;
        }

        .table-responsive table {
            font-size: 0.875rem;
        }

        .service-item {
            padding: 20px !important;
        }

        .service-item .icon {
            width: 50px !important;
            height: 50px !important;
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 576px) {
        #hero-conversion .card-body {
            padding: 1rem !important;
        }

        #dropzone {
            min-height: 180px !important;
        }

        #hero-conversion h3 {
            font-size: 0.95rem !important;
            flex-direction: column;
            text-align: center;
        }

        #hero-conversion h3 .bi {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        @if(session('conversion_completed'))
        const toastEl = document.getElementById('successToast');
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 7000
        });
        toast.show();
        @endif

        $('#conversionTable').DataTable({
            "language": {
                "emptyTable": "No conversions available",
                "info": "Showing _START_ to _END_ of _TOTAL_ conversions",
                "infoEmpty": "Showing 0 to 0 of 0 conversions",
                "infoFiltered": "(filtered from _MAX_ total conversions)",
                "lengthMenu": "Show _MENU_ conversions",
                "search": "Search:",
                "zeroRecords": "No matching conversions found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "order": [[1, "desc"]],
            "pageLength": 10,
            "responsive": true
        });

        $('.delete-form').on('submit', function(e) {
            if (!confirm('⚠️ Are you sure you want to delete this file? This action is irreversible.')) {
                e.preventDefault();
                return false;
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));

                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('sketchupFile');
        const browseBtn = document.getElementById('browseBtn');
        const dropzoneContent = document.getElementById('dropzoneContent');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFile = document.getElementById('removeFile');

        browseBtn.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('click', (e) => {
            if (e.target !== removeFile && !removeFile.contains(e.target)) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                displayFileInfo(this.files[0]);
            }
        });

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '#3150bf';
            dropzone.style.background = '#f0f8ff';
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = '#dee2e6';
            dropzone.style.background = '#f8f9fa';
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '#dee2e6';
            dropzone.style.background = '#f8f9fa';

            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                displayFileInfo(e.dataTransfer.files[0]);
            }
        });

        function displayFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            dropzoneContent.classList.add('d-none');
            fileInfo.classList.remove('d-none');
        }

        removeFile.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            dropzoneContent.classList.remove('d-none');
            fileInfo.classList.add('d-none');
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        document.getElementById('conversionForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            loadingText.classList.remove('d-none');
        });
    });
</script>

@if(session('auto_download_conversion_id'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const conversionId = {{ session('auto_download_conversion_id') }};

        console.log('🚀 Démarrage téléchargement automatique pour conversion #' + conversionId);

        setTimeout(function() {
            const downloadUrl = '{{ route("conversion.download", ":id") }}'.replace(':id', conversionId);

            console.log('📥 URL de téléchargement:', downloadUrl);

            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = downloadUrl;
            document.body.appendChild(iframe);

            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = '';
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();

            setTimeout(() => {
                if (iframe.parentNode) {
                    iframe.parentNode.removeChild(iframe);
                }
                if (link.parentNode) {
                    link.parentNode.removeChild(link);
                }

                console.log('✅ Téléchargement déclenché');
                showDownloadNotification();
            }, 1000);

        }, 800);
    });

    function showDownloadNotification() {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; max-width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-download me-2 fs-5"></i>
                <div>
                    <strong>Download started!</strong>
                    <p class="mb-0 small">Your file is being downloaded automatically.</p>
                </div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 150);
            }
        }, 5000);
    }
</script>
@endif

@if(auth()->check())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const conversionsEffectuees = {{ $conversions->count() }};
        const soldeActuel = {{ auth()->user()->coin_balance ?? 0 }};
        const isUnlimited = soldeActuel >= 9999;

        console.log('📊 Stats conversions:', {
            effectuees: conversionsEffectuees,
            solde_restant: soldeActuel,
            unlimited: isUnlimited
        });

        const shouldShowPricingModal = checkIfShouldShowPricing();

        if (shouldShowPricingModal) {
            console.log('🎯 Affichage du modal pricing');
            showPricingModal();
        }

        function checkIfShouldShowPricing() {
            if (isUnlimited) {
                console.log('♾️ Unlimited - pas de modal');
                return false;
            }

            if (soldeActuel === 0) {
                console.log('✅ Déclencheur: Balance à 0 - Modal pricing requis');

                @if(session('conversion_completed'))
                    console.log('   → Conversion vient d\'être complétée');
                @endif

                return true;
            }

            if (soldeActuel > 0) {
                localStorage.removeItem('show_pricing_modal');
                console.log('💰 Crédit disponible - pas de modal');
                return false;
            }

            return false;
        }

        function showPricingModal() {
            setTimeout(function() {
                const modalElement = document.getElementById('pricingModal');

                if (!modalElement) {
                    console.error('❌ Modal #pricingModal introuvable');
                    return;
                }

                const pricingModal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });

                customizeModalContent();
                pricingModal.show();

                modalElement.addEventListener('hidden.bs.modal', function () {
                    console.log('🔒 Modal fermé - marqué comme vu');
                    localStorage.setItem('pricing_modal_dismissed', Date.now());
                }, { once: true });

            }, 1500);
        }

        function customizeModalContent() {
            const modalTitle = document.querySelector('#pricingModal .modal-title');
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-exclamation-circle-fill text-warning me-2"></i>You\'ve used all your credits!';
            }

            const modalSubtitle = document.querySelector('#pricingModal .modal-header p');
            if (modalSubtitle) {
                modalSubtitle.innerHTML = `
                    <strong>${conversionsEffectuees}</strong> conversion(s) completed<br>
                    <span style="color: white;">Recharge to continue converting your files</span>
                `;
            }

            // 2 cartes désormais (plus 3)
            const pricingCards = document.querySelectorAll('#pricingModal .card');
            if (pricingCards.length > 0) {
                pricingCards[0]?.classList.add('border-warning', 'shadow-sm');
            }
        }

        window.forceShowPricingModal = function() {
            localStorage.setItem('show_pricing_modal', 'true');
            showPricingModal();
        };

        window.resetPricingModal = function() {
            localStorage.removeItem('show_pricing_modal');
            localStorage.removeItem('pricing_modal_dismissed');
            console.log('🔄 État du modal réinitialisé');
        };
    });
</script>
@endif

@endpush
