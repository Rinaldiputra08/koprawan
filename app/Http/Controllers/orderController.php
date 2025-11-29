<?php

namespace App\Http\Controllers;

use App\DataTables\OrderDataTable;
use Illuminate\Http\Request;

class orderController extends Controller
{
    public function index(OrderDataTable $datatable)
    {
        return $datatable->render('order.order');
    }
}
