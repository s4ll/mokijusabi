<x-layout>
    <div class="flex justify-end mb-6">
        <x-profile/>
    </div>
    <div class="flex justify-between mb-3">
        <div class="">
            <x-user.create-modal :roles="$roles"/>
        </div>
        <div class="">
            <x-search placeholder="Search here..."/>
        </div>
    </div>
    <x-alert.success/>  
    <x-alert.error/>  
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($users->isEmpty())
                        <tr class="bg-white  dark:bg-gray-800">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Data not available
                            </td>
                        </tr>
                @else
                @foreach ($users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->role }}
                    </td>
                    <td class="px-6 py-4 space-x-1 text-right flex">
                        <x-user.update-modal :userId="$user->id" :user="$user" :roles="$roles"/>
                        <x-user.delete-modal :userId="$user->id"/>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-4">
        <x-pagination :paginator="$users"/>
    </div>
</x-layout>