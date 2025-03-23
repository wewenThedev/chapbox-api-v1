<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $model = Brand::class;
    public function run(): void
    {
        $brands = [
            // Alimentaire
            [
                'name' => 'Délice de l\'Atacora',
                'website' => 'delice-atacora.bj',
                'infos' => 'Spécialiste des jus de fruits locaux et confitures artisanales'
            ],
            [
                'name' => 'Bénin Control',
                'website' => 'benincontrol.bj',
                'infos' => 'Huiles végétales et sucre de qualité certifiée'
            ],
            [
                'name' => 'La Béninoise',
                'website' => null,
                'infos' => 'Lait et produits laitiers fabriqués au Bénin'
            ],

            // Boissons
            [
                'name' => 'Jus Aphrodite',
                //'website' => 'https:\/\/jusaphrodite.com',
                'website' => 'jusaphrodite.com',
                'infos' => 'Jus naturels de mangue, ananas et baobab'
            ],
            [
                'name' => 'Brasserie La Béninoise',
                'website' => 'brasserie-benin.bj',
                'infos' => 'Producteur de la bière La Béninoise et sodas'
            ],

            // Produits de la mer
            [
                'name' => 'Agoué Fish',
                'website' => null,
                'infos' => 'Poissons fumés et produits halieutiques traditionnels'
            ],

            // Cosmétiques
            [
                'name' => 'Super Wax',
                'website' => null,
                'infos' => 'Savons traditionnels à base de karité'
            ],

            // Entretien
            [
                'name' => 'Alodo',
                'website' => null,
                'infos' => 'Lessive et produits ménagers made in Benin'
            ],

            // Céréales
            [
                'name' => 'Zomayi',
                'website' => null,
                'infos' => 'Farines de maïs et sorgho traditionnels'
            ]
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'website' => $brand['website'],
                'infos' => $brand['infos'],
                'logo_id' => null // À remplacer par des ID médias existants si nécessaire
            ]);
        }

        $this->command->info(count($brands) . ' marques béninoises créées !');
    }
}
