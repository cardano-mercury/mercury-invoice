<script setup>
/**
 * FormCard - A consistent card wrapper for form sections
 * 
 * Usage:
 * <FormCard title="Customer Details" icon="mdi-account">
 *   <template #description>Optional description text</template>
 *   <v-text-field ... />
 * </FormCard>
 */

defineProps({
    title: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        default: null,
    },
    subtitle: {
        type: String,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    flat: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <v-card 
        class="form-card" 
        :elevation="flat ? 0 : 2"
        :loading="loading"
    >
        <!-- Header -->
        <div class="form-card-header" v-if="title || $slots.header">
            <slot name="header">
                <div class="form-card-title-wrapper">
                    <div class="form-card-title">
                        <v-icon 
                            v-if="icon" 
                            :icon="icon" 
                            class="form-card-icon"
                            color="primary"
                        />
                        <div>
                            <h3 class="form-card-heading">{{ title }}</h3>
                            <p v-if="subtitle" class="form-card-subtitle">{{ subtitle }}</p>
                        </div>
                    </div>
                    <div v-if="$slots.actions" class="form-card-header-actions">
                        <slot name="actions" />
                    </div>
                </div>
                <div v-if="$slots.description" class="form-card-description">
                    <slot name="description" />
                </div>
            </slot>
        </div>

        <!-- Body -->
        <div class="form-card-body">
            <slot />
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" class="form-card-footer">
            <slot name="footer" />
        </div>
    </v-card>
</template>

<style scoped>
.form-card {
    margin-bottom: var(--space-6, 1.5rem);
    overflow: hidden;
}

.form-card:last-child {
    margin-bottom: 0;
}

.form-card-header {
    padding: var(--space-5, 1.25rem) var(--space-6, 1.5rem);
    border-bottom: 1px solid var(--color-surface-border, #E2E8F0);
    background: var(--color-surface-secondary, #F7F8FA);
}

.form-card-title-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--space-4, 1rem);
}

.form-card-title {
    display: flex;
    align-items: center;
    gap: var(--space-3, 0.75rem);
}

.form-card-icon {
    flex-shrink: 0;
}

.form-card-heading {
    font-family: var(--font-family-heading, 'Source Code Pro', monospace);
    font-size: var(--text-lg, 1.125rem);
    font-weight: var(--font-bold, 700);
    color: var(--color-text-primary, #2B2B2B);
    margin: 0;
    line-height: 1.3;
}

.form-card-subtitle {
    font-size: var(--text-sm, 0.875rem);
    color: var(--color-text-secondary, #525252);
    margin: var(--space-1, 0.25rem) 0 0;
}

.form-card-header-actions {
    flex-shrink: 0;
}

.form-card-description {
    margin-top: var(--space-3, 0.75rem);
    font-size: var(--text-sm, 0.875rem);
    color: var(--color-text-secondary, #525252);
    line-height: 1.5;
}

.form-card-body {
    padding: var(--space-6, 1.5rem);
}

.form-card-footer {
    padding: var(--space-4, 1rem) var(--space-6, 1.5rem);
    border-top: 1px solid var(--color-surface-border, #E2E8F0);
    background: var(--color-surface-secondary, #F7F8FA);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: var(--space-3, 0.75rem);
}

/* Responsive */
@media (max-width: 600px) {
    .form-card-header {
        padding: var(--space-4, 1rem);
    }
    
    .form-card-body {
        padding: var(--space-4, 1rem);
    }
    
    .form-card-footer {
        padding: var(--space-3, 0.75rem) var(--space-4, 1rem);
        flex-direction: column;
    }
    
    .form-card-title-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

