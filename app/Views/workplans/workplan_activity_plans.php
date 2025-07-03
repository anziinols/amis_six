<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<!-- Add Select2 Bootstrap 5 Theme CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<div class="mb-3">
    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id']) ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Activity Details
    </a>
</div>

<!-- Activity and Workplan Information Card -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Activity and Workplan Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Activity Details</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Activity Title:</th>
                        <td><strong><?= esc($activity['title']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td>
                            <?php
                            $branchModel = new \App\Models\BranchesModel();
                            $branch = $branchModel->find($activity['branch_id']);
                            echo esc($branch['name'] ?? 'Not specified');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Activity Type:</th>
                        <td><?= ucfirst($activity['activity_type']) ?></td>
                    </tr>
                    <tr>
                        <th>Supervisor:</th>
                        <td>
                            <?php
                            $userModel = new \App\Models\UserModel();
                            $supervisor = !empty($activity['supervisor_id']) ? $userModel->find($activity['supervisor_id']) : null;
                            echo $supervisor ? esc($supervisor['fname'] . ' ' . $supervisor['lname']) : 'Not assigned';
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Workplan Details</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Workplan Title:</th>
                        <td><strong><?= esc($workplan['title']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td><?= esc($workplan['description'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Period:</th>
                        <td>
                            <?php if (!empty($workplan['start_date']) && !empty($workplan['end_date'])): ?>
                                <?= date('d M Y', strtotime($workplan['start_date'])) ?> -
                                <?= date('d M Y', strtotime($workplan['end_date'])) ?>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Objectives:</th>
                        <td><?= nl2br(esc($workplan['objectives'] ?? 'Not specified')) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Left column: Form to link NASP plans -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Link to NASP Plans</h5>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form id="naspLinkForm" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans/link') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="link_type" value="nasp">
                    <!-- Ensure we're linking to the specific activity -->
                    <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">

                    <div class="mb-3">
                        <label for="output_id" class="form-label">NASP Output</label>
                        <div class="input-group">
                            <select class="form-select" id="output_id" name="output_id">
                                <option value="">Select NASP Output</option>
                                <?php foreach ($naspOutputs as $output): ?>
                                    <option value="<?= $output['output_id'] ?>"><?= esc($output['nasp_code']) ?> - <?= esc($output['output_code']) ?> - <?= esc($output['output_title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="searchNaspOutputBtn" data-bs-toggle="modal" data-bs-target="#naspOutputSearchModal">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Select an output to automatically link to its parent NASP plan, APA, DIP, specific area, and objective.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Link NASP Output</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right column: Table of linked NASP plans -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Linked NASP Plans</h5>
            </div>
            <div class="card-body">
                <div id="naspLinksContainer">
                    <?php if (empty($naspLinks)): ?>
                        <div class="alert alert-info">
                            No NASP plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="naspLinksTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>NASP Code</th>
                                        <th>Output Code</th>
                                        <th>Output Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    foreach ($naspLinks as $link):
                                        // Load the NASP plan and output details
                                        $naspModel = new \App\Models\NaspModel();
                                        $naspPlan = !empty($link['nasp_id']) ? $naspModel->find($link['nasp_id']) : null;
                                        $output = !empty($link['output_id']) ? $naspModel->find($link['output_id']) : null;
                                    ?>
                                    <tr id="nasp-link-row-<?= $link['id'] ?>">
                                        <td><?= $counter++ ?></td>
                                        <td><?= !empty($naspPlan) ? esc($naspPlan['code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($output) ? esc($output['code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($output) ? esc($output['title']) : '<em>Not specified</em>' ?></td>
                                        <td>
                                            <button type="button"
                                                   class="btn btn-danger btn-sm delete-nasp-link"
                                                   data-link-id="<?= $link['id'] ?>"
                                                   data-workplan-id="<?= $workplan['id'] ?>"
                                                   data-activity-id="<?= $activity['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Left column: Form to link Corporate plans -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Link to Corporate Plans</h5>
            </div>
            <div class="card-body">
                <form id="corporateLinkForm" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans/link') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="link_type" value="corporate">
                    <!-- Ensure we're linking to the specific activity -->
                    <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">

                    <div class="mb-3">
                        <label for="strategies_id" class="form-label">Corporate Plan Strategy</label>
                        <div class="input-group">
                            <select class="form-select" id="strategies_id" name="strategies_id">
                                <option value="">Select Corporate Plan Strategy</option>
                                <?php foreach ($corporateStrategies as $strategy): ?>
                                    <option value="<?= $strategy['strategy_id'] ?>"><?= esc($strategy['plan_code']) ?> - <?= esc($strategy['objective_code']) ?> - <?= esc($strategy['strategy_title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="searchCorporateStrategyBtn" data-bs-toggle="modal" data-bs-target="#corporateStrategySearchModal">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Select a strategy to automatically link to its parent corporate plan, overarching objective, objective, and KRA.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Link Corporate Plan Strategy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right column: Table of linked Corporate plans -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Linked Corporate Plans</h5>
            </div>
            <div class="card-body">
                <div id="corporateLinksContainer">
                    <?php if (empty($corporateLinks)): ?>
                        <div class="alert alert-info">
                            No Corporate plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="corporateLinksTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Corporate Plan Code</th>
                                        <th>Objective Code</th>
                                        <th>Strategy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    foreach ($corporateLinks as $link):
                                        // Load the Corporate plan details
                                        $corporatePlanModel = new \App\Models\CorporatePlanModel();
                                        $corporatePlan = !empty($link['corporate_plan_id']) ? $corporatePlanModel->find($link['corporate_plan_id']) : null;
                                        $objective = !empty($link['objective_id']) ? $corporatePlanModel->find($link['objective_id']) : null;
                                        $strategy = !empty($link['strategies_id']) ? $corporatePlanModel->find($link['strategies_id']) : null;
                                    ?>
                                    <tr id="corporate-link-row-<?= $link['id'] ?>">
                                        <td><?= $counter++ ?></td>
                                        <td><?= !empty($corporatePlan) ? esc($corporatePlan['code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($objective) ? esc($objective['code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($strategy) ? esc($strategy['title']) : '<em>Not specified</em>' ?></td>
                                        <td>
                                            <button type="button"
                                                   class="btn btn-danger btn-sm delete-corporate-link"
                                                   data-link-id="<?= $link['id'] ?>"
                                                   data-workplan-id="<?= $workplan['id'] ?>"
                                                   data-activity-id="<?= $activity['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left column: Form to link MTDP plans -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Link to MTDP Plans</h5>
            </div>
            <div class="card-body">
                <form id="mtdpLinkForm" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans/link') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="link_type" value="mtdp">
                    <!-- Ensure we're linking to the specific activity -->
                    <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">

                    <div class="mb-3">
                        <label for="mtdp_strategies_id" class="form-label">MTDP Strategy</label>
                        <div class="input-group">
                            <select class="form-select" id="mtdp_strategies_id" name="strategies_id">
                                <option value="">Select MTDP Strategy</option>
                                <?php foreach ($mtdpStrategies as $strategy): ?>
                                    <option value="<?= $strategy['strategy_id'] ?>"><?= esc($strategy['mtdp_code']) ?> - <?= esc($strategy['spa_code']) ?> - <?= esc($strategy['dip_code']) ?> - <?= esc($strategy['strategy']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="searchMtdpStrategyBtn" data-bs-toggle="modal" data-bs-target="#mtdpStrategySearchModal">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Select a strategy to automatically link to its parent MTDP plan, SPA, DIP, specific area, investment, and KRA.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Link MTDP Strategy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right column: Table of linked MTDP plans -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Linked MTDP Plans</h5>
            </div>
            <div class="card-body">
                <div id="mtdpLinksContainer">
                    <?php if (empty($mtdpLinks)): ?>
                        <div class="alert alert-info">
                            No MTDP plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mtdpLinksTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>MTDP Code</th>
                                        <th>SPA Code</th>
                                        <th>DIP Code</th>
                                        <th>Strategy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    foreach ($mtdpLinks as $link):
                                        // Load the MTDP plan details
                                        $mtdpModel = new \App\Models\MtdpModel();
                                        $spaModel = new \App\Models\MtdpSpaModel();
                                        $dipModel = new \App\Models\MtdpDipModel();
                                        $strategyModel = new \App\Models\MtdpStrategiesModel();

                                        $mtdpPlan = !empty($link['mtdp_id']) ? $mtdpModel->find($link['mtdp_id']) : null;
                                        $spa = !empty($link['spa_id']) ? $spaModel->find($link['spa_id']) : null;
                                        $dip = !empty($link['dip_id']) ? $dipModel->find($link['dip_id']) : null;
                                        $strategy = !empty($link['strategies_id']) ? $strategyModel->find($link['strategies_id']) : null;
                                    ?>
                                    <tr id="mtdp-link-row-<?= $link['id'] ?>">
                                        <td><?= $counter++ ?></td>
                                        <td><?= !empty($mtdpPlan) ? esc($mtdpPlan['abbrev']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($spa) ? esc($spa['code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($dip) ? esc($dip['dip_code']) : '<em>Not specified</em>' ?></td>
                                        <td><?= !empty($strategy) ? esc($strategy['strategy']) : '<em>Not specified</em>' ?></td>
                                        <td>
                                            <button type="button"
                                                   class="btn btn-danger btn-sm delete-mtdp-link"
                                                   data-link-id="<?= $link['id'] ?>"
                                                   data-workplan-id="<?= $workplan['id'] ?>"
                                                   data-activity-id="<?= $activity['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Others Links Section -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Others Links</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Link this activity to other categories such as recurrent activities, special projects, or emergency activities
                    that don't fit into the formal planning frameworks above.
                </p>

                <?php
                // Load the WorkplanOthersLinkModel to get existing others links
                $workplanOthersLinkModel = new \App\Models\WorkplanOthersLinkModel();
                $othersLinks = $workplanOthersLinkModel->getOthersLinksForActivity($activity['id']);
                ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Current Others Links</h6>
                    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/new') ?>"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Others Link
                    </a>
                </div>

                <div id="othersLinksContainer">
                    <?php if (empty($othersLinks)): ?>
                        <div class="alert alert-info">
                            No others links have been created for this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="othersLinksTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    foreach ($othersLinks as $link):
                                    ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td>
                                            <?php
                                            $typeClass = '';
                                            switch ($link['link_type']) {
                                                case 'recurrent':
                                                    $typeClass = 'bg-primary';
                                                    break;
                                                case 'special_project':
                                                    $typeClass = 'bg-success';
                                                    break;
                                                case 'emergency':
                                                    $typeClass = 'bg-danger';
                                                    break;
                                                default:
                                                    $typeClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $typeClass ?>"><?= ucfirst(str_replace('_', ' ', esc($link['link_type']))) ?></span>
                                        </td>
                                        <td>
                                            <strong><?= esc($link['title']) ?></strong>
                                            <?php if (!empty($link['description'])): ?>
                                                <br><small class="text-muted"><?= esc(substr($link['description'], 0, 50)) ?><?= strlen($link['description']) > 50 ? '...' : '' ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($link['category'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                            $priorityClass = '';
                                            switch ($link['priority_level']) {
                                                case 'critical':
                                                    $priorityClass = 'bg-danger';
                                                    break;
                                                case 'high':
                                                    $priorityClass = 'bg-warning';
                                                    break;
                                                case 'medium':
                                                    $priorityClass = 'bg-info';
                                                    break;
                                                default:
                                                    $priorityClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $priorityClass ?>"><?= ucfirst(esc($link['priority_level'] ?? 'medium')) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch ($link['status']) {
                                                case 'active':
                                                    $statusClass = 'bg-success';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-primary';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($link['status'] ?? 'active')) ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/' . $link['id'] . '/edit') ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="post" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/' . $link['id'] . '/delete') ?>"
                                                      style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this others link?')">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-3">
                    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others') ?>"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> View All Others Links
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Define CSRF token variables
let csrfName = '<?= csrf_token() ?>';
let csrfHash = '<?= csrf_hash() ?>';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize toastr notification library
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // NASP Plan form submission
    $('#naspLinkForm').on('submit', function(e) {
        e.preventDefault();

        // Create a FormData object
        let formData = new FormData(this);

        // Add CSRF token to the form data
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfHash
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show success message
                    toastr.success(response.message);

                    // Add the new row to the table
                    if (response.link) {
                        // Check if we need to create the table first (if it was empty before)
                        if ($('#naspLinksTable').length === 0) {
                            let tableHtml = `
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="naspLinksTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>NASP Code</th>
                                                <th>Output Code</th>
                                                <th>Output Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            `;
                            $('#naspLinksContainer').html(tableHtml);
                        }

                        // Get the current row count
                        let rowCount = $('#naspLinksTable tbody tr').length + 1;

                        // Create the new row
                        let newRow = `
                            <tr id="nasp-link-row-${response.link.id}">
                                <td>${rowCount}</td>
                                <td>${response.link.nasp_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.output_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.output_title || '<em>Not specified</em>'}</td>
                                <td>
                                    <button type="button"
                                           class="btn btn-danger btn-sm delete-nasp-link"
                                           data-link-id="${response.link.id}"
                                           data-workplan-id="${$('#naspLinkForm input[name="activity_id"]').val().split('/')[0]}"
                                           data-activity-id="${$('#naspLinkForm input[name="activity_id"]').val()}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Add the row to the table
                        $('#naspLinksTable tbody').append(newRow);

                        // Reset the form
                        $('#naspLinkForm')[0].reset();
                    }
                } else {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while processing your request.');
                console.error(xhr.responseText);
            }
        });
    });

    // NASP Plan link deletion
    $(document).on('click', '.delete-nasp-link', function() {
        const linkId = $(this).data('link-id');
        const workplanId = $(this).data('workplan-id');
        const activityId = $(this).data('activity-id');
        const row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this link?')) {
            // Create FormData object
            let formData = new FormData();
            formData.append(csrfName, csrfHash);
            formData.append('activity_id', activityId);
            formData.append('link_id', linkId);
            formData.append('type', 'nasp');

            $.ajax({
                url: `<?= base_url('workplans/') ?>${workplanId}/activities/${activityId}/plans/${linkId}/delete`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfHash
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Update CSRF hash
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show success message
                        toastr.success(response.message);

                        // Remove the row from the table
                        row.fadeOut(400, function() {
                            $(this).remove();

                            // Renumber the rows
                            $('#naspLinksTable tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });

                            // If no more rows, show the "No NASP plans linked" message
                            if ($('#naspLinksTable tbody tr').length === 0) {
                                $('#naspLinksContainer').html('<div class="alert alert-info">No NASP plans linked to this activity yet.</div>');
                            }
                        });
                    } else {
                        // Update CSRF hash if provided
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show error message
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while processing your request.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

    // Corporate Plan form submission
    $('#corporateLinkForm').on('submit', function(e) {
        e.preventDefault();

        // Create a FormData object
        let formData = new FormData(this);

        // Add CSRF token to the form data
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfHash
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show success message
                    toastr.success(response.message);

                    // Add the new row to the table
                    if (response.link) {
                        // Check if we need to create the table first (if it was empty before)
                        if ($('#corporateLinksTable').length === 0) {
                            let tableHtml = `
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="corporateLinksTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Corporate Plan Code</th>
                                                <th>Objective Code</th>
                                                <th>Strategy</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            `;
                            $('#corporateLinksContainer').html(tableHtml);
                        }

                        // Get the current row count
                        let rowCount = $('#corporateLinksTable tbody tr').length + 1;

                        // Create the new row
                        let newRow = `
                            <tr id="corporate-link-row-${response.link.id}">
                                <td>${rowCount}</td>
                                <td>${response.link.plan_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.objective_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.strategy_title || '<em>Not specified</em>'}</td>
                                <td>
                                    <button type="button"
                                           class="btn btn-danger btn-sm delete-corporate-link"
                                           data-link-id="${response.link.id}"
                                           data-workplan-id="${$('#corporateLinkForm input[name="activity_id"]').val().split('/')[0]}"
                                           data-activity-id="${$('#corporateLinkForm input[name="activity_id"]').val()}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Add the row to the table
                        $('#corporateLinksTable tbody').append(newRow);

                        // Reset the form
                        $('#corporateLinkForm')[0].reset();
                    }
                } else {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while processing your request.');
                console.error(xhr.responseText);
            }
        });
    });

    // Corporate Plan link deletion
    $(document).on('click', '.delete-corporate-link', function() {
        const linkId = $(this).data('link-id');
        const workplanId = $(this).data('workplan-id');
        const activityId = $(this).data('activity-id');
        const row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this link?')) {
            // Create FormData object
            let formData = new FormData();
            formData.append(csrfName, csrfHash);
            formData.append('activity_id', activityId);
            formData.append('link_id', linkId);
            formData.append('type', 'corporate');

            $.ajax({
                url: `<?= base_url('workplans/') ?>${workplanId}/activities/${activityId}/plans/${linkId}/delete`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfHash
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Update CSRF hash
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show success message
                        toastr.success(response.message);

                        // Remove the row from the table
                        row.fadeOut(400, function() {
                            $(this).remove();

                            // Renumber the rows
                            $('#corporateLinksTable tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });

                            // If no more rows, show the "No Corporate plans linked" message
                            if ($('#corporateLinksTable tbody tr').length === 0) {
                                $('#corporateLinksContainer').html('<div class="alert alert-info">No Corporate plans linked to this activity yet.</div>');
                            }
                        });
                    } else {
                        // Update CSRF hash if provided
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show error message
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while processing your request.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

    // MTDP Plan form submission
    $('#mtdpLinkForm').on('submit', function(e) {
        e.preventDefault();

        // Create a FormData object
        let formData = new FormData(this);

        // Add CSRF token to the form data
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfHash
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show success message
                    toastr.success(response.message);

                    // Add the new row to the table
                    if (response.link) {
                        // Check if we need to create the table first (if it was empty before)
                        if ($('#mtdpLinksTable').length === 0) {
                            let tableHtml = `
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="mtdpLinksTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>MTDP Code</th>
                                                <th>SPA Code</th>
                                                <th>DIP Code</th>
                                                <th>Strategy</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            `;
                            $('#mtdpLinksContainer').html(tableHtml);
                        }

                        // Get the current row count
                        let rowCount = $('#mtdpLinksTable tbody tr').length + 1;

                        // Create the new row
                        let newRow = `
                            <tr id="mtdp-link-row-${response.link.id}">
                                <td>${rowCount}</td>
                                <td>${response.link.mtdp_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.spa_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.dip_code || '<em>Not specified</em>'}</td>
                                <td>${response.link.strategy || '<em>Not specified</em>'}</td>
                                <td>
                                    <button type="button"
                                           class="btn btn-danger btn-sm delete-mtdp-link"
                                           data-link-id="${response.link.id}"
                                           data-workplan-id="${$('#mtdpLinkForm input[name="activity_id"]').val().split('/')[0]}"
                                           data-activity-id="${$('#mtdpLinkForm input[name="activity_id"]').val()}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Add the row to the table
                        $('#mtdpLinksTable tbody').append(newRow);

                        // Reset the form
                        $('#mtdpLinkForm')[0].reset();
                    }
                } else {
                    // Update CSRF hash if provided
                    csrfHash = response.csrf_hash || csrfHash;

                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while processing your request.');
                console.error(xhr.responseText);
            }
        });
    });

    // MTDP Plan link deletion
    $(document).on('click', '.delete-mtdp-link', function() {
        const linkId = $(this).data('link-id');
        const workplanId = $(this).data('workplan-id');
        const activityId = $(this).data('activity-id');
        const row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this link?')) {
            // Create FormData object
            let formData = new FormData();
            formData.append(csrfName, csrfHash);
            formData.append('activity_id', activityId);
            formData.append('link_id', linkId);
            formData.append('type', 'mtdp');

            $.ajax({
                url: `<?= base_url('workplans/') ?>${workplanId}/activities/${activityId}/plans/${linkId}/delete`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfHash
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Update CSRF hash
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show success message
                        toastr.success(response.message);

                        // Remove the row from the table
                        row.fadeOut(400, function() {
                            $(this).remove();

                            // Renumber the rows
                            $('#mtdpLinksTable tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });

                            // If no more rows, show the "No MTDP plans linked" message
                            if ($('#mtdpLinksTable tbody tr').length === 0) {
                                $('#mtdpLinksContainer').html('<div class="alert alert-info">No MTDP plans linked to this activity yet.</div>');
                            }
                        });
                    } else {
                        // Update CSRF hash if provided
                        csrfHash = response.csrf_hash || csrfHash;

                        // Show error message
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while processing your request.');
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
</script>

<!-- NASP Output Search Modal -->
<div class="modal fade" id="naspOutputSearchModal" tabindex="-1" aria-labelledby="naspOutputSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="naspOutputSearchModalLabel">Search NASP Output</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="modalOutputSearch" class="form-label">Search Output</label>
                    <select class="form-select" id="modalOutputSearch">
                        <option value="">Type to search for outputs...</option>
                        <?php foreach ($naspOutputs as $output): ?>
                            <option value="<?= $output['output_id'] ?>"
                                    data-nasp-code="<?= esc($output['nasp_code']) ?>"
                                    data-output-code="<?= esc($output['output_code']) ?>"
                                    data-output-title="<?= esc($output['output_title']) ?>">
                                <?= esc($output['nasp_code']) ?> - <?= esc($output['output_code']) ?> - <?= esc($output['output_title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="hierarchyDisplay" class="mt-3" style="display: none;">
                    <h6>Output Hierarchy:</h6>
                    <div class="card">
                        <div class="card-body">
                            <div id="hierarchyContent">
                                <!-- Hierarchy will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectOutputBtn" disabled>Select Output</button>
            </div>
        </div>
    </div>
</div>

<!-- Corporate Plan Strategy Search Modal -->
<div class="modal fade" id="corporateStrategySearchModal" tabindex="-1" aria-labelledby="corporateStrategySearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="corporateStrategySearchModalLabel">Search Corporate Plan Strategy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="modalCorporateStrategySearch" class="form-label">Search Strategy</label>
                    <select class="form-select" id="modalCorporateStrategySearch">
                        <option value="">Type to search for strategies...</option>
                        <?php foreach ($corporateStrategies as $strategy): ?>
                            <option value="<?= $strategy['strategy_id'] ?>"
                                    data-plan-code="<?= esc($strategy['plan_code']) ?>"
                                    data-strategy-title="<?= esc($strategy['strategy_title']) ?>">
                                <?= esc($strategy['plan_code']) ?> - <?= esc($strategy['objective_code']) ?> - <?= esc($strategy['strategy_title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="corporateHierarchyDisplay" class="mt-3" style="display: none;">
                    <h6>Strategy Hierarchy:</h6>
                    <div class="card">
                        <div class="card-body">
                            <div id="corporateHierarchyContent">
                                <!-- Hierarchy will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectCorporateStrategyBtn" disabled>Select Strategy</button>
            </div>
        </div>
    </div>
</div>

<!-- MTDP Strategy Search Modal -->
<div class="modal fade" id="mtdpStrategySearchModal" tabindex="-1" aria-labelledby="mtdpStrategySearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mtdpStrategySearchModalLabel">Search MTDP Strategy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="modalMtdpStrategySearch" class="form-label">Search Strategy</label>
                    <select class="form-select" id="modalMtdpStrategySearch">
                        <option value="">Type to search for strategies...</option>
                        <?php foreach ($mtdpStrategies as $strategy): ?>
                            <option value="<?= $strategy['strategy_id'] ?>"
                                    data-mtdp-code="<?= esc($strategy['mtdp_code']) ?>"
                                    data-strategy="<?= esc($strategy['strategy']) ?>">
                                <?= esc($strategy['mtdp_code']) ?> - <?= esc($strategy['spa_code']) ?> - <?= esc($strategy['dip_code']) ?> - <?= esc($strategy['strategy']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="mtdpHierarchyDisplay" class="mt-3" style="display: none;">
                    <h6>Strategy Hierarchy:</h6>
                    <div class="card">
                        <div class="card-body">
                            <div id="mtdpHierarchyContent">
                                <!-- Hierarchy will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectMtdpStrategyBtn" disabled>Select Strategy</button>
            </div>
        </div>
    </div>
</div>

<script>
// NASP Output Search Modal functionality
$(document).ready(function() {
    let selectedOutputId = null;
    let selectedOutputData = null;

    // Initialize Select2 for the modal search dropdown
    $('#modalOutputSearch').select2({
        dropdownParent: $('#naspOutputSearchModal'),
        placeholder: 'Type to search for outputs...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Handle output selection in modal
    $('#modalOutputSearch').on('change', function() {
        const outputId = $(this).val();
        selectedOutputId = outputId;

        if (outputId) {
            const selectedOption = $(this).find('option:selected');
            selectedOutputData = {
                id: outputId,
                nasp_code: selectedOption.data('nasp-code'),
                output_code: selectedOption.data('output-code'),
                output_title: selectedOption.data('output-title')
            };

            // Load hierarchy for selected output
            loadOutputHierarchy(outputId);
            $('#selectOutputBtn').prop('disabled', false);
        } else {
            $('#hierarchyDisplay').hide();
            $('#selectOutputBtn').prop('disabled', true);
            selectedOutputData = null;
        }
    });

    // Load output hierarchy
    function loadOutputHierarchy(outputId) {
        $.ajax({
            url: '<?= base_url('api/nasp/output-hierarchy/') ?>' + outputId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    const hierarchy = response.data;
                    let hierarchyHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>NASP Plan:</strong><br>
                                <span class="text-muted">${hierarchy.nasp_title || 'N/A'} (${hierarchy.nasp_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>APA:</strong><br>
                                <span class="text-muted">${hierarchy.apa_title || 'N/A'} (${hierarchy.apa_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>DIP:</strong><br>
                                <span class="text-muted">${hierarchy.dip_title || 'N/A'} (${hierarchy.dip_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Specific Area:</strong><br>
                                <span class="text-muted">${hierarchy.specific_area_title || 'N/A'} (${hierarchy.specific_area_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Objective:</strong><br>
                                <span class="text-muted">${hierarchy.objective_title || 'N/A'} (${hierarchy.objective_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Output:</strong><br>
                                <span class="text-muted">${hierarchy.output_title || 'N/A'} (${hierarchy.output_code || 'N/A'})</span>
                            </div>
                        </div>
                    `;

                    $('#hierarchyContent').html(hierarchyHtml);
                    $('#hierarchyDisplay').show();
                } else {
                    $('#hierarchyContent').html('<div class="alert alert-warning">Unable to load hierarchy information.</div>');
                    $('#hierarchyDisplay').show();
                }
            },
            error: function(xhr, status, error) {
                $('#hierarchyContent').html('<div class="alert alert-danger">Error loading hierarchy information.</div>');
                $('#hierarchyDisplay').show();
            }
        });
    }

    // Handle select button click
    $('#selectOutputBtn').on('click', function() {
        if (selectedOutputId && selectedOutputData) {
            // Set the main dropdown value
            $('#output_id').val(selectedOutputId);

            // Close the modal
            $('#naspOutputSearchModal').modal('hide');

            // Show success message
            toastr.success('Output selected successfully');
        }
    });

    // Reset modal when closed
    $('#naspOutputSearchModal').on('hidden.bs.modal', function() {
        $('#modalOutputSearch').val('').trigger('change');
        $('#hierarchyDisplay').hide();
        $('#selectOutputBtn').prop('disabled', true);
        selectedOutputId = null;
        selectedOutputData = null;
    });

    // Corporate Plan Strategy Search Modal functionality
    let selectedCorporateStrategyId = null;
    let selectedCorporateStrategyData = null;

    // Initialize Select2 for Corporate Strategy modal
    $('#modalCorporateStrategySearch').select2({
        dropdownParent: $('#corporateStrategySearchModal'),
        placeholder: 'Type to search for strategies...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Handle corporate strategy selection in modal
    $('#modalCorporateStrategySearch').on('change', function() {
        const strategyId = $(this).val();
        selectedCorporateStrategyId = strategyId;

        if (strategyId) {
            const selectedOption = $(this).find('option:selected');
            selectedCorporateStrategyData = {
                id: strategyId,
                plan_code: selectedOption.data('plan-code'),
                strategy_title: selectedOption.data('strategy-title')
            };

            // Load hierarchy for selected strategy
            loadCorporateStrategyHierarchy(strategyId);
            $('#selectCorporateStrategyBtn').prop('disabled', false);
        } else {
            $('#corporateHierarchyDisplay').hide();
            $('#selectCorporateStrategyBtn').prop('disabled', true);
            selectedCorporateStrategyData = null;
        }
    });

    // Load corporate strategy hierarchy
    function loadCorporateStrategyHierarchy(strategyId) {
        $.ajax({
            url: '<?= base_url('api/corporate/strategy-hierarchy/') ?>' + strategyId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    const hierarchy = response.data;
                    let hierarchyHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Corporate Plan:</strong><br>
                                <span class="text-muted">${hierarchy.plan_title || 'N/A'} (${hierarchy.plan_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Overarching Objective:</strong><br>
                                <span class="text-muted">${hierarchy.overarching_title || 'N/A'} (${hierarchy.overarching_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Objective:</strong><br>
                                <span class="text-muted">${hierarchy.objective_title || 'N/A'} (${hierarchy.objective_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>KRA:</strong><br>
                                <span class="text-muted">${hierarchy.kra_title || 'N/A'} (${hierarchy.kra_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Strategy:</strong><br>
                                <span class="text-muted">${hierarchy.strategy_title || 'N/A'} (${hierarchy.strategy_code || 'N/A'})</span>
                            </div>
                        </div>
                    `;

                    $('#corporateHierarchyContent').html(hierarchyHtml);
                    $('#corporateHierarchyDisplay').show();
                } else {
                    $('#corporateHierarchyContent').html('<div class="alert alert-warning">Unable to load hierarchy information.</div>');
                    $('#corporateHierarchyDisplay').show();
                }
            },
            error: function(xhr, status, error) {
                $('#corporateHierarchyContent').html('<div class="alert alert-danger">Error loading hierarchy information.</div>');
                $('#corporateHierarchyDisplay').show();
            }
        });
    }

    // Handle corporate strategy select button click
    $('#selectCorporateStrategyBtn').on('click', function() {
        if (selectedCorporateStrategyId && selectedCorporateStrategyData) {
            // Set the main dropdown value
            $('#strategies_id').val(selectedCorporateStrategyId);

            // Close the modal
            $('#corporateStrategySearchModal').modal('hide');

            // Show success message
            toastr.success('Corporate Plan Strategy selected successfully');
        }
    });

    // Reset corporate modal when closed
    $('#corporateStrategySearchModal').on('hidden.bs.modal', function() {
        $('#modalCorporateStrategySearch').val('').trigger('change');
        $('#corporateHierarchyDisplay').hide();
        $('#selectCorporateStrategyBtn').prop('disabled', true);
        selectedCorporateStrategyId = null;
        selectedCorporateStrategyData = null;
    });

    // MTDP Strategy Search Modal functionality
    let selectedMtdpStrategyId = null;
    let selectedMtdpStrategyData = null;

    // Initialize Select2 for MTDP Strategy modal
    $('#modalMtdpStrategySearch').select2({
        dropdownParent: $('#mtdpStrategySearchModal'),
        placeholder: 'Type to search for strategies...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Handle MTDP strategy selection in modal
    $('#modalMtdpStrategySearch').on('change', function() {
        const strategyId = $(this).val();
        selectedMtdpStrategyId = strategyId;

        if (strategyId) {
            const selectedOption = $(this).find('option:selected');
            selectedMtdpStrategyData = {
                id: strategyId,
                mtdp_code: selectedOption.data('mtdp-code'),
                strategy: selectedOption.data('strategy')
            };

            // Load hierarchy for selected strategy
            loadMtdpStrategyHierarchy(strategyId);
            $('#selectMtdpStrategyBtn').prop('disabled', false);
        } else {
            $('#mtdpHierarchyDisplay').hide();
            $('#selectMtdpStrategyBtn').prop('disabled', true);
            selectedMtdpStrategyData = null;
        }
    });

    // Load MTDP strategy hierarchy
    function loadMtdpStrategyHierarchy(strategyId) {
        $.ajax({
            url: '<?= base_url('api/mtdp/strategy-hierarchy/') ?>' + strategyId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    const hierarchy = response.data;
                    let hierarchyHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>MTDP Plan:</strong><br>
                                <span class="text-muted">${hierarchy.mtdp_title || 'N/A'} (${hierarchy.mtdp_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>SPA:</strong><br>
                                <span class="text-muted">${hierarchy.spa_title || 'N/A'} (${hierarchy.spa_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>DIP:</strong><br>
                                <span class="text-muted">${hierarchy.dip_title || 'N/A'} (${hierarchy.dip_code || 'N/A'})</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Specific Area:</strong><br>
                                <span class="text-muted">${hierarchy.sa_title || 'N/A'} (${hierarchy.sa_code || 'N/A'})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Investment:</strong><br>
                                <span class="text-muted">${hierarchy.investment || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>KRA:</strong><br>
                                <span class="text-muted">${hierarchy.kra_kpi || 'N/A'}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Strategy:</strong><br>
                                <span class="text-muted">${hierarchy.strategy || 'N/A'}</span>
                            </div>
                        </div>
                    `;

                    $('#mtdpHierarchyContent').html(hierarchyHtml);
                    $('#mtdpHierarchyDisplay').show();
                } else {
                    $('#mtdpHierarchyContent').html('<div class="alert alert-warning">Unable to load hierarchy information.</div>');
                    $('#mtdpHierarchyDisplay').show();
                }
            },
            error: function(xhr, status, error) {
                $('#mtdpHierarchyContent').html('<div class="alert alert-danger">Error loading hierarchy information.</div>');
                $('#mtdpHierarchyDisplay').show();
            }
        });
    }

    // Handle MTDP strategy select button click
    $('#selectMtdpStrategyBtn').on('click', function() {
        if (selectedMtdpStrategyId && selectedMtdpStrategyData) {
            // Set the main dropdown value
            $('#mtdp_strategies_id').val(selectedMtdpStrategyId);

            // Close the modal
            $('#mtdpStrategySearchModal').modal('hide');

            // Show success message
            toastr.success('MTDP Strategy selected successfully');
        }
    });

    // Reset MTDP modal when closed
    $('#mtdpStrategySearchModal').on('hidden.bs.modal', function() {
        $('#modalMtdpStrategySearch').val('').trigger('change');
        $('#mtdpHierarchyDisplay').hide();
        $('#selectMtdpStrategyBtn').prop('disabled', true);
        selectedMtdpStrategyId = null;
        selectedMtdpStrategyData = null;
    });
});
</script>

<?= $this->endSection() ?>
