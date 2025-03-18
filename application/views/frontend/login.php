<!DOCTYPE html>

<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Axios eklendi -->
</head>

<body class="bg-[#fffaf2] flex items-center justify-center min-h-screen">
    <div id="app" class="bg-white shadow-lg rounded-xl p-8 w-96">

        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Giriş Yap</h2>

        <form @submit.prevent="login">
            <div class="mb-4">
                <label for="tc" class="block text-sm font-medium text-gray-700">T.C. Kimlik Numarası</label>
                <input v-model="tc" type="text" id="tc" maxlength="11" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
                <input v-model="password" type="password" id="password" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required />
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition duration-200">Giriş Yap</button>
        </form>

        <p v-if="error" class="mt-4 text-red-500 text-center">{{ error }}</p>
    </div>

    <script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                tc: "",
                password: "",
                error: ""
            };
        },
        methods: {
            login() {
                axios.post("<?php echo base_url('web/login'); ?>", {
                    tc: this.tc,
                    password: this.password
                })
                .then(response => {
                    if (response.data.success) {
                        alert("Giriş başarılı!");
                    } else {
                        this.error = response.data.message;
                    }
                })
                .catch(error => {
                    console.error("Giriş hatası:", error);
                    this.error = "Sunucu hatası!";
                });
            }
        }
    }).mount('#app');
</script>
</body>

</html>
