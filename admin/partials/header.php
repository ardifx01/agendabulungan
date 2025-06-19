<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Bulungan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional Icon CDN (Lucide Icons) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Favicon (optional) -->
    <link rel="icon" href="../../assets/images/logobulungan.png" type="image/png">
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header/Navbar -->
<header class="bg-white shadow fixed top-0 left-0 w-full z-50 h-16 flex items-center justify-between px-6">
    <!-- Kiri: Logo dan Judul -->
    <div class="flex items-center space-x-3">
        <img src="../../assets/images/logobulungan.png" alt="Logo Bulungan" class="h-10 w-10 object-contain">
        <span class="text-xl font-bold text-green-700">Agenda Bulungan</span>
    </div>

    <!-- Kanan: Tombol Logout -->
    <div>
        <a href="../logout.php" class="text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
            Logout
        </a>
    </div>
</header>

<!-- Spacer biar konten tidak ketutup header -->
<div class="h-16"></div>