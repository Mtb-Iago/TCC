<?php
namespace App\Interfaces;
interface CategoryInterface
{
    /**
     * Responsible method to list category
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function list_category(array $params);
    
    /**
     * Responsible method to register category
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function insert_category(array $params);
}