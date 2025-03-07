<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pratham Path - Empowering Education in Assam</title>
  <meta name="description" content="A comprehensive early childhood education initiative by the Assam Government">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            pratham: {
              navy: '#001A57',
              blue: '#0A5CDA',
              skyblue: '#E3F0FF',
              lightblue: '#C8E1FF',
              red: '#FF3B30',
              green: '#34C759',
              yellow: '#FFCC00',
              gray: '#8E8E93',
              lightgray: '#F2F2F7'
            }
          },
          keyframes: {
            'fade-in': {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' }
            },
            'scale-in': {
              '0%': {
                transform: 'scale(0.95)',
                opacity: '0'
              },
              '100%': {
                transform: 'scale(1)',
                opacity: '1'
              }
            },
            'blob': {
              '0%': {
                transform: 'translate(0px, 0px) scale(1)'
              },
              '33%': {
                transform: 'translate(30px, -50px) scale(1.1)'
              },
              '66%': {
                transform: 'translate(-20px, 20px) scale(0.9)'
              },
              '100%': {
                transform: 'translate(0px, 0px) scale(1)'
              }
            }
          },
          animation: {
            'fade-in': 'fade-in 0.3s ease-out forwards',
            'scale-in': 'scale-in 0.3s ease-out forwards',
            'blob': 'blob 7s infinite'
          }
        }
      }
    }
  </script>
  
  <style>
    /* Custom styles */
    .nav-item::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 2px;
      background-color: #0A5CDA;
      transition: width 0.3s ease-in-out;
    }
    
    .nav-item:hover::after,
    .nav-item.active::after {
      width: 100%;
    }
    
    .animation-delay-2000 {
      animation-delay: 2s;
    }
    
    .animation-delay-4000 {
      animation-delay: 4s;
    }
    
    .btn-primary {
      background-color: #001A57;
      color: white;
      transition: background-color 0.3s;
      border-radius: 0.375rem;
      padding: 0.5rem 1rem;
    }
    
    .btn-primary:hover {
      background-color: #0A5CDA;
    }
    
    .btn-secondary {
      background-color: white;
      color: #001A57;
      border: 1px solid #001A57;
      transition: background-color 0.3s;
      border-radius: 0.375rem;
      padding: 0.5rem 1rem;
    }
    
    .btn-secondary:hover {
      background-color: #E3F0FF;
    }
    
    .card-hover {
      transition: all 0.3s;
    }
    
    .card-hover:hover {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      transform: scale(1.02) translateY(-4px);
    }
  </style>
</head>
<body class="bg-white">
  <!-- Header -->
  <?php include('includes/header.php'); ?>


  <main>
    <!-- Hero Section -->
    <div class="bg-gradient-to-b from-pratham-skyblue to-white py-16 md:py-20 relative overflow-hidden">
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSI3NjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImEiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgcGF0dGVyblRyYW5zZm9ybT0icm90YXRlKDQ1KSI+PHBhdGggZD0iTSAwIDAgTCAyMCAyMCBNIDIwIDAgTCAwIDIwIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMC41IiBzdHJva2Utb3BhY2l0eT0iMC4wNSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNhKSIvPjwvc3ZnPg==')] opacity-30"></div>
      
      <div class="container mx-auto px-4 flex flex-col md:flex-row items-center z-10 relative">
        <div class="md:w-1/2 mb-8 md:mb-0">
          <div class="bg-white/50 backdrop-blur-sm p-6 rounded-lg shadow-sm border border-white/40">
            <div class="text-sm inline-block px-3 py-1 rounded-full bg-pratham-navy/10 text-pratham-navy font-medium mb-3">
              Government of Assam
            </div>
            
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-pratham-navy mb-3">
              Pratham Path
            </h1>
            
            <h2 class="text-xl md:text-2xl text-pratham-blue mb-5">
              Empowering Education in Assam
            </h2>
            
            <p class="text-gray-700 mb-6">
              A comprehensive early childhood education initiative by the Assam Government to ensure quality education for every child in the state. सुशासन बाढ़े, सुशिक्षा पढ़े, सुशिक्षा रहे।
            </p>
            
            <div class="flex flex-wrap gap-3">
              <a href="#" class="btn-primary flex items-center">
                <span class="mr-2">Student Registration</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                  <path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path>
                </svg>
              </a>
              
              <a href="#" class="btn-secondary flex items-center">
                <span class="mr-2">Download Resources</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
              </a>
            </div>
          </div>
        </div>
        
        <div class="md:w-1/2 flex justify-center">
          <div class="relative w-full max-w-lg">
            <div class="absolute top-0 -left-4 w-40 h-40 bg-pratham-blue/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-40 h-40 bg-pratham-yellow/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-40 h-40 bg-pratham-green/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            
            <div class="relative">
              <img 
                src="https://cdn.gpteng.co/4f844319-d316-46e7-8285-0561f857d4ad.png" 
                alt="Anganwadi Center" 
                class="rounded-lg shadow-lg border-4 border-white transform hover:scale-[1.02] transition-transform duration-300 z-10 relative"
              />
            </div>
          </div>
        </div>
      </div>
      
      <div class="container mx-auto px-4 mt-12">
        <div class="bg-white/70 backdrop-blur-sm border border-white/40 rounded-lg shadow-sm p-4">
          <h3 class="text-pratham-navy font-semibold mb-2 text-center">Latest Announcements</h3>
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex items-start flex-1 p-3 rounded-md hover:bg-white/80 transition-colors duration-200">
              <div class="bg-pratham-blue/10 rounded-full p-2 mr-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-pratham-blue">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <div>
                <p class="text-sm text-gray-700">Registration for new academic session 2024-25 starts from June 15, 2024</p>
              </div>
            </div>
            
            <div class="flex items-start flex-1 p-3 rounded-md hover:bg-white/80 transition-colors duration-200">
              <div class="bg-pratham-green/10 rounded-full p-2 mr-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-pratham-green">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm text-gray-700">Teacher training workshop scheduled for July 10-15, 2024</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-b from-white to-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-pratham-navy mb-3">Comprehensive Features</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Our Anganwadi Centre Management System provides a complete solution for efficient management and improved service delivery.
          </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="features-grid">
          <!-- Features will be dynamically inserted here by JavaScript -->
        </div>
      </div>
    </section>

    <!-- Demo Features Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-bold text-pratham-navy mb-3">Anganwadi Management System</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Experience the essential features of our Anganwadi Centre Management application
          </p>
        </div>
        
        <!-- Tabs -->
        <div class="max-w-4xl mx-auto">
          <div class="mb-8 grid grid-cols-3 bg-gray-100 rounded-lg overflow-hidden">
            <button id="tab-login" class="tab-btn bg-pratham-navy text-white py-3 px-4 transition-colors">User Login</button>
            <button id="tab-beneficiary" class="tab-btn bg-gray-100 text-gray-700 py-3 px-4 transition-colors">Beneficiary Registration</button>
            <button id="tab-attendance" class="tab-btn bg-gray-100 text-gray-700 py-3 px-4 transition-colors">Attendance Tracking</button>
          </div>
          
          <!-- Tab Contents -->
          <div class="tab-content" id="content-login">
            <!-- Login Form -->
            <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 w-full max-w-md mx-auto">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-pratham-navy">Login</h2>
                <p class="text-gray-600 mt-1">Access your Anganwadi Management portal</p>
              </div>
              
              <div id="login-error" class="bg-red-50 text-red-800 p-3 rounded-md flex items-center mb-4 text-sm hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 flex-shrink-0">
                  <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Invalid username or password</span>
              </div>
              
              <form id="login-form">
                <div class="mb-4">
                  <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                    Login As
                  </label>
                  <select
                    id="role"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                  >
                    <option value="worker">Anganwadi Worker</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Administrator</option>
                  </select>
                </div>
                
                <div class="mb-4">
                  <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Username
                  </label>
                  <input
                    type="text"
                    id="username"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    placeholder="Enter your username"
                  />
                </div>
                
                <div class="mb-6">
                  <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                  </label>
                  <div class="relative">
                    <input
                      type="password"
                      id="password"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40 pr-10"
                      placeholder="Enter your password"
                    />
                    <button
                      type="button"
                      id="toggle-password"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                    >
                      <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                      </svg>
                      <svg id="eye-off-icon" class="hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
                      </svg>
                    </button>
                  </div>
                  <div class="text-right mt-1">
                    <a href="#" class="text-sm text-pratham-blue hover:underline">
                      Forgot password?
                    </a>
                  </div>
                </div>
                
                <button
                  type="submit"
                  id="login-button"
                  class="w-full bg-pratham-navy hover:bg-pratham-blue text-white py-2 px-4 rounded-md transition-colors duration-300 flex justify-center items-center"
                >
                  <span id="login-button-text">Login</span>
                  <div id="login-spinner" class="hidden">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                  </div>
                </button>
                
                <div class="mt-4 text-center text-sm text-gray-600">
                  <p>For demo, use: username: <strong>demo</strong>, password: <strong>password</strong></p>
                </div>
              </form>
            </div>
          </div>
          
          <div class="tab-content hidden" id="content-beneficiary">
            <!-- Beneficiary Registration Form Placeholder -->
            <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 w-full max-w-xl mx-auto">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-pratham-navy">Beneficiary Registration</h2>
                <p class="text-gray-600 mt-1">Register a new child for Anganwadi services</p>
              </div>
              
              <form>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Full Name
                    </label>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                      placeholder="Child's full name"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Date of Birth
                    </label>
                    <input
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Gender
                    </label>
                    <select
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    >
                      <option value="">Select Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Age Group
                    </label>
                    <select
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    >
                      <option value="">Select Age Group</option>
                      <option value="0-3">0-3 years</option>
                      <option value="3-6">3-6 years</option>
                      <option value="15+">15+ years (Girls)</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Mother's Name
                    </label>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                      placeholder="Mother's full name"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Father's Name
                    </label>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                      placeholder="Father's full name"
                    />
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Address
                  </label>
                  <textarea
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    rows="2"
                    placeholder="Full residential address"
                  ></textarea>
                </div>
                
                <div class="text-center">
                  <button
                    type="button"
                    class="bg-pratham-navy hover:bg-pratham-blue text-white py-2 px-6 rounded-md transition-colors duration-300"
                  >
                    Register Beneficiary
                  </button>
                </div>
              </form>
            </div>
          </div>
          
          <div class="tab-content hidden" id="content-attendance">
            <!-- Attendance Tracking Form Placeholder -->
            <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 w-full max-w-xl mx-auto">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-pratham-navy">Attendance Tracker</h2>
                <p class="text-gray-600 mt-1">Mark daily attendance for children</p>
              </div>
              
              <div class="flex justify-between items-center mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Date
                  </label>
                  <input
                    type="date"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                    value="2024-05-22"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Age Group
                  </label>
                  <select
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
                  >
                    <option value="all">All Groups</option>
                    <option value="0-3">0-3 years</option>
                    <option value="3-6">3-6 years</option>
                    <option value="15+">15+ years (Girls)</option>
                  </select>
                </div>
              </div>
              
              <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 mb-4">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="py-2 px-4 border-b text-left">#</th>
                      <th class="py-2 px-4 border-b text-left">Name</th>
                      <th class="py-2 px-4 border-b text-left">Age Group</th>
                      <th class="py-2 px-4 border-b text-center">Attendance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="py-2 px-4 border-b">1</td>
                      <td class="py-2 px-4 border-b">Rahul Sharma</td>
                      <td class="py-2 px-4 border-b">3-6 years</td>
                      <td class="py-2 px-4 border-b text-center">
                        <label class="inline-flex items-center">
                          <input type="checkbox" class="rounded text-pratham-blue focus:ring-pratham-blue/40" checked>
                          <span class="ml-2">Present</span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 border-b">2</td>
                      <td class="py-2 px-4 border-b">Priya Patel</td>
                      <td class="py-2 px-4 border-b">3-6 years</td>
                      <td class="py-2 px-4 border-b text-center">
                        <label class="inline-flex items-center">
                          <input type="checkbox" class="rounded text-pratham-blue focus:ring-pratham-blue/40" checked>
                          <span class="ml-2">Present</span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 border-b">3</td>
                      <td class="py-2 px-4 border-b">Anjali Gupta</td>
                      <td class="py-2 px-4 border-b">3-6 years</td>
                      <td class="py-2 px-4 border-b text-center">
                        <label class="inline-flex items-center">
                          <input type="checkbox" class="rounded text-pratham-blue focus:ring-pratham-blue/40">
                          <span class="ml-2">Present</span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 border-b">4</td>
                      <td class="py-2 px-4 border-b">Vikram Singh</td>
                      <td class="py-2 px-4 border-b">0-3 years</td>
                      <td class="py-2 px-4 border-b text-center">
                        <label class="inline-flex items-center">
                          <input type="checkbox" class="rounded text-pratham-blue focus:ring-pratham-blue/40" checked>
                          <span class="ml-2">Present</span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 border-b">5</td>
                      <td class="py-2 px-4 border-b">Meena Kumari</td>
                      <td class="py-2 px-4 border-b">0-3 years</td>
                      <td class="py-2 px-4 border-b text-center">
                        <label class="inline-flex items-center">
                          <input type="checkbox" class="rounded text-pratham-blue focus:ring-pratham-blue/40">
                          <span class="ml-2">Present</span>
                        </label>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="text-center">
                <button
                  type="button"
                  class="bg-pratham-navy hover:bg-pratham-blue text-white py-2 px-6 rounded-md transition-colors duration-300"
                >
                  Save Attendance
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats-section" class="bg-pratham-navy py-16 text-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold mb-3">Impact In Numbers</h2>
          <p class="text-blue-100 max-w-2xl mx-auto">
            Our reach across Assam, serving children and mothers through the Anganwadi network.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
            <div class="text-4xl font-bold text-white mb-2 stat-counter" data-target="35728">0</div>
            <div class="text-blue-200">Anganwadi Centers</div>
          </div>
          <div class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
            <div class="text-4xl font-bold text-white mb-2 stat-counter" data-target="124500">0</div>
            <div class="text-blue-200">Children Registered</div>
          </div>
          <div class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
            <div class="text-4xl font-bold text-white mb-2 stat-counter" data-target="32150">0</div>
            <div class="text-blue-200">Pregnant Women</div>
          </div>
          <div class="text-center bg-white/5 backdrop-blur-sm p-6 rounded-lg border border-white/10 hover:bg-white/10 transition-colors duration-300">
            <div class="text-4xl font-bold text-white mb-2 stat-counter" data-target="28975">0</div>
            <div class="text-blue-200">Lactating Mothers</div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php include('includes/footer.php'); ?>

  <!-- JavaScript for the landing page -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Mobile menu toggle
      const menuToggle = document.getElementById('menuToggle');
      const mobileMenu = document.getElementById('mobileMenu');
      
      menuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
      });
      
      // Password visibility toggle
      const togglePassword = document.getElementById('toggle-password');
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eye-icon');
      const eyeOffIcon = document.getElementById('eye-off-icon');
      
      togglePassword.addEventListener('click', function() {
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          eyeIcon.classList.add('hidden');
          eyeOffIcon.classList.remove('hidden');
        } else {
          passwordField.type = 'password';
          eyeIcon.classList.remove('hidden');
          eyeOffIcon.classList.add('hidden');
        }
      });
      
      // Login form submission
      const loginForm = document.getElementById('login-form');
      const loginButton = document.getElementById('login-button');
      const loginButtonText = document.getElementById('login-button-text');
      const loginSpinner = document.getElementById('login-spinner');
      const loginError = document.getElementById('login-error');
      
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Show loading state
        loginButtonText.classList.add('hidden');
        loginSpinner.classList.remove('hidden');
        loginError.classList.add('hidden');
        
        // Simulate API call with setTimeout
        setTimeout(function() {
          if (username === 'demo' && password === 'password') {
            // Success - redirect to dashboard
            alert('Login successful! Redirecting to dashboard...');
            window.location.href = '/dashboard.html';
          } else {
            // Error
            loginButtonText.classList.remove('hidden');
            loginSpinner.classList.add('hidden');
            loginError.classList.remove('hidden');
          }
        }, 1500);
      });
      
      // Tab switching
      const tabButtons = document.querySelectorAll('.tab-btn');
      const tabContents = document.querySelectorAll('.tab-content');
      
      tabButtons.forEach(button => {
        button.addEventListener('click', () => {
          // Reset all buttons and hide all contents
          tabButtons.forEach(btn => {
            btn.classList.remove('bg-pratham-navy', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
          });
          
          tabContents.forEach(content => {
            content.classList.add('hidden');
          });
          
          // Activate clicked button and show corresponding content
          button.classList.remove('bg-gray-100', 'text-gray-700');
          button.classList.add('bg-pratham-navy', 'text-white');
          
          const contentId = 'content-' + button.id.split('-')[1];
          document.getElementById(contentId).classList.remove('hidden');
        });
      });
      
      // Populate features grid
      const featuresData = [
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
          title: "User Authentication",
          description: "Secure login for Anganwadi Workers, Supervisors, and Administrators with role-based access control."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"></path><path d="M10 22v-4"></path><path d="M14 22v-4"></path><path d="M12 8v4"></path><path d="M15.45 12.15c.2.73.55 1.4 1 2 1.55 2.05 1.55 3.85 0 5.85-2.34 3.12-7.86.92-8.9-3.23A9.27 9.27 0 0 1 7.5 15c0-2.58 1.98-4.74 2.5-5.5"></path><path d="M7 16.5c-3.5 0-5-2-5-2C5.5 10 7 10.5 9 11.5c-.5-2.5.5-5 .5-5s2 2 4 2 4-2 4-2a7.98 7.98 0 0 1-2 6.12"></path></svg>',
          title: "Beneficiary Management",
          description: "Manage profiles for children, pregnant women, and lactating mothers with ease."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
          title: "Attendance Tracking",
          description: "Mark daily attendance and log services provided to beneficiaries."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12.9V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7.1a2 2 0 0 0 1-1.3l4-9a2 2 0 0 1 4 .6v7.8h8a2 2 0 0 1 2 2z"></path><path d="M6 12h.01"></path><path d="M6 16h.01"></path><path d="M10 12h.01"></path><path d="M10 16h.01"></path><path d="M14 12h.01"></path><path d="M14 16h.01"></path><path d="M18 12h.01"></path><path d="M18 16h.01"></path></svg>',
          title: "Health Monitoring",
          description: "Record and track growth parameters and health check-ups for children."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><line x1="12" y1="22" x2="12" y2="12"></line></svg>',
          title: "SNP Management",
          description: "Track ration distribution and manage inventory of food supplies efficiently."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
          title: "Reporting & Analytics",
          description: "Generate detailed reports on attendance, health metrics, and service delivery."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
          title: "Community Engagement",
          description: "Access educational materials on nutrition, health, and hygiene for communities."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>',
          title: "System Integration",
          description: "Seamlessly sync with Poshan Tracker and Jan Andolan Portal."
        },
        {
          icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 16.326A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 .5 8.973"></path><path d="M13 12H7"></path><path d="m9 10-2 2 2 2"></path></svg>',
          title: "Offline Functionality",
          description: "Work offline with automatic synchronization when online."
        }
      ];
      
      const featuresGrid = document.getElementById('features-grid');
      
      featuresData.forEach((feature, index) => {
        const featureCard = document.createElement('div');
        featureCard.className = `bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:border-pratham-blue/20 card-hover`;
        featureCard.style.animationDelay = `${0.1 * index}s`;
        
        featureCard.innerHTML = `
          <div class="bg-pratham-skyblue/50 w-12 h-12 rounded-full flex items-center justify-center mb-4 text-pratham-blue">
            ${feature.icon}
          </div>
          <h3 class="text-xl font-semibold text-pratham-navy mb-2">${feature.title}</h3>
          <p class="text-gray-600">${feature.description}</p>
        `;
        
        featuresGrid.appendChild(featureCard);
      });
      
      // Animate statistics when scrolled into view
      const statCounters = document.querySelectorAll('.stat-counter');
      const observerOptions = {
        threshold: 0.1
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const counter = entry.target;
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const frameDuration = 1000 / 60; // 60fps
            const totalFrames = Math.round(duration / frameDuration);
            
            let frame = 0;
            
            const updateCount = () => {
              frame++;
              const progress = frame / totalFrames;
              const currentCount = Math.floor(progress * target);
              
              counter.textContent = currentCount.toLocaleString() + '+';
              
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
      
      statCounters.forEach(counter => {
        observer.observe(counter);
      });
    });
  </script>
</body>
</html>