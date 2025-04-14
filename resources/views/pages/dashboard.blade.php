<x-layout>
  <div class="flex justify-between">
    <div class="">
      <h1 class="font-semibold text-xl">Welcome Back, {{ Auth::user()->name }}!</h1>
    </div>
    <x-profile/>
  </div>
  
  @if (auth()->user()->role === 'employee')
    <div  class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total sales today : <span>{{ $totalPurchases ?? '0' }}</span></h5>
      <p class="font-normal text-gray-700 dark:text-gray-400">last update {{ $lastUpdate->created_at->format('d M Y H:i') }} </p>
    </div>
  @endif

  @if (auth()->user()->role === 'admin')
    <div class="flex space-x-10 mt-5">
      <x-dashboard.column-chart :data="$columnChartData"/>
      <x-dashboard.pie-chart :data="$pieChartData"/>
    </div>
  @endif
</x-layout>
