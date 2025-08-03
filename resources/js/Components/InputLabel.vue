<script setup>
defineProps({
    value: {
        type: String,
    },
    for: { // Añadido 'for' para asociar label con input
        type: String,
    }
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" :for="for">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>