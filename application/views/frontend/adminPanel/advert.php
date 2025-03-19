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
                <a href="<?php echo base_url('web/admin'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('web/roleAssignment'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('web/advert'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">İlan Ekle</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
        <p class="text-gray-600 mt-2">İlanları yönetebilirsin.</p>

        <!-- İlan Formu -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <h2 class="text-2xl font-bold mb-4">İlan Formu</h2>
            <form @submit.prevent="saveAdvert">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Başlık</label>
                    <input v-model="newAdvert.title" type="text" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Başvuru Koşulları</label>
                    <textarea v-model="newAdvert.conditions" class="mt-1 block w-full px-4 py-2 border rounded-lg" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Gerekli Belgeler</label>
                    <div class="mt-1 block w-full px-4 py-2 border rounded-lg bg-white">
                        <div v-for="doc in availableDocuments" :key="doc" class="flex items-center">
                            <input type="checkbox" :value="doc" v-model="newAdvert.documents" class="mr-2" />
                            <span>{{ doc }}</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4 flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                        <input v-model="newAdvert.startDate" type="date" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                        <input v-model="newAdvert.endDate" type="date" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                    {{ newAdvert.id === null ? 'İlanı Ekle' : 'İlanı Güncelle' }}
                </button>
            </form>
        </div>

        <!-- Mevcut İlanlar -->
        <div class="mt-6">
            <h2 class="text-2xl font-bold mb-4">Mevcut İlanlar</h2>
            <ul v-if="adverts.length" class="space-y-4">
                <li v-for="(advert, index) in adverts" :key="advert.id" class="bg-white p-4 rounded-lg shadow-md flex justify-between">
                    <div>
                        <h3 class="text-lg font-bold">{{ advert.title }}</h3>
                        <p class="text-sm text-gray-500">📅 {{ advert.startDate }} - {{ advert.endDate }}</p>
                        <p class="text-sm text-gray-700">Koşullar: {{ advert.conditions }}</p>
                        <p class="text-sm text-gray-700">Belgeler: {{ advert.documents.join(', ') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="editAdvert(index)" class="bg-yellow-500 text-white px-3 py-1 rounded">Düzenle</button>
                        <button @click="deleteAdvert(advert.id)" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                    </div>
                </li>
            </ul>
            <p v-else class="text-gray-600">Henüz ilan eklenmedi.</p>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    newAdvert: { id: null, title: "", conditions: "", documents: [], startDate: "", endDate: "" },
                    adverts: [],
                    availableDocuments: ["Atıf Sayısı", "İndeksli Yayın", "Konferans Yayını"]
                };
            },
            mounted() {
                this.fetchAdverts();
            },
            methods: {
                async fetchAdverts() {
                    try {
                        const response = await axios.get("<?php echo base_url('web/getAdverts'); ?>");
                        this.adverts = response.data;
                    } catch (error) {
                        console.error("İlanlar yüklenirken hata oluştu:", error);
                    }
                },
                async saveAdvert() {
                    if (!this.newAdvert.title || !this.newAdvert.conditions || !this.newAdvert.startDate || !this.newAdvert.endDate) {
                        alert("Tüm alanları doldurun!");
                        return;
                    }

                    try {
                        const response = await axios.post("<?php echo base_url('web/saveAdvert'); ?>", this.newAdvert);
                        alert(response.data.message);
                        this.fetchAdverts();
                        this.newAdvert = { id: null, title: "", conditions: "", documents: [], startDate: "", endDate: "" };
                    } catch (error) {
                        console.error("İlan kaydedilirken hata oluştu:", error);
                    }
                },
                editAdvert(index) {
                    this.newAdvert = { ...this.adverts[index] };
                    this.newAdvert.documents = JSON.parse(this.newAdvert.documents);
                },
                async deleteAdvert(id) {
                    if (!confirm("Bu ilanı silmek istediğinizden emin misiniz?")) return;

                    try {
                        const response = await axios.delete(`<?php echo base_url('web/deleteAdvert/'); ?>${id}`);
                        alert(response.data.message);
                        this.fetchAdverts();
                    } catch (error) {
                        console.error("İlan silinirken hata oluştu:", error);
                    }
                }
            }
        }).mount("#app");
    </script>

</body>
</html>