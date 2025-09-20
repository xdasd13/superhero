<?php

namespace App\Controllers;

use App\Models\HeroModel;

class TestController extends BaseController
{
    /**
     * Prueba la conectividad de la base de datos y muestra algunas estadísticas
     */
    public function index()
    {
        $heroModel = new HeroModel();
        $db = \Config\Database::connect();
        
        $data = [
            'title' => 'Test de Conectividad - Sistema de Superhéroes',
            'db_connected' => false,
            'tables_exist' => [],
            'sample_heroes' => [],
            'error' => null
        ];

        try {
            // Probar conexión
            $db->connect();
            $data['db_connected'] = true;

            // Verificar tablas
            $tables = ['superhero', 'hero_attribute', 'hero_power', 'superpower', 'attribute'];
            foreach ($tables as $table) {
                $data['tables_exist'][$table] = $db->tableExists($table);
            }

            // Obtener algunos héroes de muestra
            if ($data['tables_exist']['superhero']) {
                $data['sample_heroes'] = $heroModel->limit(5)->findAll();
            }

        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return view('test_connectivity', $data);
    }

    /**
     * API endpoint para probar la búsqueda
     */
    public function testSearch()
    {
        $heroModel = new HeroModel();
        
        try {
            $heroes = $heroModel->searchHeroesByName('bat');
            
            return $this->response->setJSON([
                'success' => true,
                'count' => count($heroes),
                'heroes' => $heroes
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
