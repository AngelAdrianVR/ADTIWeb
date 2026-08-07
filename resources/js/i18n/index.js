import { createI18n } from 'vue-i18n';
import es from './locales/es.json';
import en from './locales/en.json';

function getInitialLocale() {
    // Check localStorage first for persistence
    const stored = localStorage.getItem('locale');
    if (stored && ['es', 'en'].includes(stored)) {
        return stored;
    }
    // Fall back to browser language
    const browserLang = navigator.language.split('-')[0];
    return ['es', 'en'].includes(browserLang) ? browserLang : 'es';
}

const i18n = createI18n({
    legacy: false,
    locale: getInitialLocale(),
    fallbackLocale: 'es',
    globalInjection: true,
    messages: {
        es,
        en,
    },
});

export default i18n;
export { getInitialLocale };
