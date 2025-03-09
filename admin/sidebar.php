<div class="sidebar bg-gradient-dark shadow" id="sidebar">
    <div class="sidebar-header p-4">
        <a href="<?php echo $url; ?>admin/" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-car-side fs-4 text-primary me-3"></i>
            <span class="sidebar-brand fw-bold text-white"><?= $info_web->nama_rental;?></span>
        </a>
    </div>

    <div class="px-4 py-2">
        <div class="d-flex align-items-center mb-3">
            <div class="p-1 ps-2 rounded-circle bg-primary text-white me-2"
            style="width: 32px;height: 32px;">
            <i class="fas fa-user-circle"></i>
        </div>
        <div>
            <p class="mb-0 text-white fw-medium"><?php echo htmlspecialchars($admin['nama_pengguna']); ?></p>
            <small class="text-light opacity-75">Administrator</small>
        </div>
    </div>
</div>

<div class="sidebar-menu p-2">
    <ul class="nav flex-column">
        <li class="nav-item mb-1">
            <a href="<?php echo $url; ?>admin/" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center <?php echo ($title_web === 'Dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt me-3"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?php echo $url; ?>admin/user/index.php" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center <?php echo ($title_web == 'User') ? 'active' : ''; ?>">
                <i class="fas fa-users me-3"></i>
                <span>Pelanggan</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?php echo $url; ?>admin/mobil/mobil.php" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center <?php echo (in_array($title_web, ['Daftar Mobil', 'Tambah Mobil', 'Edit Mobil'])) ? 'active' : ''; ?>">
                <i class="fas fa-car me-3"></i>
                <span>Daftar Mobil</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?php echo $url; ?>admin/booking/booking.php" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center <?php echo (in_array($title_web, ['Daftar Booking', 'Konfirmasi'])) ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check me-3"></i>
                <span>Daftar Booking</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?php echo $url; ?>admin/peminjaman/peminjaman.php" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center <?php echo ($title_web == 'Peminjaman') ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt me-3"></i>
                <span>Peminjaman</span>
            </a>
        </li>
    </ul>
</div>

<div class="sidebar-footer p-3 mt-auto border-top border-dark">
    <div class="d-flex justify-content-around">
        <a href="<?php echo $url; ?>admin/profile.php" class="btn btn-outline-light btn-sm" title="Profile">
            <i class="fas fa-user"></i>
        </a>
        <a href="<?php echo $url; ?>admin/settings.php" class="btn btn-outline-light btn-sm" title="Settings">
            <i class="fas fa-cog"></i>
        </a>
        <a href="#" id="logoutBtn" class="btn btn-outline-danger btn-sm" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</div>
</div>

<script>

    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    // });

    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Logout?',
            text: 'Anda yakin ingin keluar dari sistem?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo $url; ?>admin/logout.php';
            }
        });
    });
</script>