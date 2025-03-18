<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM.AI - Beauty Manufacturing</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
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
        
        .footer {
            background-color: var(--dark-text);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-col {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-col h4 {
            font-size: 1.1rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-pink);
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col ul li {
            margin-bottom: 10px;
        }
        
        .footer-col ul li a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-col ul li a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-pink);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .footer-bottom p {
            font-size: 0.9rem;
            color: #ccc;
        }
        
        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }
        
        .newsletter-form input {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-radius: 4px 0 0 4px;
            outline: none;
        }
        
        .newsletter-form button {
            background-color: var(--primary-pink);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        /* Certifications */
        .certifications {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        
        .certification {
            background-color: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        @media (max-width: 1024px) {
            .hero-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .hero-image {
                text-align: center;
            }
            
            .hero-image img {
                max-width: 300px;
            }
            
            .feature-cards, 
            .process-steps {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }
            
            .process-step {
                width: 100%;
                max-width: 300px;
            }
            
            .footer-container {
                flex-direction: column;
                gap: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .feature-card {
                width: 100%;
                max-width: 350px;
            }
            
            .stat-item {
                min-width: 130px;
            }
            
            .product-categories {
                flex-direction: column;
                align-items: center;
            }
        }
        .floating-tiktok {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #000000;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 999;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .floating-tiktok:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }
        
        .floating-tiktok svg {
            width: 32px;
            height: 32px;
            fill: #ffffff;
        }
        
        .tiktok-embed-container {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 998;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 340px;
        }
        
        .tiktok-embed-container.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .close-embed {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 30px;
            height: 30px;
            background: #ff0050;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .tiktok-embed-container {
                bottom: 80px;
                right: 20px;
                max-width: calc(100vw - 40px);
            }
            
            .floating-tiktok {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
    
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="Logo.png" alt="IAM.AI Logo">
            </div>
            
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="About.php">About US</a>
                <a href="product.php">Merchandise</a>
            </div>
            
            <div class="auth-buttons">
                <a href="login.php" class="login-btn">Log In</a>
                <a href="Appointment.php">
                <button class="get-started-btn">Book Appointment</button></a>
            </div>
        </nav>
        
     

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="About.php">About Us</a></li>
                        <li><a href="#">Our Facilities</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Sustainability</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#">Product Development</a></li>
                        <li><a href="#">Contract Manufacturing</a></li>
                        <li><a href="#">Private Label</a></li>
                        <li><a href="#">Packaging Solutions</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Case Studies</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Industry Insights</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <div class="certifications">
                        <span class="certification">ISO 9001</span>
                        <span class="certification">GMP Certified</span>
                        <span class="certification">Leaping Bunny</span>
                    </div>
                    <div class="social-links">
                        <a href="https://www.facebook.com/iamaimanufacturing" target="_blank" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" target="_blank" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" target="_blank" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@iamaimanufacturing?refer=creator_embed" target="_blank" aria-label="TikTok">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 IAM.AI Beauty Manufacturing. All rights reserved.</p>
            </div>
        </div>
    </footer>

    
    

</body>

</html>
</body>
</html>