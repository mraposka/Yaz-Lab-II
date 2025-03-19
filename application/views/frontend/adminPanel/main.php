<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 flex flex-col h-screen fixed">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul class="flex-1">
            <li class="mb-4">
                <a href="<?php echo base_url('/web/admin'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('/web/roleAssignment'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('/web/advert'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">İlan Ekle</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <div v-if="currentPage === 'home'">
            <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
            <p class="text-gray-600 mt-2">Bugün harika bir gün!</p>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    currentPage: 'home'
                };
            },
            methods: {
                toggleSidebar() {
                    document.getElementById('sidebar').classList.toggle('hidden');
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
