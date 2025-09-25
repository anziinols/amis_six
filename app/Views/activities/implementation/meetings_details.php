<!-- Meeting Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Meeting Title:</strong>
            <p class="text-muted"><?= esc($implementationData['title'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Meeting Date:</strong>
            <p class="text-muted"><?= !empty($implementationData['meeting_date']) ? date('d M Y', strtotime($implementationData['meeting_date'])) : 'N/A' ?></p>
        </div>
        <div class="mb-3">
            <strong>Time:</strong>
            <p class="text-muted">
                <?php
                $startTimeValid = !empty($implementationData['start_time']) && $implementationData['start_time'] !== '0000-00-00 00:00:00';
                $endTimeValid = !empty($implementationData['end_time']) && $implementationData['end_time'] !== '0000-00-00 00:00:00';
                ?>
                <?php if ($startTimeValid && $endTimeValid): ?>
                    <?= date('H:i', strtotime($implementationData['start_time'])) ?> - <?= date('H:i', strtotime($implementationData['end_time'])) ?>
                <?php elseif ($startTimeValid): ?>
                    From <?= date('H:i', strtotime($implementationData['start_time'])) ?>
                <?php elseif ($endTimeValid): ?>
                    Until <?= date('H:i', strtotime($implementationData['end_time'])) ?>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Location:</strong>
            <p class="text-muted"><?= esc($implementationData['location'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>GPS Coordinates:</strong>
            <p class="text-muted"><?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
        </div>
        <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
        <div class="mb-3">
            <strong>Signing Sheet:</strong><br>
            <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-download"></i> Download Signing Sheet
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mb-3">
    <strong>Agenda:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['agenda'] ?? 'N/A')) ?></p>
</div>

<?php if (!empty($implementationData['participants'])): ?>
<div class="mb-3">
    <strong>Participants (<?= count($implementationData['participants']) ?> attendees):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Organization</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['participants'] as $index => $participant): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($participant['name']) ?></td>
                    <td><?= esc($participant['organization']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['minutes'])): ?>
<div class="mb-3">
    <strong>Meeting Minutes:</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Topic</th>
                    <th>Discussion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['minutes'] as $index => $minute): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($minute['topic']) ?></td>
                    <td><?= nl2br(esc($minute['discussion'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['attachments'])): ?>
<div class="mb-3">
    <strong>Meeting Attachments:</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['attachments'] as $index => $attachment): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($attachment['original_name'] ?? $attachment['filename'] ?? 'Unknown') ?></td>
                    <td><?= esc($attachment['filename'] ?? $attachment['description'] ?? 'No description') ?></td>
                    <td>
                        <a href="<?= base_url($attachment['path'] ?? $attachment['filepath'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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

<?php if (!empty($implementationData['remarks'])): ?>
<div class="mb-3">
    <strong>Remarks:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['remarks'])) ?></p>
</div>
<?php endif; ?>
