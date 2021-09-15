<?php namespace App\Controllers;

use App\Models\Home_model;

class Home extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function counties(){
        if ($this->request->isAJAX()) {
            $model = new Home_model();
            $results = $model->fetchCounties();
        }else{
            echo "No direct script access allowed";
        }
        echo json_encode($results);
    }

    public function cities(){
        if ($this->request->isAJAX()) {
            $ajax_data = $this->request->getPost();
            $model = new Home_model();
            $results = $model->getCitiesByCountyID($ajax_data['id']);
        }else{
            echo "No direct script access allowed";
        }
        echo json_encode($results);
    }

    public function insert(){
        if ($this->request->isAJAX()) {
            $ajax_data = $this->request->getPost();
            $model = new Home_model();
            $model->insertCity($ajax_data['city'], $ajax_data['county_id']);
        }else{
            echo "No direct script access allowed";
        }
    }

    public function update(){
        if ($this->request->isAJAX()) {
            $ajax_data = $this->request->getPost();
            $model = new Home_model();
            $model->updateCity($ajax_data['id'], $ajax_data['city_name']);
        }else{
            echo "No direct script access allowed";
        }
    }

    public function delete(){
        if ($this->request->isAJAX()) {
            $ajax_data = $this->request->getPost();
            $model = new Home_model();
            $model->deleteCity($ajax_data['id']);
        }else{
            echo "No direct script access allowed";
        }
    }
}
