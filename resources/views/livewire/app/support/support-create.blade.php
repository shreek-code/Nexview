<div>
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('app.support.index') }}" wire:navigate class="p-2 rounded-xl bg-surface-2 hover:bg-surface-3 text-text-tertiary hover:text-text-primary transition-all">
            <x-heroicon-o-arrow-left class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Create Support Ticket</h1>
            <p class="text-text-secondary mt-1">Describe your issue in detail so we can help you quickly.</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <x-ui.card class="overflow-hidden">
            <!-- Header Strip -->
            <div class="bg-gradient-to-r from-signal-600 to-signal-500 px-8 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <x-heroicon-o-ticket class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">New Support Request</h2>
                        <p class="text-sm text-white/70">Our team typically responds within 24 hours</p>
                    </div>
                </div>
            </div>

            <form wire:submit="submit" class="p-8 space-y-6">
                <!-- Subject -->
                <div class="space-y-2">
                    <label for="subject" class="block text-sm font-semibold text-text-primary">Subject</label>
                    <input type="text" id="subject" wire:model="subject" class="input-text w-full" placeholder="Brief summary of your issue">
                    @error('subject')
                        <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Priority Selector -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-text-primary">Priority</label>
                    <div class="grid grid-cols-3 gap-3" x-data="{ priority: @entangle('priority') }">
                        <button type="button" @click="priority = 'low'" 
                            :class="priority === 'low' ? 'border-emerald-500 bg-emerald-500/5 ring-2 ring-emerald-500/20' : 'border-border-subtle hover:border-emerald-500/50'"
                            class="relative p-4 rounded-xl border-2 transition-all text-center">
                            <x-heroicon-o-arrow-down class="w-5 h-5 mx-auto mb-2 text-emerald-500" />
                            <span class="text-sm font-semibold text-text-primary">Low</span>
                            <p class="text-xs text-text-tertiary mt-1">Non-urgent issue</p>
                        </button>
                        <button type="button" @click="priority = 'medium'" 
                            :class="priority === 'medium' ? 'border-amber-500 bg-amber-500/5 ring-2 ring-amber-500/20' : 'border-border-subtle hover:border-amber-500/50'"
                            class="relative p-4 rounded-xl border-2 transition-all text-center">
                            <x-heroicon-o-minus class="w-5 h-5 mx-auto mb-2 text-amber-500" />
                            <span class="text-sm font-semibold text-text-primary">Medium</span>
                            <p class="text-xs text-text-tertiary mt-1">Some impact</p>
                        </button>
                        <button type="button" @click="priority = 'high'" 
                            :class="priority === 'high' ? 'border-red-500 bg-red-500/5 ring-2 ring-red-500/20' : 'border-border-subtle hover:border-red-500/50'"
                            class="relative p-4 rounded-xl border-2 transition-all text-center">
                            <x-heroicon-o-arrow-up class="w-5 h-5 mx-auto mb-2 text-red-500" />
                            <span class="text-sm font-semibold text-text-primary">High</span>
                            <p class="text-xs text-text-tertiary mt-1">Critical issue</p>
                        </button>
                    </div>
                    @error('priority')
                        <p class="text-sm text-red-500 flex items-center gap-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-semibold text-text-primary">Message</label>
                    <textarea id="message" wire:model="message" rows="6" class="input-text w-full" placeholder="Please describe the issue you're facing, including any steps to reproduce..."></textarea>
                    @error('message')
                        <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Attachments -->
                <div class="space-y-2" x-data="{ isDragging: false }" 
                    @dragover.prevent="isDragging = true" 
                    @dragleave.prevent="isDragging = false" 
                    @drop.prevent="isDragging = false">
                    <label class="block text-sm font-semibold text-text-primary">Attachments <span class="font-normal text-text-tertiary">(Optional)</span></label>
                    <label for="attachments" 
                        class="flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-xl cursor-pointer transition-all"
                        :class="isDragging ? 'border-signal-500 bg-signal-500/5' : 'border-border-subtle hover:border-signal-500/50 hover:bg-surface-2'">
                        <x-heroicon-o-cloud-arrow-up class="w-8 h-8 text-text-tertiary mb-3" />
                        <p class="text-sm text-text-secondary"><span class="font-semibold text-signal-600">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-text-tertiary mt-1">Max 10MB per file • Multiple files supported</p>
                        <input type="file" id="attachments" wire:model="attachments" multiple class="hidden" />
                    </label>
                    @if($attachments)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($attachments as $index => $attachment)
                                <span class="inline-flex items-center px-3 py-1.5 bg-surface-2 border border-border-subtle rounded-lg text-sm text-text-secondary">
                                    <x-heroicon-o-paper-clip class="w-4 h-4 mr-1.5 text-text-tertiary" />
                                    {{ $attachment->getClientOriginalName() }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    @error('attachments.*')
                        <p class="text-sm text-red-500 flex items-center gap-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-border-subtle flex items-center justify-between">
                    <a href="{{ route('app.support.index') }}" wire:navigate class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                        ← Back to Tickets
                    </a>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="submit, attachments">
                        <div wire:loading wire:target="submit" class="mr-2 h-4 w-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                        <span wire:loading.remove wire:target="submit">Submit Ticket</span>
                        <span wire:loading wire:target="submit">Submitting...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
