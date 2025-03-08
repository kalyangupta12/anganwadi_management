<header class="w-full">
    <!-- Top Government Bar -->
    <div class="bg-pratham-navy text-white px-4 py-1.5 flex justify-between items-center text-xs md:text-sm">
      <div class="flex space-x-4">
        <span>Government of India</span>
        <span>|</span>
        <span>Government of Assam</span>
      </div>
      <div class="flex space-x-3">
        <button class="hover:text-pratham-blue transition-colors duration-200">English</button>
        <button class="hover:text-pratham-blue transition-colors duration-200">हिंदी</button>
        <button class="hover:text-pratham-blue transition-colors duration-200">অসমীয়া</button>
      </div>
    </div>

    <!-- Main Header -->
    <div class="bg-white px-4 py-3 flex flex-col md:flex-row md:items-center justify-between shadow-sm">
      <div class="flex items-center">
        <img 
          src="https://cdn.gpteng.co/4f844319-d316-46e7-8285-0561f857d4ad.png" 
          alt="Pratham Path Logo" 
          class="h-12 md:h-14 w-auto mr-4"
        />
        <div>
          <h1 class="text-pratham-navy font-bold text-xl md:text-2xl">Pratham Path</h1>
          <p class="text-gray-600 text-xs md:text-sm">शिक्षा अधिकार - Education Department, Assam</p>
        </div>
      </div>
      
      <div class="mt-3 md:mt-0 flex items-center">
        <div class="relative flex-1 md:w-64">
          <input
            type="text"
            placeholder="Search..."
            class="w-full pl-3 pr-10 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pratham-blue/40"
          />
          <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </button>
        </div>
        <button class="ml-2 bg-pratham-navy text-white rounded-md px-4 py-1.5 hover:bg-pratham-blue transition-colors duration-300">
          Search
        </button>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-pratham-navy text-white px-4 flex justify-between items-center">
      <div class="hidden md:flex">
        <a href="#" class="nav-item active relative px-4 py-2 transition-all duration-300 ease-in-out">Home</a>
        <a href="#" class="nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">About</a>
        <a href="#" class="nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">Schemes</a>
        <a href="#" class="nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">Resources</a>
        <a href="#" class="nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">Attendance</a>
        <a href="#" class="nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">Contact</a>
      </div>
      
      <button id="menuToggle" class="md:hidden py-3 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
      
      <a href="#" class="hidden md:flex items-center nav-item relative px-4 py-2 transition-all duration-300 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        Login
      </a>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden bg-pratham-navy/95 text-white hidden absolute z-50 w-full">
      <div class="flex flex-col divide-y divide-white/10">
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">Home</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">About</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">Schemes</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">Resources</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">Attendance</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20">Contact</a>
        <a href="#" class="px-6 py-3 hover:bg-pratham-blue/20 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Login
        </a>
      </div>
    </div>
</header>