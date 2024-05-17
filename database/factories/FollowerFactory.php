<?php

namespace Database\Factories;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follower>
 */
class FollowerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener los IDs de usuarios
        $user_ids = User::pluck('id')->toArray();
        static $combinations = [];

        $existingCombinations = function () {
            return Follower::select('follower_id', 'following_id')->get()->toArray();
        };

        // Mezclar los ids de usuario para obtener combinaciones aleatorias
        shuffle($user_ids);

        // Intentar encontrar una combinación única
        do {
            $follower_id = fake()->randomElement($user_ids);
            $following_ids = array_column(array_filter($combinations, function ($combination) use ($follower_id) {
                return $combination['follower_id'] == $follower_id;
            }), 'following_id');
            
            // Excluir combinaciones existentes en la base de datos
            $existing = $existingCombinations();
            $existing_following_ids = array_column(array_filter($existing, function ($combination) use ($follower_id) {
                return $combination['follower_id'] == $follower_id;
            }), 'following_id');
            
            // Usuarios disponibles para seguir (excluyendo a sí mismo y seguidores existentes)
            $available_user_ids = array_diff($user_ids, [$follower_id], $following_ids, $existing_following_ids);

            if (empty($available_user_ids)) {
                // No hay más combinaciones únicas posibles
                return [];
            }

            $following_id = fake()->randomElement($available_user_ids);
        } while (in_array(['follower_id' => $follower_id, 'following_id' => $following_id], $combinations));

        if (isset($following_id)) {
            // Registrar la combinación generada
            $combinations[] = [
                'follower_id' => $follower_id,
                'following_id' => $following_id,
            ];
            return [
                'follower_id' => $follower_id,
                'following_id' => $following_id,
            ];
        }

        // Retorno por defecto si no se encuentra combinación
        return [
            'follower_id' => User::inRandomOrder()->first()->id, // Valor por defecto para evitar errores de inserción
            'following_id' => User::inRandomOrder()->first()->id, // Valor por defecto para evitar errores de inserción
        ];

        //_ Y aun asi da error cuando se generan muchos followers para pocos usuarios, no se que mas hacer dios
    }
}
