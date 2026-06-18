<x-layouts.web.app title="{{ $post->title }}" description="{{ $post->excerpt }}">
    <article class="py-24 bg-bg-base min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="mb-12">
                @if($post->categories->isNotEmpty())
                    <div class="mb-6 flex flex-wrap gap-2">
                        @foreach($post->categories as $category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-signal-100 text-signal-700 dark:bg-signal-900/30 dark:text-signal-400">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <h1 class="text-4xl md:text-5xl font-extrabold text-text-primary tracking-tight mb-6">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center text-text-secondary mb-8 gap-y-4">
                    <div class="flex -space-x-2 mr-4">
                        @foreach($post->authors as $author)
                            <div class="w-10 h-10 rounded-full border-2 border-bg-base bg-bg-element flex items-center justify-center text-text-primary font-bold z-10" title="{{ $author->name }}">
                                {{ substr($author->name, 0, 1) }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <p class="font-medium text-text-primary">{{ $post->authors->pluck('name')->join(', ') }}</p>
                        <div class="flex items-center text-sm space-x-2 mt-1">
                            <time datetime="{{ $post->published_at->format('Y-m-d') }}">{{ $post->published_at->format('F d, Y') }}</time>
                        </div>
                    </div>
                </div>

                @if($post->featured_image)
                    <div class="rounded-2xl overflow-hidden bg-bg-element mb-12 border border-bg-border shadow-sm">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full object-cover max-h-[500px]">
                    </div>
                @endif
            </header>

            <div class="prose prose-lg dark:prose-invert max-w-none text-text-secondary mb-16">
                {!! $post->content !!}
            </div>

            @if($post->tags->isNotEmpty())
                <div class="border-t border-bg-border pt-8 mb-12">
                    <h3 class="text-lg font-bold text-text-primary mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-bg-element text-text-secondary border border-bg-border">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="text-center pt-8 border-t border-bg-border">
                <a href="{{ route('web.blogs') }}" class="inline-flex items-center text-signal-600 hover:text-signal-700 font-medium transition-colors">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                    Back to all posts
                </a>
            </div>
        </div>
    </article>
</x-layouts.web.app>
