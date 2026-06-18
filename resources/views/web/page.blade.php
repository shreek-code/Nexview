<x-layouts.web.app title="{{ $page->title }}" description="{{ $page->meta_description }}">
    <div class="py-24 bg-bg-base min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-text-primary tracking-tight mb-8">{{ $page->title }}</h1>
            <div class="prose prose-lg dark:prose-invert max-w-none text-text-secondary">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</x-layouts.web.app>
