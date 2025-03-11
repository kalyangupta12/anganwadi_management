<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pratham Path - Empowering Education in Assam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css"
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
          },
        },
      },
    };
  </script>
  <style>
    body {
      font-family: "Inter", sans-serif;
    }

    .clip-shape {
      clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
    }

    .glass-card {
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .govt-card {
      background-color: white;
      border-left-width: 4px;
      border-color: #00008b;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
    }

    .govt-card:hover {
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
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

    .feature-card {
      background-color: white;
      border-left-width: 4px;
      border-color: #00008b;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 0.375rem;
      padding: 1.5rem;
      opacity: 0;
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
      height: 3rem;
      width: 3rem;
      border-radius: 9999px;
      background-color: #e6f3ff;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #00008b;
      margin-bottom: 1rem;
    }

    .offline-indicator {
      background-color: #fef3c7;
      border-left-width: 4px;
      border-color: #f59e0b;
      padding: 0.75rem;
      border-radius: 0.375rem;
      display: flex;
      align-items: center;
      color: #92400e;
      font-size: 0.875rem;
    }

    /* Animation delays */
    .delay-75 {
      animation-delay: 75ms;
    }

    .delay-100 {
      animation-delay: 100ms;
    }

    .delay-150 {
      animation-delay: 150ms;
    }

    .delay-200 {
      animation-delay: 200ms;
    }

    .delay-300 {
      animation-delay: 300ms;
    }

    .delay-500 {
      animation-delay: 500ms;
    }

    .delay-700 {
      animation-delay: 700ms;
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
            <img src="./assets/logo.png" alt="Siksha Logo" class="h-16" />
            <div>
              <h1 class="text-2xl font-bold text-govt-dark-blue">
                Pratham Path
              </h1>
              <p class="text-sm text-govt-text-gray">
                शिक्षार अधिकार - Education Department, Assam
              </p>
            </div>
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
              href="/"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Home</a>
            <a
              href="#features"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">About</a>
            <a
              href="#testimonials"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Schemes</a>
            
            <a
              href="#contact"
              class="px-4 py-3 hover:bg-govt-dark-blue transition-colors">Contact</a>
          </div>
          <a href="../admin/login.php">
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
          </a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="relative pt-28 pb-16 overflow-hidden">
    <!-- Background elements -->
    <div
      class="absolute inset-0 bg-gradient-to-r from-govt-light-blue to-govt-white clip-shape z-0"></div>

    <div class="container mx-auto px-4 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 animate-fade-in">
          <div class="inline-block">
            <span
              class="inline-flex items-center px-3 py-1 text-xs font-medium bg-govt-blue/10 text-govt-blue rounded-full">
              Government of Assam
            </span>
          </div>

          <h1
            class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
            <span class="block text-govt-dark-blue">Pratham Path</span>
            <span class="text-govt-text-gray text-2xl md:text-3xl">
              Empowering Education in Assam
            </span>
          </h1>

          <p class="text-lg text-govt-text-gray max-w-xl">
            A comprehensive early childhood education initiative by the Assam
            Government to ensure quality education for every child in the
            state. সকলৰ বাবে, সকলৰে পৰা, সকলৰে দ্বাৰা।
          </p>

          <div class="flex flex-wrap gap-4">
            <a
              href="#"
              class="inline-flex items-center bg-govt-blue hover:bg-govt-dark-blue text-white px-6 py-3 rounded-md transition-colors">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                <path d="M6 12v5c0 2 1 3 3 3h6c2 0 3-1 3-3v-5" />
              </svg>
              Pregnant Lady Resources
            </a>
            <a
              href="#"
              class="inline-flex items-center border border-govt-blue/20 text-govt-blue hover:bg-govt-blue/5 px-6 py-3 rounded-md transition-colors">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path
                  d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" />
              </svg>
              Lactating Women Resources
            </a>
          </div>

          <!-- Announcements -->
          <div
            class="mt-8 p-4 bg-govt-light-blue rounded-md border-l-4 border-govt-blue">
            <h3
              class="flex items-center font-semibold text-govt-dark-blue mb-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-2"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
              Latest Announcements
            </h3>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2 text-govt-blue shrink-0 mt-0.5"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span>Registration for new academic session 2024-25 starts from
                  June 15, 2024</span>
              </li>
              <li class="flex items-start">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2 text-govt-blue shrink-0 mt-0.5"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span>Teacher training workshop scheduled for July 10-15,
                  2024</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="relative animate-fade-in delay-200">
          <div
            class="absolute inset-0 bg-gradient-to-r from-govt-blue/20 to-govt-light-blue/50 rounded-lg transform rotate-2 scale-105"></div>
          <div class="relative glass-card rounded-lg overflow-hidden p-4">
            <div
              class="aspect-[4/3] rounded-md overflow-hidden bg-white flex items-center justify-center">
              <img
                src="./assets/anganwadi.jpg"
                alt="Anganwadi classroom"
                class="w-full h-full object-cover" />
            </div>

            <div class="absolute -bottom-6 -right-6 w-28 h-28 animate-float">
              <div
                class="w-full h-full bg-white rounded-full shadow-lg overflow-hidden ">
                <img
                  src="./assets/pratham.jpg"
                  alt="Children around globe"
                  class="w-full h-full object-contain" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-16 relative bg-govt-light-gray">
    <div class="container mx-auto px-4">
      <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-block mb-4">
          <span
            class="inline-flex items-center px-3 py-1 text-xs font-medium bg-govt-green/10 text-govt-green rounded-full">
            Anganwadi Management System
          </span>
        </div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-govt-dark-blue">
          Pratham Path Digital Solutions
        </h2>
        <p class="text-govt-text-gray">
          Streamlining operations, enhancing transparency, and improving
          accountability in Anganwadi centres across Assam.
        </p>
      </div>

      <div class="offline-indicator mb-8">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="lucide lucide-cloud-off">
          <path d="m2 2 20 20" />
          <path
            d="M5.782 5.782A7 7 0 0 0 9 19h8.5a4.5 4.5 0 0 0 1.307-.193" />
          <path
            d="M21.532 16.5A4.5 4.5 0 0 0 17.5 10h-1.79A7.008 7.008 0 0 0 10 5.07" />
        </svg>
        <span>Our application works offline! Perfect for areas with limited
          internet connectivity.</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Feature 1 -->
        <div class="feature-card delay-75">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path
                d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
              <rect x="9" y="3" width="6" height="4" rx="2" />
              <path d="M9 14v-4" />
              <path d="M15 14v-4" />
              <path d="M12 14v-4" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Facial Recognition Attendance
          </h3>
          <p class="text-govt-text-gray text-sm">
            Secure biometric system for accurate daily attendance tracking of
            Anganwadi workers, helpers, and children.
          </p>
        </div>

        <!-- Feature 2 -->
        <div class="feature-card delay-150">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path
                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
              <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
              <line x1="12" y1="22.08" x2="12" y2="12" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Ration Management
          </h3>
          <p class="text-govt-text-gray text-sm">
            Real-time verification of children's attendance for precise ration
            distribution, minimizing waste and preventing misuse.
          </p>
        </div>

        <!-- Feature 3 -->
        <div class="feature-card delay-200">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path
                d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
              <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
              <path d="M12 11h4" />
              <path d="M12 16h4" />
              <path d="M8 11h.01" />
              <path d="M8 16h.01" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Supervisor Reporting
          </h3>
          <p class="text-govt-text-gray text-sm">
            Dedicated platform for supervisors to log visits, submit reports,
            and escalate issues directly to higher authorities.
          </p>
        </div>

        <!-- Feature 4 -->
        <div class="feature-card delay-300">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="lucide lucide-cloud-off">
              <path d="m2 2 20 20" />
              <path
                d="M5.782 5.782A7 7 0 0 0 9 19h8.5a4.5 4.5 0 0 0 1.307-.193" />
              <path
                d="M21.532 16.5A4.5 4.5 0 0 0 17.5 10h-1.79A7.008 7.008 0 0 0 10 5.07" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Offline Functionality
          </h3>
          <p class="text-govt-text-gray text-sm">
            Full operational capability in areas with limited internet
            connectivity, with data sync when connectivity is restored.
          </p>
        </div>

        <!-- Feature 5 -->
        <div class="feature-card delay-400">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Data Security
          </h3>
          <p class="text-govt-text-gray text-sm">
            End-to-end encryption and secure authentication protocols to
            protect sensitive information about children and staff.
          </p>
        </div>

        <!-- Feature 6 -->
        <div class="feature-card delay-500">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M21 2v6h-6"></path>
              <path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
              <path d="M3 22v-6h6"></path>
              <path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Real-time Synchronization
          </h3>
          <p class="text-govt-text-gray text-sm">
            Automatic data syncing when internet access is available, ensuring
            all information is up-to-date across the system.
          </p>
        </div>

        <!-- Feature 7 -->
        <div class="feature-card delay-550">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            User-Friendly Interface
          </h3>
          <p class="text-govt-text-gray text-sm">
            Intuitive design for easy navigation, even for users with limited
            technical expertise.
          </p>
        </div>

        <!-- Feature 8 -->
        <div class="feature-card delay-600">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M18 20V10" />
              <path d="M12 20V4" />
              <path d="M6 20v-6" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Performance Analytics
          </h3>
          <p class="text-govt-text-gray text-sm">
            Comprehensive dashboards and reports for monitoring center
            activities, attendance patterns, and resource utilization.
          </p>
        </div>

        <!-- Feature 9 -->
        <div class="feature-card delay-650">
          <div class="feature-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2 text-govt-dark-blue">
            Location Tracking
          </h3>
          <p class="text-govt-text-gray text-sm">
            GPS-based location verification for supervisor visits, ensuring
            accountability and accurate reporting.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-block mb-4">
          <span
            class="inline-flex items-center px-3 py-1 text-xs font-medium bg-govt-orange/20 text-govt-orange rounded-full">
            Success Stories
          </span>
        </div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-govt-dark-blue">
          Impact Across Assam
        </h2>
        <p class="text-govt-text-gray">
          See how Pratham Path is transforming early childhood education
          across different districts of Assam.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Success Story 1 -->
        <div
          class="border-none shadow-md opacity-0 animate-scale-in delay-100 bg-white rounded-lg overflow-hidden">
          <div class="p-6 space-y-4">
            <div class="h-3 bg-govt-blue rounded-t-md -mt-6 -mx-6 mb-6"></div>
            <p class="text-govt-text-gray">
              After implementing the Pratham Path program, our school saw a
              40% increase in enrollment and significant improvement in
              children's foundational literacy skills.
            </p>
            <div class="pt-4 border-t border-gray-200">
              <div class="flex items-center text-sm text-govt-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Golaghat District, Assam</span>
              </div>
              <div
                class="flex items-center mt-2 text-sm font-medium text-govt-dark-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2 text-govt-green"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <circle cx="12" cy="8" r="7" />
                  <polyline
                    points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                </svg>
                <span>Best Performing School Award 2023</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Story 2 -->
        <div
          class="border-none shadow-md opacity-0 animate-scale-in delay-200 bg-white rounded-lg overflow-hidden">
          <div class="p-6 space-y-4">
            <div class="h-3 bg-govt-blue rounded-t-md -mt-6 -mx-6 mb-6"></div>
            <p class="text-govt-text-gray">
              The teacher training provided under Pratham Path has transformed
              our teaching methodologies, making learning more engaging and
              effective for our young students.
            </p>
            <div class="pt-4 border-t border-gray-200">
              <div class="flex items-center text-sm text-govt-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Dibrugarh District, Assam</span>
              </div>
              <div
                class="flex items-center mt-2 text-sm font-medium text-govt-dark-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2 text-govt-green"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <circle cx="12" cy="8" r="7" />
                  <polyline
                    points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                </svg>
                <span>Excellence in Early Education Award</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Story 3 -->
        <div
          class="border-none shadow-md opacity-0 animate-scale-in delay-300 bg-white rounded-lg overflow-hidden">
          <div class="p-6 space-y-4">
            <div class="h-3 bg-govt-blue rounded-t-md -mt-6 -mx-6 mb-6"></div>
            <p class="text-govt-text-gray">
              The digital learning tools introduced through Pratham Path have
              helped bridge the urban-rural divide in educational resources
              and opportunities for our children.
            </p>
            <div class="pt-4 border-t border-gray-200">
              <div class="flex items-center text-sm text-govt-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Cachar District, Assam</span>
              </div>
              <div
                class="flex items-center mt-2 text-sm font-medium text-govt-dark-blue">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-2 text-govt-green"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <circle cx="12" cy="8" r="7" />
                  <polyline
                    points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                </svg>
                <span>Digital Innovation Recognition 2023</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        class="mt-12 p-6 bg-govt-light-blue rounded-lg border border-govt-blue/20">
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/4 mb-4 md:mb-0 flex justify-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-16 w-16 text-govt-blue"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <path
                d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
            </svg>
          </div>
          <div class="md:w-3/4 text-center md:text-left">
            <h3 class="text-xl font-bold text-govt-dark-blue mb-2">
              Join Our Mission
            </h3>
            <p class="text-govt-text-gray mb-4">
              Interested in implementing Pratham Path in your district or
              school? Contact us to learn more about our program and how we
              can collaborate.
            </p>
            <a
              href="#contact"
              class="inline-flex items-center px-4 py-2 bg-govt-blue text-white rounded-md hover:bg-govt-dark-blue transition-colors">
              Get Involved
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <section id="stats-section" class="bg-govt-dark-blue py-16 text-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold mb-3">Impact In Numbers</h2>
        <p class="text-blue-100 max-w-2xl mx-auto">
          Our reach across Assam, serving children and mothers through the
          Anganwadi network.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
          <div
            class="text-4xl font-bold text-white mb-2 stat-counter"
            data-target="35728">
            0
          </div>
          <div class="text-blue-200">Anganwadi Centers</div>
        </div>
        <div
          class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
          <div
            class="text-4xl font-bold text-white mb-2 stat-counter"
            data-target="124500">
            0
          </div>
          <div class="text-blue-200">Children Registered</div>
        </div>
        <div
          class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
          <div
            class="text-4xl font-bold text-white mb-2 stat-counter"
            data-target="32150">
            0
          </div>
          <div class="text-blue-200">Pregnant Women</div>
        </div>
        <div
          class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
          <div
            class="text-4xl font-bold text-white mb-2 stat-counter"
            data-target="28975">
            0
          </div>
          <div class="text-blue-200">Lactating Mothers</div>
        </div>
      </div>
    </div>
  </section>

  <footer class="pt-16 pb-8">
    <div class="container mx-auto px-4">
      <!-- Contact form section -->
      <div id="contact" class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-govt-light-blue p-6 rounded-lg">
          <h3 class="text-xl font-bold text-govt-dark-blue mb-4">
            Contact Us
          </h3>
          <div class="space-y-4">
            <div class="flex items-start">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="h-5 w-5 text-govt-blue mr-3 mt-1 shrink-0">
                <path
                  d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
              <div>
                <p class="font-medium">Department of School Education</p>
                <p class="text-govt-text-gray">Janata Bhawan, Dispur</p>
                <p class="text-govt-text-gray">Guwahati, Assam - 781006</p>
              </div>
            </div>

            <div class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="h-5 w-5 text-govt-blue mr-3 shrink-0">
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <p>0361-2237219</p>
            </div>

            <div class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="h-5 w-5 text-govt-blue mr-3 shrink-0">
                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
              </svg>
              <p>prathampath@assam.gov.in</p>
            </div>
          </div>

          <div
            class="mt-6 p-4 bg-white rounded-md border-l-4 border-govt-orange">
            <div class="flex">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="h-5 w-5 text-govt-orange mr-3 shrink-0">
                <path
                  d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                <path d="M12 9v4"></path>
                <path d="M12 17h.01"></path>
              </svg>
              <div>
                <p class="font-medium text-govt-dark-blue">Office Hours</p>
                <p class="text-sm text-govt-text-gray">
                  Monday to Friday: 10:00 AM to 5:00 PM
                </p>
                <p class="text-sm text-govt-text-gray">
                  Saturday: 10:00 AM to 1:00 PM
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
          <h3 class="text-xl font-bold text-govt-dark-blue mb-4">
            Send us a message
          </h3>
          <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Subject</label>
              <input
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Message</label>
              <textarea
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-govt-blue"></textarea>
            </div>

            <button
              class="bg-govt-blue hover:bg-govt-dark-blue text-white px-4 py-2 rounded-md transition-colors w-full md:w-auto">
              Submit Message
            </button>
          </form>
        </div>
      </div>

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
    const statCounters = document.querySelectorAll(".stat-counter");
    const observerOptions = {
      threshold: 0.1,
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const counter = entry.target;
          const target = parseInt(counter.getAttribute("data-target"));
          const duration = 2000; // 2 seconds
          const frameDuration = 1000 / 60; // 60fps
          const totalFrames = Math.round(duration / frameDuration);

          let frame = 0;

          const updateCount = () => {
            frame++;
            const progress = frame / totalFrames;
            const currentCount = Math.floor(progress * target);

            counter.textContent = currentCount.toLocaleString() + "+";

            if (frame < totalFrames) {
              requestAnimationFrame(updateCount);
            }
          };

          requestAnimationFrame(updateCount);

          // Stop observing once animation is triggered
          observer.unobserve(counter);
        }
      });
    }, observerOptions);

    statCounters.forEach((counter) => {
      observer.observe(counter);
    });

    // Simple animation trigger for features
    document.addEventListener("DOMContentLoaded", function() {
      const features = document.querySelectorAll(".feature-card");
      const stories = document.querySelectorAll(".animate-scale-in");

      // Add animation classes with delays
      features.forEach(function(feature, index) {
        setTimeout(function() {
          feature.style.opacity = "1";
        }, 100 * index);
      });

      stories.forEach(function(story, index) {
        setTimeout(function() {
          story.style.opacity = "1";
        }, 200 * index);
      });

      // Smooth scroll implementation for anchor links
      document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function(e) {
          e.preventDefault();
          const targetId = this.getAttribute("href");
          if (targetId !== "#") {
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
              window.scrollTo({
                top: targetElement.getBoundingClientRect().top +
                  window.scrollY -
                  100,
                behavior: "smooth",
              });
            }
          }
        });
      });
    });
  </script>
</body>

</html>