<?php

namespace App\Models;

use CodeIgniter\Model;

class SelectModel extends Model
{
    function generateNextTravelOrderID()
    {
        $year  = date('Y');

        $base = "Travel-Order#-{$year}";

        $builder = $this->db->table('travel_orders');

        $builder->select('travel_order_number');
        $builder->like('travel_order_number', $base, 'after');
        $builder->orderBy('travel_order_number', 'DESC');
        $builder->limit(1);

        $result = $builder->get()->getRow();

        if ($result) {
            $parts = explode('-', $result->travel_order_number);
            $lastIncrement = (int) end($parts);
            $nextIncrement = $lastIncrement + 1;
        } else {
            $nextIncrement = 1;
        }

        $nextIncrementFormatted = str_pad($nextIncrement, 4, '0', STR_PAD_LEFT);

        return "{$base}-{$nextIncrementFormatted}";
    }


}
