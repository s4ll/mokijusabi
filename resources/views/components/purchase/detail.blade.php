<x-layout>
    <div>
        <div class="relative w-full max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Transaction Detail
                    </h3>
                </div>
                <form class="p-4 md:p-5">
                    <div class="grid gap-1 mb-4 grid-cols-2 text-gray-700 dark:text-white">
                        <p>
                            <strong class="mr-1">Invoice:</strong> {{ $purchase->receipt_code }}
                        </p>
                        <p class="text-right">
                            <strong class="mr-1">Date:</strong> {{ $purchase->created_at->format('d M Y H:i') }}
                        </p>
                        <p>
                            <strong class="mr-1">Cashier:</strong> {{ $purchase->user->name }}
                        </p>
                    </div>                                     

                    @if ($purchase->customer)
                    <div class="grid mb-4 grid-cols-2 text-gray-700 dark:text-white">
                        <p><strong>Member Name:</strong> {{ $purchase->customer->name }}</p>
                        <p><strong>Phone:</strong> {{ $purchase->customer->phone }}</p>
                        <p><strong>Member Since:</strong> {{ $purchase->customer->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Points After:</strong> {{ $purchase->customer->points }}</p>
                    </div>
                    @else
                    <p class="mb-4 text-gray-700 dark:text-white italic">Non-member transaction</p>
                    @endif

                    <div class="relative overflow-y-auto max-h-[50vh]">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-s-lg">Product name</th>
                                    <th scope="col" class="px-6 py-3">Qty</th>
                                    <th scope="col" class="px-6 py-3">Price</th>
                                    <th scope="col" class="px-6 py-3 rounded-e-lg">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @foreach ($purchase->purchaseProducts as $item)
                                    <tr>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $item->product->name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $item->qty }}
                                        </td>
                                        <td class="px-6 py-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 bg-white z-10">
                                <tr class="font-semibold text-white bg-black dark:text-white">
                                    <td scope="row" class="px-6 py-3">
                                        Used Points : {{ $purchase->used_points }}
                                    </td>
                                    <td class="px-6 py-3"></td>
                                    <td class="px-6 py-3"></td>
                                    <td scope="row" class="px-6 py-3 text-[15px]">
                                        <div class="flex flex-col justify-between">
                                            <div>
                                                Payment : Rp {{ number_format($purchase->total_payment, 0, ',', '.') }}
                                            </div> 
                                            <div>
                                                Total : Rp {{ number_format($purchase->total_price - $purchase->used_points, 0, ',', '.') }}
                                            </div>
                                            <div>
                                                Change : Rp {{ number_format($purchase->change, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-end mt-3 space-x-2">
                        <a href="{{ route('purchase.index') }}"
                            class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg shadow hover:bg-gray-300 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                            Back
                        </a>
                        <a href="{{ route('purchase.downloadReceipt' , $purchase->id) }}"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-blue-700">
                            Download Receipt
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
