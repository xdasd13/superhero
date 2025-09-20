<?php

namespace App\Controllers;

use App\Models\HeroModel;

class HeroController extends BaseController
{
    protected $heroModel;

    public function __construct()
    {
        $this->heroModel = new HeroModel();
    }

    /**
     * Muestra la vista principal de búsqueda de superhéroes
     */
    public function index()
    {
        return view('Reportes/reporte5');
    }

    /**
     * Endpoint para búsqueda AJAX de superhéroes por nombre
     * Devuelve sugerencias cuando el usuario escribe (ej: "bat" -> Batman, Batgirl, etc.)
     */
    public function search()
    {
        $request = $this->request->getJSON();
        $searchTerm = $request->term ?? '';

        if (strlen($searchTerm) < 2) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ingresa al menos 2 caracteres para buscar'
            ]);
        }

        try {
            $heroes = $this->heroModel->searchHeroesByName($searchTerm);
            
            return $this->response->setJSON([
                'success' => true,
                'heroes' => $heroes
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene información completa de un superhéroe específico
     */
    public function getHero($id)
    {
        try {
            $hero = $this->heroModel->getHeroCompleteInfo($id);
            
            if (!$hero) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Superhéroe no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'hero' => $hero
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener información: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Genera datos para PDF con solo los poderes del superhéroe
     */
    public function generatePDFData()
    {
        $request = $this->request->getJSON();
        $heroId = $request->heroId ?? null;

        if (!$heroId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de superhéroe requerido'
            ]);
        }

        try {
            $hero = $this->heroModel->getHeroById($heroId);
            $powers = $this->heroModel->getHeroPowers($heroId);

            if (!$hero) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Superhéroe no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'hero' => $hero,
                'powers' => $powers
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar datos del PDF: ' . $e->getMessage()
            ]);
        }
    }
}
