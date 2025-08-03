<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="CST - Servicios en Equipos Médicos" />

    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <!-- Hero -->
        <section class="bg-gradient-to-br from-blue-900 to-cyan-400 text-white py-20 text-center">
            <div class="container mx-auto px-4">
                <img src="/logos/logo_cst.webp" alt="Logo CST" class="mx-auto mb-6 w-48">
                <h1 class="text-4xl font-bold mb-4">Cuidado Profesional para tu Tecnología Médica</h1>
                <p class="text-lg mb-6">Mantenimiento y asesoría técnica para equipos médicos con estándares de calidad.</p>
                <a href="#formulario" class="bg-white text-blue-900 px-6 py-3 rounded-full font-semibold hover:bg-gray-100">Solicita Asesoría</a>
            </div>
        </section>

        <!-- Beneficios -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-8">¿Por qué elegir CST?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 bg-white rounded-xl shadow">
                        <h3 class="text-xl font-semibold mb-2">Técnicos Especializados</h3>
                        <p>Equipo certificado con experiencia en mantenimiento de alta complejidad.</p>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow">
                        <h3 class="text-xl font-semibold mb-2">Respuesta Rápida</h3>
                        <p>Atención oportuna para garantizar la operación continua de tu institución.</p>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow">
                        <h3 class="text-xl font-semibold mb-2">Asesoría Personalizada</h3>
                        <p>Recomendaciones técnicas y normativas según tu necesidad.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonios -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-10">Testimonios</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-gray-50 rounded-xl shadow">
                        <p class="italic">"Desde que trabajamos con CST, nuestros equipos están siempre operativos y seguros. Su servicio es impecable."</p>
                        <p class="mt-4 font-semibold">- Dra. Carolina Rojas, Centro Médico VidaPlus</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-xl shadow">
                        <p class="italic">"Confío plenamente en su asesoría. Nos ayudaron a cumplir con las normas sin complicaciones."</p>
                        <p class="mt-4 font-semibold">- Ing. Jorge Pineda, Biomédico Hospital del Norte</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Llamado a la Acción -->
        <section class="py-16 bg-cyan-400 text-white text-center">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-4">Solicita una asesoría gratuita</h2>
                <p class="mb-6">Te orientamos sobre el estado actual de tus equipos y lo que puedes mejorar.</p>
                <a href="#formulario" class="bg-white text-cyan-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-100">Contáctanos Ahora</a>
            </div>
        </section>

        <!-- Formulario -->
        <section id="formulario" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4 max-w-2xl">
                <h2 class="text-3xl font-bold text-center mb-8">Formulario de Contacto</h2>
                <form class="bg-white p-6 rounded-xl shadow space-y-4">
                    <input type="text" placeholder="Nombre completo" class="w-full border p-3 rounded">
                    <input type="email" placeholder="Correo electrónico" class="w-full border p-3 rounded">
                    <input type="text" placeholder="Institución / Empresa" class="w-full border p-3 rounded">
                    <textarea placeholder="¿En qué podemos ayudarte?" rows="4" class="w-full border p-3 rounded"></textarea>
                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded font-semibold hover:bg-blue-800">Enviar Mensaje</button>
                </form>
            </div>
        </section>

        <!-- Pie de página -->
        <footer class="bg-blue-900 text-white text-center py-6">
            <div class="container mx-auto px-4">
                <p>&copy; 2025 CST. Todos los derechos reservados.</p>
                <div class="flex justify-center space-x-4 mt-2">
                    <a href="#" class="hover:text-cyan-300">Facebook</a>
                    <a href="#" class="hover:text-cyan-300">LinkedIn</a>
                    <a href="#" class="hover:text-cyan-300">WhatsApp</a>
                    <a href="/login" class="hover:text-cyan-300">Gestión</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
* { box-sizing: inherit; }

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
