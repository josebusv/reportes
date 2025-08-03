<!-- resources/js/Pages/Reports/Create.vue -->

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

// Props pasadas desde el controlador de Laravel
const props = defineProps({
    clients: Array,
    equipment: Array,
    engineers: Array,
    endoscopyChecklistItems: Array,
    electronicChecklistItems: Array,
    endoscopyRoutineItems: Array,
    electronicRoutineItems: Array,
});

// Formulario de Inertia
const form = useForm({
    client_name: '',
    contact_person_name: '',
    contact_person_position: '',
    client_address: '',
    client_phone: '',
    client_email: '',

    equipment_brand: '',
    equipment_model: '',
    equipment_inventory_number: '',
    equipment_location: '',
    equipment_accessories: '',

    report_number: '',
    report_date: new Date().toISOString().slice(0, 10), // Fecha actual por defecto
    report_type: 'endoscopy', // Tipo de reporte por defecto
    reported_failure: '',
    service_performed: '',
    visit_value: null,
    iva_value: null,
    billing_to: '',
    engineer_id: null,
    service_provider_name: '',

    service_types: [], // Array para los checkboxes de tipo de servicio

    initial_verification: {
        normal: false,
        irregular: false,
        out_of_service: false,
        requested_by_client: false,
    },

    checklist_status: [], // Array para los ítems del checklist
    routine_performed: [], // Array para las rutinas
});

// Computed properties para los ítems del checklist y rutinas según el tipo de reporte
const currentChecklistItems = computed(() => {
    return form.report_type === 'endoscopy' ? props.endoscopyChecklistItems : props.electronicChecklistItems;
});

const currentRoutineItems = computed(() => {
    return form.report_type === 'endoscopy' ? props.endoscopyRoutineItems : props.electronicRoutineItems;
});

// Watchers para inicializar checklist y rutinas cuando cambia el tipo de reporte
watch(currentChecklistItems, (newItems) => {
    form.checklist_status = newItems.map(item => ({
        id: item.id,
        status: 'not_applicable', // Estado por defecto
        value_1: null,
        value_2: null,
        value_3: null,
        value_4: null,
        angulation_up: false,
        angulation_down: false,
        angulation_left: false,
        angulation_right: false,
    }));
}, { immediate: true }); // Ejecutar inmediatamente al montar

watch(currentRoutineItems, (newItems) => {
    form.routine_performed = newItems.map(item => ({
        id: item.id,
        performed: false, // Estado por defecto
    }));
}, { immediate: true }); // Ejecutar inmediatamente al montar

// Función para manejar el envío del formulario
const submit = () => {
    form.post(route('reports.store'), {
        onSuccess: () => {
            // Resetear el formulario o mostrar un mensaje de éxito
            form.reset();
            alert('Reporte creado exitosamente!'); // Usar un modal real en una app de producción
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
            alert('Hubo errores al guardar el reporte. Revisa los campos.'); // Usar un modal real
        },
    });
};

// Lógica para autocompletar datos del cliente y equipo si se seleccionan existentes
const selectedClient = ref(null);
const selectedEquipment = ref(null);

watch(selectedClient, (newClient) => {
    if (newClient) {
        form.client_name = newClient.name;
        form.contact_person_name = newClient.contact_person_name;
        form.contact_person_position = newClient.contact_person_position;
        form.client_address = newClient.address;
        form.client_phone = newClient.phone;
        form.client_email = newClient.email;
    } else {
        // Limpiar campos si no se selecciona cliente
        form.contact_person_name = '';
        form.contact_person_position = '';
        form.client_address = '';
        form.client_phone = '';
        form.client_email = '';
    }
});

watch(selectedEquipment, (newEquipment) => {
    if (newEquipment) {
        form.equipment_brand = newEquipment.brand;
        form.equipment_model = newEquipment.model;
        form.equipment_inventory_number = newEquipment.inventory_number;
        form.equipment_location = newEquipment.location;
        form.equipment_accessories = newEquipment.accessories;
    } else {
        // Limpiar campos si no se selecciona equipo
        form.equipment_brand = '';
        form.equipment_model = '';
        form.equipment_inventory_number = '';
        form.equipment_location = '';
        form.equipment_accessories = '';
    }
});

// Función para obtener el nombre del ítem del checklist por su ID
const getChecklistItemName = (itemId) => {
    const item = currentChecklistItems.value.find(item => item.id === itemId);
    return item ? item.name : 'Desconocido';
};

// Función para obtener el nombre del ítem de rutina por su ID
const getRoutineItemName = (itemId) => {
    const item = currentRoutineItems.value.find(item => item.id === itemId);
    return item ? item.name : 'Desconocido';
};

</script>

<template>
    <Head title="Crear Reporte" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Nuevo Reporte</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Sección de Información del Reporte -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del Reporte</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="report_number" value="Número de Reporte (Automático)" />
                                    <TextInput
                                        id="report_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.report_number"
                                        :disabled="true" 
                                    />
                                    <InputError class="mt-2" :message="form.errors.report_number" />
                                </div>

                                <div>
                                    <InputLabel for="report_date" value="Fecha del Reporte" />
                                    <TextInput
                                        id="report_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.report_date"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.report_date" />
                                </div>

                                <div>
                                    <InputLabel for="report_type" value="Tipo de Reporte" />
                                    <select
                                        id="report_type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="form.report_type"
                                        required
                                    >
                                        <option value="endoscopy">Reporte Técnico Endoscopia</option>
                                        <option value="electronic">Reporte Técnico Electrónica</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.report_type" />
                                </div>

                                <div>
                                    <InputLabel for="engineer_id" value="Ingeniero" />
                                    <select
                                        id="engineer_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="form.engineer_id"
                                    >
                                        <option :value="null">Seleccionar Ingeniero</option>
                                        <option v-for="engineer in engineers" :key="engineer.id" :value="engineer.id">
                                            {{ engineer.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.engineer_id" />
                                </div>

                                <div>
                                    <InputLabel for="service_provider_name" value="Prestador del Servicio" />
                                    <TextInput
                                        id="service_provider_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.service_provider_name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.service_provider_name" />
                                </div>
                            </div>

                            <!-- Sección de Información del Cliente -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Información del Cliente</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="select_client" value="Seleccionar Cliente Existente" />
                                    <select
                                        id="select_client"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="selectedClient"
                                    >
                                        <option :value="null">Crear Nuevo Cliente</option>
                                        <option v-for="client in clients" :key="client.id" :value="client">
                                            {{ client.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <InputLabel for="client_name" value="Nombre de la Institución" />
                                    <TextInput
                                        id="client_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.client_name"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.client_name" />
                                </div>
                                <div>
                                    <InputLabel for="contact_person_name" value="Nombre Persona de Contacto" />
                                    <TextInput
                                        id="contact_person_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.contact_person_name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.contact_person_name" />
                                </div>
                                <div>
                                    <InputLabel for="contact_person_position" value="Cargo Persona de Contacto" />
                                    <TextInput
                                        id="contact_person_position"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.contact_person_position"
                                    />
                                    <InputError class="mt-2" :message="form.errors.contact_person_position" />
                                </div>
                                <div>
                                    <InputLabel for="client_address" value="Dirección del Cliente" />
                                    <TextInput
                                        id="client_address"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.client_address"
                                    />
                                    <InputError class="mt-2" :message="form.errors.client_address" />
                                </div>
                                <div>
                                    <InputLabel for="client_phone" value="Teléfono del Cliente" />
                                    <TextInput
                                        id="client_phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.client_phone"
                                    />
                                    <InputError class="mt-2" :message="form.errors.client_phone" />
                                </div>
                                <div>
                                    <InputLabel for="client_email" value="Email del Cliente" />
                                    <TextInput
                                        id="client_email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.client_email"
                                    />
                                    <InputError class="mt-2" :message="form.errors.client_email" />
                                </div>
                            </div>

                            <!-- Sección de Información del Equipo -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Información del Equipo</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="select_equipment" value="Seleccionar Equipo Existente" />
                                    <select
                                        id="select_equipment"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="selectedEquipment"
                                    >
                                        <option :value="null">Crear Nuevo Equipo</option>
                                        <option v-for="eq in equipment" :key="eq.id" :value="eq">
                                            {{ eq.brand }} - {{ eq.model }} ({{ eq.inventory_number }})
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <InputLabel for="equipment_brand" value="Marca del Equipo" />
                                    <TextInput
                                        id="equipment_brand"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.equipment_brand"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.equipment_brand" />
                                </div>
                                <div>
                                    <InputLabel for="equipment_model" value="Modelo del Equipo" />
                                    <TextInput
                                        id="equipment_model"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.equipment_model"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.equipment_model" />
                                </div>
                                <div>
                                    <InputLabel for="equipment_inventory_number" value="Número de Inventario" />
                                    <TextInput
                                        id="equipment_inventory_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.equipment_inventory_number"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.equipment_inventory_number" />
                                </div>
                                <div>
                                    <InputLabel for="equipment_location" value="Ubicación del Equipo" />
                                    <TextInput
                                        id="equipment_location"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.equipment_location"
                                    />
                                    <InputError class="mt-2" :message="form.errors.equipment_location" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel for="equipment_accessories" value="Accesorios" />
                                    <textarea
                                        id="equipment_accessories"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="form.equipment_accessories"
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.equipment_accessories" />
                                </div>
                            </div>

                            <!-- Sección de Tipo de Servicio -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Tipo de Servicio</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="service_corrective" value="corrective" v-model="form.service_types" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="service_corrective" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Correctivo</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service_warranty" value="warranty" v-model="form.service_types" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="service_warranty" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Garantía</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service_preventive" value="preventive" v-model="form.service_types" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="service_preventive" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Preventivo</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service_control" value="control" v-model="form.service_types" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="service_control" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Control</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="service_courtesy" value="courtesy" v-model="form.service_types" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="service_courtesy" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Cortesía</label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.service_types" />

                            <!-- Sección de Verificación Inicial -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Verificación Inicial</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="initial_normal" v-model="form.initial_verification.normal" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="initial_normal" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Normal</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="initial_irregular" v-model="form.initial_verification.irregular" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="initial_irregular" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Irregular</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="initial_out_of_service" v-model="form.initial_verification.out_of_service" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="initial_out_of_service" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Fuera de Servicio</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="initial_requested_by_client" v-model="form.initial_verification.requested_by_client" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label for="initial_requested_by_client" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Requerido por el cliente</label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors['initial_verification.normal']" />
                            <!-- ... y otros errores de initial_verification -->

                            <!-- Sección de Falla Reportada -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Falla Reportada</h3>
                            <div>
                                <textarea
                                    id="reported_failure"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="form.reported_failure"
                                    rows="3"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.reported_failure" />
                            </div>

                            <!-- Sección de Lista de Chequeo -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Lista de Chequeo ({{ form.report_type === 'endoscopy' ? 'Endoscopia' : 'Electrónica' }})</h3>
                            <div class="space-y-4">
                                <div v-for="(item, index) in form.checklist_status" :key="item.id" class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                    <InputLabel :value="getChecklistItemName(item.id)" />
                                    <div class="mt-2 flex flex-wrap gap-4 items-center">
                                        <div class="flex items-center">
                                            <input type="radio" :id="`status-${item.id}-good`" :name="`status-${item.id}`" value="good" v-model="item.status" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`status-${item.id}-good`" class="ml-2 text-sm text-gray-600 dark:text-gray-400">B-Bueno</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" :id="`status-${item.id}-regular`" :name="`status-${item.id}`" value="regular" v-model="item.status" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`status-${item.id}-regular`" class="ml-2 text-sm text-gray-600 dark:text-gray-400">R-Regular</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" :id="`status-${item.id}-bad`" :name="`status-${item.id}`" value="bad" v-model="item.status" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`status-${item.id}-bad`" class="ml-2 text-sm text-gray-600 dark:text-gray-400">M-Malo</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" :id="`status-${item.id}-na`" :name="`status-${item.id}`" value="not_applicable" v-model="item.status" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`status-${item.id}-na`" class="ml-2 text-sm text-gray-600 dark:text-gray-400">N.A-No Aplica</label>
                                        </div>

                                        <!-- Campos adicionales para Endoscopia si el ítem es 'Angulación' -->
                                        <div v-if="form.report_type === 'endoscopy' && getChecklistItemName(item.id) === 'Angulación'" class="flex items-center gap-2 ml-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Angulación:</span>
                                            <input type="checkbox" :id="`angulation-${item.id}-up`" v-model="item.angulation_up" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`angulation-${item.id}-up`" class="text-sm text-gray-600 dark:text-gray-400">UP</label>
                                            <input type="checkbox" :id="`angulation-${item.id}-down`" v-model="item.angulation_down" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`angulation-${item.id}-down`" class="text-sm text-gray-600 dark:text-gray-400">D</label>
                                            <input type="checkbox" :id="`angulation-${item.id}-left`" v-model="item.angulation_left" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`angulation-${item.id}-left`" class="text-sm text-gray-600 dark:text-gray-400">L</label>
                                            <input type="checkbox" :id="`angulation-${item.id}-right`" v-model="item.angulation_right" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                            <label :for="`angulation-${item.id}-right`" class="text-sm text-gray-600 dark:text-gray-400">R</label>
                                        </div>

                                        <!-- Campos 1, 2, 3, 4 para Endoscopia (todos los ítems) -->
                                        <div v-if="form.report_type === 'endoscopy'" class="flex items-center gap-2 ml-4">
                                            <InputLabel :for="`value1-${item.id}`" value="1" class="w-6" />
                                            <TextInput :id="`value1-${item.id}`" type="number" class="w-16" v-model="item.value_1" />
                                            <InputLabel :for="`value2-${item.id}`" value="2" class="w-6" />
                                            <TextInput :id="`value2-${item.id}`" type="number" class="w-16" v-model="item.value_2" />
                                            <InputLabel :for="`value3-${item.id}`" value="3" class="w-6" />
                                            <TextInput :id="`value3-${item.id}`" type="number" class="w-16" v-model="item.value_3" />
                                            <InputLabel :for="`value4-${item.id}`" value="4" class="w-6" />
                                            <TextInput :id="`value4-${item.id}`" type="number" class="w-16" v-model="item.value_4" />
                                        </div>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors[`checklist_status.${index}.status`]" />
                                    <!-- ... y otros errores para valores y angulacion -->
                                </div>
                            </div>

                            <!-- Sección de Rutinas -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Rutinas ({{ form.report_type === 'endoscopy' ? 'VDRL' : 'Mantenimiento' }})</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="(routine, index) in form.routine_performed" :key="routine.id" class="flex items-center">
                                    <input type="checkbox" :id="`routine-${routine.id}`" v-model="routine.performed" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <label :for="`routine-${routine.id}`" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ getRoutineItemName(routine.id) }}</label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors['routine_performed']" />


                            <!-- Sección de Servicio Realizado -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Servicio Realizado</h3>
                            <div>
                                <textarea
                                    id="service_performed"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="form.service_performed"
                                    rows="5"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.service_performed" />
                            </div>

                            <!-- Sección de Información de Facturación -->
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">Información de Facturación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="visit_value" value="Valor Visita" />
                                    <TextInput
                                        id="visit_value"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="form.visit_value"
                                    />
                                    <InputError class="mt-2" :message="form.errors.visit_value" />
                                </div>
                                <div>
                                    <InputLabel for="iva_value" value="+ I.V.A" />
                                    <TextInput
                                        id="iva_value"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="form.iva_value"
                                    />
                                    <InputError class="mt-2" :message="form.errors.iva_value" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel for="billing_to" value="Facturar a" />
                                    <TextInput
                                        id="billing_to"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.billing_to"
                                    />
                                    <InputError class="mt-2" :message="form.errors.billing_to" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Guardar Reporte
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
