<?php
// app/Views/reports_activity_maps/reports_activity_map_index.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<!-- Add Leaflet CSS in the content section -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Activities Map</h5>
                        <button onclick="AMISPdf.generateActivityMapsReportPDF()" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Error: <?= esc($error) ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> This map displays all activities with GPS coordinates. Use the legend to identify different activity types.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <!-- Map Legend -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Map Legend</h6>
                                    <div class="d-flex flex-wrap">
                                        <div class="me-4 mb-2">
                                            <img src="<?= base_url('public/assets/system_images/marker-infrastructure.png') ?>" alt="Infrastructure" height="24">
                                            <span class="ms-1">Infrastructure</span>
                                        </div>
                                        <div class="me-4 mb-2">
                                            <img src="<?= base_url('public/assets/system_images/marker-inputs.png') ?>" alt="Inputs" height="24">
                                            <span class="ms-1">Inputs</span>
                                        </div>
                                        <div class="me-4 mb-2">
                                            <img src="<?= base_url('public/assets/system_images/marker-training.png') ?>" alt="Training" height="24">
                                            <span class="ms-1">Training</span>
                                        </div>
                                        <div class="me-4 mb-2">
                                            <img src="<?= base_url('public/assets/system_images/marker-sme.png') ?>" alt="SME" height="24">
                                            <span class="ms-1">SME</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Container -->
                            <div id="activities-map" style="height: 600px;" class="rounded border"></div>
                        </div>
                    </div>

                    <!-- Activity Statistics -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Infrastructure Activities</h5>
                                    <p class="card-text fs-4">
                                        <?= count(array_filter($activities, function($a) { return $a['type'] === 'infrastructure'; })) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Input Activities</h5>
                                    <p class="card-text fs-4">
                                        <?= count(array_filter($activities, function($a) { return $a['type'] === 'inputs'; })) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Training Activities</h5>
                                    <p class="card-text fs-4">
                                        <?= count(array_filter($activities, function($a) { return $a['type'] === 'training'; })) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">SME Locations</h5>
                                    <p class="card-text fs-4">
                                        <?= count(array_filter($activities, function($a) { return $a['type'] === 'sme'; })) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
$(document).ready(function() {
    // Define baseUrl for links
    const baseUrl = '<?= base_url() ?>';

    // Initialize map
    const map = L.map('activities-map');

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Define custom icons for different activity types
    const icons = {
        infrastructure: L.icon({
            iconUrl: '<?= base_url('public/assets/system_images/marker-infrastructure.png') ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        }),
        inputs: L.icon({
            iconUrl: '<?= base_url('public/assets/system_images/marker-inputs.png') ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        }),
        training: L.icon({
            iconUrl: '<?= base_url('public/assets/system_images/marker-training.png') ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        }),
        sme: L.icon({
            iconUrl: '<?= base_url('public/assets/system_images/marker-sme.png') ?>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    };

    // Create layer groups for each activity type
    const layers = {
        infrastructure: L.layerGroup(),
        inputs: L.layerGroup(),
        training: L.layerGroup(),
        sme: L.layerGroup()
    };

    // Add layer groups to map
    Object.values(layers).forEach(layer => layer.addTo(map));

    // Add markers for each activity
    const bounds = L.latLngBounds();
    const activities = <?= json_encode($activities) ?>;

    <?php if (!isset($error)): ?>
    activities.forEach(activity => {
        if (!activity.coordinates) return;

        try {
            // Parse coordinates
            const [lat, lng] = activity.coordinates.split(',').map(coord => parseFloat(coord.trim()));

            if (isNaN(lat) || isNaN(lng)) return;

            // Create marker
            const marker = L.marker([lat, lng], {
                icon: icons[activity.type] || icons.infrastructure
            });

            // Create popup content
            let popupContent = '';
            if (activity.type === 'sme') {
                popupContent = `
                    <div style="min-width: 200px;">
                        <h6>${activity.title}</h6>
                        <p><strong>Type:</strong> SME</p>
                        <p><strong>Location:</strong> ${activity.location || 'N/A'}</p>
                        <p><strong>Description:</strong> ${activity.description || 'N/A'}</p>
                        <p><strong>Contact:</strong> ${activity.contact_details || 'N/A'}</p>
                        <p><strong>GPS:</strong> ${activity.coordinates || 'N/A'}</p>
                    </div>
                `;
            } else {
                popupContent = `
                    <div style="min-width: 200px;">
                        <h6>${activity.title}</h6>
                        <p><strong>Type:</strong> ${activity.type.charAt(0).toUpperCase() + activity.type.slice(1)}</p>
                        <p><strong>Location:</strong> ${activity.location || 'N/A'}</p>
                        <p><strong>Description:</strong> ${activity.description || 'N/A'}</p>
                        <p><strong>GPS:</strong> ${activity.coordinates || 'N/A'}</p>
                        <a href="${baseUrl}activities/${activity.activity_id}" class="btn btn-sm btn-primary" target="_blank">View Details</a>
                    </div>
                `;
            }

            // Bind popup to marker
            marker.bindPopup(popupContent);

            // Add marker to appropriate layer
            layers[activity.type].addLayer(marker);

            // Extend bounds to include this marker
            bounds.extend([lat, lng]);
        } catch (e) {
            console.error('Error adding marker:', e);
        }
    });

    // Fit map to bounds if we have any markers
    if (bounds.isValid()) {
        map.fitBounds(bounds);
    } else {
        // Default view of Papua New Guinea if no markers
        map.setView([-6.314993, 143.95555], 6);
    }

    // Add layer control
    const overlays = {
        "Infrastructure": layers.infrastructure,
        "Inputs": layers.inputs,
        "Training": layers.training,
        "SME": layers.sme
    };

    L.control.layers(null, overlays).addTo(map);

    // Fix map display issues
    setTimeout(() => {
        map.invalidateSize();
    }, 100);

});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
