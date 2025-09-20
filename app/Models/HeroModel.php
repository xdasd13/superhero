<?php

namespace App\Models;

use CodeIgniter\Model;

class HeroModel extends Model
{
    protected $table = 'superhero';
    protected $primaryKey = 'id';
    protected $allowedFields = ['superhero_name', 'full_name'];

    /**
     * Busca superhéroes por nombre para autocompletado
     * Retorna una lista de sugerencias basada en el término de búsqueda
     */
    public function searchHeroesByName($searchTerm)
    {
        return $this->select('id, superhero_name as name, full_name as alias')
                    ->like('superhero_name', $searchTerm)
                    ->orLike('full_name', $searchTerm)
                    ->limit(10)
                    ->findAll();
    }

    /**
     * Obtiene un superhéroe específico por ID
     */
    public function getHeroById($id)
    {
        return $this->select('id, superhero_name as name, full_name as alias')
                    ->where('id', $id)
                    ->first();
    }

    /**
     * Obtiene los atributos de un superhéroe
     */
    public function getHeroAttributes($heroId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('hero_attribute ha')
                  ->select('a.attribute_name, ha.attribute_value')
                  ->join('attribute a', 'ha.attribute_id = a.id')
                  ->where('ha.hero_id', $heroId)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtiene los poderes de un superhéroe
     */
    public function getHeroPowers($heroId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('hero_power hp')
                  ->select('sp.power_name')
                  ->join('superpower sp', 'hp.power_id = sp.id')
                  ->where('hp.hero_id', $heroId)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Obtiene información completa de un superhéroe (datos básicos + atributos + poderes)
     */
    public function getHeroCompleteInfo($heroId)
    {
        // Obtener información básica
        $hero = $this->getHeroById($heroId);
        
        if (!$hero) {
            return null;
        }

        // Obtener atributos
        $attributes = $this->getHeroAttributes($heroId);
        
        // Obtener poderes
        $powers = $this->getHeroPowers($heroId);

        // Combinar toda la información
        $hero['attributes'] = $attributes;
        $hero['powers'] = $powers;

        return $hero;
    }

    /**
     * Busca superhéroes con información completa por nombre
     * Útil para mostrar resultados detallados en la búsqueda
     */
    public function searchHeroesWithDetails($searchTerm)
    {
        $heroes = $this->searchHeroesByName($searchTerm);
        
        foreach ($heroes as &$hero) {
            $hero['attributes'] = $this->getHeroAttributes($hero['id']);
            $hero['powers'] = $this->getHeroPowers($hero['id']);
        }
        
        return $heroes;
    }

    /**
     * Obtiene estadísticas de atributos de un superhéroe
     * Útil para mostrar gráficos o comparaciones
     */
    public function getHeroStats($heroId)
    {
        $db = \Config\Database::connect();
        
        $stats = $db->table('hero_attribute')
                    ->select('attribute_name, attribute_value')
                    ->where('hero_id', $heroId)
                    ->whereIn('attribute_name', ['Intelligence', 'Strength', 'Speed', 'Durability', 'Power', 'Combat'])
                    ->get()
                    ->getResultArray();

        // Convertir a formato clave-valor para fácil acceso
        $formattedStats = [];
        foreach ($stats as $stat) {
            $formattedStats[$stat['attribute_name']] = $stat['attribute_value'];
        }

        return $formattedStats;
    }
}
