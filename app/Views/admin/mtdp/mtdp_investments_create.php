<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans') ?>">MTDP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/spas/' . $mtdp['id']) ?>"><?= $mtdp['title'] ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>"><?= $spa['title'] ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas') ?>">Specific Areas</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>">Investments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Investment</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Investments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/mtdp-plans/investments') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="dip_id" value="<?= $dip['id'] ?>">
                        <input type="hidden" name="sa_id" value="<?= $specificArea['id'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="investment" class="form-label">Investment Item <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="investment" name="investment" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="funding_sources" class="form-label">Funding Sources</label>
                                    <input type="text" class="form-control" id="funding_sources" name="funding_sources">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_one" class="form-label">Year 1 Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="year_one" name="year_one" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_two" class="form-label">Year 2 Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="year_two" name="year_two" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_three" class="form-label">Year 3 Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="year_three" name="year_three" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_four" class="form-label">Year 4 Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="year_four" name="year_four" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_five" class="form-label">Year 5 Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="year_five" name="year_five" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dip_link_dip_id" class="form-label">Linked DIP (Optional)</label>
                                    <select class="form-select" id="dip_link_dip_id" name="dip_link_dip_id">
                                        <option value="0">-- Select a DIP --</option>
                                        <?php foreach ($allDips as $linkedDip) : ?>
                                            <option value="<?= $linkedDip['id'] ?>"><?= $linkedDip['dip_code'] ?> - <?= $linkedDip['dip_title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Investment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // No need to calculate investment as it's now a text field
});
</script>

<?= $this->endSection(); ?>
