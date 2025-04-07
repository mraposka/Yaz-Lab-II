<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // oninput event fonksiyonu
        function validateInput(input) {
            // Girilen değeri al
            let value = input.value;

            // Sayı dışında bir karakter varsa, değeri temizle
            if (!/^\d*$/.test(value)) {
                // Sadece sayılar olmasına izin ver, sayı dışı karakteri temizle
                input.value = value.replace(/[^\d]/g, '');
            }
        }
    </script>
</head>

<body class="bg-[#fffaf2] flex items-center justify-center min-h-screen">
    <div id="app" class="bg-white shadow-lg rounded-xl p-8 w-96">
        <div v-if="success == '1'" class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm text-center">
            Kayıt başarılı. Lütfen giriş yapın.
        </div>
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Giriş Yap</h2>

        <form method="POST" action="<?php echo base_url('login_user'); ?>">
            <div class="mb-4">
                <label for="tc" class="block text-sm font-medium text-gray-700">T.C. Kimlik Numarası</label>
                <input  v-model="tc" type="text" pattern="^\d{11}$" maxlength="11" name="tc" placeholder="T.C. Kimlik Numarası"
                title="11 haneli sayı giriniz" oninput="validateInput(this)" class="input mt-1 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
                <input v-model="password" type="password" minlength="6" maxlength="12" id="password" name="password" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required />
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition duration-200">Giriş Yap</button>
        </form>

    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    tc: "",
                    password: "",
                    success: null
                };
            },
            mounted() {
                // URL'deki success parametresini oku
                const params = new URLSearchParams(window.location.search);
                this.success = <?php echo $success;?>;
            }
        }).mount('#app');
    </script>
</body>

</html>
