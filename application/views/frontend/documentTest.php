<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlan Belge Yükleme</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f7f8fc] flex items-center justify-center min-h-screen">

    <div id="app" class="bg-white shadow-xl rounded-xl p-6 w-full max-w-4xl">

        <!-- Başlık -->
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Dosya Yükle</h2>

        <!-- İlanlar Listesi ve Detay Modali Açma -->
        <div class="space-y-4">
            <div 
                v-for="(count, docName) in documentRequirements" 
                :key="docName" 
                class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-md hover:shadow-lg transition-all w-full"
            >
                <div class="flex flex-col w-full items-center">
                    <button 
                        @click="openModal(docName)" 
                        class="text-xl font-semibold text-gray-700 hover:text-blue-600 focus:outline-none mb-2"
                    >
                        {{ docName }}
                    </button>
                    <div class="flex justify-center mb-2">
                        <span class="text-gray-500 text-sm">{{ count }} dosya gerekli</span>
                    </div>
                    <button 
                        @click="openModal(docName)" 
                        class="w-32 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none text-sm"
                    >
                         EKLE
                    </button>
                </div>
            </div>
        </div>

        <!-- Ana Sayfada Girilen Bilgileri Gösterme -->
        <div class="mt-6 space-y-4">
            <h3 class="text-xl font-semibold text-gray-800">Yüklenen Dosyalar</h3>
            <div v-for="(docDetails, docName) in uploadedDocuments" :key="docName" class="bg-gray-100 p-4 rounded-md shadow-sm">
                <h4 class="font-semibold text-gray-700">{{ docName }}</h4>
                <div v-for="(detail, index) in docDetails" :key="index" class="text-gray-600 text-sm mb-2">
                    <strong>{{ detail.label }}:</strong> {{ detail.value }}
                </div>
            </div>
        </div>

        <!-- Detay Modalı -->
        <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-xl w-80 max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ modalDocName }} Yükleme</h3>
                <p class="mb-4 text-sm text-gray-600">Gerekli dosya sayısı: {{ documentRequirements[modalDocName] }}</p>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-600">Yüklenen Dosyalar: {{ uploadedCount }} / {{ documentRequirements[modalDocName] }}</span>
                    <span v-if="uploadedCount >= documentRequirements[modalDocName]" class="text-xs text-green-600 font-semibold">Tamamlandı</span>
                </div>

                <!-- Dosya Yükleme Formu -->
                <input type="file" multiple @change="handleFileChange" class="block w-full border border-gray-300 rounded-lg p-2 mb-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                
                <div v-if="selectedFiles.length > 0" class="mt-2">
                    <h4 class="text-gray-700 text-sm">Seçilen Dosyalar:</h4>
                    <ul class="list-disc ml-4 text-sm text-gray-600">
                        <li v-for="(file, index) in selectedFiles" :key="index">{{ file.name }}</li>
                    </ul>
                </div>

                <!-- Gerekli Bilgiler için Form Alanları -->
                <div class="mt-4">
                    <label for="author" class="block text-sm font-semibold text-gray-700">Yazar Mı?</label>
                    <select v-model="formData.author" id="author" class="w-full mt-2 p-2 border border-gray-300 rounded-lg bg-gray-50">
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="employees" class="block text-sm font-semibold text-gray-700">Çalışan Kişi Sayısı</label>
                    <input v-model="formData.employees" id="employees" type="number" class="w-full mt-2 p-2 border border-gray-300 rounded-lg bg-gray-50" placeholder="Çalışan sayısı" />
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-semibold text-gray-700">Açıklama</label>
                    <textarea v-model="formData.description" id="description" class="w-full mt-2 p-2 border border-gray-300 rounded-lg bg-gray-50" placeholder="Açıklama"></textarea>
                </div>

                <div class="mt-4 flex justify-between">
                    <button @click="closeModal" class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 focus:outline-none text-sm">
                        Kapat
                    </button>
                    <button 
                        :disabled="uploadedCount >= documentRequirements[modalDocName]" 
                        @click="submitFiles" 
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:outline-none disabled:opacity-50 text-sm"
                    >
                        Yükle
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    documentRequirements: {
                        "A1 BELGESİ": 5,
                        "A2 BELGESİ": 3,
                        "A3 BELGESİ": 4,
                        "A4 BELGESİ": 2,
                        "A5 BELGESİ": 6
                    },
                    isModalOpen: false,
                    modalDocName: '',
                    selectedFiles: [],
                    uploadedCount: 0, // Yüklenen dosya sayısını takip etmek için
                    uploadedDocuments: {}, // Ana sayfada gösterilecek dosyaların bilgisi
                    formData: {
                        author: 'Evet', // Varsayılan olarak 'Evet'
                        employees: '',
                        description: ''
                    }
                };
            },
            methods: {
                openModal(docName) {
                    this.modalDocName = docName;
                    this.uploadedCount = 0; // Modal her açıldığında sayacı sıfırla
                    this.selectedFiles = [];
                    this.formData = { author: 'Evet', employees: '', description: '' }; // Formu sıfırla
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                    this.selectedFiles = [];
                    this.uploadedCount = 0; // Modal kapandığında sayacı sıfırla
                },
                handleFileChange(event) {
                    const files = event.target.files;
                    // Mevcut dosyaları ve yeni seçilen dosyaları birleştiriyoruz
                    this.selectedFiles = [...this.selectedFiles, ...Array.from(files)];
                    this.uploadedCount = this.selectedFiles.length; // Yüklenen dosya sayısını güncelle
                },
                submitFiles() {
                    // Girilen bilgileri ana sayfada göstermek için
                    if (!this.uploadedDocuments[this.modalDocName]) {
                        this.uploadedDocuments[this.modalDocName] = [];
                    }
                    this.uploadedDocuments[this.modalDocName].push({
                        label: 'Yazar Mı?',
                        value: this.formData.author
                    });
                    this.uploadedDocuments[this.modalDocName].push({
                        label: 'Çalışan Kişi Sayısı',
                        value: this.formData.employees
                    });
                    this.uploadedDocuments[this.modalDocName].push({
                        label: 'Açıklama',
                        value: this.formData.description
                    });

                    // Dosya yükleme işlemi
                    alert(`Dosyalar ${this.modalDocName} için yükleniyor: ${this.selectedFiles.length} dosya.`);
                    // Burada dosyaları sunucuya yükleyebilirsiniz.
                    this.uploadedCount = this.selectedFiles.length;
                    this.closeModal();
                }
            }
        }).mount('#app');
    </script>

</body>

</html>
