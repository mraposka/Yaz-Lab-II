<?php include 'header.php'; ?>
<!-- Main Content -->
<div id="app" class="flex-1 p-8 ml-64">
    <h2 class="text-3xl font-bold">Hoşgeldin, Kullanıcı!</h2>
    <p class="text-gray-600 mt-2">İlanları Görebilirsin.</p>

    <!-- İlan Formu -->
  <div v-if="showForm" class="bg-white p-4 rounded-lg shadow-lg mt-4">
         

    <!-- Mevcut İlanlar -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Başvurduğum İlanlar</h2>
        <ul class="space-y-4">
            <?php foreach ($application as $app) : foreach($ads as $advert): if($advert->id==$app->ads_id):?>
            <li class="bg-white p-4 rounded-lg shadow-md flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold"><?php echo $advert->title; ?></h3>
                    <p class="text-sm text-gray-500">📅 <?php echo $advert->date_start; ?> -
                        <?php echo $advert->date_end; ?></p>
                    <p class="text-sm text-gray-600 mt-2"><?php echo $advert->text; ?></p>
                </div>
                <div class="flex gap-2">
                    <form action="<?php echo base_url('user/documents'); ?>" method="POST">
                        <input type="hidden" name="position_id" value="<?php echo $advert->position_id; ?>">
                        <input type="hidden" name="department_id" value="<?php echo $advert->department_id; ?>">
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Belgeleri Yükle</button>
                    </form>
                </div>
            </li>
            <?php endif; endforeach; endforeach;?>
        </ul>

        <?php if (empty($application)) : ?>
        <p class="text-gray-600 mt-4">Henüz başvuru eklenmedi.</p>
        <?php endif; ?>
    </div>
 
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