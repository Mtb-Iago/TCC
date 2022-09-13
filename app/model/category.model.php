<?php
use App\Interfaces\CategoryInterface;

class CategoryModel implements CategoryInterface
{
    /**
     * Responsible method to list category
     * @author Iago <iagooliveira09@outlook.com>
     */

    static public function list_category(array $params = null): array
    {

        try {

            //Case search
            @$params['id_category'] ? $id_category = $params['id_category'] : $id_category = "";
            @$params['name_category'] ? $name_category = $params['name_category'] : $name_category = "";

            $id_category ? $filter_search = " WHERE id_category in ($id_category)" : $filter_search = "";
            $name_category ? $filter_search = " WHERE name_category LIKE '%$name_category%' " : $filter_search = $filter_search;

            $conn = new config();

            $pdo = $conn->conn();
            $query = "SELECT * FROM category_posts $filter_search";

            $data = $pdo->query($query);

            if (!$data->execute())
                return ["status" => true, "http-code" => 200, "message" => "Category not found...", "data" => []];
            $data = $data->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }


        if (count($data) <= 0)
            return ["status" => true, "http-code" => 200, "message" => "Category not found...", "data" => []];

        return ["status" => true, "http-code" => 200, "message" => "", "data" => $data];

    }

    /**
     * Responsible method to register category
     * @author Iago <iagooliveira09@outlook.com>
     * @return array
     */
    static public function insert_category(array $params = null)
    {

        if (!@$params['name_category']) {
            return ["status" => false, "http-code" => 400, "message" => "Por favor, preencha os campos...", "data" => []];
        }

        try {
            $conn = new config();
            $pdo = $conn->conn();

            $query = "INSERT INTO category_posts 
                        (name_category) values
                        (:name_category)
                    ";
            $res = $pdo->prepare($query);
            
            $res->bindValue(':name_category',$params['name_category']);
            
            if (!$res->execute())
                return ["status" => false, "http-code" => 400, "message" => "Categoria não cadastrado", "data" => []];

            return ["status" => true, "http-code" => 200, "message" => "Categoria registrada com sucesso!", "data" => []];

        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }
}