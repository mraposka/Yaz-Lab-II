<?php include 'header.php'; ?>
<body class="bg-[#f7f8fc] flex items-center justify-center min-h-screen">

    <div id="app" class="flex-1 p-8 ml-64 bg-white shadow-xl rounded-xl p-6 w-full mt-6">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Jüri Değerlendirme</h2>

        <form method="POST" action="<?php echo base_url('jury/saveReview'); ?>">
        <?php foreach ($applications[0]['documents'] as $index => $doc): ?>
            <div class="border border-gray-300 rounded-xl shadow-lg bg-white p-6 mb-6">
                <!-- Belge Bilgisi -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Yüklenen Dosya</label>
                    <div class="py-2 px-4 text-left max-w-xs break-words">
                        <strong><?= htmlspecialchars($doc['description']) ?>:</strong><br>
                        <a href="<?= base_url('uploads/') . htmlspecialchars($doc['file_path']) ?>" 
                        class="text-blue-600 underline" target="_blank">
                            <?= htmlspecialchars($doc['file_path']) ?>
                        </a>
                        <?php if (!empty($doc['rules'])): ?>
                            <div class="mt-1 text-sm text-gray-700">
                                <?= implode('<br>', array_map('htmlspecialchars', explode(',', $doc['rules']))) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Yorum -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Yorumunuzu Girin</label>
                    <textarea v-model="formData.comment[<?= $index ?>]" name="comment[]" 
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50"
                        placeholder="Yorumunuzu buraya yazın..." rows="4"></textarea>
                    <div class="text-red-500 text-xs mt-1" v-if="formSubmitted && !formData.comment[<?= $index ?>]">Yorum alanı boş olamaz.</div>
                </div>

                <!-- Puanlama -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Puanınızı Verin</label>
                    <input
                        v-model="formData.rating[<?= $index ?>]" 
                        type="number" 
                        name="score[]" 
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50"
                        placeholder="Puan" 
                        min="1" 
                        :readonly="formData.approve[<?= $index ?>] == 1"
                    />
                    <div class="text-red-500 text-xs mt-1" 
                        v-if="formSubmitted && (!formData.rating[<?= $index ?>] || formData.rating[<?= $index ?>] < 1)">
                        Geçerli bir puan girin.
                    </div>
                </div>

                <!-- Onay -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center justify-between">
                    <label class="text-gray-700 font-semibold">Onayla<?php echo str_replace('P=','Onaylanacak Puan = ',end(explode(',', $doc['rules']))); ?></label>
                    <input 
                        v-model="formData.approve[<?= $index ?>]" 
                        type="checkbox" 
                        name="approve[]" 
                        class="rounded-lg" 
                        :true-value="1" 
                        :false-value="0"
                        @change="handleApproveChange(<?= $index ?>, '<?= htmlspecialchars($doc['rules']) ?>')"
                    />

                </div>
            </div>
        <?php endforeach; ?>


            <!-- Gönder Butonu -->
            <div class="mt-6 text-center">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 text-sm">Değerlendirmeyi Gönder</button>
            </div>
            <input name="app_id" type="hidden" value="<?php echo $app_id;?>">
            <input name="user_id" type="hidden" value="<?php echo $user_id;?>">
        </form>

    </div>

    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    formData: {
                        comment: [],
                        rating: [],
                        approve: []
                    },
                    formSubmitted: false,
                    submitted: false
                };
            },
            methods: {
                submitForm() {
                    this.formSubmitted = true;

                    if (this.formData.comment && this.formData.rating >= 1) {
                        this.submitted = true;
                    }
                },
                handleApproveChange(index, rules) {
                    if (this.formData.approve[index]) {
                        const match = rules.match(/P=(\d+)/);
                        if (match) {
                            this.formData.rating[index] = parseInt(match[1]);
                        }
                    }
                }
            }
        }).mount('#app');
    </script>
</body>

</html>