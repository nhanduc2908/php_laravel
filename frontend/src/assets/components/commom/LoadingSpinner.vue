<template>
    <div class="loading-spinner" :class="size" v-if="isLoading">
        <div class="spinner"></div>
        <p v-if="text">{{ text }}</p>
    </div>
</template>

<script setup>
defineProps({
    isLoading: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    text: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>
.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.loading-spinner.sm .spinner {
    width: 24px;
    height: 24px;
    border-width: 2px;
}

.loading-spinner.md .spinner {
    width: 40px;
    height: 40px;
    border-width: 3px;
}

.loading-spinner.lg .spinner {
    width: 60px;
    height: 60px;
    border-width: 4px;
}

.spinner {
    border: 3px solid var(--border-color);
    border-top-color: var(--primary-600);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-spinner p {
    color: var(--text-secondary);
    font-size: 0.875rem;
}
</style>