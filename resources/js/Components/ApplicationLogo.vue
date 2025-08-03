<template>
    <img
        src="/logos/logo_cst.webp"
        alt="Logo CST"
        :class="[props.size, 'h-auto']"
    />
</template>

<script setup>
const props = defineProps({
    size: {
        type: String,
        default: 'w-8'
    }
})
</script>