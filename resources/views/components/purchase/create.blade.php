<x-layout>
    <div class="flex items-center space-x-2">
        <a href="{{ route('purchase.index') }}">
            <svg class="w-7 h-7 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
            </svg>  
        </a>
        <h1 class="text-xl font-bold">Purchase</h1>
    </div>
    <div class="mt-5">
        <x-alert.error/>
    </div>

    <form action="{{ route('purchase.createStore') }}" method="POST">
        @csrf
        <div class="w-full max-h-screen bg-white">
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($products as $product)
                    <div class="w-full max-w-xs bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <img class="p-4 mx-auto w-36 h-36 object-cover rounded-md" 
                            src="{{ asset('storage/' . $product->image) }}" 
                            alt="{{ $product->name }}" />
                        <div class="px-5 pb-5">
                            <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h1>
                            <div class="flex items-center justify-between mt-2">
                                <span class="product-price text-[18px] font-semibold text-gray-900 dark:text-white">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-sm dark:bg-blue-200 dark:text-blue-800">Stock : {{ $product->stock }}</span>
                            </div>
                            <p class="subtotal text-left text-[15px] mt-1 text-gray-900">Sub Total : Rp. 0</p>
                            <div class="flex justify-center">
                                <div class="relative flex items-center mt-4">
                                    <!-- Tombol Kurang -->
                                    <button type="button" class="decrement-button bg-gray-100 px-2 py-1 rounded-l border border-gray-300 text-lg">
                                        &minus;
                                    </button>
                            
                                    <!-- Input Quantity -->
                                    <input
                                        type="text"
                                        name="qtys[{{ $product->id }}]"
                                        class="text-center border-transparent w-12"
                                        value="0"
                                        data-max="{{ $product->stock }}"
                                        readonly
                                    />
                            
                                    <!-- Tombol Tambah -->
                                    <button type="button" class="increment-button bg-gray-100 px-2 py-1 rounded-r border border-gray-300 text-lg">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Button Checkout di Pojok Kanan Bawah -->
        <div class="fixed bottom-5 right-5">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-blue-700 cursor-pointer">
                Cart
            </button>
        </div>
    </form>
</x-layout>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Restore qty dari localStorage jika ada
        // document.querySelectorAll('input[name^="quantities"]').forEach(input => {
        //     const savedQty = localStorage.getItem(`qty-${input.name}`);
        //     if (savedQty !== null) {
        //         input.value = savedQty;
        //         updateSubtotal(input, parseInt(savedQty));
        //     }
        // });

        document.querySelectorAll('.increment-button').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input');
                const maxStock = parseInt(input.dataset.max);
                let qty = parseInt(input.value) || 0;

                if (qty < maxStock) {
                    input.value = ++qty;
                    updateSubtotal(input, qty);
                } else {
                    alert("Stok tidak mencukupi.");
                }
            });
        });

        document.querySelectorAll('.decrement-button').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input');
                let qty = parseInt(input.value) || 0;

                if (qty > 0) {
                    input.value = --qty;
                    updateSubtotal(input, qty);
                }
            });
        });

        function updateSubtotal(input, qty) {
            const card = input.closest('.w-full.max-w-xs');
            const price = parseInt(card.querySelector('.product-price').innerText.replace(/\D/g, ''));
            const subtotal = price * qty;
            card.querySelector('.subtotal').innerText = 'Sub Total : Rp. ' + subtotal.toLocaleString('id-ID');

            // Simpan ke localStorage
            localStorage.setItem(`qty-${input.name}`, qty);
        }
    });
</script>

