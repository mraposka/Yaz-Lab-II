<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
</head>

<body class="bg-gray-100 flex h-screen">
    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 transition-all duration-300">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li class="mb-4">
                <a href="#" @click="currentPage = 'home'" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="roles.html" class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div id="app" class="flex-1 p-8">
        <h2 class="text-2xl font-bold mb-6">Kullanıcı Rol Atama</h2>
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kullanıcı Seç</label>
                <select v-model="selectedUser" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seçiniz</option>
                    <option v-for="user in users" :value="user">{{ user }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Rol Seç</label>
                <select v-model="selectedRole" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seçiniz</option>
                    <option v-for="role in roles" :value="role">{{ role }}</option>
                </select>
            </div>
            <button @click="assignRole" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Kaydet</button>
        </div>
        
        <h2 class="text-2xl font-bold mt-6">Atanan Roller</h2>
        <table class="w-full mt-4 bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-2 px-4">Kullanıcı</th>
                    <th class="py-2 px-4">Rol</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="entry in assignedRoles" :key="entry.user" class="border-b">
                    <td class="py-2 px-4">{{ entry.user }}</td>
                    <td class="py-2 px-4">{{ entry.role }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    users: ["Ahmet", "Mehmet", "Ayşe", "Fatma"],
                    roles: ["Aday", "Yönetici", "Jüri Üyesi"],
                    selectedUser: "",
                    selectedRole: "",
                    assignedRoles: []
                };
            },
            methods: {
                assignRole() {
                    if (this.selectedUser && this.selectedRole) {
                        this.assignedRoles.push({
                            user: this.selectedUser,
                            role: this.selectedRole
                        });
                        this.selectedUser = "";
                        this.selectedRole = "";
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
