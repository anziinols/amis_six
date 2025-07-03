<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id']) ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Strategy
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="strategy" class="form-label">Strategy <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="strategy" name="strategy" rows="3" required><?= old('strategy', $strategy['strategy']) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="policy_reference" class="form-label">Policy Reference</label>
                                <input type="text" class="form-control" id="policy_reference" name="policy_reference" value="<?= old('policy_reference', $strategy['policy_reference']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Related Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>MTDP Plan</th>
                                            <td><?= $mtdp['title'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Strategic Priority Area</th>
                                            <td><?= $spa['title'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Deliberate Intervention Program</th>
                                            <td><?= $dip['dip_title'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Specific Area</th>
                                            <td><?= $specificArea['sa_title'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Investment</th>
                                            <td><?= $investment['investment'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Key Result Area</th>
                                            <td><?= $kra['kpi'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Strategy</button>
                                <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id']) ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
