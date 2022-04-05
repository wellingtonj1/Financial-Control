<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillsToReceiveCategoriesController extends BillsCategoriesController
{
    public function __construct()
    {
        $this->model = BillsToReceiveCategories::class;
        $this->itemsModel = BillsToReceive::class;
        $this->table = 'bills_to_receive_categories';
        $this->itemsTable = 'bills_to_receive';
    }

    
}
