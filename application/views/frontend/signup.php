<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Axios eklendi -->
</head>

<body class="bg-[#fffaf2] flex items-center justify-center min-h-screen">
    <div id="app" class="bg-white shadow-xl rounded-lg p-6 w-96">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Kayıt Ol</h2>
        
        <form @submit.prevent="signup" class="space-y-4">
            <input v-model="tc" type="text" maxlength="11" placeholder="T.C. Kimlik Numarası" class="input" required />
            <input v-model="nameSurname" type="text" placeholder="Ad Soyad" class="input" required />
            <input v-model="motherName" type="text" placeholder="Anne Adı" class="input" required />
            <input v-model="fatherName" type="text" placeholder="Baba Adı" class="input" required />
            <input v-model="birthDate" type="date" class="input" required />
            <input v-model="birthPlace" type="text" placeholder="Doğum Yeri" class="input" required />
            
            <select v-model="gender" class="input" required>
                <option value="">Cinsiyet Seçiniz</option>
                <option value="Erkek">Erkek</option>
                <option value="Kadın">Kadın</option>
            </select>
            
            <input v-model="password" type="password" placeholder="Şifre" class="input" required />
            <button type="submit" class="btn">Kayıt Ol</button>
        </form>

        <p v-if="error" class="mt-4 text-red-500 text-center">{{ error }}</p>
    </div>

    <script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                tc: "",
                nameSurname: "",
                motherName: "",
                fatherName: "",
                birthDate: "",
                birthPlace: "",
                gender: "",
                password: "",
                error: ""
            };
        },
        methods: {
            signup() {
                axios.post("<?php echo base_url('web/signup'); ?>", {
                    tc: this.tc,
                    nameSurname: this.nameSurname,
                    motherName: this.motherName,
                    fatherName: this.fatherName,
                    birthDate: this.birthDate,
                    birthPlace: this.birthPlace,
                    gender: this.gender,
                    password: this.password
                })
                .then(response => {
                    if (response.data.success) {
                        alert("Kayıt başarılı!");
                    } else {
                        this.error = response.data.message;
                    }
                })
                .catch(error => {
                    console.error("Kayıt hatası:", error);
                    this.error = "Sunucu hatası!";
                });
            }
        }
    }).mount('#app');
</script>

    <style>
        .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s;
        }
        .input:focus {
            border-color: #4CAF50;
        }
        .btn {
            width: 100%;
            background: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #45A049;
        }
    </style>
</body>

</html>