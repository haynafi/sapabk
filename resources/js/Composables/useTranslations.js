import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useTranslations() {
    const page = usePage();

    const translations = computed(() => page.props.translations || {});
    const locale = computed(() => page.props.locale || 'en');

    /**
     * Get a translation by key path (e.g., 'nav.dashboard')
     * @param {string} key - Dot notation key path
     * @param {object} replacements - Optional replacements for placeholders
     * @returns {string}
     */
    function t(key, replacements = {}) {
        const keys = key.split('.');
        let value = translations.value;

        for (const k of keys) {
            if (value && typeof value === 'object' && k in value) {
                value = value[k];
            } else {
                // Return the key if translation not found
                return key;
            }
        }

        // Handle replacements like :name
        if (typeof value === 'string' && Object.keys(replacements).length > 0) {
            for (const [placeholder, replacement] of Object.entries(replacements)) {
                value = value.replace(`:${placeholder}`, replacement);
            }
        }

        return value || key;
    }

    return {
        t,
        locale,
        translations,
    };
}
