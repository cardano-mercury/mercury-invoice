<script setup>
/**
 * PageHeader - Consistent page header component
 * 
 * Usage:
 * <PageHeader 
 *   title="Customers" 
 *   subtitle="Manage your customer database"
 *   icon="mdi-account-group"
 * >
 *   <template #actions>
 *     <v-btn color="primary">Create New</v-btn>
 *   </template>
 * </PageHeader>
 */

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <header class="page-header">
        <div class="page-header-content">
            <div class="page-header-text">
                <div class="page-header-title-row">
                    <v-icon 
                        v-if="icon" 
                        :icon="icon" 
                        size="32" 
                        color="primary"
                        class="page-header-icon"
                    />
                    <h1 class="page-header-title">{{ title }}</h1>
                </div>
                <p v-if="subtitle" class="page-header-subtitle">{{ subtitle }}</p>
                <slot name="description" />
            </div>
            <div v-if="$slots.actions" class="page-header-actions">
                <slot name="actions" />
            </div>
        </div>
        <slot />
    </header>
</template>

<style scoped>
.page-header {
    margin-bottom: var(--space-6, 1.5rem);
}

.page-header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--space-4, 1rem);
    flex-wrap: wrap;
}

.page-header-text {
    flex: 1;
    min-width: 200px;
}

.page-header-title-row {
    display: flex;
    align-items: center;
    gap: var(--space-3, 0.75rem);
}

.page-header-icon {
    flex-shrink: 0;
}

.page-header-title {
    font-family: var(--font-family-heading, 'Source Code Pro', monospace);
    font-size: var(--text-3xl, 2rem);
    font-weight: var(--font-black, 900);
    color: var(--color-text-primary, #2B2B2B);
    margin: 0;
    line-height: 1.2;
    text-align: left;
}

.page-header-subtitle {
    font-size: var(--text-sm, 0.875rem);
    color: var(--color-text-secondary, #525252);
    margin: var(--space-2, 0.5rem) 0 0;
    line-height: 1.5;
}

.page-header-actions {
    display: flex;
    align-items: center;
    gap: var(--space-3, 0.75rem);
    flex-shrink: 0;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 600px) {
    .page-header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .page-header-title {
        font-size: var(--text-2xl, 1.5rem);
    }
    
    .page-header-actions {
        justify-content: stretch;
    }
    
    .page-header-actions :deep(.v-btn) {
        flex: 1;
    }
}
</style>

