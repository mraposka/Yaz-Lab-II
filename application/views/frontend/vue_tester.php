<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue Frontend</title>
    <script src="<?php echo base_url('/application/views/frontend/');?>vue.js"></script>
    <script src="<?php echo base_url('/application/views/frontend/');?>tailwind.js"></script>
</head>

<body class="bg-gray-100">
    <div id="app" class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">Vue
                            Frontend
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="text-gray-700 hover:text-gray-900">Home</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900">About</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900">Contact</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto py-8 px-4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">{{ title }}</h2>
                <p class="text-gray-600 mb-4">{{ message }}</p>
                <div class="flex space-x-4">
                    <button @click="count++" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Count is: {{ count }}
                    </button>
                    <button @click="toggleTheme" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Toggle Theme
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        const {
            createApp
        } = Vue

        createApp({
            data() {
                return {
                    title: 'Welcome to Vue Frontend',
                    message: 'This is a modern Vue.js frontend integrated with CodeIgniter',
                    count: 0,
                    isDark: false
                }
            },
            methods: {
                toggleTheme() {
                    this.isDark = !this.isDark
                    document.body.classList.toggle('bg-gray-900')
                }
            }
        }).mount('#app')
    </script>
</body>

</html>