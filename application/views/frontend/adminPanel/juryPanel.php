<?php include 'header.php'; ?>
    <div class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold mb-6">Jüri Seçimi</h2>

        <form method="POST" action="saveJury">
            <div class="mb-4">
                <label for="ad" class="block text-sm font-medium text-gray-700">İlan Seçiniz</label>
                <select id="ad" name="ad" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <?php foreach ($adverts as $advert): ?>
                        <option value="<?php echo $advert->id; ?>">
                            <?php echo $advert->title; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="jury" class="block text-sm font-medium text-gray-700">Jüri Seçiniz (5 tane)</label>
                <select id="jury" name="jury[]" multiple class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 h-40">
                    <?php foreach ($juries as $jury): ?>
                        <option value="<?php echo $jury->id; ?>">
                            <?php echo $jury->name . " " . $jury->surname; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Kaydet
            </button>
        </form>

        <h2 class="text-2xl font-bold mt-8 mb-4">Seçili İlan ve Jüriler</h2>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">İlan</th>
                    <th class="py-2 px-4 text-left">Jüriler</th>
                    <th class="py-2 px-4 text-center">Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ads_juries)): ?>
                    <?php foreach ($ads_juries as $ad): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?php echo $ad['title']; ?></td>
                            <td class="py-2 px-4"><?php echo $ad['jury'];  ?></td>
                            <td class="py-2 px-4 text-center">
                                <form method="POST" action="<?php echo base_url('jury/deleteJury'); ?>" onsubmit="return confirm('Bu ilana ait jurileri silmek istediğinizden emin misiniz?');">
                                    <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">Henüz kayıtlı ilan ve jüri yok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>


<script>
        const { createApp } = Vue;

        createApp({
            data() {

            }
        }).mount('#app');
    </script>

</body>
</html>

