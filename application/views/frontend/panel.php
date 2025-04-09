<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="<?php echo base_url('application/views/frontend/tailwind.js'); ?>"></script>
</head>

<body class="bg-gray-100 flex h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 fixed h-full ">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/admin'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Admin</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('user/user'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">User</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('jury/jury'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Juri</a>
            </li>
        </ul>
    </div>