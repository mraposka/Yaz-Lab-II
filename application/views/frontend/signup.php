<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // T.C. Kimlik numarası sadece sayı içermeli
        function validateInput(input) {
            let value = input.value;
            if (!/^\d*$/.test(value)) {
                input.value = value.replace(/[^\d]/g, '');
            }
        }
    </script>
</head>

<body class="bg-[#fffaf2] flex items-center justify-center min-h-screen">
    <div id="app" class="bg-white shadow-xl rounded-lg p-6 w-96">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Kayıt Ol</h2>

        <!-- ✅ Hata Mesajı -->
        <div v-if="showError" class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
            Kayıt başarısız. Lütfen bilgilerinizi kontrol edin.
        </div>

        <form method="POST" action="<?php echo base_url('signup_user'); ?>">
            <input v-model="tc" type="text" oninput="validateInput(this)"
                pattern="[0-9]{11}" name="tc" maxlength="11" minlength="11"
                placeholder="T.C. Kimlik Numarası" class="input" required title="11 haneli sayı giriniz" />

            <input v-model="name"
                @input="validateLettersOnly('name', $event)"
                type="text" name="name" placeholder="Ad"
                class="input" required title="Sadece harf giriniz" />

            <input v-model="surname"
                @input="validateLettersOnly('surname', $event)"
                type="text" name="surname" placeholder="Soyad"
                class="input" required title="Sadece harf giriniz" />

            <input v-model="birthDate" type="date" name="birthDate" class="input" required />

            <input v-model="email" type="email" name="email" placeholder="Email"
                class="input" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required />

            <input v-model="password" type="password" minlength="6" maxlength="12"
                name="password" placeholder="Şifre" class="input" required />

            <button type="submit" class="btn">Kayıt Ol</button>
        </form>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    tc: "",
                    name: "",
                    surname: "",
                    birthDate: "",
                    email: "",
                    password: "",
                    showError: <?php echo $success === 1 ? 'false' : 'true'; ?>
                };
            },
            methods: {
                validateLettersOnly(field, event) {
                    const pattern = /^[^0-9]*$/;
                    const value = event.target.value;

                    if (pattern.test(value)) {
                        this[field] = value;
                    } else {
                        this[field] = value.replace(/[0-9]/g, '');
                    }
                }
            }
        }).mount('#app');
    </script>

    <style>
        .input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
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
