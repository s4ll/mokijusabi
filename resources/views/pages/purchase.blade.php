<x-layout>
    <div class="flex justify-end mb-6">
        <x-profile/>
    </div>
    <div class="flex justify-between mb-3">
        <div class="flex justify-start space-x-1">
            @if (auth()->user()->role != 'admin')
            <a href="{{ route('purchase.create') }}">
                <button class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Create Purchase
                </button>
            </a>
            @endif

            <!-- Dropdown Excel -->
            <button id="dropdownExcel" data-dropdown-toggle="excel" class="text-white bg-green-900 hover:bg-green-950 focus:ring-2 focus:outline-none focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center" type="button">
                Export (.xlsx)
            </button>
            <div id="excel" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownExcel">
                    <li>
                        <a href="{{ route('purchase.export' , ['filter_by' => 'all']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">All</a>
                    </li>
                    <li>
                        <a href="{{ route('purchase.export' , ['filter_by' => 'daily']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Daily</a>
                    </li>
                    <li>
                        <a href="{{ route('purchase.export' , ['filter_by' => 'weekly']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Weekly</a>
                    </li>
                    <li>
                        <a href="{{ route('purchase.export' , ['filter_by' => 'monthly']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Monthly</a>
                    </li>
                </ul>
            </div>
            
            <!-- Dropdown Sort -->
            <button id="sort" data-dropdown-toggle="dropdown" class="text-white bg-green-900 hover:bg-green-950 focus:ring-2 focus:outline-none focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center" type="button">
                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3"/>
                </svg>
            </button>
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="sort">
                    <li>
                        <a href="{{ route('purchase.index', ['filter_by' => 'daily']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Daily</a>
                    </li>
                    <li>
                        <a href="{{ route('purchase.index', ['filter_by' => 'weekly']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Weekly</a>
                    </li>
                    <li>
                        <a href="{{ route('purchase.index', ['filter_by' => 'monthly']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Monthly</a>
                    </li>
                </ul>
            </div>
    
        </div>
        <div class="justify-end">
            <x-search/>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Customer Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Sale Date
                            <a href="{{ route('purchase.index', ['sort_by' => 'sale_date', 'order' => request('order') == 'desc' ? 'asc' : 'desc']) }}">
                                <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z" />
                                </svg>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Total Price
                            <a href="{{ route('purchase.index', ['sort_by' => 'total_price', 'order' => request('order') == 'desc' ? 'asc' : 'desc']) }}">
                                <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z" />
                                </svg>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Created By
                            <a href="{{ route('purchase.index', ['sort_by' => 'created_by', 'order' => request('order') == 'desc' ? 'asc' : 'desc']) }}">
                                <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z" />
                                </svg>
                            </a>
                        </div>
                    </th>                    
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($purchases->isEmpty())
                        <tr class="bg-white  dark:bg-gray-800">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No purchase
                            </td>
                        </tr>
                @else
                @foreach ($purchases as $index => $purchase)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration + ($purchases->currentPage() - 1) * $purchases->perPage() }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $purchase->customer->name ?? 'non-member' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $purchase->created_at->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4">
                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $purchase->user->name }}
                        </td>
                        <td class="px-6 py-4 text-right flex space-x-2">
                            <x-purchase.receipt-modal :purchaseId="$purchase->id" :purchase="$purchase"/>
                            <a href="{{ route('purchase.downloadReceipt' , $purchase->id) }}" target="_blank">
                            <button type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M13 11.15V4a1 1 0 1 0-2 0v7.15L8.78 8.374a1 1 0 1 0-1.56 1.25l4 5a1 1 0 0 0 1.56 0l4-5a1 1 0 1 0-1.56-1.25L13 11.15Z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M9.657 15.874 7.358 13H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.358l-2.3 2.874a3 3 0 0 1-4.685 0ZM17 16a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd"/>
                                  </svg>
                            </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-4">
        <x-pagination :paginator="$purchases"/>
    </div>
</x-layout>
