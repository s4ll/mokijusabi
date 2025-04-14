<x-layout>
    <div>
        <div class="relative w-full max-h-screen">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Shopping Cart
                    </h3>
                </div>
                <form action="{{ route('purchase.cartStore') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 sm:col-span-1">
                            <label for="is_member"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Member
                                status</label>
                            <select id="is_member" name="is_member"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                onchange="toggleMemberField(this.value)">
                                <option value="0" {{ !$cart['is_member'] ? 'selected' : '' }}>Non Member</option>
                                <option value="1" {{ $cart['is_member'] ? 'selected' : '' }}>Member</option>
                            </select>
                        </div>
                        
                        <div id="phoneField" class="col-span-2 sm:col-span-1" style="{{ !$cart['is_member'] ? 'display: none;' : '' }}">
                            <label for="phone"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="number" name="phone" id="phone" value="{{ $cart['phone'] ?? '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="08123456789">
                        </div>
                        
                        <div class="col-span-2 sm:col-span-1">
                            <label for="payment"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment</label>
                            <input type="text" name="payment" id="payment"
                                value="{{ old('payment', isset($cart['payment']) ? 'Rp. ' . number_format($cart['payment'], 0, ',', '.') : '') }}"
                                required
                                placeholder="Rp. "
                                oninput="formatRupiah(this)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>                        
                    </div>

                    <div class="relative overflow-y-auto max-h-[50vh]">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-s-lg">
                                        Product name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Qty
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 rounded-e-lg">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart['products'] as $item)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</td>
                                    <td class="px-6 py-4">{{ $item['qty'] }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 bg-gray-100 dark:bg-gray-700">
                                <tr class="font-semibold text-gray-900 dark:text-white">
                                    <td colspan="3" class="px-6 py-3 text-right">Total Price :</td>
                                    <td class="px-6 py-3">Rp {{ number_format($cart['total_price'], 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>                                                           
                        </table>
                    </div>
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('purchase.create') }}" 
                           class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-800">
                            Back to Products
                        </a>
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Proceed to Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleMemberField(value) {
            const phoneField = document.getElementById('phoneField');
            if (value === '1') {
                phoneField.style.display = 'block';
            } else {
                phoneField.style.display = 'none';
            }
        }
    </script>
    <script>
        function formatRupiah(input) {
        let angka = input.value.replace(/\D/g, '');
        let formatted = '';

        for (let i = angka.length - 1, j = 1; i >= 0; i--, j++) {
            formatted = angka[i] + formatted;
            if (j % 3 === 0 && i !== 0) {
            formatted = '.' + formatted;
            }
        }

        input.value = angka ? 'Rp. ' + formatted : '';
        }
    </script>

    <script>
        document.querySelector("form").addEventListener("submit", function (e) {
            const input = document.getElementById("payment");
            const value = input.value.replace(/\D/g, '');
            const subTotal = {{ $cart['total_price'] }};

            if (parseInt(value) < subTotal) {
                e.preventDefault();
                alert("Payment kurang dari total belanja.");
            }
        });
    </script>
</x-layout>