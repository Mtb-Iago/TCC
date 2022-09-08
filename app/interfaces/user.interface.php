<?php
namespace App\Interfaces;
interface UserInterface
{
    /**
     * Responsible method to list user
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function list_user(array $params);
    
    /**
     * Responsible method to register user
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function insert_user(array $params);
}