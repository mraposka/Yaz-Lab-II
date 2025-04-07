<?php include 'header.php';?>

    <!-- Main content -->
    <div class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold mb-6">Kullanıcılar</h2>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2 border">Fotoğraf</th>
                        <th class="px-4 py-2 border">TC</th>
                        <th class="px-4 py-2 border">Ad</th>
                        <th class="px-4 py-2 border">Soyad</th>
                        <th class="px-4 py-2 border">E-Posta</th>
                        <th class="px-4 py-2 border">Rol</th>
                        <th class="px-4 py-2 border">Kayıt Tarihi</th>
                        <th class="px-4 py-2 border">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) : ?>
                <tr class="border">
                    <td class="px-4 py-2 border">
                        <img src="<?php echo base_url('admin/uploads/' . $user->image); ?>" alt="Profil" class="w-12 h-12 rounded-full">
                    </td>
                    <td class="px-4 py-2 border"><?php echo $user->tc; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user->name; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user->surname; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user->email; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user->role; ?></td>
                    <td class="px-4 py-2 border"><?php echo $user->created_at; ?></td>
                    <td class="px-4 py-2 border flex gap-2">
                        <button type="button" onclick="openModal()" class="bg-yellow-500 text-white px-3 py-1 rounded">Düzenle</button>
                        <form method="POST" action="<?php echo base_url('admin/deleteUser'); ?>" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($users)) : ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Henüz kullanıcı eklenmedi.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

 <!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
        <h2 class="text-xl font-bold mb-4">Kullanıcıyı Düzenle</h2>

        <form>
            <div class="mb-4">
                <label for="tc" class="block text-sm font-medium text-gray-700">TC</label>
                <input type="text" id="tc" name="tc" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                    pattern="\d*" maxlength="11" required 
                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" 
                    placeholder="TC Giriniz" />
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Ad</label>
                <input type="text" id="name" name="name" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <label for="surname" class="block text-sm font-medium text-gray-700">Soyad</label>
                <input type="text" id="surname" name="surname" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
                <input type="email" id="email" name="email" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                    pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required placeholder="Email Giriniz" />
            </div>

            <div class="flex justify-between mt-6">
                <!-- Kapat Butonu -->
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kapat</button>
                <!-- Düzenle Butonu -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Düzenle</button>
            </div>
        </form>
    </div>
</div>



    <!-- JavaScript -->
    <script>
        function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</body>
</html>
