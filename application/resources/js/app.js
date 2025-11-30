import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

/**
 * Install Vuetify
 */
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

/**
 * Mercury Design System Theme
 */
const mercuryTheme = {
    defaultTheme: 'light',
    options: {
        customProperties: true,
    },
    themes: {
        light: {
            dark: false,
            colors: {
                // Primary brand colors
                primary: '#16C170',
                'primary-darken-1': '#129959',
                'primary-lighten-1': '#1ED980',
                
                // Secondary/accent
                secondary: '#2B2B2B',
                'secondary-darken-1': '#1F1F1F',
                'secondary-lighten-1': '#404040',
                
                // Surface colors
                background: '#F7F8FA',
                surface: '#FFFFFF',
                'surface-bright': '#FFFFFF',
                'surface-light': '#F7F8FA',
                'surface-variant': '#ECEFF4',
                
                // Semantic colors
                error: '#EF4444',
                'error-darken-1': '#DC2626',
                info: '#3B82F6',
                'info-darken-1': '#2563EB',
                success: '#16C170',
                'success-darken-1': '#129959',
                warning: '#F59E0B',
                'warning-darken-1': '#D97706',
                
                // Text colors
                'on-background': '#2B2B2B',
                'on-surface': '#2B2B2B',
                'on-surface-variant': '#525252',
                'on-primary': '#FFFFFF',
                'on-secondary': '#FFFFFF',
                'on-error': '#FFFFFF',
                'on-info': '#FFFFFF',
                'on-success': '#FFFFFF',
                'on-warning': '#FFFFFF',
            },
            variables: {
                'border-color': '#E2E8F0',
                'border-opacity': 0.12,
                'high-emphasis-opacity': 0.87,
                'medium-emphasis-opacity': 0.60,
                'disabled-opacity': 0.38,
                'idle-opacity': 0.04,
                'hover-opacity': 0.04,
                'focus-opacity': 0.12,
                'selected-opacity': 0.08,
                'activated-opacity': 0.12,
                'pressed-opacity': 0.12,
                'dragged-opacity': 0.08,
                'theme-kbd': '#2B2B2B',
                'theme-on-kbd': '#FFFFFF',
                'theme-code': '#F7F8FA',
                'theme-on-code': '#2B2B2B',
            },
        },
        dark: {
            dark: true,
            colors: {
                primary: '#1ED980',
                'primary-darken-1': '#16C170',
                'primary-lighten-1': '#4ade8a',
                
                secondary: '#E2E8F0',
                'secondary-darken-1': '#CBD5E1',
                'secondary-lighten-1': '#F1F5F9',
                
                background: '#1F1F1F',
                surface: '#2B2B2B',
                'surface-bright': '#363636',
                'surface-light': '#404040',
                'surface-variant': '#525252',
                
                error: '#F87171',
                info: '#60A5FA',
                success: '#4ade8a',
                warning: '#FBBF24',
                
                'on-background': '#F7F8FA',
                'on-surface': '#F7F8FA',
                'on-surface-variant': '#CBD5E1',
                'on-primary': '#1F1F1F',
                'on-secondary': '#1F1F1F',
            },
        },
    },
};

/**
 * Vuetify defaults for consistent component styling
 */
const defaults = {
    VBtn: {
        rounded: 'lg',
        fontWeight: 600,
    },
    VCard: {
        rounded: 'xl',
        elevation: 2,
    },
    VTextField: {
        variant: 'outlined',
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VTextarea: {
        variant: 'outlined',
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VSelect: {
        variant: 'outlined',
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VAutocomplete: {
        variant: 'outlined',
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VCombobox: {
        variant: 'outlined',
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VCheckbox: {
        density: 'comfortable',
        hideDetails: 'auto',
    },
    VSwitch: {
        density: 'comfortable',
        hideDetails: 'auto',
        inset: true,
    },
    VChip: {
        rounded: 'lg',
    },
    VAlert: {
        rounded: 'lg',
        variant: 'tonal',
    },
    VDataTable: {
        hover: true,
    },
    VDialog: {
        maxWidth: 600,
    },
};

const Vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
    },
    theme: mercuryTheme,
    defaults,
    components: {
        ...components,
    },
    directives: {
        ...directives,
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Cardano Mercury';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(ToastPlugin)
            .use(Vuetify)
            .mount(el);
    },
    progress: {
        color: '#16C170',
    },
});
