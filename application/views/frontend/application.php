<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>İlanlar</title>
  <script src="<?php echo base_url('application/views/frontend/vue.js'); ?>"></script>
  <script src="<?php echo base_url('application/views/frontend/axios.min.js'); ?>"></script>
  <script src="<?php echo base_url('application/views/frontend/tailwind.js'); ?>"></script>
  <script src="<?php echo base_url('application/views/frontend/tailwind.css'); ?>"></script>
</head>
<body class="bg-[#fffaf2] flex flex-col items-center min-h-screen p-6 space-y-6">
  <!-- Sayfa Başlığı -->
  <h1 class="text-3xl font-bold text-gray-700">İlanlar</h1>

  <!-- İlanlar Listesi -->
  <div id="app" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-6xl">
    <ilan-karti 
      v-for="ilan in ilanlar" 
      :key="ilan.id" 
      :ilan-adi="ilan.title" 
      bg-color="bg-blue-500" 
      :ilan-id="ilan.id">
    </ilan-karti>
  </div>

  <script>
    const { createApp, defineComponent } = Vue;

    const IlanKarti = defineComponent({
      props: ['ilanAdi', 'bgColor', 'ilanId'],
      template: `
        <div class="p-5 shadow-lg rounded-lg bg-white flex flex-col items-center space-y-4">
          <h3 class="text-lg font-bold text-gray-800">{{ ilanAdi }}</h3>
          <!-- Buton: Her ilana özgü ilanId'yi URL'ye ekleyerek yönlendirme yapıyor -->
          <a 
            :href="'adverts.php?ilanId=' + ilanId"
            class="text-white px-5 py-2 rounded-lg shadow-md hover:opacity-80 transition-all"
            :class="[bgColor]"
          >
            İlana Başvur
          </a>
        </div>
      `
    });

    createApp({
      components: { IlanKarti },
      data() {
        return {
          ilanlar: [] // İlan verilerini buraya atacağız
        };
      },
      mounted() {
        // İlanları backend'den çekiyoruz
        fetch('http://3.17.139.227/web/get_ads')
          .then(response => response.json())
          .then(data => {
            this.ilanlar = data;
          })
          .catch(error => console.error('Hata:', error));
      }
    }).mount('#app');
  </script>
</body>
</html>
