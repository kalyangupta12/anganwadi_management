
// Dashboard Data
const userRoles = {
    worker: {
      title: 'Anganwadi Worker',
      center: 'Centre #1234',
      avatar: 'AW',
      reportTypes: ['Daily Attendance', 'Nutrition Distribution', 'Monthly Summary'],
      settingsCategories: ['Profile', 'Notifications', 'Data Sync']
    },
    supervisor: {
      title: 'Supervisor',
      center: 'Sector #42',
      avatar: 'SV',
      reportTypes: ['Centre Performance', 'Attendance Trends', 'Nutrition Compliance', 'Health Metrics'],
      settingsCategories: ['Profile', 'Notifications', 'Centre Management', 'Data Access']
    },
    admin: {
      title: 'CDPO Admin',
      center: 'Project Office',
      avatar: 'CD',
      reportTypes: ['Project Overview', 'Centre Comparison', 'Resource Allocation', 'Government Reports', 'Assam Student Data'],
      settingsCategories: ['Profile', 'User Management', 'Project Configuration', 'System Settings', 'Data Management']
    }
  };
  
  const beneficiaryTypes = [
    { id: 'children-0-3', label: 'Children (0-3 years)', provisions: ['Chawal', 'Dal', 'Matar'] },
    { id: 'children-3-6', label: 'Children (3-6 years)', provisions: ['Chawal', 'Dal', 'Matar', 'Khichdi instant', 'Sooji', 'Horlicks', 'Bag', 'Bottle', 'Uniform', 'Tiffin box', 'Activity book', 'Reader book', 'Fruits book'] },
    { id: 'girls-15-plus', label: 'Girls (15+ years)', provisions: [], notes: 'Student Data required by Assam Government' }
  ];
  
  let beneficiaries = [
    { id: 1, name: 'Aarav Kumar', age: 2, gender: 'Male', guardian: 'Priya Kumar', provisions: ['Chawal', 'Dal'], studentId: '' },
    { id: 2, name: 'Diya Patel', age: 4, gender: 'Female', guardian: 'Rajesh Patel', provisions: ['Chawal', 'Dal', 'Khichdi instant', 'Bag'], studentId: '' },
    { id: 3, name: 'Advika Singh', age: 16, gender: 'Female', guardian: 'Neha Singh', provisions: [], studentId: 'ASM2023456' }
  ];
  
  let attendanceData = [
    { id: 1, name: 'Aarav Kumar', age: 2, present: true, provisions: ['Chawal', 'Dal'] },
    { id: 2, name: 'Diya Patel', age: 4, present: false, provisions: [] },
    { id: 3, name: 'Prisha Sharma', age: 3, present: true, provisions: ['Chawal', 'Dal', 'Matar'] },
    { id: 4, name: 'Vihaan Singh', age: 5, present: true, provisions: ['Chawal', 'Dal', 'Book'] },
    { id: 5, name: 'Advika Reddy', age: 16, present: true, provisions: [] },
  ];
  
  // DOM References
  const sidebar = document.getElementById('sidebar');
  const sidebarTitle = document.getElementById('sidebar-title');
  const toggleSidebarBtn = document.getElementById('toggle-sidebar');
  const mainContent = document.getElementById('main-content');
  const userAvatar = document.getElementById('user-avatar');
  const userRoleDisplay = document.getElementById('user-role-display');
  const userCenter = document.getElementById('user-center');
  const roleSwitcher = document.getElementById('role-switcher');
  const userInfo = document.getElementById('user-info');
  const roleSwitcherContainer = document.getElementById('role-switcher-container');
  const pageTitle = document.getElementById('page-title');
  
  const beneficiariesSection = document.getElementById('beneficiaries-section');
  const attendanceSection = document.getElementById('attendance-section');
  const reportsSection = document.getElementById('reports-section');
  const settingsSection = document.getElementById('settings-section');
  
  const navBeneficiaries = document.getElementById('nav-beneficiaries');
  const navAttendance = document.getElementById('nav-attendance');
  const navReports = document.getElementById('nav-reports');
  const navSettings = document.getElementById('nav-settings');
  
  const addBeneficiaryButton = document.getElementById('add-beneficiary-button');
  const addBeneficiaryForm = document.getElementById('add-beneficiary-form');
  const cancelBeneficiaryButton = document.getElementById('cancel-beneficiary-button');
  const beneficiaryForm = document.getElementById('beneficiary-form');
  const studentIdField = document.getElementById('student-id-field');
  const studentIdHeader = document.getElementById('student-id-header');
  const beneficiariesTableBody = document.getElementById('beneficiaries-table-body');
  const provisionsTitle = document.getElementById('provisions-title');
  const provisionsList = document.getElementById('provisions-list');
  
  const typeChildren03 = document.getElementById('type-children-0-3');
  const typeChildren36 = document.getElementById('type-children-3-6');
  const typeGirls15Plus = document.getElementById('type-girls-15-plus');
  
  const attendanceDate = document.getElementById('attendance-date');
  const attendanceTableBody = document.getElementById('attendance-table-body');
  const attendanceSummary = document.getElementById('attendance-summary');
  const generateReportButton = document.getElementById('generate-report-button');
  
  const reportsDescription = document.getElementById('reports-description');
  const reportsGrid = document.getElementById('reports-grid');
  
  const settingsCategories = document.getElementById('settings-categories');
  const userManagementSection = document.getElementById('user-management-section');
  
  const homeButton = document.getElementById('home-button');
  const logoutButton = document.getElementById('logout-button');
  
  // State Variables
  let isSidebarOpen = true;
  let currentUserRole = 'worker';
  let selectedBeneficiaryType = 'children-0-3';
  
  // Initialize
  document.addEventListener('DOMContentLoaded', () => {
    // Set current date for attendance
    attendanceDate.valueAsDate = new Date();
    
    // Initialize UI based on current role
    updateUIForRole(currentUserRole);
    
    // Render beneficiaries table
    renderBeneficiariesTable();
    
    // Render attendance table
    renderAttendanceTable();
    
    // Add event listeners
    setupEventListeners();
  });
  
  // Event Listeners
  function setupEventListeners() {
    // Sidebar toggle
    toggleSidebarBtn.addEventListener('click', toggleSidebar);
    
    // Navigation
    navBeneficiaries.addEventListener('click', () => switchSection('beneficiaries'));
    navAttendance.addEventListener('click', () => switchSection('attendance'));
    navReports.addEventListener('click', () => switchSection('reports'));
    navSettings.addEventListener('click', () => switchSection('settings'));
    
    // Role switcher
    roleSwitcher.addEventListener('change', (e) => {
      currentUserRole = e.target.value;
      updateUIForRole(currentUserRole);
    });
    
    // Beneficiary type buttons
    typeChildren03.addEventListener('click', () => selectBeneficiaryType('children-0-3'));
    typeChildren36.addEventListener('click', () => selectBeneficiaryType('children-3-6'));
    typeGirls15Plus.addEventListener('click', () => selectBeneficiaryType('girls-15-plus'));
    
    // Add beneficiary
    addBeneficiaryButton.addEventListener('click', toggleBeneficiaryForm);
    cancelBeneficiaryButton.addEventListener('click', toggleBeneficiaryForm);
    beneficiaryForm.addEventListener('submit', handleAddBeneficiary);
    
    // Attendance date change
    attendanceDate.addEventListener('change', renderAttendanceTable);
    
    // Home and logout buttons
    homeButton.addEventListener('click', () => window.location.href = 'index.html');
    logoutButton.addEventListener('click', () => window.location.href = 'index.html');
  }
  
  // Toggle sidebar visibility
  function toggleSidebar() {
    isSidebarOpen = !isSidebarOpen;
    
    if (isSidebarOpen) {
      sidebar.classList.remove('w-20');
      sidebar.classList.add('w-64');
      mainContent.classList.remove('ml-20');
      mainContent.classList.add('ml-64');
      sidebarTitle.classList.remove('hidden');
      userInfo.classList.remove('hidden');
      roleSwitcherContainer.classList.remove('hidden');
      
      // Show text in navigation
      document.querySelectorAll('#sidebar nav button span, #sidebar #logout-button span').forEach(span => {
        span.classList.remove('hidden');
      });
      
      // Update toggle icon
      toggleSidebarBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
    } else {
      sidebar.classList.remove('w-64');
      sidebar.classList.add('w-20');
      mainContent.classList.remove('ml-64');
      mainContent.classList.add('ml-20');
      sidebarTitle.classList.add('hidden');
      userInfo.classList.add('hidden');
      roleSwitcherContainer.classList.add('hidden');
      
      // Hide text in navigation
      document.querySelectorAll('#sidebar nav button span, #sidebar #logout-button span').forEach(span => {
        span.classList.add('hidden');
      });
      
      // Update toggle icon
      toggleSidebarBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';
    }
  }
  
  // Switch between content sections
  function switchSection(section) {
    // Update navigation styling
    navBeneficiaries.classList.remove('bg-pratham-blue');
    navAttendance.classList.remove('bg-pratham-blue');
    navReports.classList.remove('bg-pratham-blue');
    navSettings.classList.remove('bg-pratham-blue');
    
    navBeneficiaries.classList.add('hover:bg-pratham-blue/20');
    navAttendance.classList.add('hover:bg-pratham-blue/20');
    navReports.classList.add('hover:bg-pratham-blue/20');
    navSettings.classList.add('hover:bg-pratham-blue/20');
    
    // Hide all sections
    beneficiariesSection.classList.add('hidden');
    attendanceSection.classList.add('hidden');
    reportsSection.classList.add('hidden');
    settingsSection.classList.add('hidden');
    
    // Show selected section and update navigation
    switch (section) {
      case 'beneficiaries':
        beneficiariesSection.classList.remove('hidden');
        navBeneficiaries.classList.add('bg-pratham-blue');
        navBeneficiaries.classList.remove('hover:bg-pratham-blue/20');
        pageTitle.textContent = 'Beneficiary Management';
        break;
      case 'attendance':
        attendanceSection.classList.remove('hidden');
        navAttendance.classList.add('bg-pratham-blue');
        navAttendance.classList.remove('hover:bg-pratham-blue/20');
        pageTitle.textContent = 'Attendance Tracking';
        break;
      case 'reports':
        reportsSection.classList.remove('hidden');
        navReports.classList.add('bg-pratham-blue');
        navReports.classList.remove('hover:bg-pratham-blue/20');
        pageTitle.textContent = 'Reports & Analytics';
        renderReports();
        break;
      case 'settings':
        settingsSection.classList.remove('hidden');
        navSettings.classList.add('bg-pratham-blue');
        navSettings.classList.remove('hover:bg-pratham-blue/20');
        pageTitle.textContent = 'Settings';
        renderSettings();
        break;
    }
  }
  
  // Update UI based on selected user role
  function updateUIForRole(role) {
    const roleData = userRoles[role];
    
    // Update user info
    userRoleDisplay.textContent = roleData.title;
    userCenter.textContent = roleData.center;
    userAvatar.textContent = roleData.avatar;
    
    // Update reports description based on role
    if (role === 'worker') {
      reportsDescription.textContent = 'View and generate reports for your Anganwadi centre';
      generateReportButton.classList.add('hidden');
    } else if (role === 'supervisor') {
      reportsDescription.textContent = 'Monitor performance across multiple centres';
      generateReportButton.classList.remove('hidden');
    } else { // admin
      reportsDescription.textContent = 'Comprehensive project analytics and government reporting';
      generateReportButton.classList.remove('hidden');
    }
    
    // Show/hide user management section for admin
    if (role === 'admin') {
      userManagementSection.classList.remove('hidden');
      addBeneficiaryButton.classList.remove('hidden');
    } else if (role === 'worker') {
      userManagementSection.classList.add('hidden');
      addBeneficiaryButton.classList.remove('hidden');
    } else { // supervisor
      userManagementSection.classList.add('hidden');
      addBeneficiaryButton.classList.add('hidden');
    }
    
    // Re-render tables and sections that depend on role
    renderBeneficiariesTable();
    renderAttendanceTable();
    renderReports();
    renderSettings();
  }
  
  // Toggle beneficiary form visibility
  function toggleBeneficiaryForm() {
    addBeneficiaryForm.classList.toggle('hidden');
    
    // Show student ID field if girls 15+ selected
    if (selectedBeneficiaryType === 'girls-15-plus') {
      studentIdField.classList.remove('hidden');
    } else {
      studentIdField.classList.add('hidden');
    }
  }
  
  // Handle adding new beneficiary
  function handleAddBeneficiary(e) {
    e.preventDefault();
    
    const nameInput = document.getElementById('beneficiary-name');
    const ageInput = document.getElementById('beneficiary-age');
    const genderInput = document.getElementById('beneficiary-gender');
    const guardianInput = document.getElementById('beneficiary-guardian');
    const studentIdInput = document.getElementById('beneficiary-student-id');
    
    const newBeneficiary = {
      id: beneficiaries.length + 1,
      name: nameInput.value,
      age: parseInt(ageInput.value),
      gender: genderInput.value,
      guardian: guardianInput.value,
      provisions: [],
      studentId: studentIdInput.value || ''
    };
    
    beneficiaries.push(newBeneficiary);
    
    // Reset form
    beneficiaryForm.reset();
    addBeneficiaryForm.classList.add('hidden');
    
    // Re-render table
    renderBeneficiariesTable();
    
    // Show success alert
    alert('Beneficiary added successfully!');
  }
  
  // Select beneficiary type
  function selectBeneficiaryType(type) {
    selectedBeneficiaryType = type;
    
    // Update button styling
    typeChildren03.classList.remove('bg-pratham-navy', 'text-white');
    typeChildren03.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
    
    typeChildren36.classList.remove('bg-pratham-navy', 'text-white');
    typeChildren36.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
    
    typeGirls15Plus.classList.remove('bg-pratham-navy', 'text-white');
    typeGirls15Plus.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
    
    if (type === 'children-0-3') {
      typeChildren03.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
      typeChildren03.classList.add('bg-pratham-navy', 'text-white');
      studentIdHeader.classList.add('hidden');
    } else if (type === 'children-3-6') {
      typeChildren36.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
      typeChildren36.classList.add('bg-pratham-navy', 'text-white');
      studentIdHeader.classList.add('hidden');
    } else {
      typeGirls15Plus.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
      typeGirls15Plus.classList.add('bg-pratham-navy', 'text-white');
      studentIdHeader.classList.remove('hidden');
    }
    
    // Update provisions display
    const typeData = beneficiaryTypes.find(t => t.id === type);
    provisionsTitle.textContent = typeData.label;
    
    // Update provisions list
    renderProvisionsList(type);
    
    // Re-render table with filtered data
    renderBeneficiariesTable();
  }
  
  // Render provisions list
  function renderProvisionsList(type) {
    const provisions = beneficiaryTypes.find(t => t.id === type).provisions;
    provisionsList.innerHTML = '';
    
    if (provisions.length === 0 && type === 'girls-15-plus') {
      const note = document.createElement('span');
      note.className = 'text-gray-600 italic';
      note.textContent = 'Student data collection as required by Assam Government';
      provisionsList.appendChild(note);
      return;
    }
    
    provisions.forEach(item => {
      const span = document.createElement('span');
      span.className = 'bg-pratham-lightblue text-pratham-navy px-3 py-1 rounded-full text-sm';
      span.textContent = item;
      provisionsList.appendChild(span);
    });
  }
  
  // Render beneficiaries table
  function renderBeneficiariesTable() {
    beneficiariesTableBody.innerHTML = '';
    
    // Filter beneficiaries based on selected type
    const filteredBeneficiaries = beneficiaries.filter(b => {
      if (selectedBeneficiaryType === 'children-0-3') return b.age >= 0 && b.age < 3;
      if (selectedBeneficiaryType === 'children-3-6') return b.age >= 3 && b.age <= 6;
      return b.age >= 15 && b.gender === 'Female';
    });
    
    if (filteredBeneficiaries.length === 0) {
      const row = document.createElement('tr');
      const cell = document.createElement('td');
      cell.colSpan = selectedBeneficiaryType === 'girls-15-plus' ? 7 : 6;
      cell.className = 'px-6 py-4 text-center text-gray-500';
      cell.textContent = 'No beneficiaries found';
      row.appendChild(cell);
      beneficiariesTableBody.appendChild(row);
      return;
    }
    
    filteredBeneficiaries.forEach(beneficiary => {
      const row = document.createElement('tr');
      
      // Name cell
      const nameCell = document.createElement('td');
      nameCell.className = 'px-6 py-4 whitespace-nowrap';
      nameCell.textContent = beneficiary.name;
      row.appendChild(nameCell);
      
      // Age cell
      const ageCell = document.createElement('td');
      ageCell.className = 'px-6 py-4 whitespace-nowrap';
      ageCell.textContent = `${beneficiary.age} years`;
      row.appendChild(ageCell);
      
      // Gender cell
      const genderCell = document.createElement('td');
      genderCell.className = 'px-6 py-4 whitespace-nowrap';
      genderCell.textContent = beneficiary.gender;
      row.appendChild(genderCell);
      
      // Guardian cell
      const guardianCell = document.createElement('td');
      guardianCell.className = 'px-6 py-4 whitespace-nowrap';
      guardianCell.textContent = beneficiary.guardian;
      row.appendChild(guardianCell);
      
      // Student ID cell (for girls 15+)
      if (selectedBeneficiaryType === 'girls-15-plus') {
        const studentIdCell = document.createElement('td');
        studentIdCell.className = 'px-6 py-4 whitespace-nowrap';
        studentIdCell.textContent = beneficiary.studentId || 'N/A';
        row.appendChild(studentIdCell);
      }
      
      // Provisions cell
      const provisionsCell = document.createElement('td');
      provisionsCell.className = 'px-6 py-4';
      provisionsCell.textContent = beneficiary.provisions && beneficiary.provisions.length > 0 
        ? beneficiary.provisions.join(', ') 
        : 'None';
      row.appendChild(provisionsCell);
      
      // Actions cell
      const actionsCell = document.createElement('td');
      actionsCell.className = 'px-6 py-4 whitespace-nowrap';
      
      const actionsDiv = document.createElement('div');
      actionsDiv.className = 'flex space-x-2';
      
      const editButton = document.createElement('button');
      editButton.className = 'text-blue-600 hover:text-blue-800';
      editButton.textContent = 'Edit';
      
      const deleteButton = document.createElement('button');
      deleteButton.className = 'text-red-600 hover:text-red-800';
      deleteButton.textContent = 'Delete';
      
      actionsDiv.appendChild(editButton);
      actionsDiv.appendChild(deleteButton);
      actionsCell.appendChild(actionsDiv);
      row.appendChild(actionsCell);
      
      beneficiariesTableBody.appendChild(row);
    });
  }
  
  // Render attendance table
  function renderAttendanceTable() {
    attendanceTableBody.innerHTML = '';
    
    attendanceData.forEach(person => {
      const row = document.createElement('tr');
      
      // Name cell
      const nameCell = document.createElement('td');
      nameCell.className = 'px-6 py-4 whitespace-nowrap';
      nameCell.textContent = person.name;
      row.appendChild(nameCell);
      
      // Age cell
      const ageCell = document.createElement('td');
      ageCell.className = 'px-6 py-4 whitespace-nowrap';
      ageCell.textContent = `${person.age} years`;
      row.appendChild(ageCell);
      
      // Attendance cell
      const attendanceCell = document.createElement('td');
      attendanceCell.className = 'px-6 py-4 whitespace-nowrap';
      
      const attendanceButton = document.createElement('button');
      attendanceButton.className = `px-3 py-1 rounded-full text-sm ${
        person.present 
          ? 'bg-green-100 text-green-800' 
          : 'bg-red-100 text-red-800'
      }`;
      attendanceButton.textContent = person.present ? 'Present' : 'Absent';
      
      // Disable for supervisors on past dates (except yesterday)
      const today = new Date();
      const selectedDate = new Date(attendanceDate.value);
      const isYesterdayOrEarlier = selectedDate.getTime() < today.getTime() - 86400000;
      
      if (currentUserRole === 'supervisor' && isYesterdayOrEarlier) {
        attendanceButton.disabled = true;
      } else {
        attendanceButton.addEventListener('click', () => {
          // Toggle attendance
          person.present = !person.present;
          renderAttendanceTable();
        });
      }
      
      attendanceCell.appendChild(attendanceButton);
      row.appendChild(attendanceCell);
      
      // Provisions cell
      const provisionsCell = document.createElement('td');
      provisionsCell.className = 'px-6 py-4';
      
      if (person.present) {
        if (person.provisions.length > 0) {
          const provisionsDiv = document.createElement('div');
          provisionsDiv.className = 'flex flex-wrap gap-1';
          
          person.provisions.forEach(item => {
            const span = document.createElement('span');
            span.className = 'bg-pratham-lightblue text-pratham-navy px-2 py-0.5 rounded-full text-xs';
            span.textContent = item;
            provisionsDiv.appendChild(span);
          });
          
          provisionsCell.appendChild(provisionsDiv);
        } else {
          const addButton = document.createElement('button');
          addButton.className = 'text-sm text-pratham-blue hover:underline';
          addButton.textContent = '+ Add provisions';
          addButton.disabled = currentUserRole === 'supervisor';
          provisionsCell.appendChild(addButton);
        }
      } else {
        const span = document.createElement('span');
        span.className = 'text-gray-400';
        span.textContent = 'N/A';
        provisionsCell.appendChild(span);
      }
      
      row.appendChild(provisionsCell);
      attendanceTableBody.appendChild(row);
    });
    
    // Update summary
    const presentCount = attendanceData.filter(p => p.present).length;
    attendanceSummary.textContent = `Total Present: ${presentCount} of ${attendanceData.length}`;
  }
  
  // Render reports
  function renderReports() {
    const reportTypes = userRoles[currentUserRole].reportTypes;
    reportsGrid.innerHTML = '';
    
    reportTypes.forEach(report => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow';
      
      const title = document.createElement('h3');
      title.className = 'font-bold text-lg text-pratham-navy mb-2';
      title.textContent = report;
      
      const description = document.createElement('p');
      description.className = 'text-gray-600 mb-4';
      
      // Custom descriptions based on report type
      switch (report) {
        case 'Daily Attendance':
          description.textContent = 'Track daily attendance records and trends';
          break;
        case 'Nutrition Distribution':
          description.textContent = 'Monitor distribution of nutrition items';
          break;
        case 'Monthly Summary':
          description.textContent = 'Monthly overview of centre activities';
          break;
        case 'Centre Performance':
          description.textContent = 'Analyze performance metrics across centres';
          break;
        case 'Attendance Trends':
          description.textContent = 'Long-term attendance patterns and insights';
          break;
        case 'Nutrition Compliance':
          description.textContent = 'Compliance with nutrition program guidelines';
          break;
        case 'Health Metrics':
          description.textContent = 'Health indicators for beneficiaries';
          break;
        case 'Project Overview':
          description.textContent = 'Comprehensive project status and metrics';
          break;
        case 'Centre Comparison':
          description.textContent = 'Comparative analysis across centres';
          break;
        case 'Resource Allocation':
          description.textContent = 'Track and optimize resource distribution';
          break;
        case 'Government Reports':
          description.textContent = 'Reports required by government authorities';
          break;
        case 'Assam Student Data':
          description.textContent = 'Student data collection for Assam Government';
          break;
        default:
          description.textContent = 'View and analyze data';
      }
      
      const viewButton = document.createElement('button');
      viewButton.className = 'text-pratham-blue hover:underline flex items-center text-sm';
      viewButton.innerHTML = `
        View Report
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      `;
      
      card.appendChild(title);
      card.appendChild(description);
      card.appendChild(viewButton);
      reportsGrid.appendChild(card);
    });
  }
  
  // Render settings
  function renderSettings() {
    const categories = userRoles[currentUserRole].settingsCategories;
    settingsCategories.innerHTML = '';
    
    categories.forEach(category => {
      const button = document.createElement('button');
      button.className = 'px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors';
      button.textContent = category;
      settingsCategories.appendChild(button);
    });
  }
  
  // Demo login functionality
  homeButton.addEventListener('click', () => {
    window.location.href = 'index.html';
  });
  
  logoutButton.addEventListener('click', () => {
    // Simulate logout
    window.location.href = 'index.html';
  });