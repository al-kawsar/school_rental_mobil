    <div class="footer h-full mt-4 bg-dark text-white pt-3 pb-2">
      <div class="container">
        Copyright <?= date('Y');?> <?= $info_web->nama_rental;?> All Reserved
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('logout')) {
          Swal.fire({
            title: "Logout Berhasil!",
            text: "Anda telah keluar dari sistem.",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          }).then(() => {
            // Hapus parameter dari URL tanpa reload
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
          });
        }
      });
    </script>

  </body>
  </html>