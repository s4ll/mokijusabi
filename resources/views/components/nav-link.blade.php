<a {{ $attributes->merge(['class' => 'flex items-center gap-3 p-3 rounded-lg transition 
    '. ($active ? 'bg-[#D4EBF8] text-[#16325B]' : 'text-gray-600 dark:text-white hover:text-[#16325B] hover:bg-[#D4EBF8] dark:hover:bg-gray-700')]) }}>
    {{ $slot }}
</a>
