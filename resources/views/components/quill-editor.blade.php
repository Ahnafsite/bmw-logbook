@props([
    'wire:model' => null,
    'id' => 'quill-' . uniqid(),
    'height' => 300,
    'placeholder' => '',
    'value' => '',
])

<div wire:ignore class="relative">
    <div
        id="{{ $id }}"
        style="height: {{ $height }}px;"
        class="overflow-hidden rounded-lg border-2 border-zinc-300 dark:border-zinc-600 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-opacity-20 dark:focus-within:ring-blue-400 dark:focus-within:ring-opacity-30 transition-all duration-200 shadow-sm dark:shadow-lg dark:shadow-black/10"
        {{ $attributes->whereDoesntStartWith('wire:model') }}
    >{!! $value !!}</div>
</div>

@push('styles')
<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
/* Quill Editor Custom Styling for Flux UI */
.ql-toolbar {
    @apply border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800;
    border-radius: 0.5rem 0.5rem 0 0 !important; /* rounded-t-lg */
    border-bottom: 2px solid rgb(212 212 216 / 1) !important;
    border-left: none !important;
    border-right: none !important;
    border-top: none !important;
    box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.1) !important;
}

.dark .ql-toolbar {
    @apply bg-zinc-800;
    border-bottom-color: rgb(82 82 91 / 1) !important;
    background-color: rgb(39 39 42 / 1) !important;
    box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.05) !important;
}

.ql-container {
    @apply border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900;
    font-family: inherit !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: none !important;
    border-top: none !important;
    border-radius: 0 0 0.5rem 0.5rem !important; /* rounded-b-lg */
    box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.05) !important;
}

.dark .ql-container {
    @apply bg-zinc-900;
    background-color: rgb(24 24 27 / 1) !important;
    box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.02) !important;
}

.ql-editor {
    @apply text-zinc-900 dark:text-zinc-100;
    color: rgb(24 24 27 / 1) !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    padding: 12px 15px !important;
    border-radius: 0 0 0.5rem 0.5rem !important;
}

.dark .ql-editor {
    color: rgb(250 250 250 / 1) !important;
}

.ql-editor.ql-blank::before {
    @apply text-zinc-500 dark:text-zinc-400;
    font-style: normal !important;
    left: 15px !important;
}

.dark .ql-editor.ql-blank::before {
    color: rgb(161 161 170 / 1) !important;
}

/* Toolbar buttons styling - Enhanced for dark mode */
.ql-toolbar .ql-stroke {
    @apply stroke-zinc-600 dark:stroke-zinc-300;
}

.dark .ql-toolbar .ql-stroke {
    stroke: rgb(212 212 216 / 1) !important;
}

.ql-toolbar .ql-fill {
    @apply fill-zinc-600 dark:fill-zinc-300;
}

.dark .ql-toolbar .ql-fill {
    fill: rgb(212 212 216 / 1) !important;
}

.ql-toolbar .ql-picker-label {
    @apply text-zinc-700 dark:text-zinc-200;
}

.dark .ql-toolbar .ql-picker-label {
    color: rgb(212 212 216 / 1) !important;
}

.ql-toolbar button {
    @apply rounded-md;
    border-radius: 0.375rem !important;
    margin: 1px !important;
    transition: all 0.15s ease-in-out !important;
}

.ql-toolbar button:hover,
.ql-toolbar button:focus {
    @apply bg-zinc-100 dark:bg-zinc-700;
    transform: translateY(-0.5px) !important;
    box-shadow: 0 2px 4px rgb(0 0 0 / 0.1) !important;
}

.dark .ql-toolbar button:hover,
.dark .ql-toolbar button:focus {
    background-color: rgb(63 63 70 / 1) !important;
    box-shadow: 0 2px 4px rgb(0 0 0 / 0.3) !important;
}

.ql-toolbar button.ql-active {
    @apply bg-zinc-200 dark:bg-zinc-600;
    box-shadow: inset 0 2px 4px rgb(0 0 0 / 0.1) !important;
}

.dark .ql-toolbar button.ql-active {
    background-color: rgb(82 82 91 / 1) !important;
    box-shadow: inset 0 2px 4px rgb(0 0 0 / 0.2) !important;
}

.ql-toolbar .ql-picker {
    @apply rounded-md;
    border-radius: 0.375rem !important;
}

.ql-toolbar .ql-picker-options {
    @apply bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-600 rounded-lg shadow-lg;
    border-radius: 0.5rem !important;
    border-width: 1px !important;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05) !important;
}

.dark .ql-toolbar .ql-picker-options {
    background-color: rgb(39 39 42 / 1) !important;
    border-color: rgb(82 82 91 / 1) !important;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.4), 0 10px 10px -5px rgb(0 0 0 / 0.2) !important;
}

.ql-toolbar .ql-picker-item {
    @apply rounded-md;
    border-radius: 0.375rem !important;
    margin: 2px !important;
    padding: 4px 8px !important;
    transition: all 0.15s ease-in-out !important;
}

.ql-toolbar .ql-picker-item:hover {
    @apply bg-zinc-100 dark:bg-zinc-700;
    transform: translateX(2px) !important;
}

.dark .ql-toolbar .ql-picker-item:hover {
    background-color: rgb(63 63 70 / 1) !important;
}

/* Dropdown styling - Enhanced for dark mode */
.ql-snow .ql-picker-options {
    @apply bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-600;
    border-radius: 0.5rem !important;
    padding: 6px !important;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05) !important;
}

.dark .ql-snow .ql-picker-options {
    background-color: rgb(39 39 42 / 1) !important;
    border-color: rgb(82 82 91 / 1) !important;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.4), 0 10px 10px -5px rgb(0 0 0 / 0.2) !important;
}

.ql-snow .ql-picker-item {
    @apply text-zinc-700 dark:text-zinc-200;
    border-radius: 0.375rem !important;
    padding: 6px 10px !important;
    margin: 2px !important;
    transition: all 0.15s ease-in-out !important;
}

.dark .ql-snow .ql-picker-item {
    color: rgb(212 212 216 / 1) !important;
}

.ql-snow .ql-picker-item:hover {
    @apply bg-zinc-100 dark:bg-zinc-700;
    transform: translateX(2px) !important;
}

.dark .ql-snow .ql-picker-item:hover {
    background-color: rgb(63 63 70 / 1) !important;
}

/* Focus states */
.ql-container.ql-snow {
    border: none !important;
}

.ql-editor:focus {
    outline: none !important;
}

.ql-snow.ql-toolbar {
    border: none !important;
}

/* Enhanced scrollbar for both modes */
.ql-editor::-webkit-scrollbar {
    width: 10px;
}

.ql-editor::-webkit-scrollbar-track {
    @apply bg-zinc-100 dark:bg-zinc-800;
    border-radius: 0.375rem !important;
    margin-bottom: 0.5rem !important;
}

.dark .ql-editor::-webkit-scrollbar-track {
    background-color: rgb(39 39 42 / 1) !important;
}

.ql-editor::-webkit-scrollbar-thumb {
    @apply bg-zinc-300 dark:bg-zinc-600;
    border-radius: 0.375rem !important;
    border: 2px solid transparent !important;
    background-clip: content-box !important;
    transition: all 0.15s ease-in-out !important;
}

.dark .ql-editor::-webkit-scrollbar-thumb {
    background-color: rgb(113 113 122 / 1) !important;
}

.ql-editor::-webkit-scrollbar-thumb:hover {
    @apply bg-zinc-400 dark:bg-zinc-500;
    transform: scaleY(1.1) !important;
}

.dark .ql-editor::-webkit-scrollbar-thumb:hover {
    background-color: rgb(161 161 170 / 1) !important;
}

/* Enhanced focus ring for accessibility */
.ql-container:focus-within {
    @apply ring-2 ring-blue-500 ring-opacity-50;
}

/* Color picker enhanced visibility in dark mode */
.dark .ql-color-picker .ql-picker-options {
    background-color: rgb(39 39 42 / 1) !important;
    border-color: rgb(82 82 91 / 1) !important;
    border-radius: 0.5rem !important;
}

/* Tooltip styling for dark mode */
.dark .ql-tooltip {
    @apply bg-zinc-800 border-zinc-600 text-zinc-200;
    background-color: rgb(39 39 42 / 1) !important;
    border-color: rgb(82 82 91 / 1) !important;
    color: rgb(212 212 216 / 1) !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.3) !important;
}

.ql-tooltip {
    border-radius: 0.375rem !important;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
}

/* Enhanced separator styling */
.ql-toolbar .ql-formats {
    margin-right: 8px !important;
}

.ql-toolbar .ql-formats:not(:last-child) {
    border-right: 1px solid rgb(228 228 231 / 0.5) !important;
    padding-right: 8px !important;
}

.dark .ql-toolbar .ql-formats:not(:last-child) {
    border-right-color: rgb(82 82 91 / 0.5) !important;
}
</style>
@endpush

@push('scripts')
<!-- Quill.js JavaScript -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Quill editor
    var quill{{ str_replace(['-', '.'], '', $id) }} = new Quill('#{{ $id }}', {
        theme: 'snow',
        placeholder: '{{ $placeholder }}',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        }
    });

    // Apply custom styling after initialization
    setTimeout(function() {
        var container = document.querySelector('#{{ $id }}');
        if (container) {
            var toolbar = container.querySelector('.ql-toolbar');
            var editor = container.querySelector('.ql-container');

            if (toolbar && editor) {
                // Remove default borders since we're using our custom wrapper
                toolbar.style.border = 'none';
                toolbar.style.borderBottom = '2px solid';
                editor.style.border = 'none';

                // Apply border color based on current theme
                if (document.documentElement.classList.contains('dark')) {
                    toolbar.style.borderBottomColor = 'rgb(82 82 91)';
                } else {
                    toolbar.style.borderBottomColor = 'rgb(212 212 216)';
                }
            }
        }
    }, 100);

    @if($attributes->has('wire:model'))
    var wireModel = '{{ $attributes->get('wire:model') }}';
    var quillInstance = quill{{ str_replace(['-', '.'], '', $id) }};
    var isUpdating = false;

    // Immediately sync any existing content from div to Livewire
    setTimeout(function() {
        var initialContent = quillInstance.root.innerHTML;
        console.log('Raw initial content:', initialContent);
        console.log('Cleaned initial content:', initialContent.trim());

        if (initialContent && initialContent !== '<p><br></p>' && initialContent.trim() !== '') {
            console.log('Setting initial content to Livewire:', initialContent);
            try {
                @this.set(wireModel, initialContent);
                console.log('Successfully set content to Livewire');

                // Verify it was set
                setTimeout(function() {
                    var livewireContent = @this.get(wireModel);
                    console.log('Livewire content after setting:', livewireContent);

                    // Call debug method to check on server side
                    @this.call('checkDescValue').then(function(result) {
                        console.log('Server-side desc value:', result);
                    });
                }, 100);
            } catch (error) {
                console.error('Error setting content to Livewire:', error);
            }
        } else {
            console.log('No initial content to sync');
        }
    }, 50);

    // Function to set content from Livewire
    function setQuillContent() {
        if (isUpdating) return; // Prevent infinite loops

        try {
            var content = @this.get(wireModel) || '';
            var currentContent = quillInstance.root.innerHTML;

            // Clean up content for comparison
            var cleanContent = content.trim();
            var cleanCurrent = currentContent.trim();

            if (cleanContent && cleanContent !== '<p><br></p>' && cleanContent !== cleanCurrent) {
                console.log('Setting Quill content:', cleanContent);
                isUpdating = true;
                quillInstance.root.innerHTML = cleanContent;
                isUpdating = false;
            }
        } catch (error) {
            console.log('Quill: Error loading content:', error);
            isUpdating = false;
        }
    }

    // Function to sync initial content from div to Livewire
    function syncInitialContent() {
        var initialContent = quillInstance.root.innerHTML;
        if (initialContent && initialContent !== '<p><br></p>' && initialContent.trim() !== '') {
            console.log('Syncing initial content to Livewire:', initialContent);
            isUpdating = true;
            @this.set(wireModel, initialContent);
            setTimeout(() => { isUpdating = false; }, 100);
        }
    }

    // Listen for text changes and update Livewire property
    quillInstance.on('text-change', function() {
        if (isUpdating) return;

        var html = quillInstance.root.innerHTML;
        console.log('Quill text changed, content:', html);

        if (html === '<p><br></p>' || html === '<p></p>' || html === '<br>') {
            html = '';
        }

        console.log('Setting to Livewire:', html);
        isUpdating = true;

        try {
            @this.set(wireModel, html);
            console.log('Successfully set content to Livewire via text-change');
        } catch (error) {
            console.error('Error setting content via text-change:', error);
        }

        setTimeout(() => { isUpdating = false; }, 100);
    });

    // Also listen for selection change (when user stops typing)
    quillInstance.on('selection-change', function() {
        if (isUpdating) return;

        var html = quillInstance.root.innerHTML;
        if (html === '<p><br></p>' || html === '<p></p>' || html === '<br>') {
            html = '';
        }

        // Only sync if there's actual content or if it was cleared
        var currentLivewireContent = '';
        try {
            currentLivewireContent = @this.get(wireModel) || '';
        } catch (e) {}

        if (html !== currentLivewireContent) {
            console.log('Selection changed, syncing content:', html);
            isUpdating = true;
            @this.set(wireModel, html);
            setTimeout(() => { isUpdating = false; }, 50);
        }
    });

    // Set initial content with delays
    setTimeout(setQuillContent, 100);
    setTimeout(setQuillContent, 300);
    setTimeout(setQuillContent, 500);
    setTimeout(setQuillContent, 1000);

    // Sync initial content from div to Livewire (for edit mode)
    setTimeout(syncInitialContent, 150);
    setTimeout(syncInitialContent, 400);
    setTimeout(syncInitialContent, 800);

    // Listen for Livewire events
    document.addEventListener('livewire:navigated', () => {
        setTimeout(setQuillContent, 200);
    });

    // Listen for refresh event
    window.addEventListener('refresh-quill-content', () => {
        console.log('Received refresh-quill-content event');
        setTimeout(setQuillContent, 100);
    });

    // Watch for Livewire property changes
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('morph.updated', () => {
            setTimeout(setQuillContent, 100);
        });

        // Add periodic sync as fallback (every 2 seconds)
        setInterval(function() {
            if (!isUpdating) {
                var html = quillInstance.root.innerHTML;
                if (html === '<p><br></p>' || html === '<p></p>' || html === '<br>') {
                    html = '';
                }

                try {
                    var currentLivewireContent = @this.get(wireModel) || '';
                    if (html !== currentLivewireContent) {
                        console.log('Periodic sync - updating Livewire with:', html);
                        @this.set(wireModel, html);
                    }
                } catch (e) {
                    console.log('Periodic sync error:', e);
                }
            }
        }, 2000);
    }

    // Listen for focus/blur events
    quillInstance.root.addEventListener('blur', function() {
        console.log('Quill editor lost focus, syncing content');
        var html = quillInstance.root.innerHTML;
        if (html === '<p><br></p>' || html === '<p></p>' || html === '<br>') {
            html = '';
        }

        isUpdating = true;
        @this.set(wireModel, html);
        setTimeout(() => { isUpdating = false; }, 100);
    });

    // Listen for any input events as backup
    quillInstance.root.addEventListener('input', function() {
        console.log('Input event detected');
        setTimeout(function() {
            if (!isUpdating) {
                var html = quillInstance.root.innerHTML;
                if (html === '<p><br></p>' || html === '<p></p>' || html === '<br>') {
                    html = '';
                }
                console.log('Input sync - setting content:', html);
                isUpdating = true;
                @this.set(wireModel, html);
                setTimeout(() => { isUpdating = false; }, 100);
            }
        }, 300);
    });
    @endif

    // Listen for theme changes and update border colors
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                var container = document.querySelector('#{{ $id }}');
                if (container) {
                    var toolbar = container.querySelector('.ql-toolbar');
                    if (toolbar) {
                        if (document.documentElement.classList.contains('dark')) {
                            toolbar.style.borderBottomColor = 'rgb(82 82 91)';
                        } else {
                            toolbar.style.borderBottomColor = 'rgb(212 212 216)';
                        }
                    }
                }
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
@endpush
