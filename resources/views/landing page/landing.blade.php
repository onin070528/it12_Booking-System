<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking System</title>


<!-- SEO -->
<meta name="description" content="A fast and secure booking system built with Laravel.">


<!-- OpenGraph -->
<meta property="og:title" content="Booking System">
<meta property="og:description" content="Book smarter. Manage faster.">

<script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-50 text-gray-800">


    <!-- HERO -->
        <section class="text-white py-20" style="background: linear-gradient(to right, #93BFC7, #a8cdd4);">
            <div class="max-w-6xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Smart & Reliable Booking System</h1>
            <p class="text-lg mb-8">Manage reservations efficiently with a secure Laravel-powered platform.</p>
            <a href="{{ route('login') }}" class="bg-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition" style="color: #93BFC7;">Get Started</a>
            </div>
        </section>


    <!-- EVENT GALLERY BANNERS -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-8 text-center">Our Event Styles</h2>
            <p class="text-center text-gray-600 mb-12">Explore different event styles and themes we offer</p>
            
            <div class="space-y-6">
                <!-- Wedding Banner -->
                <div class="relative overflow-hidden rounded-lg shadow-lg group h-48 md:h-64">
                    <img src="{{ asset('img/landing_wedd.jpg') }}" 
                         alt="Wedding" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30 group-hover:from-black/60 group-hover:to-black/20 transition-all duration-300"></div>
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="text-white max-w-2xl">
                            <h3 class="text-3xl md:text-4xl font-bold mb-2 text-rose-300">Wedding</h3>
                            <p class="text-base md:text-lg">Elegant and romantic designs to make your special day unforgettable. From classic to modern themes.</p>
                        </div>
                    </div>
                </div>

                <!-- Birthday Banner -->
                <div class="relative overflow-hidden rounded-lg shadow-lg group h-48 md:h-64">
                    <img src="{{ asset('img/landing_birth.jpg') }}" 
                         alt="Birthday Party" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30 group-hover:from-black/60 group-hover:to-black/20 transition-all duration-300"></div>
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="text-white max-w-2xl">
                            <h3 class="text-3xl md:text-4xl font-bold mb-2 text-orange-300">Birthday</h3>
                            <p class="text-base md:text-lg">Fun and vibrant party setups for all ages. Customizable themes from kids to milestone celebrations.</p>
                        </div>
                    </div>
                </div>

                <!-- Debut Banner -->
                <div class="relative overflow-hidden rounded-lg shadow-lg group h-48 md:h-64">
                    <img src="{{ asset('img/landing_debut.jpg') }}" 
                         alt="Debut Celebration" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30 group-hover:from-black/60 group-hover:to-black/20 transition-all duration-300"></div>
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="text-white max-w-2xl">
                            <h3 class="text-3xl md:text-4xl font-bold mb-2 text-purple-300">Debut</h3>
                            <p class="text-base md:text-lg">Sophisticated and memorable 18th birthday celebrations with elegant decorations and stunning setups.</p>
                        </div>
                    </div>
                </div>

                <!-- Pageant Banner -->
                <div class="relative overflow-hidden rounded-lg shadow-lg group h-48 md:h-64">
                    <img src="{{ asset('img/landing_pageant.jpg') }}" 
                         alt="Pageant Event" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30 group-hover:from-black/60 group-hover:to-black/20 transition-all duration-300"></div>
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="text-white max-w-2xl">
                            <h3 class="text-3xl md:text-4xl font-bold mb-2 text-blue-300">Pageant</h3>
                            <p class="text-base md:text-lg">Glamorous stage designs and professional setups for beauty pageants and competitions.</p>
                        </div>
                    </div>
                </div>

                <!-- Corporate Event Banner -->
                <div class="relative overflow-hidden rounded-lg shadow-lg group h-48 md:h-64">
                    <img src="{{ asset('img/landing_cor.jpg') }}" 
                         alt="Corporate Event" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30 group-hover:from-black/60 group-hover:to-black/20 transition-all duration-300"></div>
                    <div class="absolute inset-0 flex items-center px-8 md:px-16">
                        <div class="text-white max-w-2xl">
                            <h3 class="text-3xl md:text-4xl font-bold mb-2 text-slate-300">Corporate Event</h3>
                            <p class="text-base md:text-lg">Professional and polished setups for conferences, seminars, product launches, and company gatherings.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CONTACT INFORMATION -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-8 text-center">Contact Us</h2>
            
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <!-- Facebook -->
                <div class="p-6 bg-gray-50 rounded-xl shadow">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <h3 class="font-semibold text-lg mb-2">Facebook</h3>
                    <a href="https://www.facebook.com/rollyjagonob" target="_blank" class="text-blue-600 hover:underline">Rolly Jagonob</a>
                </div>

                <!-- Email -->
                <div class="p-6 bg-gray-50 rounded-xl shadow">
                    <svg class="w-12 h-12 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="font-semibold text-lg mb-2">Email</h3>
                    <a href="mailto:rollyjagonob31@gmail.com" class="text-teal-600 hover:underline">rollyjagonob31@gmail.com</a>
                </div>

                <!-- Phone -->
                <div class="p-6 bg-gray-50 rounded-xl shadow">
                    <svg class="w-12 h-12 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <h3 class="font-semibold text-lg mb-2">Phone</h3>
                    <a href="tel:09077258153" class="text-teal-600 hover:underline">09077258153</a>
                </div>
            </div>
        </div>
    </section>

</body>
</html>