<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Panel</title>
    <script src="<?php echo base_url('application/views/frontend/vue.global.js'); ?>"></script>
    <script src="<?php echo base_url('application/views/frontend/axios.min.js'); ?>"></script>
    <script src="<?php echo base_url('application/views/frontend/tailwind.js'); ?>"></script>
</head>

<body class="bg-gray-100 flex h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 fixed h-full ">
        <h2 class="text-2xl font-bold mb-6 text-center">Yönetici Panel</h2>
        <ul>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/admin'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Ana
                    Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/users'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Kullanıcılar</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/roleAssignment'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/position'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Pozisyonlar</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/department'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Departmanlar</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/advert'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">İlanlar</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('admin/addJury'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Jüri Ekleme</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('login'); ?>"
                    class="block py-2 px-4 rounded hover:bg-gray-700">Çıkış Yap</a>
            </li>
            <li class="mb-4">
                <hr>
            </li>
            <?php if(file_get_contents(base_url()."admin/role_check")):?>
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
            <?php endif;?>
        </ul>
    </div>