<?php include 'header.php';?>

    <!-- Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <div v-if="currentPage === 'home'">
            <h2 class="text-3xl font-bold">Hoşgeldin, Admin!</h2>
            <p class="text-gray-600 mt-2">Bugün harika bir gün!</p>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    currentPage: 'home'
                };
            },
            methods: {
                toggleSidebar() {
                    document.getElementById('sidebar').classList.toggle('hidden');
                }
            }
        }).mount('#app');
    </script>

</body>
</html>
