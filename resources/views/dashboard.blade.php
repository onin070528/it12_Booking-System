<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJ's Event Styling Dashboard</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">

             <!-- Header -->
            @include('layouts.header')
                    
            <div class="flex justify-end mb-6">
                <a href="{{ route('booking.create') }}"
                    class="bg-[#5394D0] text-white px-6 py-2.5 rounded-md shadow hover:bg-[#3e78a9] transition inline-block">
                    Book Now!
                </a>
            </div>

            <!-- Event Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-8 justify-center max-w-7xl mx-auto">

                <!-- Wedding Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="/img/wedding.jpg" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110" alt="Wedding Event">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-2xl font-bold text-[#93BFC7]">Wedding</h5>
                        </div>
                        <p class="text-gray-600 mt-2 text-base leading-relaxed mb-5">
                            Elegant styling and complete packages for your dream wedding.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('booking.create') }}"
                               class="flex-1 bg-gradient-to-r from-[#5394D0] to-[#3e78a9] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:from-[#3e78a9] hover:to-[#2d5a7a] transition-all duration-300 shadow-md hover:shadow-lg text-center transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>Book Now
                            </a>
                            <a onclick="openModal('/img/wedding.jpg', 'Wedding', 'Elegant styling and complete packages for your dream wedding.')"
                               class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-200">
                                <i class="fas fa-eye mr-2"></i>Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Birthday Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="/img/birthday.jpg" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110" alt="Birthday Event">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-2xl font-bold text-[#93BFC7]">Birthday</h5>
                        </div>
                        <p class="text-gray-600 mt-2 text-base leading-relaxed mb-5">
                            Colorful and themed setups perfect for any birthday celebration.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('booking.create') }}"
                               class="flex-1 bg-gradient-to-r from-[#5394D0] to-[#3e78a9] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:from-[#3e78a9] hover:to-[#2d5a7a] transition-all duration-300 shadow-md hover:shadow-lg text-center transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>Book Now
                            </a>
                            <a onclick="openModal('/img/birthday.jpg', 'Birthday', 'Colorful and themed setups perfect for any birthday celebration.')"
                               class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-200">
                                <i class="fas fa-eye mr-2"></i>Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pageant Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="/img/pageant.jpg" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110" alt="Pageant Event">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-2xl font-bold text-[#93BFC7]">Pageant</h5>
                        </div>
                        <p class="text-gray-600 mt-2 text-base leading-relaxed mb-5">
                            Stylish backdrops and stage aesthetics designed for pageants.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('booking.create') }}"
                               class="flex-1 bg-gradient-to-r from-[#5394D0] to-[#3e78a9] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:from-[#3e78a9] hover:to-[#2d5a7a] transition-all duration-300 shadow-md hover:shadow-lg text-center transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>Book Now
                            </a>
                            <a onclick="openModal('/img/pageant.jpg', 'Pageant', 'Stylish backdrops and stage aesthetics designed for pageants.')"
                               class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-200">
                                <i class="fas fa-eye mr-2"></i>Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Christening Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="/img/christening.jpg" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110" alt="Christening Event">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-2xl font-bold text-[#93BFC7]">Christening</h5>
                        </div>
                        <p class="text-gray-600 mt-2 text-base leading-relaxed mb-5">
                            Soft tones and charming designs perfect for christening events.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('booking.create') }}"
                               class="flex-1 bg-gradient-to-r from-[#5394D0] to-[#3e78a9] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:from-[#3e78a9] hover:to-[#2d5a7a] transition-all duration-300 shadow-md hover:shadow-lg text-center transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>Book Now
                            </a>
                            <a onclick="openModal('/img/christening.jpg', 'Christening', 'Soft tones and charming designs perfect for christening events.')"
                               class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-200">
                                <i class="fas fa-eye mr-2"></i>Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Debut Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="/img/debut.jpg" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110" alt="Debut Event">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-2xl font-bold text-[#93BFC7]">Debut</h5>
                        </div>
                        <p class="text-gray-600 mt-2 text-base leading-relaxed mb-5">
                            Elegant and sophisticated styling for your memorable 18th birthday debut celebration.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('booking.create') }}"
                               class="flex-1 bg-gradient-to-r from-[#5394D0] to-[#3e78a9] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:from-[#3e78a9] hover:to-[#2d5a7a] transition-all duration-300 shadow-md hover:shadow-lg text-center transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>Book Now
                            </a>
                            <a onclick="openModal('/img/debut.jpg', 'Debut', 'Elegant and sophisticated styling for your memorable 18th birthday debut celebration.')"
                               class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-200">
                                <i class="fas fa-eye mr-2"></i>Details
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Bottom Blue Border -->
    <div class="w-full h-1 bg-[#93BFC7]"></div>


    <!-- ===================== MODAL ===================== -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">

        <!-- Modal Box -->
        <div class="bg-white rounded-xl shadow-xl w-[28rem] p-6 relative">

            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                ✖
            </button>

            <!-- Modal Content -->
            <img id="modalImage" class="w-full h-48 object-cover rounded-lg" src="" alt="">
            <h3 id="modalTitle" class="text-2xl font-bold text-[#93BFC7] mt-4"></h3>
            <p id="modalDescription" class="text-gray-600 mt-2"></p>
        </div>

    </div>

    <!-- ===================== JAVASCRIPT ===================== -->
    <script>
        function openModal(image, title, description) {
            document.getElementById("modalImage").src = image;
            document.getElementById("modalTitle").textContent = title;
            document.getElementById("modalDescription").textContent = description;

            document.getElementById("detailsModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("detailsModal").classList.add("hidden");
        }
    </script>

</body>
</html>
