<?php

    namespace Database\Seeders;

    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash; // Asegúrate de importar Hash

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            // Crea un usuario de ejemplo (puedes eliminarlo o modificarlo)
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'), // Contraseña por defecto
            ]);

            $this->call([
                ChecklistItemsSeeder::class,
                RoutineItemsSeeder::class,
            ]);
        }
    }
