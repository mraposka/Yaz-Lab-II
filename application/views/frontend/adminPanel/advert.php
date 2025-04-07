<?php include 'header.php';?>
    <!-- Main Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
        <p class="text-gray-600 mt-2">İlanları Yönetebilirsin.</p>

        <!-- İlan Formu -->
        <div v-if="showForm" class="bg-white p-2 rounded-lg shadow-lg mt-2">
            <form method="POST" action="<?php echo base_url('admin/addAdvert'); ?>"
                class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow" id="custom-form">
                <!-- Başlık -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Başlık</label>
                    <input type="text" name="title" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>

                <!-- Başlangıç Tarihi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                    <input type="date" name="date_start" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                        required />
                </div>

                <!-- Bitiş Tarihi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                    <input type="date" name="date_end" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>

                <!-- Pozisyon -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pozisyon</label>
                    <select name="position" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($positions as $position) : ?>
                        <option value="<?php echo $position->id; ?>"><?php echo $position->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Departman -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Departman</label>
                    <select name="department" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($departments as $department) : ?>
                        <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Açıklama / Metin -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                    <textarea name="text" rows="5" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                        placeholder="Açıklama girin..." required></textarea>
                </div>

                <!-- Gönder butonu -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                    Kaydet
                </button>
            </form>

        </div>
    </div>

    <!-- Mevcut İlanlar -->
    <div class="mt-6">
        <h2 class="text-2xl font-bold mb-4">Mevcut İlanlar</h2>
        <ul class="space-y-4">
            <?php foreach ($ads as $advert) : ?>
            <li class="bg-white p-4 rounded-lg shadow-md flex justify-between">
                <div>
                    <h3 class="text-lg font-bold"><?php echo $advert->title; ?></h3>
                    <p class="text-sm text-gray-500">📅 <?php echo $advert->date_start; ?> -
                        <?php echo $advert->date_end; ?></p>
                    <p class="text-sm text-gray-500">📅 <?php echo $advert->text; ?>
                </div>
                <div class="gap-2">
                    <!-- Düzenle Formu -->
                    <form action="<?php echo base_url('admin/editAdvert'); ?>" method="POST" class="inline">
                        <input type="hidden" name="id" value="<?php echo $advert->id; ?>">
                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded">Düzenle</button>
                    </form>

                    <!-- Sil Formu -->
                    <form action="<?php echo base_url('admin/deleteAdvert'); ?>" method="POST" class="inline"
                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                        <input type="hidden" name="id" value="<?php echo $advert->id; ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                    </form>

                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php if (empty($ads)) : ?>
        <p class="text-gray-600">Henüz ilan eklenmedi.</p>
        <?php endif; ?>
    </div>

    <script>
    const {
        createApp
    } = Vue;

    createApp({
        data() {
            return {
                showForm: true
            };
        }
    }).mount("#app");
    </script>
</body>

</html>