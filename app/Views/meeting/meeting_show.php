<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Meeting Details</h5>
                <div>
                    <a href="<?= base_url('meetings') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <a href="<?= base_url('meetings/edit/' . $meeting['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4 class="mb-3"><?= esc($meeting['title']) ?></h4>

                        <?php
                        $statusClass = [
                            'scheduled' => 'bg-info',
                            'in_progress' => 'bg-warning',
                            'completed' => 'bg-success',
                            'cancelled' => 'bg-danger',
                        ];
                        $status = $meeting['status'] ?? 'scheduled';

                        $accessClass = [
                            'private' => 'bg-danger',
                            'internal' => 'bg-warning',
                            'public' => 'bg-success',
                        ];
                        $access = $meeting['access_type'] ?? 'public';
                        ?>

                        <div class="mb-2">
                            <span class="badge <?= $statusClass[$status] ?> me-2"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                            <span class="badge <?= $accessClass[$access] ?>"><?= ucfirst($access) ?></span>
                        </div>

                        <div class="mb-2">
                            <strong>Branch:</strong> <?= esc($branch['name'] ?? 'N/A') ?>
                        </div>

                        <div class="mb-2">
                            <strong>Date:</strong> <?= date('F d, Y', strtotime($meeting['meeting_date'])) ?>
                        </div>

                        <div class="mb-2">
                            <strong>Time:</strong>
                            <?= date('h:i A', strtotime($meeting['start_time'])) ?> -
                            <?= date('h:i A', strtotime($meeting['end_time'])) ?>
                        </div>

                        <div class="mb-2">
                            <strong>Location:</strong> <?= esc($meeting['location'] ?? 'N/A') ?>
                        </div>

                        <?php if (!empty($meeting['participants'])) : ?>
                            <div class="mb-2">
                                <strong>Participants:</strong>
                                <div class="mt-2">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Position</th>
                                                    <th>Contacts</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $participants = is_array($meeting['participants'])
                                                    ? $meeting['participants']
                                                    : explode(',', $meeting['participants']);

                                                $counter = 1;
                                                foreach ($participants as $participant) :
                                                    // Handle both formats (array of objects or simple array of strings)
                                                    if (is_array($participant)) {
                                                        $name = $participant['name'] ?? '';
                                                        $position = $participant['position'] ?? '';
                                                        $contacts = $participant['contacts'] ?? '';
                                                        $remarks = $participant['remarks'] ?? '';
                                                    } else {
                                                        $name = trim($participant);
                                                        $position = '';
                                                        $contacts = '';
                                                        $remarks = '';
                                                    }

                                                    if (!empty($name)) :
                                                ?>
                                                    <tr>
                                                        <td><?= $counter++ ?></td>
                                                        <td><?= esc($name) ?></td>
                                                        <td><?= esc($position) ?></td>
                                                        <td><?= esc($contacts) ?></td>
                                                        <td><?= esc($remarks) ?></td>
                                                    </tr>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5>Agenda</h5>
                            <div class="p-3 bg-light rounded">
                                <?php if (!empty($meeting['agenda'])) : ?>
                                    <p class="mb-0"><?= nl2br(esc($meeting['agenda'])) ?></p>
                                <?php else : ?>
                                    <p class="text-muted mb-0">No agenda provided.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($meeting['remarks'])) : ?>
                            <div class="mb-3">
                                <h5>Remarks</h5>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-0"><?= nl2br(esc($meeting['remarks'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Attachments Section -->
                <?php if (!empty($meeting['attachments']) && is_array($meeting['attachments'])) : ?>
                    <div class="mt-4">
                        <h5>Attachments</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Filename</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meeting['attachments'] as $index => $attachment) : ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($attachment['filename']) ?></td>
                                            <td>
                                                <a href="<?= base_url('meetings/download/' . $meeting['id'] . '/' . $index) ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Minutes Section (if meeting is completed) -->
                <?php if ($status === 'completed') : ?>
                    <div class="mt-4">
                        <h5>Minutes of Meeting</h5>
                        <?php if (!empty($meeting['minutes']) && is_array($meeting['minutes'])) : ?>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(esc(json_encode($meeting['minutes']))) ?>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">No meeting minutes recorded.</p>
                            <a href="<?= base_url('meetings/minutes/' . $meeting['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Minutes
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Meeting Status Update -->
                <div class="mt-4">
                    <h5>Update Meeting Status</h5>
                    <form action="<?= base_url('meetings/status/' . $meeting['id']) ?>" method="post" class="d-flex align-items-center">
                        <?= csrf_field() ?>
                        <select name="status" class="form-select me-2" style="max-width: 200px;">
                            <option value="scheduled" <?= ($status === 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                            <option value="in_progress" <?= ($status === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= ($status === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-success">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>