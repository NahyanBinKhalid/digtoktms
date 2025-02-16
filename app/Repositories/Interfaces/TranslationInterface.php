<?php

namespace App\Repositories\Interfaces;

interface TranslationInterface extends BaseInterface {
    
    public function listing($query = []);
    
}