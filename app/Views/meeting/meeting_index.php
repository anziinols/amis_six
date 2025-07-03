<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Meetings</h5>
                <a href="<?= base_url('meetings/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Meeting
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Branch</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Access</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($meetings)) : ?>
                                <?php foreach ($meetings as $key => $meeting) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= esc($meeting['title']) ?></td>
                                        <td><?= esc($meeting['branch_name'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($meeting['meeting_date'])) ?></td>
                                        <td>
                                            <?= date('h:i A', strtotime($meeting['start_time'])) ?> -
                                            <?= date('h:i A', strtotime($meeting['end_time'])) ?>
                                        </td>
                                        <td><?= esc($meeting['location']) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'scheduled' => 'bg-info',
                                                'in_progress' => 'bg-warning',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                            ];
                                            $status = $meeting['status'] ?? 'scheduled';
                                            ?>
                                            <span class="badge <?= $statusClass[$status] ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $accessClass = [
                                                'private' => 'bg-danger',
                                                'internal' => 'bg-warning',
                                                'public' => 'bg-success',
                                            ];
                                            $access = $meeting['access_type'] ?? 'public';
                                            ?>
                                            <span class="badge <?= $accessClass[$access] ?>"><?= ucfirst($access) ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('meetings/' . $meeting['id']) ?>" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('meetings/edit/' . $meeting['id']) ?>" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-meeting" data-id="<?= $meeting['id'] ?>" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="9" class="text-center">No meetings found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this meeting? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.delete-meeting').click(function() {
            const id = $(this).data('id');
            $('#confirmDelete').attr('href', '<?= base_url('meetings/delete') ?>/' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>