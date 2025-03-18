<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment Booking</title>
  <!-- Include Tailwind CSS from CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
    }
    
    .bg-pink-gradient {
      background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
    }
    
    .header-gradient {
      background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
      padding: 2rem 1rem;
      border-radius: 1rem 1rem 0 0;
      margin: -2rem -2rem 2rem -2rem;
    }
    
    .date-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(236, 72, 153, 0.1);
    }
    
    .date-selected {
      background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
    }
    
    .time-selected {
      background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
    }
    
    .input-focus:focus {
      box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.25);
    }
    
    .input-error {
      border-color: #ef4444;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
    }
    
    .btn-pink {
      background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
      box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
      transition: all 0.3s ease;
    }
    
    .btn-pink:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(236, 72, 153, 0.3);
    }
    
    .btn-pink:active {
      transform: translateY(0);
    }
    
    .form-container {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    
    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
      animation: fadeIn 0.5s ease forwards;
    }
    
    /* Custom scrollbar for time slots */
    .time-slots-container::-webkit-scrollbar {
      width: 6px;
    }
    
    .time-slots-container::-webkit-scrollbar-track {
      background: #f9fafb;
      border-radius: 10px;
    }
    
    .time-slots-container::-webkit-scrollbar-thumb {
      background: #fce7f3;
      border-radius: 10px;
    }
    
    .time-slots-container::-webkit-scrollbar-thumb:hover {
      background: #fbcfe8;
    }
    
    /* For modal transition */
    .modal-transition {
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    .modal-active {
      opacity: 1;
      transform: scale(1);
    }
    
    .modal-inactive {
      opacity: 0;
      transform: scale(0.95);
    }
    
    /* Error message animation */
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .error-shake {
      animation: shake 0.6s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-b from-white to-pink-50">
    <div class="bg-white rounded-2xl form-container p-8 w-full max-w-lg animate-fadeIn overflow-hidden relative">
      <div class="header-gradient text-center">
        <h1 class="text-3xl font-bold text-white">Book Your Appointment</h1>
        <p class="text-white text-opacity-90 mt-2">Select a date and time that works for you</p>
      </div>
      
      <form id="appointmentForm" class="space-y-6">
        <!-- Date Selection -->
        <div class="bg-gray-50 p-5 rounded-xl">
          <label class="block text-gray-700 font-medium mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            Select Date <span class="text-pink-500 ml-1">*</span>
          </label>
          <div class="mb-4">
            <div class="flex justify-between items-center mb-4">
              <button type="button" id="prevMonth" class="text-gray-600 hover:text-pink-500 focus:outline-none p-2 rounded-full hover:bg-pink-50 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              <h2 id="currentMonth" class="text-lg font-semibold text-gray-800"></h2>
              <button type="button" id="nextMonth" class="text-gray-600 hover:text-pink-500 focus:outline-none p-2 rounded-full hover:bg-pink-50 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center mb-2">
              <div class="text-xs font-medium text-gray-500">Su</div>
              <div class="text-xs font-medium text-gray-500">Mo</div>
              <div class="text-xs font-medium text-gray-500">Tu</div>
              <div class="text-xs font-medium text-gray-500">We</div>
              <div class="text-xs font-medium text-gray-500">Th</div>
              <div class="text-xs font-medium text-gray-500">Fr</div>
              <div class="text-xs font-medium text-gray-500">Sa</div>
            </div>
            <div id="calendarDays" class="grid grid-cols-7 gap-1 mt-1"></div>
          </div>
        </div>

        <!-- Time Selection -->
        <div class="bg-gray-50 p-5 rounded-xl">
          <label class="block text-gray-700 font-medium mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            Select Time <span class="text-pink-500 ml-1">*</span>
          </label>
          <div class="time-slots-container max-h-52 overflow-y-auto pr-2">
            <div id="timeSlots" class="grid grid-cols-3 gap-2"></div>
          </div>
          <input type="hidden" id="selectedTime" name="selectedTime">
        </div>

        <!-- Contact Information -->
        <div class="bg-gray-50 p-5 rounded-xl">
          <label class="block text-gray-700 font-medium mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            Your Information <span class="text-pink-500 ml-1">*</span>
          </label>
          
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label for="firstName" class="block text-gray-600 text-sm mb-1">First Name <span class="text-pink-500">*</span></label>
              <input type="text" id="firstName" name="firstName" required 
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-pink-500 input-focus transition duration-300">
            </div>
            <div>
              <label for="lastName" class="block text-gray-600 text-sm mb-1">Last Name <span class="text-pink-500">*</span></label>
              <input type="text" id="lastName" name="lastName" required 
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-pink-500 input-focus transition duration-300">
            </div>
          </div>

          <div class="mb-4">
            <label for="email" class="block text-gray-600 text-sm mb-1">Email <span class="text-pink-500">*</span></label>
            <input type="email" id="email" name="email" required 
              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-pink-500 input-focus transition duration-300">
            <div id="emailError" class="hidden mt-1 text-sm text-red-500"></div>
          </div>

          <div>
            <label for="phone" class="block text-gray-600 text-sm mb-1">Phone Number <span class="text-pink-500">*</span></label>
            <input type="tel" id="phone" name="phone" required 
              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-pink-500 input-focus transition duration-300">
          </div>
        </div>

        <!-- Service Selection -->
        <div class="bg-gray-50 p-5 rounded-xl">
          <label for="service" class="block text-gray-700 font-medium mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
            </svg>
            Select Service <span class="text-pink-500 ml-1">*</span>
          </label>
          <select id="service" name="service" required 
            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-pink-500 input-focus appearance-none bg-white transition duration-300">
            <option value="">Select a service</option>
            <option value="rebranding">Rebranding</option>
            <option value="agent">Connect with an agent</option>
          </select>
        </div>

        <button type="submit" class="w-full btn-pink text-white py-3 px-4 rounded-xl font-medium transition duration-300 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Book Appointment
        </button>
      </form>

      <!-- Confirmation Modal -->
      <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="modal-transition modal-inactive bg-white rounded-2xl p-8 max-w-md w-full mx-4">
          <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-pink-100 text-pink-500 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Appointment Confirmed!</h2>
            <p class="text-gray-600 mt-2">Thank you for booking with us.</p>
          </div>
          
          <div id="confirmationDetails" class="bg-gray-50 p-5 rounded-xl mb-6 text-gray-700"></div>
          
          <div class="flex justify-center">
            <button id="closeModal" class="btn-pink text-white py-3 px-8 rounded-xl font-medium transition duration-300 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize date picker
      const today = moment();
      let currentMonth = moment();
      const selectedDate = { day: null, month: null, year: null };
      const selectedTimeSlot = { time: null };
      
      // Elements
      const currentMonthEl = document.getElementById('currentMonth');
      const calendarDaysEl = document.getElementById('calendarDays');
      const timeSlotsEl = document.getElementById('timeSlots');
      const prevMonthBtn = document.getElementById('prevMonth');
      const nextMonthBtn = document.getElementById('nextMonth');
      const appointmentForm = document.getElementById('appointmentForm');
      const confirmationModal = document.getElementById('confirmationModal');
      const confirmationDetails = document.getElementById('confirmationDetails');
      const closeModalBtn = document.getElementById('closeModal');
      const modalContent = document.querySelector('.modal-transition');
      const emailInput = document.getElementById('email');
      const emailError = document.getElementById('emailError');
      
      // Email validation function
      function validateEmail(email) {
        // Complex email regex pattern for thorough validation
        const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        // Basic check for common fake domains
        const fakeDomains = ['example.com', 'test.com', 'fake.com', 'mailinator.com', 'tempmail.com', 'yopmail.com'];
        
        if (!emailRegex.test(email)) {
          return { valid: false, message: 'Please enter a valid email address.' };
        }
        
        const domain = email.substring(email.lastIndexOf('@') + 1);
        if (fakeDomains.includes(domain)) {
          return { valid: false, message: 'Please use a real email address.' };
        }
        
        // Check for reasonable domain structure
        const domainParts = domain.split('.');
        if (domainParts.length < 2 || domainParts[domainParts.length - 1].length < 2) {
          return { valid: false, message: 'Please use a valid domain in your email.' };
        }
        
        return { valid: true, message: '' };
      }
      
      // Email input blur event for validation
      emailInput.addEventListener('blur', function() {
        if (emailInput.value.trim() !== '') {
          const validation = validateEmail(emailInput.value);
          if (!validation.valid) {
            emailInput.classList.add('input-error');
            emailError.textContent = validation.message;
            emailError.classList.remove('hidden');
            emailInput.classList.add('error-shake');
            setTimeout(() => {
              emailInput.classList.remove('error-shake');
            }, 600);
          } else {
            emailInput.classList.remove('input-error');
            emailError.classList.add('hidden');
          }
        }
      });
      
      // Email input focus event to clear error
      emailInput.addEventListener('focus', function() {
        emailInput.classList.remove('input-error');
        emailError.classList.add('hidden');
      });
      
      // Render calendar
      function renderCalendar() {
        currentMonthEl.textContent = currentMonth.format('MMMM YYYY');
        calendarDaysEl.innerHTML = '';
        
        const firstDay = moment(currentMonth).startOf('month');
        const lastDay = moment(currentMonth).endOf('month');
        const daysInMonth = lastDay.date();
        
        // Add empty cells for days before the first day of the month
        const firstDayOfWeek = firstDay.day(); // 0 = Sunday, 6 = Saturday
        for (let i = 0; i < firstDayOfWeek; i++) {
          const emptyDay = document.createElement('div');
          calendarDaysEl.appendChild(emptyDay);
        }
        
        // Add days of the month
        for (let i = 1; i <= daysInMonth; i++) {
          const dayEl = document.createElement('div');
          dayEl.classList.add('h-10', 'flex', 'items-center', 'justify-center', 'rounded-lg', 'cursor-pointer', 'text-sm', 'transition', 'duration-300', 'date-hover');
          
          // Disable past dates
          const currentDate = moment(currentMonth).date(i);
          if (currentDate.isBefore(today, 'day')) {
            dayEl.classList.add('text-gray-300', 'cursor-not-allowed');
            dayEl.textContent = i;
          } else {
            dayEl.classList.add('hover:bg-pink-100');
            dayEl.textContent = i;
            
            // Check if this day is selected
            if (selectedDate.day === i && 
                selectedDate.month === currentMonth.month() && 
                selectedDate.year === currentMonth.year()) {
              dayEl.classList.add('date-selected');
            }
            
            // Add click event to select date
            dayEl.addEventListener('click', function() {
              // Remove selected class from previous selection
              const prevSelected = document.querySelector('.date-selected');
              if (prevSelected) {
                prevSelected.classList.remove('date-selected');
              }
              
              // Add selected class to current selection
              dayEl.classList.add('date-selected');
              
              // Update selected date
              selectedDate.day = i;
              selectedDate.month = currentMonth.month();
              selectedDate.year = currentMonth.year();
              
              // Generate time slots for the selected date
              generateTimeSlots();
            });
          }
          
          calendarDaysEl.appendChild(dayEl);
        }
      }
      
      // Generate time slots
      function generateTimeSlots() {
        timeSlotsEl.innerHTML = '';
        
        // Create time slots from 9 AM to 5 PM
        const timeSlots = ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];
        
        timeSlots.forEach(time => {
          const timeEl = document.createElement('div');
          timeEl.classList.add('border', 'border-gray-200', 'rounded-lg', 'py-2', 'text-center', 'cursor-pointer', 'text-sm', 'transition', 'duration-300', 'date-hover');
          timeEl.textContent = time;
          
          // Check if this time is selected
          if (selectedTimeSlot.time === time) {
            timeEl.classList.add('time-selected');
          }
          
          // Add click event to select time
          timeEl.addEventListener('click', function() {
            // Remove selected class from previous selection
            const prevSelected = document.querySelector('.time-selected');
            if (prevSelected) {
              prevSelected.classList.remove('time-selected');
            }
            
            // Add selected class to current selection
            timeEl.classList.add('time-selected');
            
            // Update selected time
            selectedTimeSlot.time = time;
            document.getElementById('selectedTime').value = time;
          });
          
          timeSlotsEl.appendChild(timeEl);
        });
      }
      
      // Event listeners for month navigation
      prevMonthBtn.addEventListener('click', function() {
        currentMonth = moment(currentMonth).subtract(1, 'month');
        renderCalendar();
      });
      
      nextMonthBtn.addEventListener('click', function() {
        currentMonth = moment(currentMonth).add(1, 'month');
        renderCalendar();
      });
      
      // Form submission
      appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate if date and time are selected
        if (!selectedDate.day || !selectedTimeSlot.time) {
          alert('Please select both a date and time for your appointment.');
          return;
        }
        
        // Validate email
        const emailValidation = validateEmail(emailInput.value);
        if (!emailValidation.valid) {
          emailInput.classList.add('input-error');
          emailError.textContent = emailValidation.message;
          emailError.classList.remove('hidden');
          emailInput.classList.add('error-shake');
          setTimeout(() => {
            emailInput.classList.remove('error-shake');
          }, 600);
          emailInput.focus();
          return;
        }
        
        // Get form data
        const formData = new FormData(appointmentForm);
        const firstName = formData.get('firstName');
        const lastName = formData.get('lastName');
        const email = formData.get('email');
        const phone = formData.get('phone');
        const service = formData.get('service');
        const serviceText = appointmentForm.querySelector('#service option:checked').textContent;
        
        // Format selected date
        const formattedDate = moment(new Date(selectedDate.year, selectedDate.month, selectedDate.day)).format('dddd, MMMM D, YYYY');
        
        // Show confirmation
        confirmationDetails.innerHTML = `
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="font-medium">Name:</span>
              <span>${firstName} ${lastName}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Service:</span>
              <span>${serviceText}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Date:</span>
              <span>${formattedDate}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Time:</span>
              <span>${selectedTimeSlot.time}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Email:</span>
              <span>${email}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Phone:</span>
              <span>${phone}</span>
            </div>
          </div>
        `;
        
        confirmationModal.classList.remove('hidden');
        setTimeout(() => {
          modalContent.classList.remove('modal-inactive');
          modalContent.classList.add('modal-active');
        }, 10);
      });
      
      // Close modal
      closeModalBtn.addEventListener('click', function() {
        modalContent.classList.remove('modal-active');
        modalContent.classList.add('modal-inactive');
        
        setTimeout(() => {
          confirmationModal.classList.add('hidden');
          appointmentForm.reset();
          
          // Reset selected date and time
          selectedDate.day = null;
          selectedDate.month = null;
          selectedDate.year = null;
          selectedTimeSlot.time = null;
          
          // Reset UI
          const dateSelected = document.querySelector('.date-selected');
          if (dateSelected) {
            dateSelected.classList.remove('date-selected');
          }
          
          const timeSelected = document.querySelector('.time-selected');
          if (timeSelected) {
            timeSelected.classList.remove('time-selected');
          }
          
          // Reset calendar to current month
          currentMonth = moment();
          renderCalendar();
          timeSlotsEl.innerHTML = '';
        }, 300);
      });
      
      // Initialize calendar with current month
      renderCalendar();
    });
  </script>
</body>
</html>