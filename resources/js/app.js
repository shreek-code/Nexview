import './bootstrap';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.css';

document.addEventListener('alpine:init', () => {
    Alpine.data('searchableSelect', (options = {}) => ({
        instance: null,
        init() {
            this.instance = new TomSelect(this.$refs.select, {
                create: false,
                ...options
            });
            
            // Handle Livewire synchronization
            this.instance.on('change', value => {
                this.$refs.select.dispatchEvent(new Event('change'));
            });
        },
        destroy() {
            if (this.instance) {
                this.instance.destroy();
            }
        }
    }));
});
