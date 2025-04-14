@props(['userId', 'user', 'roles'])
<div>
    <!-- Modal toggle -->
    <button data-modal-target="update-user--{{ $userId }}" data-modal-toggle="update-user--{{ $userId }}" class="font-medium text-blue-600 dark:text-blue-500 cursor-pointer" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </button>

     <!-- Main modal -->
     <div id="update-user--{{ $userId }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
       <div class="relative p-4 w-full max-w-xl max-h-full text-left">
           <!-- Modal content -->
           <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
               <!-- Modal header -->
               <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                   <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                       Update User
                   </h3>
                   <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="update-user--{{ $userId }}">
                       <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                       </svg>
                       <span class="sr-only">Close modal</span>
                   </button>
               </div>
               <!-- Modal body -->
               <form action="{{ route('user.update', $userId) }}" method="POST" class="p-4 md:p-5">
                @csrf
                @method('PUT')
                   <div class="grid gap-4 mb-4 grid-cols-2">
                       <div class="col-span-2 sm:col-span-1">
                           <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                           <input type="text" name="name" id="name" value="{{ $user->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="salma hayya" required="">
                       </div>
                       <div class="col-span-2 sm:col-span-1">
                           <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                           <input type="email" name="email" id="email" value="{{ $user->email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="name@gmail.com" required="">
                       </div>
                       <div class="col-span-2 sm:col-span-1">
                           <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                           <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" disabled {{ $user->role ? '' : 'selected' }}>Choose..</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ $role == $user->role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                                @endforeach
                           </select>
                       </div>
                       <div class="col-span-2 sm:col-span-1">
                           <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">password</label>
                           <input type="password" name="password" id="password" value="{{ $user->password    }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="******" required="">
                       </div>
                   </div>
                   <div class="flex justify-end">
                       <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                           Update
                       </button>
                   </div>                    
               </form>
           </div>
       </div>
   </div> 
</div>