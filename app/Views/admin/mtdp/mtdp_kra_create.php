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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>">KRAs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New KRA</li>
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
                        <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to KRAs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/mtdp-plans/kras') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="investment_id" value="<?= $investment['id'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="kpi" class="form-label">Key Performance Indicator (KPI) <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="kpi" name="kpi" rows="3" required><?= old('kpi') ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="responsible_agencies" class="form-label">Responsible Agencies</label>
                                <textarea class="form-control" id="responsible_agencies" name="responsible_agencies" rows="3"><?= old('responsible_agencies') ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Annual Targets</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="year_one" class="form-label">Year 1</label>
                                <input type="text" class="form-control" id="year_one" name="year_one" value="<?= old('year_one', '0') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="year_two" class="form-label">Year 2</label>
                                <input type="text" class="form-control" id="year_two" name="year_two" value="<?= old('year_two', '0') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="year_three" class="form-label">Year 3</label>
                                <input type="text" class="form-control" id="year_three" name="year_three" value="<?= old('year_three', '0') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="year_four" class="form-label">Year 4</label>
                                <input type="text" class="form-control" id="year_four" name="year_four" value="<?= old('year_four', '0') ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="year_five" class="form-label">Year 5</label>
                                <input type="text" class="form-control" id="year_five" name="year_five" value="<?= old('year_five', '0') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Create KRA</button>
                                <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
