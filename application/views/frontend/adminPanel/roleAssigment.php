<?php include 'header.php';?>

    <!-- Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
        <h2 class="text-gray-600 mt-2">Rol Ataması yapabilirsin</h2>
        <br>

        <!-- PHP Form ile Vue.js Entegrasyonu -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg">
            <form method="POST" action="<?php echo base_url('admin/assignRole'); ?>">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kullanıcı Seç</label>
                    <select v-model="selectedUser" name="user" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($all_users as $user) : ?>
                            <option :value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                         <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rol Seç</label>
                    <select v-model="selectedRole" name="role" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seçiniz</option>
                        <option value="Juri">Jüri Üyesi</option>
                        <option value="Yonetici">Yönetici</option>
                        <option value="Kullanici">Kullanıcı</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Kaydet</button>
            </form>
        </div>

        <h2 class="text-2xl font-bold mt-6">Atanan Roller</h2>
        <table class="w-full mt-4 bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-2 px-4 text-center">Ad</th>
                    <th class="py-2 px-4 text-center">Soyad</th>
                    <th class="py-2 px-4 text-center">Rol</th>
                     <th class="py-2 px-4 text-center">Aksiyon</th> 
                </tr>
            </thead>
        <tbody>
             <?php foreach ($users as $user) : ?>
                <tr class="border-b">   
                <td class="py-2 px-4 text-center"><?php echo $user->name; ?></td>
                <td class="py-2 px-4 text-center"><?php echo $user->surname; ?></td>
                <td class="py-2 px-4 text-center"><?php echo $user->role; ?></td>
                <td class="py-2 px-4 text-center">
                <form action="<?php echo base_url('admin/removeRole'); ?>" method="POST" onsubmit="return confirm('Rolü silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                    <button type="submit" class="text-red-500">Rolü Sil</button>
                </form>

                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>



    <!-- Vue Uygulaması -->
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
                axios.get("<?php echo base_url('admin/getUsers'); ?>")
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(error => {
                        console.error("Kullanıcıları alırken hata oluştu:", error);
                    });
            },
            fetchRoles() {
                axios.get("<?php echo base_url('admin/getRoles'); ?>")
                    .then(response => {
                        this.roles = response.data;
                    })
                    .catch(error => {
                        console.error("Rolleri alırken hata oluştu:", error);
                    });
            },
            fetchAssignedRoles() {
                axios.get("<?php echo base_url('admin/roleAssignment'); ?>")
                    .then(response => {
                        this.assignedRoles = response.data;
                    })
                    .catch(error => {
                        console.error("Atanan rolleri alırken hata oluştu:", error);
                    });
            }
        }
    }).mount('#app');
    </script>

</body>
</html>
