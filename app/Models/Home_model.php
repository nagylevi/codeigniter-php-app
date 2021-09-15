<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Home_model extends Model
{
    public function fetchCounties(){
        $db = Database::connect();
        $query = $db->query('SELECT * FROM county');
        $results = $query->getResult();
        return $results;
    }

    public function getCitiesByCountyID($id){
        $db = Database::connect();
        $query = $db->query('SELECT city.id, city.city_name FROM city WHERE county_id = '. $id);
        $results = $query->getResult();
        return $results;
    }

    public function insertCity($city, $county_id){
        $db = Database::connect();
        $db->query('INSERT INTO city (city_name, county_id) VALUES ("'. $city .'", '. $county_id .')');
    }

    public function updateCity($id, $city_name){
        $db = Database::connect();
        $db->query('UPDATE city SET city_name = "'. $city_name .'" WHERE city.id = '. $id);
    }

    public function deleteCity($id){
        $db = Database::connect();
        $db->query('DELETE FROM city WHERE city.id = ' . $id);
    }
}