<?= $this->extend('templates/system_template') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-chart-bar text-primary"></i>
                    Comprehensive HR Analytics Dashboard
                </h2>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= number_format($chartData['summary_stats']['total_staff'] ?? 0) ?></h4>
                            <p class="mb-0">Total Staff</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= number_format($chartData['summary_stats']['total_branches'] ?? 0) ?></h4>
                            <p class="mb-0">Branches</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= number_format($chartData['summary_stats']['average_age'] ?? 0, 1) ?></h4>
                            <p class="mb-0">Average Age</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-birthday-cake fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">K<?= number_format($chartData['summary_stats']['total_budget'] / 1000, 0) ?></h4>
                            <p class="mb-0">Total Budget (K)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Distribution Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" id="age-distribution-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-birthday-cake text-info"></i> Age Distribution Analytics</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('age-distribution-card', this)">
                        <i class="fas fa-print"></i> Print Section
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Age Range Distribution Chart -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Age & Gender Distribution</strong>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('ageRangeChart')">
                                        <i class="fas fa-copy"></i> Copy Chart
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="ageRangeChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Age by Gender Table -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Age Distribution by Gender</strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Age Range</th>
                                                    <th>Male</th>
                                                    <th>Female</th>
                                                    <th>Unspecified</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $ageByGender = $chartData['age_by_gender'] ?? [];
                                                foreach ($chartData['age_ranges'] ?? [] as $ageRange => $total): 
                                                    $maleCount = $ageByGender['male'][$ageRange] ?? 0;
                                                    $femaleCount = $ageByGender['female'][$ageRange] ?? 0;
                                                    $unspecifiedCount = $ageByGender['unspecified'][$ageRange] ?? 0;
                                                ?>
                                                <tr>
                                                    <td><strong><?= esc($ageRange) ?></strong></td>
                                                    <td><span class="badge bg-primary"><?= $maleCount ?></span></td>
                                                    <td><span class="badge bg-danger"><?= $femaleCount ?></span></td>
                                                    <td><span class="badge bg-secondary"><?= $unspecifiedCount ?></span></td>
                                                    <td><strong><?= $total ?></strong></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Age Statistics -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Age Statistics</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Average Age:</strong> <?= number_format($chartData['summary_stats']['average_age'] ?? 0, 1) ?> years</p>
                                            <p><strong>Total Staff:</strong> <?= number_format($chartData['summary_stats']['total_staff'] ?? 0) ?></p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>Staff with DOB:</strong> <?= number_format($ageDistribution['users_with_dob'] ?? 0) ?></p>
                                            <p><strong>Data Coverage:</strong> <?= number_format((($ageDistribution['users_with_dob'] ?? 0) / ($ageDistribution['total_users'] ?? 1)) * 100, 1) ?>%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Age Extremes</strong></div>
                                <div class="card-body">
                                    <?php if ($ageDistribution['youngest_employee']): ?>
                                        <p><strong>Youngest Employee:</strong><br>
                                        <?= $ageDistribution['youngest_employee']['name'] ?> (<?= $ageDistribution['youngest_employee']['age'] ?> years)<br>
                                        <small class="text-muted"><?= $ageDistribution['youngest_employee']['branch'] ?></small></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($ageDistribution['oldest_employee']): ?>
                                        <p><strong>Oldest Employee:</strong><br>
                                        <?= $ageDistribution['oldest_employee']['name'] ?> (<?= $ageDistribution['oldest_employee']['age'] ?> years)<br>
                                        <small class="text-muted"><?= $ageDistribution['oldest_employee']['branch'] ?></small></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender Distribution Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" id="gender-distribution-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-venus-mars text-success"></i> Gender Distribution Analytics</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('gender-distribution-card', this)">
                        <i class="fas fa-print"></i> Print Section
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Gender Summary Chart -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Overall Gender Distribution</strong>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('genderSummaryChart')">
                                        <i class="fas fa-copy"></i> Copy Chart
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="genderSummaryChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gender by Role Table -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Gender Distribution by Role</strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Role</th>
                                                    <th>Male</th>
                                                    <th>Female</th>
                                                    <th>Unspecified</th>
                                                    <th>Total</th>
                                                    <th>Male %</th>
                                                    <th>Female %</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $genderByRole = $chartData['gender_by_role'] ?? [];
                                                foreach ($genderByRole as $role => $genders): 
                                                    $total = $genders['male'] + $genders['female'] + $genders['unspecified'];
                                                    $malePercent = $total > 0 ? round(($genders['male'] / $total) * 100, 1) : 0;
                                                    $femalePercent = $total > 0 ? round(($genders['female'] / $total) * 100, 1) : 0;
                                                ?>
                                                <tr>
                                                    <td><strong><?= esc(ucfirst($role)) ?></strong></td>
                                                    <td><span class="badge bg-primary"><?= $genders['male'] ?></span></td>
                                                    <td><span class="badge bg-danger"><?= $genders['female'] ?></span></td>
                                                    <td><span class="badge bg-secondary"><?= $genders['unspecified'] ?></span></td>
                                                    <td><strong><?= $total ?></strong></td>
                                                    <td><?= $malePercent ?>%</td>
                                                    <td><?= $femalePercent ?>%</td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Statistics -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header"><strong>Gender Statistics Summary</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <h3 class="text-primary"><?= number_format($chartData['gender_summary']['male'] ?? 0) ?></h3>
                                            <p class="mb-0">Male Staff</p>
                                            <small class="text-muted"><?= number_format($chartData['gender_percentages']['male'] ?? 0, 1) ?>%</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h3 class="text-danger"><?= number_format($chartData['gender_summary']['female'] ?? 0) ?></h3>
                                            <p class="mb-0">Female Staff</p>
                                            <small class="text-muted"><?= number_format($chartData['gender_percentages']['female'] ?? 0, 1) ?>%</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h3 class="text-secondary"><?= number_format($chartData['gender_summary']['unspecified'] ?? 0) ?></h3>
                                            <p class="mb-0">Unspecified</p>
                                            <small class="text-muted"><?= number_format($chartData['gender_percentages']['unspecified'] ?? 0, 1) ?>%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Strength Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" id="staff-strength-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users text-warning"></i> Staff Strength by Division</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('staff-strength-card', this)">
                        <i class="fas fa-print"></i> Print Section
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Staff by Branch Chart -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Staff Distribution by Branch</strong>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('staffBranchChart')">
                                        <i class="fas fa-copy"></i> Copy Chart
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="staffBranchChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Staff by Role Chart -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Staff Distribution by Role</strong>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('staffRoleChart')">
                                        <i class="fas fa-copy"></i> Copy Chart
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="staffRoleChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    // Chart.js configuration
    const colors = {
        primary: '#007bff',
        secondary: '#6c757d',
        success: '#28a745',
        danger: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8',
        light: '#f8f9fa',
        dark: '#343a40'
    };

    // Age Range Distribution Chart (now stacked with gender data)
    const ageRangeCtx = document.getElementById('ageRangeChart').getContext('2d');
    const ageRangeData = <?= json_encode($chartData['age_ranges'] ?? []) ?>;
    const ageByGenderData = <?= json_encode($chartData['age_by_gender'] ?? []) ?>;
    
    // Prepare data arrays for the stacked bar chart
    const ageRangeLabels = Object.keys(ageRangeData);
    const maleCounts = [];
    const femaleCounts = [];
    const unspecifiedCounts = [];
    
    // Extract gender counts for each age range
    ageRangeLabels.forEach(range => {
        maleCounts.push(ageByGenderData.male?.[range] || 0);
        femaleCounts.push(ageByGenderData.female?.[range] || 0);
        unspecifiedCounts.push(ageByGenderData.unspecified?.[range] || 0);
    });
    
    const stackedBar = new Chart(ageRangeCtx, {
        type: 'bar',
        data: {
            labels: ageRangeLabels,
            datasets: [
                {
                    label: 'Male',
                    data: maleCounts,
                    backgroundColor: colors.primary,
                    borderColor: colors.primary,
                    borderWidth: 1
                },
                {
                    label: 'Female',
                    data: femaleCounts,
                    backgroundColor: colors.danger,
                    borderColor: colors.danger,
                    borderWidth: 1
                },
                {
                    label: 'Unspecified',
                    data: unspecifiedCounts,
                    backgroundColor: colors.secondary,
                    borderColor: colors.secondary,
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Age & Gender Distribution (Stacked)'
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gender Summary Chart
    const genderSummaryCtx = document.getElementById('genderSummaryChart').getContext('2d');
    const genderSummaryData = <?= json_encode($chartData['gender_summary'] ?? []) ?>;

    new Chart(genderSummaryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female', 'Unspecified'],
            datasets: [{
                data: [
                    genderSummaryData.male || 0,
                    genderSummaryData.female || 0,
                    genderSummaryData.unspecified || 0
                ],
                backgroundColor: [
                    colors.primary,
                    colors.danger,
                    colors.secondary
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Overall Gender Distribution'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Staff by Branch Chart
    const staffBranchCtx = document.getElementById('staffBranchChart').getContext('2d');
    const staffBranchData = <?= json_encode($chartData['staff_by_branch'] ?? []) ?>;

    const branchLabels = [];
    const branchCounts = [];

    Object.values(staffBranchData).forEach(branch => {
        branchLabels.push(branch.branch_name);
        branchCounts.push(branch.total_staff);
    });

    new Chart(staffBranchCtx, {
        type: 'bar',
        data: {
            labels: branchLabels,
            datasets: [{
                label: 'Total Staff',
                data: branchCounts,
                backgroundColor: colors.warning,
                borderColor: colors.warning,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Staff Distribution by Branch'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Staff by Role Chart
    const staffRoleCtx = document.getElementById('staffRoleChart').getContext('2d');
    const staffRoleData = <?= json_encode($chartData['staff_by_role'] ?? []) ?>;

    new Chart(staffRoleCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(staffRoleData),
            datasets: [{
                data: Object.values(staffRoleData),
                backgroundColor: [
                    colors.danger,
                    colors.warning,
                    colors.success,
                    colors.info
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Staff Distribution by Role'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Copy chart as image function
    function copyChartAsImage(chartId) {
        const canvas = document.getElementById(chartId);
        canvas.toBlob(function(blob) {
            const item = new ClipboardItem({ "image/png": blob });
            navigator.clipboard.write([item]).then(function() {
                toastr.success('Chart copied to clipboard!');
            }).catch(function(error) {
                console.error('Error copying chart:', error);
                toastr.error('Failed to copy chart to clipboard');
            });
        });
    }

    // Print specific section function
    function printSection(sectionId, buttonElement) {
        const sectionElement = document.getElementById(sectionId);
        if (!sectionElement) {
            console.error('Section not found:', sectionId);
            return;
        }

        // Store original button content and show spinner
        const originalContent = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        buttonElement.disabled = true;

        // Show loading indicator
        const loadingToast = toastr.info('Preparing section for printing...', 'Please wait', {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: false
        });

        // Configure html2canvas options for better quality
        const options = {
            scale: 2, // Higher resolution
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            width: sectionElement.scrollWidth,
            height: sectionElement.scrollHeight,
            scrollX: 0,
            scrollY: 0,
            onclone: function(clonedDoc) {
                // Ensure charts are rendered in the cloned document
                const clonedSection = clonedDoc.getElementById(sectionId);
                if (clonedSection) {
                    // Add some padding for better appearance
                    clonedSection.style.padding = '20px';
                    clonedSection.style.margin = '0';
                    clonedSection.style.backgroundColor = '#ffffff';
                }
            }
        };

        // Convert section to canvas
        html2canvas(sectionElement, options).then(function(canvas) {
            // Clear loading toast
            toastr.clear(loadingToast);
            
            // Restore button state
            buttonElement.innerHTML = originalContent;
            buttonElement.disabled = false;

            // Get section title for dynamic naming
            const sectionTitle = sectionElement.querySelector('.card-header h5')?.textContent?.trim() || 'Section Print';

            // Try to create a new window for printing
            const printWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');

            // Check if popup was blocked or failed
            if (!printWindow || printWindow.closed || typeof printWindow.closed === 'undefined') {
                // Popup was blocked, offer download instead
                toastr.warning('Popup blocked! Downloading image instead...', 'Download');
                downloadCanvasAsImage(canvas, sectionTitle);
                // Restore button state
                buttonElement.innerHTML = originalContent;
                buttonElement.disabled = false;
                return;
            }

            // Check if document is accessible
            try {
                if (!printWindow.document) {
                    throw new Error('Cannot access popup document');
                }

                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>${sectionTitle} - Print</title>
                        <meta charset="utf-8">
                        <style>
                            body {
                                margin: 0;
                                padding: 20px;
                                background: white;
                                display: flex;
                                justify-content: center;
                                align-items: flex-start;
                                font-family: Arial, sans-serif;
                            }
                            .print-container {
                                max-width: 100%;
                                text-align: center;
                            }
                            .print-header {
                                margin-bottom: 20px;
                                font-size: 18px;
                                font-weight: bold;
                                color: #333;
                            }
                            .print-image {
                                max-width: 100%;
                                height: auto;
                                border: 1px solid #ddd;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            }
                            .print-footer {
                                margin-top: 20px;
                                font-size: 12px;
                                color: #666;
                            }
                            @media print {
                                body {
                                    padding: 0;
                                }
                                .print-container {
                                    max-width: none;
                                }
                                .print-image {
                                    border: none;
                                    box-shadow: none;
                                    max-width: 100%;
                                    page-break-inside: avoid;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-container">
                            <div class="print-header">${sectionTitle}</div>
                            <img src="${canvas.toDataURL('image/png')}" alt="${sectionTitle}" class="print-image" onload="window.focus();" />
                            <div class="print-footer">Generated on ${new Date().toLocaleString()}</div>
                        </div>
                    </body>
                    </html>
                `);

                printWindow.document.close();

                // Wait for content to load then trigger print
                setTimeout(function() {
                    try {
                        printWindow.focus();
                        printWindow.print();

                        // Set up cleanup after printing
                        const cleanup = function() {
                            try {
                                if (!printWindow.closed) {
                                    printWindow.close();
                                }
                            } catch (e) {
                                console.log('Print window cleanup completed');
                            }
                        };

                        // Try multiple cleanup methods
                        if (printWindow.onafterprint !== undefined) {
                            printWindow.onafterprint = cleanup;
                        } else {
                            // Fallback for browsers that don't support onafterprint
                            setTimeout(cleanup, 3000);
                        }

                    } catch (printError) {
                        console.error('Print error:', printError);
                        toastr.warning('Print dialog may have issues. You can manually print from the opened window.', 'Print Warning');
                    }
                }, 1000);

                toastr.success('Section prepared for printing!', 'Success');

            } catch (docError) {
                console.error('Document access error:', docError);
                toastr.warning('Cannot access print window. Downloading image instead...', 'Download');
                downloadCanvasAsImage(canvas, sectionTitle);
                // Restore button state
                buttonElement.innerHTML = originalContent;
                buttonElement.disabled = false;
            }

        }).catch(function(error) {
            // Clear loading toast and restore button state
            toastr.clear(loadingToast);
            buttonElement.innerHTML = originalContent;
            buttonElement.disabled = false;
            console.error('Error generating image:', error);
            toastr.error('Failed to prepare section for printing. Please try again.', 'Error');
        });
    }

    // Fallback function to download canvas as image when popup is blocked
    function downloadCanvasAsImage(canvas, sectionTitle) {
        try {
            // Create download link
            const link = document.createElement('a');
            link.download = `${sectionTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase()}_${new Date().getTime()}.png`;
            link.href = canvas.toDataURL('image/png');

            // Trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            toastr.success('Image downloaded successfully! You can print it from your downloads folder.', 'Download Complete');
        } catch (downloadError) {
            console.error('Download error:', downloadError);
            toastr.error('Failed to download image. Please try enabling popups for this site.', 'Download Failed');
        }
    }
</script>

<!-- Workload Distribution Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" id="workload-distribution-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tasks text-primary"></i> Workload Distribution Analytics</h5>
                <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('workload-distribution-card', this)">
                    <i class="fas fa-print"></i> Print Section
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Workload Summary Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Workload Distribution Summary</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('workloadSummaryChart')">
                                    <i class="fas fa-copy"></i> Copy Chart
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="workloadSummaryChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Performers by Workload -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header"><strong>Top 5 Staff by Workload</strong></div>
                            <div class="card-body" style="overflow-x: auto;">
                                <table id="topWorkloadTable" class="table table-bordered table-striped table-sm" style="white-space: nowrap; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Role</th>
                                            <th>Total Workload</th>
                                            <th>Workplans</th>
                                            <th>Activities</th>
                                            <th>Proposals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($workloadDistribution['top_performers'] as $userId => $user): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td><?= $user['user_name'] ?></td>
                                                <td><?= $user['branch_name'] ?></td>
                                                <td><span class="badge bg-dark"><?= ucfirst($user['role']) ?></span></td>
                                                <td><strong><?= $user['total_workload'] ?></strong></td>
                                                <td><?= $user['supervised_workplans'] ?></td>
                                                <td><?= $user['supervised_activities'] ?></td>
                                                <td><?= $user['assigned_proposals'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Accountability Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" id="financial-accountability-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave text-success"></i> Financial Accountability Analytics</h5>
                <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('financial-accountability-card', this)">
                    <i class="fas fa-print"></i> Print Section
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Budget by Status Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Budget Allocation by Status</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('budgetStatusChart')">
                                    <i class="fas fa-copy"></i> Copy Chart
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="budgetStatusChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Budget Managers -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header"><strong>Top 5 Budget Managers</strong></div>
                            <div class="card-body" style="overflow-x: auto;">
                                <table id="topBudgetTable" class="table table-bordered table-striped table-sm" style="white-space: nowrap; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Total Budget</th>
                                            <th>Approved</th>
                                            <th>Pending</th>
                                            <th>Proposals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($financialAccountability['top_budget_managers'] as $userId => $user): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td><?= $user['user_name'] ?></td>
                                                <td><?= $user['branch_name'] ?></td>
                                                <td><strong>K<?= number_format($user['total_budget'] / 1000, 0) ?></strong></td>
                                                <td>K<?= number_format($user['approved_budget'] / 1000, 0) ?></td>
                                                <td>K<?= number_format($user['pending_budget'] / 1000, 0) ?></td>
                                                <td><?= $user['proposal_count'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Workload Summary Chart
    const workloadSummaryCtx = document.getElementById('workloadSummaryChart').getContext('2d');
    const workloadSummaryData = <?= json_encode($chartData['workload_summary'] ?? []) ?>;

    new Chart(workloadSummaryCtx, {
        type: 'doughnut',
        data: {
            labels: ['High Workload (>10)', 'Medium Workload (5-10)', 'Low Workload (1-4)', 'No Workload'],
            datasets: [{
                data: [
                    workloadSummaryData.high_workload || 0,
                    workloadSummaryData.medium_workload || 0,
                    workloadSummaryData.low_workload || 0,
                    workloadSummaryData.no_workload || 0
                ],
                backgroundColor: [
                    colors.danger,
                    colors.warning,
                    colors.success,
                    colors.secondary
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Staff Workload Distribution'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Budget by Status Chart
    const budgetStatusCtx = document.getElementById('budgetStatusChart').getContext('2d');
    const budgetStatusData = <?= json_encode($chartData['budget_by_status'] ?? []) ?>;

    new Chart(budgetStatusCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(budgetStatusData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            datasets: [{
                label: 'Budget Amount (K)',
                data: Object.values(budgetStatusData).map(value => value / 1000),
                backgroundColor: [
                    colors.warning,
                    colors.info,
                    colors.success,
                    colors.primary,
                    colors.secondary
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Budget Allocation by Proposal Status'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'K' + value;
                        }
                    }
                }
            }
        }
    });

    // Initialize DataTables for all tables
    $(document).ready(function() {
        const dataTableConfig = {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(doc) {
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 9,
                            color: 'black',
                            fillColor: '#f0f0f0'
                        };
                        doc.defaultStyle.fontSize = 8;
                    }
                }
            ],
            responsive: true,
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            scrollX: true,
            language: {
                search: "Search:",
                zeroRecords: "No matching records found"
            }
        };

        // Initialize DataTables for each table
        $('#topWorkloadTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'Top Staff by Workload'
            }]
        });

        $('#topBudgetTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'Top Budget Managers'
            }]
        });

        $('#topPerformersTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'Top Performing Staff'
            }]
        });
    });
</script>

<!-- Performance Metrics Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" id="performance-metrics-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-star text-warning"></i> Performance Metrics Analytics</h5>
                <button type="button" class="btn btn-outline-primary btn-sm print-section-btn" onclick="printSection('performance-metrics-card', this)">
                    <i class="fas fa-print"></i> Print Section
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Performance Distribution Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Performance Rating Distribution</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('performanceDistributionChart')">
                                    <i class="fas fa-copy"></i> Copy Chart
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="performanceDistributionChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Performers Table -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header"><strong>Top 5 Performing Staff</strong></div>
                            <div class="card-body" style="overflow-x: auto;">
                                <table id="topPerformersTable" class="table table-bordered table-striped table-sm" style="white-space: nowrap; min-width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Role</th>
                                            <th>Avg Rating</th>
                                            <th>Completion Rate</th>
                                            <th>Total Proposals</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($chartData['top_performers_rating'] ?? [] as $userId => $user): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td><?= $user['user_name'] ?></td>
                                                <td><?= $user['branch_name'] ?></td>
                                                <td><span class="badge badge-primary"><?= ucfirst($user['role']) ?></span></td>
                                                <td>
                                                    <strong><?= number_format($user['average_rating'], 2) ?></strong>
                                                    <div class="rating-stars">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $user['average_rating']): ?>
                                                                <i class="fas fa-star"></i>
                                                            <?php elseif ($i - 0.5 <= $user['average_rating']): ?>
                                                                <i class="fas fa-star-half-alt"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-star"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </div>
                                                </td>
                                                <td><?= number_format($user['completion_rate'], 1) ?>%</td>
                                                <td><?= $user['total_proposals'] ?></td>
                                                <td>
                                                    <?php
                                                    $category = $user['performance_category'];
                                                    $badgeClass = '';
                                                    switch ($category) {
                                                        case 'excellent': $badgeClass = 'badge-success'; break;
                                                        case 'good': $badgeClass = 'badge-primary'; break;
                                                        case 'average': $badgeClass = 'badge-warning'; break;
                                                        case 'below_average': $badgeClass = 'badge-danger'; break;
                                                        case 'poor': $badgeClass = 'badge-dark'; break;
                                                        default: $badgeClass = 'badge-secondary'; break;
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $category)) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary Statistics -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"><strong>Performance Summary Statistics</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <h4 class="text-primary"><?= number_format($performanceMetrics['total_rated_proposals'] ?? 0) ?></h4>
                                        <p class="mb-0">Total Rated Proposals</p>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h4 class="text-success"><?= number_format($performanceMetrics['average_rating'] ?? 0, 2) ?></h4>
                                        <p class="mb-0">Average Rating</p>
                                        <div class="rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= ($performanceMetrics['average_rating'] ?? 0)): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php elseif ($i - 0.5 <= ($performanceMetrics['average_rating'] ?? 0)): ?>
                                                    <i class="fas fa-star-half-alt"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h4 class="text-warning"><?= number_format(($performanceMetrics['performance_distribution']['excellent'] ?? 0) + ($performanceMetrics['performance_distribution']['good'] ?? 0)) ?></h4>
                                        <p class="mb-0">High Performers</p>
                                        <small class="text-muted">(Excellent + Good)</small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <h4 class="text-info"><?= number_format(count($performanceMetrics['top_performers'] ?? [])) ?></h4>
                                        <p class="mb-0">Staff with Ratings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Performance Distribution Chart
    const performanceDistributionCtx = document.getElementById('performanceDistributionChart').getContext('2d');
    const performanceDistributionData = <?= json_encode($chartData['performance_distribution'] ?? []) ?>;

    new Chart(performanceDistributionCtx, {
        type: 'bar',
        data: {
            labels: ['Excellent (4.5-5.0)', 'Good (3.5-4.4)', 'Average (2.5-3.4)', 'Below Average (1.5-2.4)', 'Poor (0-1.4)'],
            datasets: [{
                label: 'Number of Staff',
                data: [
                    performanceDistributionData.excellent || 0,
                    performanceDistributionData.good || 0,
                    performanceDistributionData.average || 0,
                    performanceDistributionData.below_average || 0,
                    performanceDistributionData.poor || 0
                ],
                backgroundColor: [
                    colors.success,
                    colors.primary,
                    colors.warning,
                    colors.danger,
                    colors.dark
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Staff Performance Rating Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<style>
    .rating-stars {
        margin-left: 5px;
        color: #ffc107;
    }

    .card-header h5 {
        margin-bottom: 0;
    }

    .badge {
        font-size: 0.75em;
    }

    @media print {
        .btn {
            display: none !important;
        }
    }

    @media print {
        body.printing-section .print-hide {
            display: none !important;
        }
        
        body.printing-section .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            margin: 0 !important;
            page-break-inside: avoid;
        }
        
        body.printing-section .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        body.printing-section canvas {
            max-width: 100% !important;
            height: auto !important;
        }
        
        body.printing-section .table {
            font-size: 12px !important;
        }
        
        body.printing-section .card-body {
            padding: 15px !important;
        }
    }

    .print-hide {
        transition: none;
    }

    /* Spinner animation for print buttons */
    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn .fa-spinner {
        margin-right: 5px;
    }

    /* Ensure consistent button sizing during spinner state */
    .print-section-btn {
        min-width: 120px;
        transition: all 0.3s ease;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }
</style>

<!-- DataTables Core Library -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons Extensions -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<?= $this->endSection() ?>
