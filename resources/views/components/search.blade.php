@props(['placeholder' => 'Search...', 'value' => request('search')])

<div>
    <form class="flex max-w-sm mx-auto" method="GET" id="search-form">   
        <label for="search" class="sr-only">Search</label>
        <div class="relative w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>                  
            </div>
            <input type="text" name="search" id="search" value="{{ $value }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $placeholder }}" />
        </div>
        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
        </button>
        <button type="button" class="p-2.5 ms-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg border border-gray-300 hover:bg-gray-300 focus:ring-1 focus:outline-none focus:ring-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" id="clear-search">
            Clear
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search');
        const clearButton = document.getElementById('clear-search');

        clearButton.addEventListener('click', function () {
            searchInput.value = '';
            searchForm.submit();
        });
    });
</script>
