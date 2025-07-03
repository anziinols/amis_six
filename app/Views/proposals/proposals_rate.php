<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Rate Proposal</h5>
                    <div>
                        <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="<?= base_url('proposals') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Proposals
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Proposal Information</h5>
                                <p><strong>Workplan:</strong> <?= esc($proposal['workplan_title']) ?></p>
                                <p><strong>Activity:</strong> <?= esc($proposal['activity_title']) ?> <span class="badge bg-info"><?= ucfirst($proposal['activity_type']) ?></span></p>
                                <p><strong>Location:</strong> <?= esc($proposal['district_name']) ?>, <?= esc($proposal['province_name']) ?></p>
                                <p><strong>Date Range:</strong> <?= date('d M Y', strtotime($proposal['date_start'])) ?> - <?= date('d M Y', strtotime($proposal['date_end'])) ?></p>
                                <p class="mb-0"><strong>Status:</strong> 
                                    <?php
                                    $statusBadgeClass = 'bg-secondary';
                                    switch ($proposal['status']) {
                                        case 'pending':
                                            $statusBadgeClass = 'bg-warning text-dark';
                                            break;
                                        case 'submitted':
                                            $statusBadgeClass = 'bg-info text-dark';
                                            break;
                                        case 'approved':
                                            $statusBadgeClass = 'bg-success';
                                            break;
                                        case 'rated':
                                            $statusBadgeClass = 'bg-primary';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst($proposal['status']) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="<?= base_url('proposals/rate/' . $proposal['id']) ?>" method="post" id="ratingForm">
                        <?= csrf_field() ?>

                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <label class="form-label fs-5 mb-3">Rating <span class="text-danger">*</span></label>
                                <div class="rating-stars mb-3">
                                    <div class="fs-1">
                                        <i class="far fa-star star-rating" data-rating="1"></i>
                                        <i class="far fa-star star-rating" data-rating="2"></i>
                                        <i class="far fa-star star-rating" data-rating="3"></i>
                                        <i class="far fa-star star-rating" data-rating="4"></i>
                                        <i class="far fa-star star-rating" data-rating="5"></i>
                                    </div>
                                    <input type="hidden" name="rating_score" id="rating_score" value="<?= old('rating_score', !empty($proposal['rating_score']) ? $proposal['rating_score'] : '') ?>" required>
                                    <div class="mt-2 fs-5">
                                        <span id="rating_text">Click to rate</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="rate_remarks" class="form-label">Rating Remarks</label>
                                <textarea name="rate_remarks" id="rate_remarks" class="form-control" rows="4" placeholder="Enter any remarks about this rating"><?= old('rate_remarks', !empty($proposal['rate_remarks']) ? $proposal['rate_remarks'] : '') ?></textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary" id="confirmRatingBtn">
                                    <i class="fas fa-save me-1"></i> Submit Rating
                                </button>
                                <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Star rating functionality
        $('.star-rating').hover(
            function() {
                const rating = $(this).data('rating');
                updateStars(rating, false);
            },
            function() {
                const currentRating = $('#rating_score').val();
                if (currentRating) {
                    updateStars(currentRating, true);
                } else {
                    $('.star-rating').removeClass('fas').addClass('far');
                    $('#rating_text').text('Click to rate');
                }
            }
        );

        $('.star-rating').click(function() {
            const rating = $(this).data('rating');
            $('#rating_score').val(rating);
            updateStars(rating, true);
        });

        // Initialize stars if there's a pre-existing rating
        const initialRating = $('#rating_score').val();
        if (initialRating) {
            updateStars(initialRating, true);
        }

        // Function to update star display
        function updateStars(rating, permanent) {
            $('.star-rating').each(function() {
                const starRating = $(this).data('rating');
                if (starRating <= rating) {
                    $(this).removeClass('far').addClass('fas');
                } else {
                    $(this).removeClass('fas').addClass('far');
                }
            });

            // Update rating text
            const ratingTexts = [
                'Click to rate',
                'Poor',
                'Fair',
                'Good',
                'Very Good',
                'Excellent'
            ];
            
            $('#rating_text').text(ratingTexts[rating] || 'Click to rate');
            
            if (permanent) {
                $('#rating_text').text(ratingTexts[rating] + ' (' + rating + '.0/5.0)');
            }
        }

        // Confirmation dialog for rating submission
        $('#confirmRatingBtn').click(function() {
            const rating = $('#rating_score').val();
            
            if (!rating) {
                toastr.error('Please select a rating');
                return;
            }
            
            if (confirm('Are you sure you want to submit this rating? This will mark the proposal as rated.')) {
                $('#ratingForm').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>
