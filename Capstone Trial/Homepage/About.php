<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - I AM AI Manufacturing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    
    <style>
        ** {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-pink: #ff66c4;
            --soft-pink: #fce4f0;
            --dark-text: #1a1a2e;
            --light-text: #4a4a68;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: white;
            color: var(--dark-text);
            line-height: 1.6;
        }
        
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
       
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .logo img {
            height: 40px;
            margin-right: 10px;
            border-radius: 8px;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--light-text);
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: var(--primary-pink);
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .login-btn {
            background: none;
            border: none;
            color: var(--light-text);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-btn:hover {
            color: var(--primary-pink);
        }
        
        .get-started-btn {
            background-color: var(--primary-pink);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .get-started-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(255,102,196,0.3);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Footer Styles */
        .footer {
            background-color: #1F2937;
            color: white;
            padding: 4rem 0 2rem;
        }
        
        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        
        .footer-col h4 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1.25rem;
            padding-bottom: 0.625rem;
            position: relative;
            display: inline-block;
            border-bottom: 2px solid var(--primary-pink);
        }
        
        .footer-col ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-col ul li {
            margin-bottom: 0.75rem;
        }
        
        .footer-col ul li a {
            color: #D1D5DB;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }
        
        .footer-col ul li a:hover {
            color: white;
            padding-left: 0.5rem;
        }
        
        .social-links {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background-color: #374151;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-pink);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 1.875rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .footer-bottom p {
            font-size: 0.9rem;
            color: #9CA3AF;
        }

        .certifications {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1.25rem;
        }
        
        .certification {
            background-color: #374151;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
        }
        
        @media (max-width: 1024px) {
            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 640px) {
            .footer-container {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                display: none;
            }
            
            .auth-buttons {
                gap: 0.5rem;
            }
        }
    </style>
</head>

<div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="Logo.png" alt="IAM.AI Logo">
            </div>
            
            <div class="nav-links">
                <a href="homepage.php">Home</a>
                <a href="About.php">About US</a>
                <a href="product.php">Merchandise</a>
            </div>
            
            <div class="auth-buttons">
                <a href="../login.php" class="login-btn">Log In</a>
                <a href="Appointment.php">
                <button class="get-started-btn">Book Appointment</button></a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="md:w-1/2 mb-10 md:mb-0">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">About us</h1>
                        <p class="text-lg text-gray-600 mb-6">IAM AI MANUFACTURING CORPORATION formerly known as KIRPHLAB Cosmetics Manufacturing is your leading partner for innovative development and manufacturing of Color Cosmetics & Skincare via our own in-house R&D laboratory which enables us to offer our customers with effective bespoke ranges of products. IAM AI covers the entire process, beginning from concept, product development and formulation of your products through the sourcing components, graphic design and finally filling and packaging.</p>
                        <div class="flex space-x-4">
                            <a href="Homepage/inquries.php" class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition shadow-md">Inquire Now</a>
                            <a href="Homepage/Appointment.php" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-50 transition">Book Appointment</a>
                        </div>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <div class="relative">  
                            <div class="absolute inset-0 bg-pink-300 rounded-full opacity-20 blur-xl transform -translate-x-4 translate-y-4"></div>
                            <div class="relative z-10 w-full max-w-md overflow-hidden rounded-full shadow-xl">
                                <img src="Logo2.jpg" alt="AI Manufacturing Technology" class="w-full h-full object-cover animate-float">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vision & Mission (HIGHLIGHTED SECTION) -->
        <section class="py-20 bg-gradient-to-b from-white to-pink-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-pink-100 text-pink-600 rounded-full text-sm font-semibold mb-3">OUR PURPOSE</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 relative inline-block">
                        Our Vision & Mission
                        <span class="absolute bottom-0 left-0 w-full h-1 bg-pink-300 opacity-50 rounded"></span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                    <div class="bg-white p-8 rounded-2xl shadow-lg transform transition duration-300 hover:-translate-y-2 hover:shadow-xl border-t-4 border-pink-400">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our <span class="text-pink-500">Vision</span></h3>
                        </div>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            To be the global leader in AI-powered manufacturing, driving innovation and sustainability through intelligent automation and human-machine collaboration.
                        </p>
                    </div>
                    
                    <div class="bg-white p-8 rounded-2xl shadow-lg transform transition duration-300 hover:-translate-y-2 hover:shadow-xl border-t-4 border-pink-400">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our <span class="text-pink-500">Mission</span></h3>
                        </div>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            To empower manufacturers with AI-driven insights and automation, enhancing productivity, reducing waste, and ensuring high-quality outputs through continuous innovation and collaboration.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-center mt-16">
                    <div class="w-20 h-1.5 bg-gradient-to-r from-pink-300 to-pink-500 rounded-full"></div>
                </div>
            </div>
        </section>

        <!-- CEO/Owner Section -->
        <section class="py-16 bg-pink-50">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    <div class="md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-pink-300 rounded-full opacity-20 blur-xl transform translate-x-4 translate-y-4"></div>
                            <img src="CEO.jpg" alt="CEO Portrait" class="relative z-10 w-full max-w-sm rounded-lg shadow-xl">
                        </div>
                    </div>
                    <div class="md:w-2/3">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">Meet Our Founder</h2>
                        <div class="w-20 h-1 bg-pink-500 mb-6"></div>
                        <p class="text-lg text-gray-600 mb-6">
                            Dr. Sarah Chen, with over 15 years of experience in AI research and manufacturing innovation, founded I AM AI Manufacturing with a vision to transform traditional factories into intelligent, self-optimizing production centers.
                        </p>
                        <p class="text-lg text-gray-600 mb-6">
                            "I believe that AI isn't just about automation—it's about augmentation. Our systems work alongside human experts, enhancing their capabilities and freeing them to focus on creative problem-solving and innovation. This partnership between human ingenuity and machine intelligence is the key to the future of manufacturing."
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-pink-500 hover:text-pink-600 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                                </svg>
                            </a>
                            <a href="#" class="text-pink-500 hover:text-pink-600 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                            <a href="#" class="text-pink-500 hover:text-pink-600 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Company Description -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Company</h2>
                    <div class="w-20 h-1 bg-pink-500 mx-auto mb-6"></div>
                    <p class="text-lg text-gray-600">
                        Founded in 2022, I AM AI Manufacturing has quickly established itself as a pioneer in AI-driven manufacturing solutions. We specialize in developing intelligent systems that optimize production processes, reduce waste, and increase efficiency. Our team of AI experts, engineers, and manufacturing specialists work together to create customized solutions that address the unique challenges of modern manufacturing environments.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="bg-pink-50 p-8 rounded-xl shadow-md hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-pink-500 text-white rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">AI-Powered Systems</h3>
                        <p class="text-gray-600">Our advanced AI algorithms analyze production data in real-time, identifying inefficiencies and suggesting optimizations automatically.</p>
                    </div>
                    
                    <div class="bg-pink-50 p-8 rounded-xl shadow-md hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-pink-500 text-white rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Predictive Maintenance</h3>
                        <p class="text-gray-600">Our systems predict equipment failures before they happen, reducing downtime and extending the lifespan of manufacturing assets.</p>
                    </div>
                    
                    <div class="bg-pink-50 p-8 rounded-xl shadow-md hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-pink-500 text-white rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Quality Assurance</h3>
                        <p class="text-gray-600">Our computer vision systems inspect products with superhuman accuracy, ensuring only perfect items reach your customers.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
   


    <!-- Footer -->
<footer class="bg-gray-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div>
                <h4 class="text-lg font-bold mb-6 pb-2 border-b border-pink-500 inline-block">Company</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">About Us</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Our Facilities</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Careers</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Sustainability</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold mb-6 pb-2 border-b border-pink-500 inline-block">Services</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Product Development</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Contract Manufacturing</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Private Label</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Packaging Solutions</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold mb-6 pb-2 border-b border-pink-500 inline-block">Resources</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Case Studies</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Blog</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">Industry Insights</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white hover:pl-1 transition-all">FAQ</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold mb-6 pb-2 border-b border-pink-500 inline-block">Connect With Us</h4>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-gray-700 px-3 py-1 text-sm rounded">ISO 9001</span>
                    <span class="bg-gray-700 px-3 py-1 text-sm rounded">GMP Certified</span>
                    <span class="bg-gray-700 px-3 py-1 text-sm rounded">Leaping Bunny</span>
                </div>
                
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/iamaimanufacturing" target="_blank" aria-label="Facebook" 
                       class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" aria-label="Twitter" 
                       class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" aria-label="Instagram" 
                       class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@iamaimanufacturing?refer=creator_embed" target="_blank" aria-label="TikTok" 
                       class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                </div>
                
                <!-- Newsletter subscription could be added here if needed -->
            </div>
        </div>
        
        <div class="pt-8 border-t border-gray-700 text-center">
            <p class="text-gray-400">&copy; 2025 IAM.AI Beauty Manufacturing. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>
