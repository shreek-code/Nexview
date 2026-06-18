<div>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Static Pages (CMS)</h1>
            <p class="text-text-secondary mt-1">Manage marketing and legal content.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="px-5 py-2.5 bg-signal-600 text-white rounded-xl hover:bg-signal-700 font-medium transition-colors text-sm shadow-md flex items-center">
            <x-heroicon-o-plus class="w-4 h-4 mr-2" />
            Create Page
        </a>
    </div>

    <div class="mt-8 bg-bg-surface border border-bg-border rounded-xl shadow-sm overflow-hidden">
        @if($pages->isEmpty())
            <div class="p-12 text-center">
                <p class="text-text-secondary">No pages found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-bg-element border-b border-bg-border text-text-secondary">
                        <tr>
                            <th class="px-6 py-4 font-medium">Title</th>
                            <th class="px-6 py-4 font-medium">Slug</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Last Updated</th>
                            <th class="px-6 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bg-border">
                        @foreach($pages as $page)
                            <tr class="hover:bg-bg-element transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $page->title }}</div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    /{{ $page->slug }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($page->is_published)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-500/10 text-green-500">Published</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-500/10 text-gray-500">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $page->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="text-signal-500 hover:text-signal-600 font-medium mr-3">Edit</a>
                                    <a href="{{ url('/' . $page->slug) }}" target="_blank" class="text-text-secondary hover:text-text-primary font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
