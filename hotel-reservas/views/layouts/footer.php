<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\layouts\footer.php

$base = '/hotel-reservas/public';
?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS Bundle (incluye Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= $base ?>/js/main.js"></script>
    
    <!-- Script adicional para asegurar dropdowns -->
    <script>
        // Asegurar que Bootstrap está cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            console.log('Bootstrap dropdowns inicializados:', dropdownList.length);
        });
    </script>
</body>
</html>