<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    equipment: Array, // Propiedad que contendrá la lista de equipos
});
</script>

<template>
    <Head title="Mis Equipos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Mis Equipos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="equipment.length > 0">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="item in equipment" :key="item.id" class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-medium">{{ item.brand }} - {{ item.model }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Inventario: {{ item.inventory_number }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400" v-if="item.client">Cliente: {{ item.client.name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400" v-if="item.location">Ubicación: {{ item.location }}</p>
                                        </div>
                                        <!-- Aquí podrías añadir un botón para solicitar mantenimiento para este equipo -->
                                        <!-- <Link :href="route('maintenance-requests.create', { equipment_id: item.id })" class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Solicitar Mantenimiento</Link> -->
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-center text-gray-500 dark:text-gray-400">
                            No tienes equipos asociados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>