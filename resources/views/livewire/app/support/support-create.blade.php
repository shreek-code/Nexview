<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Create Support Ticket</h1>
        <p class="text-text-secondary mt-1">Please describe your issue in detail so we can help you.</p>
    </div>

    <x-ui.card class="max-w-3xl">
        <form wire:submit="submit" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 md:col-span-2">
                    <label for="subject" class="text-sm font-medium text-text-secondary">Subject</label>
                    <input type="text" id="subject" wire:model="subject" class="input-text" placeholder="Brief summary of the issue">
                    @error('subject') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="priority" class="text-sm font-medium text-text-secondary">Priority</label>
                    <select id="priority" wire:model="priority" class="input-text">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="message" class="text-sm font-medium text-text-secondary">Message</label>
                <textarea id="message" wire:model="message" rows="6" class="input-text" placeholder="Describe the issue in detail..."></textarea>
                @error('message') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="attachments" class="text-sm font-medium text-text-secondary">Attachments (Optional)</label>
                <input type="file" id="attachments" wire:model="attachments" multiple class="block w-full text-sm text-text-secondary
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-xl file:border-0
                    file:text-sm file:font-semibold
                    file:bg-surface-2 file:text-signal-600
                    hover:file:bg-surface-3 transition-colors cursor-pointer" />
                @error('attachments.*') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                <p class="text-xs text-text-tertiary">You can upload multiple files (Max 10MB each).</p>
            </div>

            <div class="pt-4 border-t border-border-subtle flex justify-end space-x-3">
                <a href="{{ route('app.support.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Submit Ticket</span>
                    <span wire:loading wire:target="submit">Submitting...</span>
                </button>
            </div>
        </form>
    </x-ui.card>
</div>
