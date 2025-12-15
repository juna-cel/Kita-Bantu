<?php
require_once __DIR__ . '/../../config/database.php';
$conn = GetDbConnection();

$query = $conn->query("SELECT * FROM roles ORDER BY id ASC");
$roles = $query->fetch_all(MYSQLI_ASSOC);

include_once __DIR__ . '/partials/app.php';
?>

<div class="container mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Role Permission</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Role Name</th>
                        <th width="200">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; foreach ($roles as $r): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($r['name']) ?></td>
                            <td>
                                <a href="/admin/role-show?id=<?= $r['id']; ?>" class="btn btn-info btn-sm">Show</a>
                                <a href="/admin/roles/edit?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/admin/roles/delete?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
