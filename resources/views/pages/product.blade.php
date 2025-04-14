<x-layout>
    <div class="flex justify-end mb-6">
        <x-profile/>
    </div>
    <div class="flex justify-between mb-3">
        @if (auth()->user()->role === 'admin')
        <div class="">
            <x-product.create-modal />
        </div>
        @endif
        <div class="">
            <x-search placeholder="Search products..." />
        </div>
    </div>
    <x-alert.success/>  
    <x-alert.error/>  
    <div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product Image
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Stock
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->isEmpty())
                        <tr class="bg-white  dark:bg-gray-800">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Data not available
                            </td>
                        </tr>
                    @else
                    @foreach ($products as $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                        </th>
                        <td class="px-6 py-4">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                    class="w-16 h-16 object-cover rounded-md">
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->stock }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-1 h-full">
                                <x-product.update-modal :productId="$product->id" :product="$product"/>
                                <x-product.stock-modal :productId="$product->id" :product="$product" />
                                <x-product.delete-modal :productId="$product->id"/>
                            </div>
                        </td>                        
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mt-4">
            <x-pagination :paginator="$products"/>
        </div>
    </div>
</x-layout>