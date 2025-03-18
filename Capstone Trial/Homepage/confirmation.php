<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
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
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-b from-white to-pink-50">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-lg animate-fadeIn">
            <div class="header-gradient text-center p-8 rounded-t-xl -mt-8 -mx-8 mb-8">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-white text-pink-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Appointment Confirmed!</h1>
                <p class="text-white text-opacity-90 mt-2">Thank you for booking with us</p>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-xl mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Appointment Details</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="font-medium text-gray-600">Name:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="font-medium text-gray-600">Service:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($appointment['service']); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="font-medium text-gray-600">Date:</span>
                        <span class="text-gray-800"><?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="font-medium text-gray-600">Time:</span>
                        <span class="text-gray-800"><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="font-medium text-gray-600">Status:</span>
                        <span class="px-3 py-1 text-sm rounded-full <?php echo $appointment['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                            <?php echo ucfirst($appointment['status']); ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Reference #:</span>
                        <span class="text-gray-800"><?php echo str_pad($appointment['id'], 6, '0', STR_PAD_LEFT); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="text-center space-y-4">
                <p class="text-gray-600">We've sent a confirmation email to <strong><?php echo htmlspecialchars($appointment['email']); ?></strong> with all these details.</p>
                
                <p class="text-sm text-gray-500">Need to make changes? Contact us at <a href="mailto:<?php echo htmlspecialchars($settings['company_email'] ?? 'contact@example.com'); ?>" class="text-pink-500 hover:underline"><?php echo htmlspecialchars($settings['company_email'] ?? 'contact@example.com'); ?></a> or call <?php echo htmlspecialchars($settings['company_phone'] ?? '(123) 456-7890'); ?></p>
                
                <div class="pt-4">
                    <a href="index.html" class="inline-block bg-pink-gradient text-white py-3 px-6 rounded-xl font-medium transition duration-300 hover:shadow-lg">
                        Return to Home
                    </a>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['company_name'] ?? 'Your Company'); ?>. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>