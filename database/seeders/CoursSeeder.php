<?php
namespace Database\Seeders;

use App\Models\Cours;
use Illuminate\Database\Seeder;

class CoursSeeder extends Seeder
{
    public function run()
    {
        Cours::create([
            'nom' => 'Mathématiques',
            'description' => 'Cours de maths',
            'heure_debut' => '2025-03-10 09:00:00',
            'heure_fin' => '2025-03-10 11:00:00',
            'salle_id' => 1, // Assurez-vous qu’une salle existe
            'professeur_id' => 1, // Assurez-vous qu’un professeur existe
        ]);
    }
}
