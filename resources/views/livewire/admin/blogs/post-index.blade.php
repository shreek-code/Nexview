<div>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Blog Engine</h1>
            <p class="text-text-secondary mt-1">Manage blog posts, authors, and categories.</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="px-5 py-2.5 bg-signal-600 text-white rounded-xl hover:bg-signal-700 font-medium transition-colors text-sm shadow-md flex items-center">
            <x-heroicon-o-plus class="w-4 h-4 mr-2" />
            Create Post
        </a>
    </div>

    <div class="mt-8 bg-bg-surface border border-bg-border rounded-xl shadow-sm overflow-hidden">
        @if($posts->isEmpty())
            <div class="p-12 text-center">
                <p class="text-text-secondary">No blog posts found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-bg-element border-b border-bg-border text-text-secondary">
                        <tr>
                            <th class="px-6 py-4 font-medium">Title</th>
                            <th class="px-6 py-4 font-medium">Authors</th>
                            <th class="px-6 py-4 font-medium">Categories</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Published At</th>
                            <th class="px-6 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bg-border">
                        @foreach($posts as $post)
                            <tr class="hover:bg-bg-element transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $post->title }}</div>
                                    <div class="text-xs text-text-secondary">/blogs/{{ $post->slug }}</div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $post->authors->pluck('name')->join(', ') ?: 'No Author' }}
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $post->categories->pluck('name')->join(', ') ?: 'Uncategorized' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($post->is_published)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-500/10 text-green-500">Published</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-500/10 text-gray-500">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.blogs.edit', $post) }}" class="text-signal-500 hover:text-signal-600 font-medium mr-3">Edit</a>
                                    <button wire:click="deletePost({{ $post->id }})" wire:confirm="Are you sure you want to delete this post?" class="text-red-500 hover:text-red-600 font-medium">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
