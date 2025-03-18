<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#FF69B4',
                        'primary-light': '#FFC0CB',
                        'primary-dark': '#FF1493',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-5xl flex flex-col md:flex-row rounded-3xl shadow-2xl overflow-hidden">
            <!-- Form Section -->
            <div class="w-full md:w-3/5 bg-white p-8 md:p-12">
                <!-- Logo Added Here -->
                <div class="flex items-center mb-8">
                <a href="homepage.php">
                    <img src="Logo.png" alt="Company Logo" class="h-12 mr-4"></a>
                    <!-- You can replace the placeholder with your actual logo: src="path/to/your-logo.png" -->
                </div>
                
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Get in touch</h2>
                <p class="text-gray-600 mb-8">We'd love to hear from you. Fill out the form below.</p>
                
                <form class="space-y-6">
                    <div class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition" placeholder="Your name">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition" placeholder="your@email.com">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition" placeholder="How can we help?">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition resize-none" placeholder="Tell us more about your inquiry..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                        <span>Send Message</span>
                        <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
            
            <!-- Info Section -->
            <div class="w-full md:w-2/5 bg-gradient-to-br from-primary to-primary-dark p-8 md:p-12 text-white">
                <div class="h-full flex flex-col">
                    <!-- Added Logo Here Too -->
                    <div class="mb-6">
                        <a href="{{route('welcome')}}">
                        <img src="Logo.png" alt="Company Logo" class="h-10 filter brightness-0 invert"></a>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-8">Contact us</h2>
                    
                    <div class="flex-grow space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium">Address</h3>
                                <p class="mt-1">123 Main Street, Suite 21<br>New York, NY 10001</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-phone-alt text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium">Phone</h3>
                                <p class="mt-1">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium">Email</h3>
                                <p class="mt-1">info@example.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-globe text-white"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium">Website</h3>
                                <p class="mt-1">example.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <div class="flex space-x-4">
                            <a href="#" class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center transition hover:bg-opacity-30">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center transition hover:bg-opacity-30">
                                <i class="fab fa-twitter text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center transition hover:bg-opacity-30">
                                <i class="fab fa-instagram text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center transition hover:bg-opacity-30">
                                <i class="fab fa-linkedin-in text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>