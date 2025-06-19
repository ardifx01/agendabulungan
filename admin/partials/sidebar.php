<!-- Sidebar -->
<aside class="fixed top-16 left-0 w-64 h-full bg-white border-r border-gray-200 shadow z-40">
    <nav class="mt-4 px-4">
        <ul class="space-y-2 text-sm text-gray-700 font-medium">

            <li>
                <a href="../dashboard/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <i data-lucide="home" class="w-4 h-4 mr-2"></i> Dashboard
                </a>
            </li>

            <li class="mt-4 text-xs uppercase text-gray-500">Modul</li>

            <!-- Modul yang bisa diakses semua role -->
            <li>
                <a href="../agenda/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i> Agenda
                </a>
            </li>

            <li>
                <a href="../pidato/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Pidato
                </a>
            </li>

            <li>
                <a href="../keberadaan/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                    <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Keberadaan
                </a>
            </li>

            <!-- Modul khusus admin -->
            <?php if (is_admin()): ?>
                <li class="mt-4 text-xs uppercase text-gray-500">Master Data</li>

                <li>
                    <a href="../pejabat/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Pejabat
                    </a>
                </li>

                <li>
                    <a href="../peliput/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                        <i data-lucide="mic" class="w-4 h-4 mr-2"></i> Peliput
                    </a>
                </li>

                <li>
                    <a href="../protokoler/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                        <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i> Protokoler
                    </a>
                </li>

                <li>
                    <a href="../users/index.php" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
                        <i data-lucide="shield" class="w-4 h-4 mr-2"></i> Users
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

<!-- Spacer biar konten tidak ketindih sidebar -->
<div class="ml-64"></div>

<!-- Aktifkan ikon Lucide -->
<script>
    lucide.createIcons();
</script>