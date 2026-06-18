<x-layouts.web.app title="Blog - NexView" description="Latest news and updates from NexView">
    <div class="py-24 bg-bg-base min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-text-primary tracking-tight mb-4">The NexView Blog</h1>
                <p class="text-xl text-text-secondary max-w-2xl mx-auto">Insights, updates, and strategies for modern digital signage.</p>
            </div>

            @if($posts->isEmpty())
                <div class="text-center py-12">
                    <p class="text-text-secondary">No blog posts found. Check back later!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="bg-bg-surface rounded-2xl overflow-hidden border border-bg-border shadow-sm hover:shadow-md transition-shadow group flex flex-col">
                            @if($post->featured_image)
                                <div class="aspect-w-16 aspect-h-9 overflow-hidden bg-bg-element">
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center space-x-2 text-xs text-text-secondary mb-3">
                                    <time datetime="{{ $post->published_at->format('Y-m-d') }}">{{ $post->published_at->format('M d, Y') }}</time>
                                    @if($post->categories->isNotEmpty())
                                        <span>&bull;</span>
                                        <span class="text-signal-500 font-medium">{{ $post->categories->first()->name }}</span>
                                    @endif
                                </div>
                                
                                <h2 class="text-xl font-bold text-text-primary mb-3">
                                    <a href="{{ route('web.post', $post->slug) }}" class="hover:text-signal-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                
                                <p class="text-text-secondary mb-6 flex-1 line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                                
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center space-x-3">
                                        @if($post->authors->isNotEmpty())
                                            <div class="w-8 h-8 rounded-full bg-signal-100 dark:bg-signal-900/30 flex items-center justify-center text-signal-600 font-bold text-sm">
                                                {{ substr($post->authors->first()->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-text-primary">{{ $post->authors->first()->name }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('web.post', $post->slug) }}" class="text-signal-600 text-sm font-medium hover:text-signal-700 flex items-center">
                                        Read <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.web.app>
