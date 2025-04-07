<?php include 'header.php';?>

    <!-- Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold mb-6">Pozisyon Ekle</h2>

        <form method="POST" action="<?php echo base_url('admin/addPosition'); ?>" class="mb-6">
            <div class="mb-4">
                <label for="position_name" class="block text-sm font-medium text-gray-700">Pozisyon Adı</label>
             <input type="text" id="position_name" name="position_name" required
                class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
             </div>

             <button type="submit"
                 class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Ekle
            </button>
        </form>

        <!-- Pozisyonları Listeleme -->
        <h2 class="text-2xl font-bold mb-4">Mevcut Pozisyonlar</h2>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">Pozisyon</th>
                    <th class="py-2 px-4 text-center">Sil</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($positions)): ?>
            <?php foreach ($positions as $position): ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?php echo htmlspecialchars($position->title); ?></td>
                    <td class="py-2 px-4 text-center">
                        <form method="POST" action="<?php echo base_url('admin/deletePosition'); ?>" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                            <input type="hidden" name="id" value="<?php echo $position->id; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" class="py-2 px-4 text-center text-gray-500">Henüz eklenmiş pozisyon yok.</td>
            </tr>
        <?php endif; ?>
            </tbody>
        </table>

    </div>


    <script>
        const { createApp } = Vue;

        createApp({
            data() {
        
            },
        }).mount('#app');
    </script>

</body>
</html>

