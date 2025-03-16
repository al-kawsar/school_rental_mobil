<?php
// Start session at the beginning
session_start();
require_once 'koneksi/koneksi.php';
include 'header.php';

// Fetch data for the hero section - most expensive cars
$featured_cars = $koneksi->query('SELECT * FROM mobil WHERE status = "Tersedia" ORDER BY harga DESC LIMIT 5')->fetchAll();

// Fetch latest cars for new arrivals section
$latest_cars = $koneksi->query('SELECT * FROM mobil WHERE status = "Tersedia" ORDER BY id_mobil DESC LIMIT 4')->fetchAll();

// Fetch budget-friendly cars
$budget_cars = $koneksi->query('SELECT * FROM mobil WHERE status = "Tersedia" ORDER BY harga ASC LIMIT 4')->fetchAll();

// Get info for statistics
$total_cars = $koneksi->query('SELECT COUNT(*) as total FROM mobil WHERE status = "Tersedia"')->fetch()['total'];
$car_brands = $koneksi->query('SELECT COUNT(DISTINCT merk) as total FROM mobil')->fetch()['total'];
$rental_info = $koneksi->query('SELECT * FROM infoweb WHERE id = 1')->fetch();
?>

<!-- Hero Section with Carousel -->
<div class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < count($featured_cars); $i++) { ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" <?= $i == 0 ? 'class="active"' : '' ?>></button>
            <?php } ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($featured_cars as $index => $car) { ?>
                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>" data-bs-interval="5000">
                    <img src="assets/image/<?= htmlspecialchars($car['gambar']); ?>" class="d-block w-100"
                        alt="<?= htmlspecialchars($car['merk']); ?>"
                        style="height: 85vh; object-fit: cover; filter: brightness(0.7);">
                    <div class="carousel-caption d-none d-md-block text-start">
                        <h1 class="display-4 fw-bold"><?= htmlspecialchars($car['merk']); ?></h1>
                        <p class="lead">Rp. <?= number_format($car['harga']); ?> / hari</p>
                        <a href="detail.php?id=<?= $car['id_mobil']; ?>" class="btn btn-primary btn-lg px-4 me-2">Lihat
                            Detail</a>
                        <a href="booking.php?id=<?= $car['id_mobil']; ?>" class="btn btn-outline-light btn-lg px-4">Sewa
                            Sekarang</a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Quick Search Section -->
<div class="search-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Cari Mobil Sesuai Kebutuhan Anda</h4>
                        <form action="blog.php" method="get">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="keyword" class="form-label">Kata Kunci</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input type="text" class="form-control" id="keyword" name="cari"
                                            placeholder="Merek atau tipe mobil">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="price" class="form-label">Harga Maksimum</label>
                                    <select class="form-select" id="price" name="price">
                                        <option value="">Semua Harga</option>
                                        <option value="300000">
                                            < Rp. 300.000/hari</option>
                                        <option value="500000">
                                            < Rp. 500.000/hari</option>
                                        <option value="1000000">
                                            < Rp. 1.000.000/hari</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="available" class="form-label">Status</label>
                                    <select class="form-select" id="available" name="available">
                                        <option value="">Semua Status</option>
                                        <option value="1">Tersedia</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-search me-2"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Cars Section -->
<section class="featured-cars py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold border-start border-4 border-primary ps-3">Mobil Terbaru</h2>
                <p class="text-muted">Temukan mobil terbaru dari koleksi kami</p>
            </div>
        </div>

        <div class="row">
            <?php foreach ($latest_cars as $car) { ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="position-relative">
                            <img src="assets/image/<?= htmlspecialchars($car['gambar']); ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($car['merk']); ?>" style="height: 180px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success">Tersedia</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($car['merk']); ?></h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold fs-5">Rp. <?= number_format($car['harga']); ?></span>
                                <span class="text-muted">/hari</span>
                            </div>
                            <p class="card-text small text-muted mb-0">
                                <?= substr(htmlspecialchars($car['deskripsi'] ?? ''), 0, 60); ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="detail.php?id=<?= $car['id_mobil']; ?>" class="btn btn-outline-primary">
                                    <i class="fa fa-info-circle me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="text-center mt-3">
            <a href="blog.php" class="btn btn-outline-primary px-4">
                Lihat Semua Mobil <i class="fa fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits py-5 bg-light">
    <div class="container">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
                <p class="text-muted">Keunggulan layanan rental mobil kami</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3">
                            <i class="fa fa-car fa-3x text-primary"></i>
                        </div>
                        <h4>Armada Berkualitas</h4>
                        <p class="text-muted">Semua mobil kami dalam kondisi prima dan terawat dengan baik untuk
                            kenyamanan perjalanan Anda.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3">
                            <i class="fa fa-money fa-3x text-primary"></i>
                        </div>
                        <h4>Harga Terjangkau</h4>
                        <p class="text-muted">Kami menawarkan harga yang kompetitif dan transparan, tanpa biaya
                            tersembunyi.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3">
                            <i class="fa fa-check-circle fa-3x text-primary"></i>
                        </div>
                        <h4>Proses Mudah</h4>
                        <p class="text-muted">Proses pemesanan yang cepat dan mudah, dengan dukungan customer service
                            24/7.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Budget Cars Section -->
<section class="budget-cars py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold border-start border-4 border-primary ps-3">Mobil Ekonomis</h2>
                <p class="text-muted">Pilihan mobil dengan harga terjangkau</p>
            </div>
        </div>

        <div class="row">
            <?php foreach ($budget_cars as $car) { ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="position-relative">
                            <img src="assets/image/<?= htmlspecialchars($car['gambar']); ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($car['merk']); ?>" style="height: 180px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-primary">Hemat</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($car['merk']); ?></h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold fs-5">Rp. <?= number_format($car['harga']); ?></span>
                                <span class="text-muted">/hari</span>
                            </div>
                            <p class="card-text small text-muted mb-0">
                                <?= substr(htmlspecialchars($car['deskripsi'] ?? ''), 0, 60); ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="booking.php?id=<?= $car['id_mobil']; ?>" class="btn btn-primary">
                                    <i class="fa fa-calendar-check-o me-2"></i>Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <i class="fa fa-car fa-3x mb-3"></i>
                <h2 class="fw-bold"><?= $total_cars ?></h2>
                <p class="lead">Mobil Tersedia</p>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <i class="fa fa-tags fa-3x mb-3"></i>
                <h2 class="fw-bold"><?= $car_brands ?></h2>
                <p class="lead">Merek Mobil</p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-users fa-3x mb-3"></i>
                <h2 class="fw-bold">1000+</h2>
                <p class="lead">Pelanggan Puas</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg
            <div class=" col-lg-8 mx-auto text-center">
                <h2 class="fw-bold">Siap Untuk Menyewa Mobil?</h2>
                <p class="lead mb-4">Hubungi kami sekarang atau lakukan pemesanan online dengan mudah</p>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="blog.php" class="btn btn-primary btn-lg px-4 me-md-2">
                        <i class="fa fa-search me-2"></i>Cari Mobil
                    </a>
                    <a href="kontak.php" class="btn btn-outline-dark btn-lg px-4">
                        <i class="fa fa-phone me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Testimoni Pelanggan</h2>
                <p class="text-muted">Apa kata mereka tentang layanan kami</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4 text-center">
                                    <img src="assets/image/testimonial1.avif"
                                        class="rounded-circle object-fit-cover mb-3" width="80" height="80"
                                        alt="Testimonial">
                                    <p class="lead fst-italic mb-4">"Pelayanan sangat memuaskan, mobil bersih dan
                                        terawat. Proses booking sangat mudah dan cepat. Sangat direkomendasikan!"</p>
                                    <h5 class="fw-bold mb-1">Ahmad Ridwan</h5>
                                    <p class="text-muted">Jakarta</p>
                                    <div class="text-warning mb-3">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4 text-center">
                                    <img src="assets/image/testimonial2.avif"
                                        class="rounded-circle object-fit-cover mb-3" width="80" height="80"
                                        alt="Testimonial">
                                    <p class="lead fst-italic mb-4">"Harga terjangkau dengan kualitas mobil yang prima.
                                        Customer service sangat responsif. Pasti akan kembali lagi!"</p>
                                    <h5 class="fw-bold mb-1">Siti Nurhaliza</h5>
                                    <p class="text-muted">Bandung</p>
                                    <div class="text-warning mb-3">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4 text-center">
                                    <img src="assets/image/testimonial3.avif"
                                        class="rounded-circle object-fit-cover mb-3" width="80" height="80"
                                        alt="Testimonial">
                                    <p class="lead fst-italic mb-4">"Sangat membantu untuk kebutuhan transportasi bisnis
                                        kami. Pilihan mobilnya lengkap dan semua dalam kondisi baik."</p>
                                    <h5 class="fw-bold mb-1">Budi Santoso</h5>
                                    <p class="text-muted">Surabaya</p>
                                    <div class="text-warning mb-3">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Pertanyaan Umum</h2>
                <p class="text-muted">Jawaban untuk pertanyaan yang sering ditanyakan</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apa saja persyaratan untuk menyewa mobil?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Untuk menyewa mobil di layanan kami, Anda memerlukan KTP yang masih berlaku, SIM A yang
                                masih berlaku, dan kartu kredit/debit untuk deposit. Usia minimum penyewa adalah 21
                                tahun.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Bagaimana cara melakukan pembayaran?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Kami menerima pembayaran melalui transfer bank, kartu kredit/debit, dan e-wallet (DANA,
                                OVO, GoPay). Pembayaran penuh harus dilakukan sebelum pengambilan mobil.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Bagaimana kebijakan pembatalan pemesanan?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Pembatalan 7 hari sebelum tanggal pemesanan akan mendapatkan pengembalian penuh.
                                Pembatalan 3-6 hari sebelumnya akan dikenakan biaya 30%, dan pembatalan kurang dari 3
                                hari akan dikenakan biaya 50%.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Apakah bisa menyewa dengan sopir?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Ya, kami menyediakan layanan sopir profesional dengan biaya tambahan. Sopir kami
                                berpengalaman dan telah melalui seleksi ketat untuk memastikan keamanan dan kenyamanan
                                perjalanan Anda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="partners py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Mitra Kami</h2>
                <p class="text-muted">Bekerja sama dengan mitra terpercaya</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4 align-items-center justify-content-center">
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner1.jpg" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner2.jpg" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner3.png" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner4.png" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner5.webp" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                    <div class="col-12 col-md-2 text-center ">
                        <img src="assets/image/partner6.png" alt="Partner" width="150px" height="150px"
                            class="object-fit-cover grayscale rounded-circle hover-color">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="fw-bold">Dapatkan Info Terbaru</h2>
                <p class="text-muted mb-4">Berlangganan newsletter kami untuk mendapatkan promo dan penawaran eksklusif
                </p>
                <form class="newsletter-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Alamat email Anda" required>
                        <button class="btn btn-primary" type="submit">Berlangganan</button>
                    </div>
                    <div class="form-text text-muted">
                        Kami tidak akan pernah membagikan email Anda dengan pihak lain.
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Add custom scripts -->
<script>
    // Enable tooltips
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    // })

    // Add animation on scroll
    // window.addEventListener('DOMContentLoaded', (event) => {
    //     const animateElements = document.querySelectorAll('.card, .icon-box');

    //     function checkIfInView() {
    //         animateElements.forEach(el => {
    //             const rect = el.getBoundingClientRect();
    //             const isInViewport = rect.top <= window.innerHeight * 0.8;

    //             if (isInViewport) {
    //                 el.classList.add('animate__animated', 'animate__fadeInUp');
    //             }
    //         });
    //     }

    //     window.addEventListener('scroll', checkIfInView);
    //     checkIfInView(); // Check on initial load
    // });
</script>
</body>

</html>