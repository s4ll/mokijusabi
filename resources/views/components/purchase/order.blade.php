<x-layout>
    <div>
        <div class="relative w-full max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Confirm Order
                    </h3>
                </div>

                <form class="p-4 md:p-5" method="POST" action="{{ route('purchase.orderStore') }}">
                    @csrf
                    @if ($cart['is_member'])
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Member Name</label>
                            <input type="text" name="member_name" value="{{ $customer->name }}" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    @endif

                    <div class="mb-4 text-sm text-gray-800 dark:text-gray-200">
                        <p><strong>Total Price:</strong> Rp {{ number_format($cart['total_price'], 0, ',', '.') }}</p>
                        <p><strong>Points Earned:</strong> +{{ $earnedPoints }}</p>

                        @if ($canUsePoints)
                            <input type="hidden" name="used_points" id="used_points_input" value="0">
                            <div class="flex items-center mt-2">
                                <input id="used_points_checkbox" name="used_points_checkbox" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600"
                                    onchange="toggleUsePoints(this)">
                                <label for="used_points_checkbox" class="ms-2 text-sm font-medium">Use my point ({{ $totalAvailablePoints }})</label>
                            </div>
                        @else
                            @if ($cart['is_member'])
                                <p class="mt-2 text-red-600 text-sm">
                                    *Tidak dapat menggunakan point karena ini adalah pembelian pertama anda.
                                </p>
                            @else
                                <p class="mt-2 text-red-600 text-sm">
                                    *Tidak dapat menggunakan point karena anda bukan member.
                                </p>
                            @endif
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="payment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment</label>
                        <input type="text" name="total_payment" id="payment"
                            value="Rp {{ number_format($cart['payment'], 0, ',', '.') }}"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white"
                            disabled>
                    </div>

                    <div class="overflow-y-auto max-h-[50vh] relative mb-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3">Qty</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @foreach ($cart['products'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</td>
                                        <td class="px-6 py-4">{{ $item['qty'] }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 bg-white z-10">
                                <tr class="font-semibold text-gray-900 dark:text-white">
                                    <td class="px-6 py-3 text-base" colspan="3">Sub Total</td>
                                    <td class="px-6 py-3">Rp {{ number_format($cart['total_price'], 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-between mt-5">
                        <a href="{{ route('purchase.cart') }}" 
                           class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-800">
                            Back to cart
                        </a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-blue-700 cursor-pointer">
                            Confirm Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleUsePoints(checkbox) {
            const previousPoints = {{ $customer->points ?? 0 }};
            const earnedPoints = {{ $earnedPoints }};
            const totalPoints = previousPoints + earnedPoints;
            const subTotal = {{ $cart['total_price'] }};
            const paymentInput = document.getElementById('payment');
            const usedPointsInput = document.getElementById('used_points_input');
    
            if (checkbox.checked) {
                const newTotal = subTotal - totalPoints;
                paymentInput.value = "Rp " + newTotal.toLocaleString('id-ID');
                usedPointsInput.value = 1; // Kirim sinyal pakai poin
            } else {
                paymentInput.value = "Rp " + subTotal.toLocaleString('id-ID');
                usedPointsInput.value = 0; // Tidak pakai poin
            }
        }
    </script>    
</x-layout>
