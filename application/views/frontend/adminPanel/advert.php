<?php include 'header.php'; ?>
<!-- Main Content -->
<div id="app" class="flex-1 p-8 ml-64">
    <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
    <p class="text-gray-600 mt-2">İlanları Yönetebilirsin.</p>

    <!-- İlan Formu -->
    <div v-if="showForm" class="bg-white p-4 rounded-lg shadow-lg mt-4">
        <form method="POST" action="<?php echo base_url('admin/addAdvert'); ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Başlık</label>
                <input type="text" name="title" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                <input type="date" name="date_start" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                <input type="date" name="date_end" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pozisyon</label>
                <select name="position" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                    <?php foreach ($positions as $position) : ?>
                    <option value="<?php echo $position->id; ?>"><?php echo $position->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Departman</label>
                <select name="department" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                    <?php foreach ($departments as $department) : ?>
                    <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                <textarea name="text" rows="4" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                    required></textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Kaydet</button>
        </form>
    </div>

    <!-- Mevcut İlanlar -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Mevcut İlanlar</h2>
        <ul class="space-y-4">
            <?php foreach ($ads as $advert) : ?>
            <li class="bg-white p-4 rounded-lg shadow-md flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold"><?php echo $advert->title; ?></h3>
                    <p class="text-sm text-gray-500">📅 <?php echo $advert->date_start; ?> -
                        <?php echo $advert->date_end; ?></p>
                    <p class="text-sm text-gray-600 mt-2"><?php echo $advert->text; ?></p>
                </div>
                <div class="flex gap-2">
                    <button @click="openEditModal(<?php echo htmlspecialchars(json_encode($advert)); ?>)"
                        class="bg-yellow-500 text-white px-3 py-1 rounded">Düzenle</button>

                    <form action="<?php echo base_url('admin/deleteAdvert'); ?>" method="POST"
                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                        <input type="hidden" name="id" value="<?php echo $advert->id; ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                    </form>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

        <?php if (empty($ads)) : ?>
        <p class="text-gray-600 mt-4">Henüz ilan eklenmedi.</p>
        <?php endif; ?>
    </div>

    <!-- Düzenleme Modalı -->
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">İlanı Düzenle</h2>
            <form method="POST" action="<?php echo base_url('admin/updateAdvert'); ?>" class="space-y-4">
                <input type="hidden" name="id" :value="selectedAdvert.id" />

                <div>
                    <label class="block text-sm font-medium text-gray-700">Başlık</label>
                    <input type="text" name="title" v-model="selectedAdvert.title"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                    <input type="date" name="date_start" v-model="selectedAdvert.date_start"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                    <input type="date" name="date_end" v-model="selectedAdvert.date_end"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pozisyon</label>
                    <select name="position_id" v-model="selectedAdvert.position_id"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($positions as $position) : ?>
                        <option value="<?php echo $position->id; ?>"><?php echo $position->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Departman</label>
                    <select name="department_id" v-model="selectedAdvert.department_id"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($departments as $department) : ?>
                        <option value="<?php echo $department->id; ?>"><?php echo $department->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                    <textarea name="text" rows="4" v-model="selectedAdvert.text"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg" required></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showModal = false"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">İptal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Vue.js Script -->
<script>
const {
    createApp
} = Vue;

createApp({
    data() {
        return {
            showForm: true,
            showModal: false,
            selectedAdvert: {
                id: '',
                title: '',
                date_start: '',
                date_end: '',
                position_id: '',
                department_id: '',
                text: ''
            }
        };
    },
    methods: {
        openEditModal(advert) {
            this.selectedAdvert = {
                ...advert
            };
            this.showModal = true;
        }
    }
}).mount("#app");
</script>

</body>

</html>