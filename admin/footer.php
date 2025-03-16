<!-- <div class="footer mt-4 bg-dark text-white w-100 pt-3 pb-2">
  <div class="container">
    Copyright <?= date('Y'); ?> <?= $info_web->nama_rental; ?> All Reserved
  </div> -->
</div>
</div>
<script src="<?php echo $url; ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo $url; ?>assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
<script>
  // Add this to your JavaScript file or in a script tag at the bottom of your page

  document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
          sidebar.style.width = '70px';
          mainContent.style.marginLeft = '70px';
          mainContent.style.width = 'calc(100% - 70px)';

          // Hide text in sidebar
          const navText = document.querySelectorAll('.sidebar-menu .nav-link span');
          navText.forEach(span => {
            span.style.display = 'none';
          });

          // Center icons
          const navIcons = document.querySelectorAll('.sidebar-menu .nav-link i');
          navIcons.forEach(icon => {
            icon.classList.add('mx-auto');
            icon.classList.remove('me-3');
          });

          // Hide sidebar header and footer
          document.querySelector('.sidebar-header').style.display = 'none';
          document.querySelector('.sidebar-footer').style.display = 'none';
        } else {
          sidebar.style.width = '280px';
          mainContent.style.marginLeft = '280px';
          mainContent.style.width = 'calc(100% - 280px)';

          // Show text in sidebar
          const navText = document.querySelectorAll('.sidebar-menu .nav-link span');
          navText.forEach(span => {
            span.style.display = 'block';
          });

          // Restore icon alignment
          const navIcons = document.querySelectorAll('.sidebar-menu .nav-link i');
          navIcons.forEach(icon => {
            icon.classList.remove('mx-auto');
            icon.classList.add('me-3');
          });

          // Show sidebar header and footer
          document.querySelector('.sidebar-header').style.display = 'block';
          document.querySelector('.sidebar-footer').style.display = 'block';
        }
      });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Handle active menu item highlighting based on URL
    const currentLocation = window.location.href;
    const menuItems = document.querySelectorAll('.sidebar-menu .nav-link');


    // Add animation class to content cards
    const cards = document.querySelectorAll('.card');

    function addFadeInClass() {
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('fade-in');
        }, index * 100);
      });
    }

    if (cards.length > 0) {
      addFadeInClass();
    }
  });
</script>
</body>

</html>