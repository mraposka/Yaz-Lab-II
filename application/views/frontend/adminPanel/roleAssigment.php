<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Axios eklendi -->
</head>

<body class="bg-gray-100 flex h-screen">
   <!-- Sidebar -->
   <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 h-screen fixed">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel</h2>
        <ul>
            <li class="mb-4">
                <a href="<?php echo base_url('/web/admin'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Ana Sayfa</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('/web/roleAssignment'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">Rol Atama</a>
            </li>
            <li class="mb-4">
                <a href="<?php echo base_url('/web/advert'); ?>" class="block py-2 px-4 rounded hover:bg-gray-700">İlan Ekle</a>
            </li>
        </ul>
    </div>

    <!-- Content -->

    <div id="app" class="flex-1 p-8 ml-64">
    <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
        <h2 class="text-gray-600 mt-2">Rol Ataması yapabilirsin</h2>
        <br>
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
                users: [],
                roles: [],
                selectedUser: "",
                selectedRole: "",
                assignedRoles: []
            };
        },
        created() {
            this.fetchUsers();
            this.fetchRoles();
            this.fetchAssignedRoles();
        },
        methods: {
            fetchUsers() {
                axios.get("<?php echo base_url('web/getUsers'); ?>")
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(error => {
                        console.error("Kullanıcıları alırken hata oluştu:", error);
                    });
            },
            fetchRoles() {
                axios.get("<?php echo base_url('web/getRoles'); ?>")
                    .then(response => {
                        this.roles = response.data;
                    })
                    .catch(error => {
                        console.error("Rolleri alırken hata oluştu:", error);
                    });
            },
            fetchAssignedRoles() {
                axios.get("<?php echo base_url('web/roleAssignment'); ?>")
                    .then(response => {
                        this.assignedRoles = response.data;
                    })
                    .catch(error => {
                        console.error("Atanan rolleri alırken hata oluştu:", error);
                    });
            },
            assignRole() {
                if (this.selectedUser && this.selectedRole) {
                    axios.post("<?php echo base_url('web/assignRole'); ?>", {
                        user: this.selectedUser,
                        role: this.selectedRole
                    })
                    .then(response => {
                        if (response.data.success) {
                            this.assignedRoles.push({
                                user: this.selectedUser,
                                role: this.selectedRole
                            });
                            this.selectedUser = "";
                            this.selectedRole = "";
                        } else {
                            alert(response.data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Rol atama hatası:", error);
                    });
                }
            }
        }
    }).mount('#app');
</script>
</body>
</html>
