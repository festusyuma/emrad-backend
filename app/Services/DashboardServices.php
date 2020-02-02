<?php

namespace Emrad\Services;

use Emrad\Models\RetailerSale;
use Emrad\Models\RetailerOrder;
use Exception;


class DashboardServices
{

    public function getDashboardStats($user_id)
    {
        return [
            'monthly_sales' => $this->getMonthlySales($user_id),
            'num_products_sold' => $this->getNumOfProductsSold($user_id),
            'num_products_ordered' => $this->getNumOfProductsOrdered($user_id)
        ];

    }

    /**
     * Get monthly sales
     *
     * @return $monthly_sales
     */
    public function getMonthlySales($user_id)
    {
        $sales = RetailerSale::where('user_id', $user_id)->get();

        $monthly_sales = [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sept' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];

        foreach ($sales as $sale) {
            $month = $sale->updated_at->month;
            if($month == 1) {
                $monthly_sales['Jan'] += $sale->sale_amount;
            }if ($month == 2) {
                $monthly_sales['Feb'] += $sale->sale_amount;
            }if ($month == 3) {
                $monthly_sales['Mar'] += $sale->sale_amount;
            }if ($month == 4) {
                $monthly_sales['Apr'] += $sale->sale_amount;
            }if ($month == 5) {
                $monthly_sales['May'] += $sale->sale_amount;
            }if ($month == 6) {
                $monthly_sales['Jun'] += $sale->sale_amount;
            }if ($month == 7) {
                $monthly_sales['Jul'] += $sale->sale_amount;
            }if ($month == 8) {
                $monthly_sales['Aug'] += $sale->sale_amount;
            }if ($month == 9) {
                $monthly_sales['Sept'] += $sale->sale_amount;
            }if ($month == 10) {
                $monthly_sales['Oct'] += $sale->sale_amount;
            }if ($month == 11) {
                $monthly_sales['Nov'] += $sale->sale_amount;
            }if ($month == 12) {
                $monthly_sales['Dec'] += $sale->sale_amount;
            }
        }

        return $monthly_sales;
    }

    /**
     * Get number of products sold
     *
     * @return $numOfProductsSold
     */
    public function getNumOfProductsSold($user_id)
    {
        $sales = RetailerSale::where('user_id', $user_id)->get();

        $numOfProductsSold = 0;

        foreach($sales as $sale) {
            $numOfProductsSold += $sale->quantity;
        }

        return $numOfProductsSold;
    }


    /**
     * Get number of products ordered
     *
     * @return $numOfProductsOrdered
     */
    public function getNumOfProductsOrdered($user_id)
    {
        $orders = RetailerOrder::where('user_id', $user_id)->get();

        $numOfProductsOrdered = 0;

        foreach($orders as $order) {
            $numOfProductsOrdered += $order->quantity;
        }
        return $numOfProductsOrdered;
    }
}
