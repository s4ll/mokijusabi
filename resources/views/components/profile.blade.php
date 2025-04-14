<div>
    <button data-popover-target="popover-click" data-popover-trigger="click" type="button">
        <div class="flex space-x-2 items-center">
            <div class="text-right space-y-[-3px]">
                <h1 class="font-semibold text-[17px]">{{ Auth::user()->name }}</h1>
                <h1 class="font-normal text-[15px]">{{ Auth::user()->role }} role</h1>
            </div>
            <div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </button>
    <div data-popover id="popover-click" role="tooltip"
        class="absolute left-0 ml-4 z-10 invisible inline-block w-40 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
        <a href="{{ route('logout') }}">
            <div
                class="py-2 px-4 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Logout</h3>
            </div>
        </a>
    </div>
</div>
