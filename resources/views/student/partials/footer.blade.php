{{-- resources/views/student/partials/footer.blade.php --}}
<footer class="fixed inset-x-0 bottom-0 bg-white border-t z-40">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
        <span class="text-sm font-medium text-gray-700">
            For Nation's Greater High
        </span>

        <div class="flex items-center gap-4">
            <img src="{{ asset('assets/images/library-logo.png') }}"
                 alt="Library Logo"
                 class="h-12 w-12 object-contain rounded-full border border-gray-300 bg-white shadow-sm">
            <img src="{{ asset('assets/images/snsu-logo.png') }}"
                 alt="SNSU Logo"
                 class="h-12 w-12 object-contain rounded-full border border-gray-300 bg-white shadow-sm">
        </div>
    </div>
</footer>
