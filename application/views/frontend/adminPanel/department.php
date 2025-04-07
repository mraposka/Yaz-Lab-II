<?php include 'header.php';?>

    <!-- Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold mb-6">Departman Ekle</h2>

        <!-- Departman Ekleme Formu -->
        <form method="POST" action="<?php echo base_url('admin/addDepartment'); ?>" class="mb-6">
            <div class="mb-4">
                <label for="department_name" class="block text-sm font-medium text-gray-700">Departman Adı</label>
                <input type="text" id="department_name" name="department_name" required
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Ekle
            </button>
        </form>

        <!-- Departmanları Listeleme -->
        <h2 class="text-2xl font-bold mb-4">Mevcut Departmanlar</h2>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">Departman</th>
                    <th class="py-2 px-4 text-center">Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?php echo $department->title; ?></td>
                    <td class="py-2 px-4 text-center">
                        <form action="<?php echo base_url('admin/deleteDepartment'); ?>" method="POST" class="inline"
                            onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="id" value="<?php echo $department->id; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($departments)): ?>
                <tr>
                    <td colspan="2" class="py-2 px-4 text-center text-gray-500">Henüz eklenmiş departman yok.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    const {
        createApp
    } = Vue;

    createApp({
        data() {

        },
    }).mount('#app');
    </script>

</body>

</html>