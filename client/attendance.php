<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Attendance Management - Pratham Path</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "govt-blue": "#00008B",
            "govt-orange": "#FF9933",
            "govt-green": "#138808",
            "govt-white": "#FFFFFF",
            "govt-light-blue": "#E6F3FF",
            "govt-dark-blue": "#001D6E",
            "govt-light-gray": "#F1F1F1",
            "govt-text-gray": "#444444",
            "govt-red": "#FF0000",
          },
          animation: {
            "fade-in": "fadeIn 0.5s ease-out forwards",
            "scale-in": "scaleIn 0.3s ease-out forwards",
            "slide-up": "slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards",
            float: "float 6s ease-in-out infinite",
            ping: "ping 1s cubic-bezier(0, 0, 0.2, 1) infinite",
          },
          keyframes: {
            fadeIn: {
              "0%": {
                opacity: "0",
                transform: "translateY(10px)"
              },
              "100%": {
                opacity: "1",
                transform: "translateY(0)"
              },
            },
            scaleIn: {
              "0%": {
                transform: "scale(0.95)",
                opacity: "0"
              },
              "100%": {
                transform: "scale(1)",
                opacity: "1"
              },
            },
            slideUp: {
              "0%": {
                transform: "translateY(20px)",
                opacity: "0"
              },
              "100%": {
                transform: "translateY(0)",
                opacity: "1"
              },
            },
            float: {
              "0%, 100%": {
                transform: "translateY(0)"
              },
              "50%": {
                transform: "translateY(-10px)"
              },
            },
            ping: {
              "75%, 100%": {
                transform: "scale(2)",
                opacity: "0"
              },
            },
          },
        },
      },
    };
  </script>
  <style>
    body {
      font-family: "Inter", sans-serif;
    }

    .tricolor-border {
      background: linear-gradient(to right,
          #ff9933 33.33%,
          #ffffff 33.33%,
          #ffffff 66.66%,
          #138808 66.66%);
      height: 4px;
      width: 100%;
    }

    .glass-card {
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body class="bg-white text-gray-900 min-h-screen">
  <!-- Header Section -->
  <header class="relative z-50">
    <!-- Top bar with national symbols and language selector -->
    <div class="bg-govt-dark-blue text-white py-2 px-4">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-4 text-xs">
          <a href="#" class="hover:underline">Government of India</a>
          <span>|</span>
          <a href="#" class="hover:underline">Government of Assam</a>
        </div>
        <div class="flex items-center space-x-4 text-xs">
          <a href="#" class="hover:underline">English</a>
          <a href="#" class="hover:underline">हिंदी</a>
          <a href="#" class="hover:underline">অসমীয়া</a>
        </div>
      </div>
    </div>

    <!-- Tricolor border -->
    <div class="tricolor-border"></div>

    <!-- Main header with logo and navigation -->
    <div class="py-3 px-6 transition-all duration-300 ease-spring bg-white">
      <div class="container mx-auto">
        <!-- Logo and title section -->
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center space-x-4">
            <img
              src="./assets/logo.png"
              alt="Siksha Logo"
              class="h-16" />
            <div>
              <h1 class="text-2xl font-bold text-govt-dark-blue">
                Pratham Path
              </h1>
              <p class="text-sm text-govt-text-gray">
                शिक्षार अधिकार - Education Department, Assam
              </p>
            </div>
          </div>

          <div class="hidden md:flex items-center space-x-3">
            <div class="relative">
              <input
                type="text"
                placeholder="Search..."
                class="pl-10 pr-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-govt-blue" />
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <button
              class="bg-govt-blue hover:bg-govt-dark-blue text-white px-4 py-2 rounded-md flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              Search
            </button>
          </div>

          <button class="md:hidden">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Main navigation -->
        <nav
          class="hidden md:flex justify-between bg-govt-blue text-white rounded-md overflow-hidden">
          <div class="flex">
            <a
              href="./index.php"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Home</a>
            <a
              href="/#features"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">About</a>
            <a
              href="/#testimonials"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Schemes</a>
            <a
              href="#"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Resources</a>
            <a
              href="/attendance.php"
              class="px-4 py-3 bg-govt-dark-blue transition-colors flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Attendance
            </a>
            <a
              href="#contact"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Contact</a>
          </div>
          <div class="px-4 py-3 bg-govt-dark-blue flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path d="M12 14l9-5-9-5-9 5 9 5z" />
              <path
                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
            <span>LOGIN</span>
          </div>
        </nav>

        <!-- Mobile menu (hidden by default) -->
        <div
          class="hidden md:hidden bg-white border rounded-md mt-2 shadow-md">
          <div class="flex flex-col">
            <a href="/" class="px-4 py-3 hover:bg-govt-light-blue border-b">Home</a>
            <a
              href="/#features"
              class="px-4 py-3 hover:bg-govt-light-blue border-b">About</a>
            <a
              href="/#testimonials"
              class="px-4 py-3 hover:bg-govt-light-blue border-b">Schemes</a>
            <a href="#" class="px-4 py-3 hover:bg-govt-light-blue border-b">Resources</a>
            <a
              href="/attendance.php"
              class="px-4 py-3 hover:bg-govt-light-blue border-b flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Attendance
            </a>
            <a href="#contact" class="px-4 py-3 hover:bg-govt-light-blue">Contact</a>
          </div>
          <div class="p-3 bg-govt-light-blue">
            <div class="relative">
              <input
                type="text"
                placeholder="Search..."
                class="w-full pl-10 pr-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-govt-blue" />
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-govt-dark-blue mb-6">
      Facial Recognition Attendance System
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg border shadow-sm">
        <div class="p-4 border-b">
          <h2 class="text-xl font-bold text-govt-blue">Take Attendance</h2>
        </div>
        <div class="p-4 space-y-4">
          <div
            class="flex items-center justify-center bg-gray-100 rounded-md overflow-hidden relative"
            style="height: 200px">
            <div class="text-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-12 w-12 text-govt-blue mx-auto mb-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <p class="text-sm text-gray-500">Camera ready</p>
            </div>
          </div>
          <button
            class="w-full bg-govt-blue hover:bg-govt-dark-blue text-white px-4 py-2 rounded-md flex items-center justify-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Start Face Recognition
          </button>

          <div class="flex items-center mt-4 gap-2">
            <button
              class="inline-flex items-center justify-start text-left font-normal w-full px-4 py-2 border rounded-md">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Pick a date
            </button>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border shadow-sm md:col-span-2">
        <div class="p-4 border-b">
          <h2 class="text-xl font-bold text-govt-blue">
            Attendance Overview
          </h2>
        </div>
        <div class="p-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-govt-light-blue rounded-md p-4 text-center">
              <p class="text-sm font-medium text-govt-dark-blue">
                Total Students
              </p>
              <p class="text-3xl font-bold text-govt-blue">120</p>
            </div>
            <div class="bg-green-100 rounded-md p-4 text-center">
              <p class="text-sm font-medium text-green-800">Present Today</p>
              <p class="text-3xl font-bold text-green-600">98</p>
            </div>
            <div class="bg-red-100 rounded-md p-4 text-center">
              <p class="text-sm font-medium text-red-800">Absent Today</p>
              <p class="text-3xl font-bold text-red-600">22</p>
            </div>
          </div>

          <div class="flex flex-col md:flex-row gap-4 items-center mb-4">
            <div class="w-full md:w-1/3">
              <div class="relative">
                <input
                  type="text"
                  placeholder="Search by name..."
                  class="w-full px-4 py-2 border rounded-md" />
              </div>
            </div>
            <div class="w-full md:w-1/3">
              <select class="w-full px-4 py-2 border rounded-md">
                <option>All Classes</option>
                <option>Class 5</option>
                <option>Class 6</option>
              </select>
            </div>
            <div class="w-full md:w-1/3">
              <select class="w-full px-4 py-2 border rounded-md">
                <option>All Sections</option>
                <option>Section A</option>
                <option>Section B</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
          <button
            class="px-6 py-3 border-b-2 border-govt-blue text-govt-blue font-medium">
            Attendance List
          </button>
          <button
            class="px-6 py-3 text-gray-500 hover:text-gray-700 font-medium">
            Reports
          </button>
        </nav>
      </div>
    </div>

    <div class="bg-white rounded-lg border shadow-sm mb-8">
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-bold text-govt-blue">Today's Attendance</h2>
        <div class="flex gap-2">
          <button
            class="flex items-center px-3 py-1 border rounded text-sm hover:bg-gray-50">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 mr-1"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2.5 2.5 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>
            Excel
          </button>
          <button
            class="flex items-center px-3 py-1 border rounded text-sm hover:bg-gray-50">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 mr-1"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Word
          </button>
          <button
            class="flex items-center px-3 py-1 border rounded text-sm hover:bg-gray-50">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 mr-1"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            PDF
          </button>
        </div>
      </div>
      <div class="p-4">
        <div class="border rounded-md overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Name
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Class
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Section
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Date
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Time
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Rahul Sharma
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Class 5
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  A
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  2024-07-01
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  09:15 AM
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-3 w-3 mr-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Present
                  </span>
                </td>
              </tr>
              <tr>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Priya Patel
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Class 5
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  A
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  2024-07-01
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  09:10 AM
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-3 w-3 mr-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Present
                  </span>
                </td>
              </tr>
              <tr>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Neha Singh
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Class 5
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  B
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  2024-07-01
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Absent
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Absent
                  </span>
                </td>
              </tr>
              <tr>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Vikram Mehta
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Class 6
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  A
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  2024-07-01
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  09:05 AM
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-3 w-3 mr-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Present
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="pt-16 pb-8">
    <div class="container mx-auto px-4">
      <!-- Quick links section -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
        <div>
          <h4 class="font-bold text-govt-dark-blue mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Home
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                About Us
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Schemes
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Resources
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-govt-dark-blue mb-4">Important Links</h4>
          <ul class="space-y-2">
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                National Education Policy
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                NCERT
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Samagra Shiksha
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Mid-Day Meal
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-govt-dark-blue mb-4">Resources</h4>
          <ul class="space-y-2">
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Learning Materials
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Teaching Guides
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Research Papers
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Annual Reports
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-govt-dark-blue mb-4">Other Links</h4>
          <ul class="space-y-2">
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                RTI
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Tenders
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Recruitment
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-govt-text-gray hover:text-govt-blue transition-colors flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="h-3 w-3 mr-2">
                  <path
                    d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                  <polyline points="15 3 21 3 21 9"></polyline>
                  <line x1="10" x2="21" y1="14" y2="3"></line>
                </svg>
                Sitemap
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- National emblem and copyright -->
      <div
        class="pt-8 border-t border-gray-200 flex flex-col items-center justify-center text-center">
        <div class="mb-4 flex flex-col items-center">
          <div class="w-16 h-full mb-2">
            <img
              src="./assets/image.png"
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
    // Add events for the camera button functionality
    document.addEventListener("DOMContentLoaded", function() {
      const cameraButton = document.querySelector(
        "button.w-full.bg-govt-blue"
      );
      const cameraDiv = document.querySelector(
        ".flex.items-center.justify-center.bg-gray-100"
      );

      cameraButton.addEventListener("click", function() {
        // Change the camera display
        cameraDiv.innerHTML = `
          <div class="w-full h-full bg-black flex items-center justify-center relative">
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="animate-ping h-40 w-40 rounded-full border-4 border-govt-blue"></div>
              <div class="absolute text-white text-sm">Scanning faces...</div>
            </div>
          </div>
        `;

        cameraButton.disabled = true;
        cameraButton.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Scanning...
        `;

        // Simulate completion after 3 seconds
        setTimeout(function() {
          // Reset the camera view
          cameraDiv.innerHTML = `
            <div class="text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-govt-blue mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <p class="text-sm text-gray-500">Camera ready</p>
            </div>
          `;

          cameraButton.disabled = false;
          cameraButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Start Face Recognition
          `;

          // Add new attendance entry to the table
          const tableBody = document.querySelector("tbody");
          const newRow = document.createElement("tr");
          newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">New Student</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Class 5</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">A</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${
              new Date().toISOString().split("T")[0]
            }</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date().toLocaleTimeString(
              [],
              { hour: "2-digit", minute: "2-digit" }
            )}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Present
              </span>
            </td>
          `;
          tableBody.insertBefore(newRow, tableBody.firstChild);
          newRow.style.backgroundColor = "#f0f9ff";
          setTimeout(() => {
            newRow.style.backgroundColor = "";
          }, 3000);
        }, 3000);
      });

      // Tab switching functionality
      const tabs = document.querySelectorAll(".border-b nav button");
      tabs.forEach((tab) => {
        tab.addEventListener("click", function() {
          tabs.forEach((t) => {
            t.classList.remove(
              "border-b-2",
              "border-govt-blue",
              "text-govt-blue"
            );
            t.classList.add("text-gray-500", "hover:text-gray-700");
          });
          this.classList.remove("text-gray-500", "hover:text-gray-700");
          this.classList.add(
            "border-b-2",
            "border-govt-blue",
            "text-govt-blue"
          );
        });
      });

      // Mobile menu toggle
      const mobileMenuButton = document.querySelector("button.md\\:hidden");
      const mobileMenu = document.querySelector(".hidden.md\\:hidden");
      mobileMenuButton.addEventListener("click", function() {
        mobileMenu.classList.toggle("hidden");
      });
    });
  </script>
</body>

</html>