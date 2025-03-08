<?php
// supervisor/dashboard.php

require '../includes/auth.php';
redirectIfNotSupervisor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'govt-blue': '#0a2463',
                        'govt-dark-blue': '#081f5c',
                        'govt-light-blue': '#e6f0ff',
                        'govt-orange': '#ff9933',
                        'govt-green': '#138808',
                        'govt-text-gray': '#4a5568',
                        'govt-red': '#e3342f'
                    }
                }
            }
        }
    </script>
    <style>
        .tricolor-border {
            background: linear-gradient(to right, #FF9933 33.33%, #FFFFFF 33.33%, #FFFFFF 66.66%, #138808 66.66%);
            height: 4px;
            width: 100%;
        }
    </style>
    <style>
        #notification-card {
            transition: all 0.3s ease;
            background-color: white; /* Ensure background color is white */
        }

        #notification-content {
            display: flex;
            align-items: center;
        }

        #notification-buttons {
            display: none; /* Hidden by default */
        }

        #notification-buttons a {
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header with Tricolor border -->
    <header class="bg-govt-blue shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="../../client/index.php" class="flex items-center gap-2">
                <div class="text-white text-4xl font-semibold">
                    Pratham Path
                    <p class="text-xl">Supervisor Dashboard</p>
                </div>
            </a>

            <!-- Desktop Navigation (Hidden on mobile) -->
            <div class="hidden md:flex gap-6">
                <a href="workers/view.php" class="text-white text-lg hover:text-govt-light-blue">Workers</a>
                <a href="centres/view.php" class="text-white text-lg hover:text-govt-light-blue">Centres</a>
                <a href="../logout.php" class="font-bold text-white text-xl hover:text-govt-light-blue">Logout</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-govt-blue mt-4 rounded-lg shadow-lg">
            <div class="px-4 py-4">
                <a href="workers/view.php" class="block text-white text-lg hover:text-govt-light-blue mb-2">Workers</a>
                <a href="centres/view.php" class="block text-white text-lg hover:text-govt-light-blue mb-2">Centres</a>
                <a href="../logout.php" class="block font-bold text-white text-xl hover:text-govt-light-blue">Logout</a>
            </div>
        </div>

        <div class="tricolor-border mt-4"></div>
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Workers Card -->
            <a href="workers/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-green">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-green">Workers</h2>
                        <p class="text-gray-600">Manage Workers</p>
                    </div>
                </div>
            </a>

            <!-- Centres Card -->
            <a href="centres/view.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-govt-blue">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-govt-light-blue rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-govt-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-blue">Centres</h2>
                        <p class="text-gray-600">Manage Centres</p>
                    </div>
                </div>
            </a>

            <!-- Notifications Card -->
            <div id="notification-card" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border-l-4 border-govt-red">
                <!-- Default Content (Notifications Info) -->
                <div id="notification-content" class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-govt-red h-8 w-8">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29c-.63.63-.19 1.71.7 1.71h13.17c.89 0 1.34-1.08.71-1.71L18 16z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-govt-red">Notifications</h2>
                        <p class="text-gray-600">View and add notifications for supervisor/worker</p>
                    </div>
                </div>

                <!-- Buttons (Hidden by Default) -->
                <div id="notification-buttons" class="hidden flex flex-col gap-4 mt-4">
                    <a href="?action=add_notification" class="bg-blue-500 text-white px-4 py-2 rounded-md text-center hover:bg-blue-600">Add</a>
                    <a href="?action=edit_notification" class="bg-yellow-500 text-white px-4 py-2 rounded-md text-center hover:bg-yellow-600">Edit</a>
                    <a href="?action=view_notifications" class="bg-green-500 text-white px-4 py-2 rounded-md text-center hover:bg-green-600">View</a>
                    <a href="?action=delete_notification" class="bg-red-500 text-white px-4 py-2 rounded-md text-center hover:bg-red-600">Delete</a>
                </div>
            </div>
        </div>
    </main>
            
    <!-- Footer with Tricolor border -->
    <footer class="pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="pt-8 border-t border-gray-200 flex flex-col items-center justify-center text-center">
                <div class="mb-4 flex flex-col items-center">
                    <div class="w-16 h-full mb-2">
                        <img
                            src="../assets/image.png"
                            alt="National Emblem of India"
                            class="w-full h-full" />
                    </div>
                    <p class="text-sm text-govt-text-gray">Government of Assam</p>
                    <p class="text-sm text-govt-text-gray">
                        Department of School Education
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-govt-text-gray">
                        This site is designed, developed, and maintained by the IT Cell,
                        Department of School Education, Assam
                    </p>
                </div>

                <div
                    class="tricolor-border my-4 w-24 rounded-full h-1 bg-gradient-to-r from-orange-500 via-white to-green-500"></div>

                <div class="text-xs text-govt-text-gray">
                    <p class="mb-2">© 2024 Pratham Path. All rights reserved.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="hover:text-govt-blue transition-colors">Privacy Policy</a>
                        <span>|</span>
                        <a href="#" class="hover:text-govt-blue transition-colors">Terms of Use</a>
                        <span>|</span>
                        <a href="#" class="hover:text-govt-blue transition-colors">Accessibility</a>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-govt-text-gray">
                        Last Updated: June 1, 2024
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
    </script>
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this supervisor?");
    }
    </script>
    <script>
    function confirmDeleteNotification() {
        return confirm("Are you sure you want to delete this notification?");
    }
    </script>
    <script>
        document.getElementById('notification-card').addEventListener('click', function () {
            const content = document.getElementById('notification-content');
            const buttons = document.getElementById('notification-buttons');

            // Toggle visibility
            if (content.style.display !== 'none') {
                content.style.display = 'none'; // Hide the content
                buttons.classList.remove('hidden'); // Show the buttons
            } else {
                content.style.display = 'flex'; // Show the content
                buttons.classList.add('hidden'); // Hide the buttons
            }
        });
    </script>
</body>
</html>