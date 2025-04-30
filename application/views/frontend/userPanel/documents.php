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
    <?php
    $paperRules = $rules->paperrules;
    $scoreRules = $rules->scorerules;

    function parseRulesToDocumentRequirements($ruleString)
    {
        $requirements = [];
        $by = 0;
        $tm = 0;

        $parts = explode(',', $ruleString);
        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, '=') !== false) {
                [$key, $value] = explode('=', $part);
                $key = trim($key);
                $value = (int)trim($value);
                if ($key === 'BY') {
                    $by = $value;
                } elseif ($key === 'TM') {
                    $tm = $value;
                } else {
                    $requirements[$key . ' BELGESİ'] = $value;
                }
            }
        }

        return [
            'documents' => $requirements,
            'rules' => [
                'BY' => $by,
                'TM' => $tm
            ]
        ];
    }

    $parsedRules = parseRulesToDocumentRequirements($paperRules);
    $documentRequirements = $parsedRules['documents'];
    $ruleCounts = $parsedRules['rules'];
    ?>
    <script>
        const categories = <?php echo json_encode($cats, JSON_UNESCAPED_UNICODE); ?>;
        const documentRequirements = <?php echo json_encode($documentRequirements, JSON_UNESCAPED_UNICODE); ?>;
        const ruleCounts = <?php echo json_encode($ruleCounts, JSON_UNESCAPED_UNICODE); ?>;
    </script>

    <div id="app" class="bg-white shadow-xl rounded-xl p-6 w-full max-w-4xl">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Dosya Yükle</h2>

        <form method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
            <input type="hidden" name="app_id" value="<?php echo $app_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <!-- Belge listesi -->
            <div class="space-y-4">
                <div v-for="(count, docName) in documentRequirements" :key="docName"
                    class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-md">
                    <div class="flex flex-col w-full items-center">
                        <button type="button" @click="openModal(docName)"
                            class="text-xl font-semibold text-gray-700 hover:text-blue-600 mb-2">
                            {{ docName }}
                        </button>
                        <span class="text-gray-500 text-sm mb-2">{{ remainingFiles(docName) }} dosya gerekli</span>
                        <button type="button" @click="openModal(docName)"
                            class="w-32 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 text-sm">
                            EKLE
                        </button>
                    </div>
                </div>
            </div>

            <!-- Yüklenen belgeler -->
            <div class="mt-6 space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">Yüklenen Dosyalar</h3>
                <div v-for="(docDetails, docName) in uploadedDocuments" :key="docName"
                    class="bg-gray-100 p-4 rounded-md shadow-sm">
                    <h4 class="font-semibold text-gray-700">{{ docName }}</h4>
                    <div v-for="(detail, index) in docDetails" :key="index" class="text-gray-600 text-sm mb-2">
                        <strong>{{ detail.file.name }}</strong>
                        <ul class="ml-4 list-disc">
                            <li><strong>Baş Yazar Mı:</strong> {{ detail.author }}</li>
                            <li><strong>Çalışan Sayısı:</strong> {{ detail.employees }}</li>
                            <li><strong>Açıklama:</strong> {{ detail.description }}</li>
                        </ul>
                        <div class="mt-1 flex gap-2">
                            <button type="button" class="text-sm text-blue-500" @click="editFile(docName, index)">Düzenle</button>
                            <button type="button" class="text-sm text-red-500" @click="removeFile(docName, index)">Sil</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tamamla Butonu -->
            <div class="mt-6 text-center" v-if="isSubmissionReady">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 text-sm">TAMAMLA</button>
            </div>
        </form>

        <!-- Modal -->
        <!-- Modal -->
        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-xl w-96 max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ modalDocName }} Yükleme</h3>
                <p class="mb-2 text-sm text-gray-600">Gerekli dosya sayısı: {{ documentRequirements[modalDocName] }}</p>

                <input type="file" @change="handleFileChange"
                    class="block w-full border border-gray-300 rounded-lg p-2 mb-2 bg-gray-50" />
                <div class="text-red-500 text-xs mb-2" v-if="!formData.file && formSubmitted">Dosya seçmelisiniz.</div>

                <select v-model="formData.author"
                    class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 mb-2">
                    <option disabled value="">Baş Yazar mı?</option>
                    <option value="Evet">Evet</option>
                    <option value="Hayır">Hayır</option>
                </select>
                <div class="text-red-500 text-xs mb-2" v-if="!formData.author && formSubmitted">Seçim yapmalısınız.</div>

                <input type="number" v-model="formData.employees"
                    class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 mb-2"
                    placeholder="Çalışan Sayısı" />
                <div class="text-red-500 text-xs mb-2" v-if="!formData.employees && formSubmitted">Zorunlu alan.</div>

                <textarea v-model="formData.description"
                    class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 mb-2"
                    placeholder="Açıklama"></textarea>
                <div class="text-red-500 text-xs mb-2" v-if="!formData.description && formSubmitted">Zorunlu alan.</div>

                <!-- Kategori Dropdown -->
                <select v-model="formData.category"
                    class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 mb-2">
                    <option v-for="category in categories" :key="category.id" :value="category.title">
                        {{ category.title }}
                    </option>
                </select>
                <div class="text-red-500 text-xs mb-2" v-if="!formData.category && formSubmitted">Kategori seçmelisiniz.</div>

                <div class="flex justify-between mt-4 gap-2">
                    <button @click="closeModal"
                        class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 text-sm">İptal</button>
                    <button @click="submitSingleFile"
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 text-sm">Yükle</button>
                </div>
            </div>
        </div>


    </div>

    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    categories: categories, // PHP'den gelen kategori verisi
                    documentRequirements,
                    ruleCounts,
                    isModalOpen: false,
                    modalDocName: '',
                    uploadedDocuments: {},
                    formData: {
                        file: null,
                        author: '',
                        employees: '',
                        description: '',
                        category: '' // Kategori
                    },
                    currentEditIndex: null,
                    formSubmitted: false
                };
            },
            computed: {
                isSubmissionReady() {
                    const docCheck = Object.entries(this.documentRequirements).every(([doc, count]) => {
                        return (this.uploadedDocuments[doc]?.length || 0) >= count;
                    });
                    const totalFiles = Object.values(this.uploadedDocuments).flat();
                    const byCount = totalFiles.filter(f => f.author === 'Evet').length;
                    return docCheck && totalFiles.length >= this.ruleCounts.TM && byCount >= this.ruleCounts.BY;
                }
            },
            methods: {
                openModal(docName) {
                    this.modalDocName = docName;
                    this.isModalOpen = true;
                    this.formData = { file: null, author: '', employees: '', description: '' };
                    this.currentEditIndex = null;
                    this.formSubmitted = false;
                },
                closeModal() {
                    this.isModalOpen = false;
                },
                handleFileChange(e) {
                    this.formData.file = e.target.files[0];
                },
                submitSingleFile() {
                    this.formSubmitted = true;
                    if (!this.formData.file || !this.formData.author || !this.formData.employees || !this.formData.description || !this.formData.category) return;

                    const newEntry = { ...this.formData };

                    if (!this.uploadedDocuments[this.modalDocName]) {
                        this.uploadedDocuments[this.modalDocName] = [];
                    }

                    if (this.currentEditIndex !== null) {
                        this.uploadedDocuments[this.modalDocName][this.currentEditIndex] = newEntry;
                    } else {
                        this.uploadedDocuments[this.modalDocName].push(newEntry);
                    }

                    this.closeModal();
                },
                removeFile(docName, index) {
                    this.uploadedDocuments[docName].splice(index, 1);
                },
                editFile(docName, index) {
                    const file = this.uploadedDocuments[docName][index];
                    this.modalDocName = docName;
                    this.formData = { ...file };
                    this.isModalOpen = true;
                    this.currentEditIndex = index;
                    this.formSubmitted = false;
                },
                remainingFiles(docName) {
                    const uploaded = this.uploadedDocuments[docName]?.length || 0;
                    return this.documentRequirements[docName] - uploaded;
                },
                submitForm() {
                    const formData = new FormData();
                    const appId = document.querySelector('input[name="app_id"]').value;
                    const userId = document.querySelector('input[name="user_id"]').value;
                    let i=0;
                    // Dosyaları ve meta bilgilerini FormData'ya ekle
                    for (const docName in this.uploadedDocuments) {
                        this.uploadedDocuments[docName].forEach((fileDetail, index) => { 
                            formData.append(`files[${docName}][]`, fileDetail.file); 
                            formData.append(`meta[${i}][${index}][author]`, fileDetail.author);
                            formData.append(`meta[${i}][${index}][employees]`, fileDetail.employees);
                            formData.append(`meta[${i}][${index}][description]`, fileDetail.description);
                            formData.append(`meta[${i}][${index}][category]`, fileDetail.category); // Kategori ekle
                            i++;
                        });
                    }
                    formData.append('app_id', appId);
                    formData.append('user_id', userId);
                    fetch('handleUpload', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .then(responseText => {
                        if(responseText==1){
                            window.location.replace('<?php echo base_url('user/applications');?>');
                        }else{
                            console.log('Dosyalar yüklenirken bir hata oluştu.');
                        }
                    })
                    .catch(err => {
                        alert("Hata: " + err.message);
                    });
                }
            }
        }).mount('#app');
    </script>
</body>

</html>
