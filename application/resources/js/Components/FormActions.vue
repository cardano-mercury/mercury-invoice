<script setup>
/**
 * FormActions - Standardized button group for form actions
 * 
 * Usage:
 * <FormActions
 *   :loading="form.processing"
 *   :cancel-route="route('customers.index')"
 *   save-text="Create Customer"
 *   @submit="form.post(route('customers.store'))"
 * />
 */

import { Link } from '@inertiajs/vue3';

const props = defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    saveText: {
        type: String,
        default: 'Save',
    },
    saveIcon: {
        type: String,
        default: 'mdi-content-save',
    },
    saveColor: {
        type: String,
        default: 'primary',
    },
    showReset: {
        type: Boolean,
        default: true,
    },
    resetText: {
        type: String,
        default: 'Reset',
    },
    resetIcon: {
        type: String,
        default: 'mdi-refresh',
    },
    showCancel: {
        type: Boolean,
        default: true,
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    cancelIcon: {
        type: String,
        default: 'mdi-close',
    },
    cancelRoute: {
        type: String,
        default: null,
    },
    // For secondary action (e.g., "Save as Draft")
    showSecondary: {
        type: Boolean,
        default: false,
    },
    secondaryText: {
        type: String,
        default: 'Save as Draft',
    },
    secondaryIcon: {
        type: String,
        default: 'mdi-content-save-outline',
    },
    // Alignment
    align: {
        type: String,
        default: 'end', // 'start', 'center', 'end', 'between'
        validator: (value) => ['start', 'center', 'end', 'between'].includes(value),
    },
    // Sticky footer
    sticky: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['submit', 'reset', 'cancel', 'secondary']);

const handleReset = () => {
    emit('reset');
};

const handleCancel = () => {
    emit('cancel');
};

const handleSecondary = () => {
    emit('secondary');
};
</script>

<template>
    <div 
        class="form-actions-wrapper"
        :class="{ 
            'form-actions-sticky': sticky,
            [`form-actions-${align}`]: true 
        }"
    >
        <div class="form-actions-left" v-if="$slots.left">
            <slot name="left" />
        </div>

        <div class="form-actions-buttons">
            <!-- Cancel Button -->
            <v-btn
                v-if="showCancel"
                variant="text"
                color="secondary"
                :prepend-icon="cancelIcon"
                :href="cancelRoute"
                :disabled="loading"
                @click="!cancelRoute && handleCancel()"
            >
                {{ cancelText }}
            </v-btn>

            <!-- Reset Button -->
            <v-btn
                v-if="showReset"
                variant="tonal"
                color="secondary"
                :prepend-icon="resetIcon"
                :disabled="loading"
                type="reset"
                @click="handleReset"
            >
                {{ resetText }}
            </v-btn>

            <!-- Secondary Action Button (e.g., Save as Draft) -->
            <v-btn
                v-if="showSecondary"
                variant="outlined"
                color="primary"
                :prepend-icon="secondaryIcon"
                :loading="loading"
                :disabled="disabled"
                @click="handleSecondary"
            >
                {{ secondaryText }}
            </v-btn>

            <!-- Custom buttons slot -->
            <slot name="buttons" />

            <!-- Primary Save Button -->
            <v-btn
                type="submit"
                variant="flat"
                :color="saveColor"
                :prepend-icon="saveIcon"
                :loading="loading"
                :disabled="disabled"
            >
                {{ saveText }}
            </v-btn>
        </div>
    </div>
</template>

<style scoped>
.form-actions-wrapper {
    display: flex;
    align-items: center;
    gap: var(--space-4, 1rem);
    padding-top: var(--space-6, 1.5rem);
    margin-top: var(--space-6, 1.5rem);
    border-top: 1px solid var(--color-surface-border, #E2E8F0);
}

.form-actions-sticky {
    position: sticky;
    bottom: 0;
    background: var(--color-surface, #FFFFFF);
    margin: var(--space-6, 1.5rem) calc(var(--space-6, 1.5rem) * -1) 0;
    padding: var(--space-4, 1rem) var(--space-6, 1.5rem);
    border-top: 1px solid var(--color-surface-border, #E2E8F0);
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
    z-index: 10;
}

.form-actions-left {
    flex: 1;
}

.form-actions-buttons {
    display: flex;
    align-items: center;
    gap: var(--space-3, 0.75rem);
    flex-wrap: wrap;
}

/* Alignment variants */
.form-actions-start {
    justify-content: flex-start;
}

.form-actions-start .form-actions-buttons {
    order: -1;
}

.form-actions-center {
    justify-content: center;
}

.form-actions-end {
    justify-content: flex-end;
}

.form-actions-between {
    justify-content: space-between;
}

/* Responsive */
@media (max-width: 600px) {
    .form-actions-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-actions-buttons {
        flex-direction: column;
    }
    
    .form-actions-buttons .v-btn {
        width: 100%;
    }
    
    .form-actions-sticky {
        margin-left: calc(var(--space-4, 1rem) * -1);
        margin-right: calc(var(--space-4, 1rem) * -1);
        padding: var(--space-4, 1rem);
    }
}
</style>

