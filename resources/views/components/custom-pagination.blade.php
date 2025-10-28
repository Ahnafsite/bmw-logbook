@props([
    'paginator',
    'perPageOptions' => [10, 25, 50, 100],
    'perPageModel' => 'perPage'
])

<!-- Enhanced Pagination Component -->
<div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Left side: Per page selector and description -->
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Show</span>
            <flux:select wire:model.live="{{ $perPageModel }}" class="w-20">
                @foreach($perPageOptions as $option)
                    <flux:select.option value="{{ $option }}">{{ $option }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        @if($paginator->total() > 0)
            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
            </div>
        @endif
    </div>

    <!-- Right side: Custom pagination numbers -->
    @if($paginator->hasPages())
        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-500 cursor-not-allowed">Previous</span>
            @else
                <button wire:click="previousPage" class="rounded-md bg-zinc-200 dark:bg-zinc-700 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">Previous</button>
            @endif

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $showPages = [];

                if ($lastPage <= 7) {
                    // If total pages is 7 or less, show all pages
                    for ($i = 1; $i <= $lastPage; $i++) {
                        $showPages[] = $i;
                    }
                } else {
                    // Always show first page
                    $showPages[] = 1;

                    // Calculate center range around current page
                    $centerStart = max(2, $currentPage - 1);
                    $centerEnd = min($lastPage - 1, $currentPage + 1);

                    // Adjust center range to always show 3 pages when possible
                    if ($centerEnd - $centerStart < 2) {
                        if ($centerStart == 2) {
                            $centerEnd = min($lastPage - 1, $centerStart + 2);
                        } else {
                            $centerStart = max(2, $centerEnd - 2);
                        }
                    }

                    // Add ellipsis before center if needed
                    if ($centerStart > 2) {
                        $showPages[] = '...';
                    }

                    // Add center pages
                    for ($i = $centerStart; $i <= $centerEnd; $i++) {
                        $showPages[] = $i;
                    }

                    // Add ellipsis after center if needed
                    if ($centerEnd < $lastPage - 1) {
                        $showPages[] = '...';
                    }

                    // Always show last page (if different from first)
                    if ($lastPage > 1) {
                        $showPages[] = $lastPage;
                    }
                }
            @endphp

            @foreach ($showPages as $page)
                @if ($page === '...')
                    <span class="px-3 py-2 text-sm text-zinc-500 dark:text-zinc-400">...</span>
                @elseif ($page == $currentPage)
                    <span class="px-3 py-2 text-sm bg-green-600/80 text-white rounded-md font-medium">{{ $page }}</span>
                @else
                    <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 text-sm text-zinc-600 bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors">{{ $page }}</button>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="bg-zinc-200 dark:bg-zinc-700 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors">Next</button>
            @else
                <span class="px-3 py-2 text-sm text-zinc-400 dark:text-zinc-500 cursor-not-allowed">Next</span>
            @endif
        </div>
    @endif
</div>
