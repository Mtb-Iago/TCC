<?php
namespace App\Interfaces;
interface PostsInterface
{
    /**
     * Responsible method to list posts
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function list_posts(array $params);
    
    /**
     * Responsible method to register post
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function insert_post(array $params);

    /**
     * Responsible method to update status post
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param array $params
     * @return array
     */
    static public function update_status_post(array $params);

    /**
     * Responsible method to register image
     * @author Iago Oliveira <iagooliveira09@outlook.com>
     * @param int $id_post
     * @param array $params
     * @return array
     */
    static public function image_base64_arq(int $id_post, array $archives);
}