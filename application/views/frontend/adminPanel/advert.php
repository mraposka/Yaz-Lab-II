<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
</head>

<body class="bg-gray-100 flex h-screen">
    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 transition-all duration-300">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li class="mb-4">
                <a href="#" @click="currentPage = 'home'" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="roles.html" class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div id="app" class="flex-1 p-8">
        <div v-if="currentPage === 'home'">
            <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
            <p class="text-gray-600 mt-2">İlanları yönetebilirsiniz.</p>

            <!-- Yeni İlan Ekleme -->
            <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
                <h2 class="text-2xl font-bold mb-4">Yeni İlan Ekle</h2>
                <form @submit.prevent="addListing">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Başlık</label>
                        <input v-model="newListing.title" type="text" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                        <textarea v-model="newListing.description" class="mt-1 block w-full px-4 py-2 border rounded-lg" required></textarea>
                    </div>
                    <div class="mb-4 flex gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                            <input v-model="newListing.startDate" type="date" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                            <input v-model="newListing.endDate" type="date" class="mt-1 block w-full px-4 py-2 border rounded-lg" required />
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">İlanı Ekle</button>
                </form>
            </div>

            <!-- Mevcut İlanlar -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Mevcut İlanlar</h2>
                <ul v-if="listings.length" class="space-y-4">
                    <li v-for="(listing, index) in listings" :key="index" class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold">{{ listing.title }}</h3>
                            <p class="text-gray-600">{{ listing.description }}</p>
                            <p class="text-sm text-gray-500">📅 {{ listing.startDate }} - {{ listing.endDate }}</p>
                        </div>
                        <button @click="editListing(index)" class="bg-yellow-500 text-white py-1 px-3 rounded-lg">Düzenle</button>
                    </li>
                </ul>
                <p v-else class="text-gray-600">Henüz ilan eklenmedi.</p>
            </div>

            <!-- Başvuruları Görüntüle -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Başvurular</h2>
                <ul v-if="applications.length" class="space-y-4">
                    <li v-for="(app, index) in applications" :key="index" class="bg-white p-4 rounded-lg shadow-md">
                        <p><strong>{{ app.user }}</strong>, <span class="text-blue-500">{{ app.listingTitle }}</span> ilanına başvurdu.</p>
                    </li>
                </ul>
                <p v-else class="text-gray-600">Başvuru bulunmamaktadır.</p>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    currentPage: 'home',
                    newListing: { title: "", description: "", startDate: "", endDate: "" },
                    listings: [],
                    applications: [
                        { user: "Mehmet", listingTitle: "Yazılım Uzmanı Aranıyor" },
                        { user: "Ayşe", listingTitle: "Grafik Tasarımcı Aranıyor" }
                    ]
                };
            },
            methods: {
                addListing() {
                    if (!this.newListing.title || !this.newListing.description || !this.newListing.startDate || !this.newListing.endDate) {
                        alert("Tüm alanları doldurun!");
                        return;
                    }
                    this.listings.push({ ...this.newListing });
         
