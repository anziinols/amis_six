<?php
// app/Views/sme/sme_show.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<!-- Add Leaflet CSS in the content section -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes') ?>">SMEs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($sme['sme_name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">SME Details</h3>
                    <div>
                        <a href="<?= base_url('smes/staff/' . $sme['id']) ?>" class="btn btn-success me-2">
                            <i class="fas fa-users"></i> View Staff
                        </a>
                        <a href="<?= base_url('smes/' . $sme['id'] . '/edit') ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('smes') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to SMEs
                        </a>
                    </div>
                </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column - Basic Info -->
                <div class="col-md-8">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="130">SME Name</th>
                                    <td><?= esc($sme['sme_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Village</th>
                                    <td><?= esc($sme['village_name']) ?: 'Not specified' ?></td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>
                                        <?php 
                                        $location = [];
                                        if (!empty($sme['province_name'])) $location[] = esc($sme['province_name']);
                                        if (!empty($sme['district_name'])) $location[] = esc($sme['district_name']);
                                        if (!empty($sme['llg_name'])) $location[] = esc($sme['llg_name']);
                                        echo implode(' / ', $location) ?: 'No location set';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $sme['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($sme['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Contact Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="130">Contact Details</th>
                                    <td><?= nl2br(esc($sme['contact_details'])) ?: 'No contact details provided' ?></td>
                                </tr>
                                <tr>
                                    <th>GPS Coordinates</th>
                                    <td><?= esc($sme['gps_coordinates']) ?: 'Not specified' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Description</h5>
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(esc($sme['description'])) ?: 'No description provided' ?>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">System Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="130">Created</th>
                                <td><?= $sme['created_at'] ? date('d M Y H:i', strtotime($sme['created_at'])) : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td><?= $sme['updated_at'] ? date('d M Y H:i', strtotime($sme['updated_at'])) : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Status Changed</th>
                                <td>
                                    <?= $sme['status_at'] ? date('d M Y H:i', strtotime($sme['status_at'])) : '-' ?>
                                    <?= $sme['status_remarks'] ? '<br><small class="text-muted">Remarks: ' . esc($sme['status_remarks']) . '</small>' : '' ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Right Column - Logo and Map -->
                <div class="col-md-4">
                    <!-- Logo -->
                    <div class="mb-4 text-center">
                        <h5 class="border-bottom pb-2">Logo</h5>
                        <?php if (!empty($sme['logo_filepath']) && file_exists(ROOTPATH . $sme['logo_filepath'])): ?>
                            <img src="<?= base_url($sme['logo_filepath']) ?>" alt="SME Logo" class="img-fluid rounded mb-2" style="max-height: 200px;">
                        <?php else: ?>
                            <div class="bg-light p-4 rounded text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p class="mb-0">No logo uploaded</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Map -->
                    <?php if (!empty($sme['gps_coordinates'])): ?>
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Location Map</h5>
                            <div id="map" style="height: 300px;" class="rounded"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });
</script>
<?php if (!empty($sme['gps_coordinates'])): ?>
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            if (document.getElementById('map')) {
                // Parse coordinates
                const coordinates = "<?= $sme['gps_coordinates'] ?>".split(',').map(coord => parseFloat(coord.trim()));
                const lat = coordinates[0];
                const lng = coordinates[1];
                
                // Initialize map
                const map = L.map('map').setView([lat, lng], 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                // Add marker
                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<b><?= esc($sme['sme_name']) ?></b><br><?= esc($sme['village_name']) ?>").openPopup();
                
                // Improve map display
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            }
        });
    </script>
<?php endif; ?>
<?= $this->endSection() ?> 