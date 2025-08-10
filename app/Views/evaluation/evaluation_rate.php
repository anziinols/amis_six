<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('evaluation') ?>">Evaluation</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('evaluation/' . $activity['id']) ?>">Activity Details</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Evaluate & Rate</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('evaluation/' . $activity['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Activity
            </a>
        </div>
    </div>

    <!-- Activity Overview Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Activity Evaluation Form</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%">Activity Code:</th>
                            <td><span class="badge bg-primary fs-6"><?= esc($activity['activity_code'] ?? 'N/A') ?></span></td>
                        </tr>
                        <tr>
                            <th>Activity Title:</th>
                            <td><strong><?= esc($activity['title']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Activity Type:</th>
                            <td><span class="badge bg-info"><?= ucfirst(esc($activity['activity_type'])) ?></span></td>
                        </tr>
                        <tr>
                            <th>Workplan:</th>
                            <td><?= esc($activity['workplan_title'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Branch:</th>
                            <td><?= esc($activity['branch_name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Supervisor:</th>
                            <td><?= esc($activity['supervisor_name'] ?? 'Not assigned') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-target"></i> Current Targets</h6>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <div class="card border-primary">
                                <div class="card-body text-center p-2">
                                    <small class="text-primary">Q1 Target</small>
                                    <p class="mb-0 fw-bold"><?= $activity['q_one_target'] ? number_format($activity['q_one_target'], 2) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card border-success">
                                <div class="card-body text-center p-2">
                                    <small class="text-success">Q2 Target</small>
                                    <p class="mb-0 fw-bold"><?= $activity['q_two_target'] ? number_format($activity['q_two_target'], 2) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card border-warning">
                                <div class="card-body text-center p-2">
                                    <small class="text-warning">Q3 Target</small>
                                    <p class="mb-0 fw-bold"><?= $activity['q_three_target'] ? number_format($activity['q_three_target'], 2) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="card border-info">
                                <div class="card-body text-center p-2">
                                    <small class="text-info">Q4 Target</small>
                                    <p class="mb-0 fw-bold"><?= $activity['q_four_target'] ? number_format($activity['q_four_target'], 2) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Form -->
    <form action="<?= base_url('evaluation/' . $activity['id'] . '/rate') ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Quarterly Achievements Section -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Quarterly Achievements</h5>
                <small>Enter the actual achievements for each quarter</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="q_one_achieved" class="form-label">Quarter 1 Achieved</label>
                            <input type="number" step="0.01" class="form-control <?= session('errors.q_one_achieved') ? 'is-invalid' : '' ?>" 
                                   id="q_one_achieved" name="q_one_achieved" 
                                   value="<?= old('q_one_achieved', $activity['q_one_achieved'] ?? '') ?>"
                                   placeholder="Enter Q1 achievement">
                            <?php if (session('errors.q_one_achieved')): ?>
                                <div class="invalid-feedback"><?= session('errors.q_one_achieved') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Target: <?= $activity['q_one_target'] ? number_format($activity['q_one_target'], 2) : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="q_two_achieved" class="form-label">Quarter 2 Achieved</label>
                            <input type="number" step="0.01" class="form-control <?= session('errors.q_two_achieved') ? 'is-invalid' : '' ?>" 
                                   id="q_two_achieved" name="q_two_achieved" 
                                   value="<?= old('q_two_achieved', $activity['q_two_achieved'] ?? '') ?>"
                                   placeholder="Enter Q2 achievement">
                            <?php if (session('errors.q_two_achieved')): ?>
                                <div class="invalid-feedback"><?= session('errors.q_two_achieved') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Target: <?= $activity['q_two_target'] ? number_format($activity['q_two_target'], 2) : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="q_three_achieved" class="form-label">Quarter 3 Achieved</label>
                            <input type="number" step="0.01" class="form-control <?= session('errors.q_three_achieved') ? 'is-invalid' : '' ?>" 
                                   id="q_three_achieved" name="q_three_achieved" 
                                   value="<?= old('q_three_achieved', $activity['q_three_achieved'] ?? '') ?>"
                                   placeholder="Enter Q3 achievement">
                            <?php if (session('errors.q_three_achieved')): ?>
                                <div class="invalid-feedback"><?= session('errors.q_three_achieved') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Target: <?= $activity['q_three_target'] ? number_format($activity['q_three_target'], 2) : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="q_four_achieved" class="form-label">Quarter 4 Achieved</label>
                            <input type="number" step="0.01" class="form-control <?= session('errors.q_four_achieved') ? 'is-invalid' : '' ?>" 
                                   id="q_four_achieved" name="q_four_achieved" 
                                   value="<?= old('q_four_achieved', $activity['q_four_achieved'] ?? '') ?>"
                                   placeholder="Enter Q4 achievement">
                            <?php if (session('errors.q_four_achieved')): ?>
                                <div class="invalid-feedback"><?= session('errors.q_four_achieved') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Target: <?= $activity['q_four_target'] ? number_format($activity['q_four_target'], 2) : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Section -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-star"></i> Activity Rating</h5>
                <small>Provide an overall rating and evaluation remarks</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Overall Rating <span class="text-danger">*</span></label>
                            <div class="star-rating mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star" data-rating="<?= $i ?>">
                                        <i class="fas fa-star"></i>
                                    </span>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="<?= old('rating', $activity['rating'] ?? '') ?>" required>
                            <div class="rating-text mb-2">
                                <span id="rating-label">Click stars to rate</span>
                            </div>
                            <?php if (session('errors.rating')): ?>
                                <div class="text-danger small"><?= session('errors.rating') ?></div>
                            <?php endif; ?>
                            <div class="form-text">Rate the overall performance of this activity (1 = Poor, 5 = Excellent)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Achievement Summary (Auto-calculated) -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="fas fa-calculator"></i> Achievement Summary</h6>
                                <div id="achievement-summary">
                                    <p class="mb-1"><strong>Total Target:</strong> <span id="total-target">
                                        <?php 
                                        $totalTarget = ($activity['q_one_target'] ?? 0) + ($activity['q_two_target'] ?? 0) + ($activity['q_three_target'] ?? 0) + ($activity['q_four_target'] ?? 0);
                                        echo number_format($totalTarget, 2);
                                        ?>
                                    </span></p>
                                    <p class="mb-1"><strong>Total Achieved:</strong> <span id="total-achieved">0.00</span></p>
                                    <p class="mb-0"><strong>Achievement %:</strong> <span id="achievement-percentage" class="badge bg-info">0%</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="reated_remarks" class="form-label">Evaluation Remarks</label>
                    <textarea class="form-control <?= session('errors.reated_remarks') ? 'is-invalid' : '' ?>" 
                              id="reated_remarks" name="reated_remarks" rows="4" 
                              placeholder="Enter detailed evaluation remarks, observations, and recommendations..."><?= old('reated_remarks', $activity['reated_remarks'] ?? '') ?></textarea>
                    <?php if (session('errors.reated_remarks')): ?>
                        <div class="invalid-feedback"><?= session('errors.reated_remarks') ?></div>
                    <?php endif; ?>
                    <div class="form-text">Provide detailed feedback on the activity performance, challenges, and recommendations for improvement.</div>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-info-circle"></i> 
                            This evaluation will be recorded with your user ID and timestamp.
                        </p>
                    </div>
                    <div>
                        <a href="<?= base_url('evaluation/' . $activity['id']) ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Evaluation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.star-rating {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
}

.star-rating .star {
    display: inline-block;
    margin-right: 5px;
    transition: color 0.2s;
}

.star-rating .star:hover,
.star-rating .star.active {
    color: #ffc107;
}

.star-rating .star.active {
    color: #ff8c00;
}

.rating-text {
    font-weight: 500;
    color: #495057;
}
</style>

<script>
// Auto-calculate achievement summary and star rating
document.addEventListener('DOMContentLoaded', function() {
    const achievementInputs = ['q_one_achieved', 'q_two_achieved', 'q_three_achieved', 'q_four_achieved'];
    const totalTarget = <?= $totalTarget ?>;

    function updateAchievementSummary() {
        let totalAchieved = 0;

        achievementInputs.forEach(function(inputId) {
            const input = document.getElementById(inputId);
            const value = parseFloat(input.value) || 0;
            totalAchieved += value;
        });

        document.getElementById('total-achieved').textContent = totalAchieved.toFixed(2);

        const percentage = totalTarget > 0 ? (totalAchieved / totalTarget * 100) : 0;
        const percentageElement = document.getElementById('achievement-percentage');
        percentageElement.textContent = percentage.toFixed(1) + '%';

        // Update badge color based on percentage
        percentageElement.className = 'badge ' +
            (percentage >= 90 ? 'bg-success' :
             percentage >= 70 ? 'bg-warning' :
             percentage >= 50 ? 'bg-info' : 'bg-danger');
    }

    // Add event listeners to achievement inputs
    achievementInputs.forEach(function(inputId) {
        document.getElementById(inputId).addEventListener('input', updateAchievementSummary);
    });

    // Star rating functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingLabel = document.getElementById('rating-label');

    const ratingLabels = {
        1: '1 Star - Poor',
        2: '2 Stars - Fair',
        3: '3 Stars - Good',
        4: '4 Stars - Very Good',
        5: '5 Stars - Excellent'
    };

    // Set initial rating if exists
    const currentRating = parseInt(ratingInput.value);
    if (currentRating > 0) {
        updateStarDisplay(currentRating);
        ratingLabel.textContent = ratingLabels[currentRating];
    }

    // Add click event to stars
    stars.forEach(function(star) {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            updateStarDisplay(rating);
            ratingLabel.textContent = ratingLabels[rating];
        });

        // Add hover effect
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });
    });

    // Reset hover effect when leaving star area
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value);
        updateStarDisplay(currentRating);
    });

    function updateStarDisplay(rating) {
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function highlightStars(rating) {
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }

    // Initial calculation
    updateAchievementSummary();
});
</script>
<?= $this->endSection() ?>
