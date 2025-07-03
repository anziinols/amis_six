<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id']) ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Indicator Details
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Strategy Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Strategy Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Strategy:</div>
                                <div class="col-md-9"><?= nl2br(esc($strategy['strategy'])) ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Policy Reference:</div>
                                <div class="col-md-9"><?= esc($strategy['policy_reference']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Indicator Form -->
                    <form action="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="indicator" class="form-label">Indicator <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="indicator" name="indicator" rows="3" required><?= old('indicator', $indicator['indicator']) ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="source" class="form-label">Source</label>
                                <input type="text" class="form-control" id="source" name="source" value="<?= old('source', $indicator['source']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="baseline" class="form-label">Baseline</label>
                                <input type="text" class="form-control" id="baseline" name="baseline" value="<?= old('baseline', $indicator['baseline']) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5>Yearly Targets</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year_one" class="form-label">Year One</label>
                                <input type="text" class="form-control" id="year_one" name="year_one" value="<?= old('year_one', $indicator['year_one']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_two" class="form-label">Year Two</label>
                                <input type="text" class="form-control" id="year_two" name="year_two" value="<?= old('year_two', $indicator['year_two']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_three" class="form-label">Year Three</label>
                                <input type="text" class="form-control" id="year_three" name="year_three" value="<?= old('year_three', $indicator['year_three']) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year_four" class="form-label">Year Four</label>
                                <input type="text" class="form-control" id="year_four" name="year_four" value="<?= old('year_four', $indicator['year_four']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_five" class="form-label">Year Five</label>
                                <input type="text" class="form-control" id="year_five" name="year_five" value="<?= old('year_five', $indicator['year_five']) ?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Indicator
                                </button>
                                <a href="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
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
