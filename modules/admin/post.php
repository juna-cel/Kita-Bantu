<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Kategori.php'; // ✅ ambil model kategori

// 🟩 Folder upload disesuaikan (bukan "posts" tapi "post")
$uploadDir = __DIR__ . '/../../uploads/post/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

// Ambil semua kategori untuk dropdown
$kategoriList = getKategori();

// FETCH untuk modal edit
if (isset($_GET['fetch']) && $_GET['fetch'] == 1 && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = getDBConnection();
    $data = $conn->query("SELECT * FROM post WHERE id='$id'")->fetch_assoc();
    $conn->close();
    echo json_encode($data);
    exit;
}

// CREATE / UPDATE
if (isset($_POST['simpan']) || isset($_POST['update'])) {
    $conn = getDBConnection();
    $id = $_POST['id'] ?? null;
    $judul = $conn->real_escape_string($_POST['judul']);
    $nominal = (float)$_POST['nominal'];
    $jumlah_terkumpul = (float)$_POST['jumlah_terkumpul'];
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $kategori_id = (int)$_POST['kategori_id'];
    // $kategori_row = $conn->query("SELECT nama_kategori, slug FROM kategori WHERE id='$kategori_id'")->fetch_assoc();
    $kategori_nama = $kategori_row['nama_kategori'] ?? '';
    // Ubah judul menjadi slug
    $slug = strtolower(trim($judul));                   // ubah ke huruf kecil
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);  // hapus karakter selain huruf, angka, spasi, atau tanda minus
    $slug = preg_replace('/[\s-]+/', '-', $slug);       // ganti spasi atau tanda minus ganda menjadi satu minus
    $slug = trim($slug, '-');                           // hapus minus di awal/akhir
    $created_by = 1;

    // 🟩 Upload foto (pastikan ke uploads/post/)
    $foto_path = $_POST['existing_foto'] ?? '';
    if (!empty($_FILES['foto']['name'])) {
        $foto_file = time() . '_' . basename($_FILES['foto']['name']);
        $targetFile = $uploadDir . $foto_file;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            if ($foto_path && file_exists(__DIR__ . '/../../' . $foto_path)) unlink(__DIR__ . '/../../' . $foto_path);
            $foto_path = 'uploads/post/' . $foto_file; // 🟢 simpan path ke database
        }
    }

    // 🟩 Query database tetap ke tabel post
    if (isset($_POST['simpan'])) {
        $conn->query("INSERT INTO post (judul, kategori, slug, kategori_id, nominal, jumlah_terkumpul, deskripsi, foto, tanggal, status, created_by)
                      VALUES ('$judul','$kategori_nama','$slug','$kategori_id','$nominal','$jumlah_terkumpul','$deskripsi','$foto_path','$tanggal','$status','$created_by')");
    } else {
        $conn->query("UPDATE post SET 
                        judul='$judul', 
                        kategori='$kategori', 
                        slug='$slug', 
                        kategori_id='$kategori_id',
                        nominal='$nominal', 
                        jumlah_terkumpul='$jumlah_terkumpul',
                        deskripsi='$deskripsi', 
                        foto='$foto_path', 
                        tanggal='$tanggal', 
                        status='$status'
                      WHERE id='$id'");
    }
    $conn->close();
    echo "<script>window.location.href='/admin/post';</script>";
    exit;
}

// HAPUS SATU DATA
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn = getDBConnection();
    $row = $conn->query("SELECT foto FROM post WHERE id='$id'")->fetch_assoc();
    if ($row && $row['foto'] && file_exists(__DIR__ . '/../../' . $row['foto'])) unlink(__DIR__ . '/../../' . $row['foto']);
    $conn->query("DELETE FROM post WHERE id='$id'");
    $conn->close();
    echo "<script>window.location.href='/admin/post';</script>";
    exit;
}

// BULK DELETE
if (isset($_POST['hapus_bulk'])) {
    $conn = getDBConnection();
    $ids = explode(',', $_POST['hapus_ids'] ?? '');
    foreach ($ids as $id) {
        $id = (int)$id;
        $row = $conn->query("SELECT foto FROM post WHERE id='$id'")->fetch_assoc();
        if ($row && $row['foto'] && file_exists(__DIR__ . '/../../' . $row['foto'])) unlink(__DIR__ . '/../../' . $row['foto']);
        $conn->query("DELETE FROM post WHERE id='$id'");
    }
    $conn->close();
    echo "<script>window.location.href='/admin/post';</script>";
    exit;
}

// GET semua post
function getPost()
{
    $conn = getDBConnection();
    $data = [];
    $result = $conn->query("SELECT * FROM post ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $conn->close();
    return $data;
}

include_once __DIR__ . '/partials/app.php';
?>

<main class="app-main">
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col">
            <h2 class="mb-4">Post Admin - Advanced</h2>

            <div class="mb-3 d-flex justify-content-between">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#postModal" onclick="openModal('tambah')">Tambah Post</button>
                <form method="post" id="bulkDeleteForm">
                    <input type="hidden" name="hapus_bulk" value="1">
                    <input type="hidden" name="hapus_ids" id="hapus_ids">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin hapus post terpilih?')">Hapus Terpilih</button>
                </form>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Tambah Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="post" enctype="multipart/form-data" id="postForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="post_id">
                        <input type="hidden" name="existing_foto" id="existing_foto">

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori_id" id="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategoriList as $kat): ?>
                                    <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" id="judul" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nominal</label>
                            <input type="number" class="form-control" name="nominal" id="nominal" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dana Terkumpul</label>
                            <input type="number" class="form-control" name="jumlah_terkumpul" id="jumlah_terkumpul" required value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status" required>
                                <?php foreach (['aktif','nonaktif','pending'] as $st): ?>
                                    <option value="<?= $st ?>"><?= ucfirst($st) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <div class="drop-zone" id="dropZone">
                                <span>Drag & Drop atau klik untuk pilih file</span>
                                <input type="file" name="foto" id="foto" class="form-control">
                            </div>
                            <div id="fotoPreview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" id="submitBtn" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Tabel -->
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <table id="postTable" class="table table-striped table-hover table-sm align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>No</th>
                                <th>Judul</th>
                                
                                <th>Nominal</th>
                                <th>Dana Terkumpul</th>
                                <th>Deskripsi</th>
                                <th>Foto</th>
                                <th>Tanggal</th>
                                <th>Status</th> 
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $post = getPost();
                        $no = 1;
                        foreach ($post as $row):
                        ?>
                            <tr>
                                <td><input type="checkbox" class="checkItem" value="<?= $row['id'] ?>"></td>
                                <td><?= $no ?></td>
                                <td><?= $row['judul'] ?></td>
                                <td>Rp <?= number_format($row['nominal'],2,',','.') ?></td>
                                <td>Rp <?= number_format($row['jumlah_terkumpul'],2,',','.') ?></td>
                                <td><?= strlen($row['deskripsi'])>50 ? substr($row['deskripsi'],0,50).'...' : $row['deskripsi'] ?></td>
                                <td>
                                    <?php if($row['foto']): ?>
                                        <img src="/<?= $row['foto'] ?>" style="height:50px;" class="rounded">
                                    <?php endif;?>
                                </td>
                                <td><?= $row['tanggal'] ?></td>
                                <td><span class="badge <?= $row['status']=='aktif'?'bg-success':($row['status']=='nonaktif'?'bg-secondary':'bg-warning') ?>"><?= ucfirst($row['status']) ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning mb-1" onclick="openModal('edit', <?= $row['id'] ?>)">Edit</button>
                                    <a href="/admin/post?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="btn btn-sm btn-danger mb-1">Hapus</a>
                                </td>
                            </tr>
                        <?php $no++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openModal(type, id=null){
    const modalTitle = document.getElementById('postModalLabel');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('postForm');
    const fotoPreview = document.getElementById('fotoPreview');

    fotoPreview.innerHTML = '';
    if(type==='tambah'){
        modalTitle.innerText='Tambah Post';
        submitBtn.name='simpan';
        form.reset();
        document.getElementById('post_id').value='';
        document.getElementById('existing_foto').value='';
        document.getElementById('jumlah_terkumpul').value='0';
    } 
    else if(type==='edit' && id){
        modalTitle.innerText='Edit Post';
        submitBtn.name='update';
        fetch('/admin/post?fetch=1&id='+id)
        .then(res=>res.json())
        .then(data=>{
            document.getElementById('post_id').value=data.id;
            document.getElementById('judul').value=data.judul;
            document.getElementById('kategori_id').value=data.kategori_id;
            document.getElementById('nominal').value=data.nominal;
            document.getElementById('jumlah_terkumpul').value=data.jumlah_terkumpul;
            document.getElementById('deskripsi').value=data.deskripsi;
            document.getElementById('tanggal').value=data.tanggal;
            document.getElementById('status').value=data.status;
            document.getElementById('existing_foto').value=data.foto;
            if(data.foto) fotoPreview.innerHTML='<img src="/'+data.foto+'" style="height:70px;" class="rounded">';
        });
    }

    var myModal = new bootstrap.Modal(document.getElementById('postModal'));
    myModal.show();
}
</script>
