<?php include 'header.php'; ?>
    <!-- Main Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold">Hoşgeldin, Juri!</h2>
        <p class="text-gray-600 mt-2">İlan incelemelerini yapabilirsin.</p>

        <!-- İlan Formu -->
        <div v-if="showForm" class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <h2 class="text-2xl font-bold mb-4">İnceleme</h2>
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 text-center">İlan</th>
                        <th class="py-2 px-4 text-center">Başvuran</th>
                        <th class="py-2 px-4 text-center">Belgeler</th>
                        <th class="py-2 px-4 text-center">Durum</th>
                        <th class="py-2 px-4 text-center">İncele</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applications)): ?>
                        <?php foreach ($applications as $app): ?>
                            <tr class="border-b">
                                <td class="py-2 px-4 text-center"><?php echo $app['ad_title']; ?></td>
                                <td class="py-2 px-4 text-center"><?php echo $app['user_name'] . ' ' . $app['user_surname']; ?></td>
                                <td class="py-2 px-4 text-center">
                                        <?php foreach ($app['documents'] as $doc): ?>
                                            <?= htmlspecialchars($doc['file_path']) . '<br>'; ?>
                                        <?php endforeach; ?>
                                </td>

                                <td class="py-2 px-4 text-center"><?php echo $app['status_title']; ?></td>
                                <td class="py-2 px-4 text-center">
                                    <form method="POST" action="<?php echo base_url('jury/reviewApplication'); ?>" onsubmit="return confirm('Bu başvuruyu incelemek için yönlendirileceksiniz.');">
                                        <input type="hidden" name="id" value="<?php echo $app['application_id']; ?>">
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">İncele</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-2 px-4 text-center text-gray-500">Henüz incelenecek başvuru yok.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
    </div> <!-- #app kapanışı -->
<script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    formData: {
                        comment: '',
                        rating: null,
                        approve: false
                    },
                    formSubmitted: false,
                    submitted: false,
                    showForm: true
                };
            },
            methods: {
                submitForm() {
                    this.formSubmitted = true;

                    if (this.formData.comment && this.formData.rating >= 1 && this.formData.rating <= 100) {
                        this.submitted = true;
                    }
                }
            }
        }).mount('#app');
    </script>
</body>

</html>