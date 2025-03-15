<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Salle;
use App\Models\Cours;
use App\Models\Emargement;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Utilisateurs
        User::create([
            'nom' => 'Dupont', 'prenom' => 'Jean', 'email' => 'admin@isi.com',
            'password' => bcrypt('password'), 'role' => 'admin'
        ]);
        User::create([
            'nom' => 'Martin', 'prenom' => 'Sophie', 'email' => 'prof1@isi.com',
            'password' => bcrypt('password'), 'role' => 'professeur'
        ]);
        User::create([
            'nom' => 'Leroy', 'prenom' => 'Paul', 'email' => 'gestion@isi.com',
            'password' => bcrypt('password'), 'role' => 'gestionnaire'
        ]);

        // Salles
        Salle::create(['libelle' => 'Salle A1']);
        Salle::create(['libelle' => 'Salle B2']);

        // Cours
        Cours::create([
            'nom' => 'Programmation Laravel', 'description' => 'Cours avancé sur Laravel',
            'heure_debut' => '2025-03-10 09:00:00', 'heure_fin' => '2025-03-10 11:00:00',
            'salle_id' => 1, 'professeur_id' => 2
        ]);
        Cours::create([
            'nom' => 'Base de données', 'description' => 'Introduction à PostgreSQL',
            'heure_debut' => '2025-03-10 13:00:00', 'heure_fin' => '2025-03-10 15:00:00',
            'salle_id' => 2, 'professeur_id' => 2
        ]);

        // Émargements
        Emargement::create([
            'date' => '2025-03-10 09:00:00', 'statut' => 'présent',
            'professeur_id' => 2, 'cours_id' => 1
        ]);
        Emargement::create([
            'date' => '2025-03-10 13:00:00', 'statut' => 'absent',
            'professeur_id' => 2, 'cours_id' => 2
        ]);
    }
}
