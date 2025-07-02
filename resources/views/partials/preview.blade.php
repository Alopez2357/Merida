<div class="grid gap-2 my-2 grid-cols-12">
    <div class="col-start-1 col-span-4 md:max-h-[15vh] xl:max-h-[15vh]">
        <!-- Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 flex flex-wrap h-auto">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Pasajeros
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    6389
                </p>
            </div>
            <div>
                <img class="w-2/3 h-auto m-auto" src="{{ asset('img/screen2.png') }}" alt="">
            </div>
        </div>
    </div>

    <div class="col-start-5 col-span-8 flex gap-2 ">
    <!-- Card -->
        <div class="flex w-1/3 items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 flex flex-wrap">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Balance
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    $ 46,760.89
                </p>
            </div>
            <div>
                <img src="{{ asset('img/screen.png') }}" alt="">
            </div>
        </div>
        <!-- Card -->
        <div class="flex w-1/3 items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 flex flex-wrap">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                <i class="fas fa-plane"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                  Vuelos
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    376
                </p>
            </div>
            <div>
                <img src="{{ asset('img/screen.png') }}" alt="">
            </div>
        </div>
        <!-- Card -->
        <div class="flex w-1/3 items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 flex flex-wrap">
            <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Ocupación
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                   35 %
                </p>
            </div>
            <div>
                <img src="{{ asset('img/screen.png') }}" alt="">
            </div>
        </div>
    </div>
</div>