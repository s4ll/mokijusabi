@props(['purchaseId' , 'purchase'])
<div>
    <button data-modal-target="receipt--{{ $purchaseId }}" data-modal-toggle="receipt--{{ $purchaseId }}" type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Receipt</button>
    <div id="receipt--{{ $purchaseId }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Receipt details
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="receipt--{{ $purchaseId }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="space-y-4 text-left p-4">
                    <div>
                        <p>Created by : {{ $purchase->user->name }}</p>
                        <p>Date : {{ $purchase->created_at->format('d-m-Y') }}</p>
                    </div>
                    <hr class="text-gray-400">
                    <div class="grid grid-cols-2 gap-2">
                        <p>Member Status : {{ $purchase->customer_id ? 'Member' : 'Non-member' }} </p>
                        <p>Phone : {{ $purchase->customer->phone ?? '-' }}</p>
                        <p>Member Point : {{ $purchase->customer->points ?? '-'}} </p>
                        <p>joined since : {{ $purchase->customer_id ? $purchase->created_at->format('d-m-y') : '-' }} </p>
                    </div>
            
                    <div class="relative max-h-[50vh] overflow-y-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-s-lg">Product name</th>
                                    <th scope="col" class="px-6 py-3">Qty</th>
                                    <th scope="col" class="px-6 py-3 rounded-e-lg">Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @foreach ($purchase->purchaseProducts as $item)
                                <tr>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $item->product->name }}
                                    </th>
                                    <td class="px-6 py-4">{{ $item->qty }}</td>
                                    <td class="px-6 py-4">
                                        {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot class="sticky bottom-0 bg-white z-10">
                                <tr class="font-semibold text-gray-900 dark:text-white">
                                    <th scope="row" class="px-6 py-3 text-base">Total</th>
                                    <td class="px-6 py-3">
                                        {{ $purchase->purchaseProducts->sum('qty') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>