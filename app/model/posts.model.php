<?php
use App\Interfaces\PostsInterface;

define("PATH_UPLOAD_ARCHIVES", "app/archives/");

class PostsModel implements PostsInterface
{
    /**
     * Responsible method to list user
     * @author Iago <iagooliveira09@outlook.com>
     */

    static public function list_posts(array $params = null): array
    {

        try {

            //Case search
            @$params['id_post'] ? $id_post = $params['id_post']             : $id_post = "";
            @$params['id_category'] ? $id_category = $params['id_category'] : $id_category = "";
            @$params['tag'] ? $tag = $params['tag'] : $tag = "";

            $id_post ? $filter_search = " WHERE posts.id_post in ($id_post)" : $filter_search = "";
            $id_category ? $filter_search = " WHERE posts.id_category in ($id_category)" : $filter_search;
            $tag ? $filter_search = " WHERE tags.tag LIKE '%$tag%' " : $filter_search;

            $conn = new config();

            $pdo = $conn->conn();
            $query = "SELECT 
                            user.name,
                            posts.id_post,
                            posts.id_user,
                            posts.id_category,
                            category_posts.name_category,
                            posts.title_post,
                            posts.post,
                            posts.accept_post,
                            archives.id_post as archives_id_post,
                            archives.id_archive,
                            archives.name_archive,
                            archives.url_archive,
                            tags.id_post as tag_id_post,
                            tags.id_tag,
                            tags.tag,
                            posts.created_at
                             FROM posts 
                            LEFT JOIN archives ON archives.id_post = posts.id_post
                            LEFT JOIN category_posts ON category_posts.id_category = posts.id_category
                            LEFT JOIN user ON  posts.id_user = user.id_user
                            LEFT JOIN tags ON  tags.id_post = posts.id_post
                        $filter_search

                        ORDER BY posts.id_post DESC
                    ";

            $data = $pdo->query($query);

            if (!$data->execute())
                return ["status" => true, "http-code" => 200, "message" => "Posts not found...", "data" => []];
            $data = $data->fetchAll(PDO::FETCH_ASSOC);
        


            if (count($data) <= 0)
                return ["status" => true, "http-code" => 200, "message" => "Posts not found...", "data" => []];


            foreach ($data as $value) {
                $data_convert[] = $value["id_post"];
            }
            $data_finally = [];
            $data_convert = array_unique($data_convert, SORT_REGULAR);
            
            foreach ($data as $key => $value) {
                foreach ($data_convert as $key1 => $id_post) {
                    if ($id_post == $value["archives_id_post"]) {
                        $archives[$key1][] = [
                            "id_archive" => $value["id_archive"],
                            "name_archive" => $value["name_archive"],
                            "url_photos" => $value["url_archive"]
                        ];
                    }

                    if ($id_post == $value["tag_id_post"]) {
                        $tags[$key1] = [
                            "id_tag_post" => $value["id_tag"],
                            "tag" => $value["tag"]
                        ];
                    }
                    
                    if ($id_post == $value["id_post"]) {
                        $data_finally[$key1] = [
                            "id_post" => $value["id_post"],
                            "id_category" => $value["id_category"],
                            "name_category" => $value["name_category"],
                            "id_user" => $value["id_user"],
                            "name_user" => $value["name"],
                            "title_post" => $value["title_post"],
                            "post" => $value["post"],
                            "accept_post" => $value["accept_post"],
                            "tags_post" => @$tags,
                            "url_archives" => @$archives[$key1],
                            "created_at" => $value["created_at"]
                        ];
                    }
                }
            }

            foreach ($data_finally as $key => $value) {
                if ($data_finally[$key]["url_archives"]) {
                    $data_finally[$key]["url_archives"] = array_values(array_unique($data_finally[$key]["url_archives"], SORT_REGULAR));
                }
                if ($data_finally[$key]["tags_post"]) {
                    $data_finally[$key]["tags_post"] = array_values(array_unique($data_finally[$key]["tags_post"], SORT_REGULAR));
                }
            }

            $data = array_values($data_finally);

            return ["status" => true, "http-code" => 200, "message" => "", "data" => $data];

        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }

    }


    /**
     * Responsible method to register post
     * @author Iago <iagooliveira09@outlook.com>
     * @return array
     */
    static public function insert_post(array $params = null)
    {

        if (!@$params['id_user'] || !@$params['post'] || !@$params['id_category']) {
            return ["status" => false, "http-code" => 400, "message" => "Por favor, preencha os campos...", "data" => []];
        }
        
        @$params['files'][0]['file'] ? $file = $params['files'] : $file = null;

        try {
            $conn = new config();
            $pdo = $conn->conn();

            $query = "INSERT INTO posts 
                        (id_user, title_post, post, id_category) values
                        (:id_user, :title_post, :post, :id_category)
                    ";

            $res = $pdo->prepare($query);

            $res->bindValue(':id_user',         $params['id_user']);
            $res->bindValue(':id_category',     $params['id_category']);
            $res->bindValue(':title_post',      $params['title_post']);
            $res->bindValue(':post',            $params['post']);

            if (!$res->execute())
                return ["status" => false, "http-code" => 400, "message" => "Post n??o cadastrado", "data" => []];
            
            $id_post = $pdo->lastInsertId();
            if (@$file) {
                PostsModel::image_base64_arq($id_post, $file);
            }

            if ($params['tag']) {
                PostsModel::insert_tag($id_post, $params['tag']);
            }
            return ["status" => true, "http-code" => 200, "message" => "Post registrado com sucesso. Seu post passar?? por modera????o e ap??s aprova????o ficar?? visiv??l", "data" => []];

        }
        catch (\Throwable $th) {
            if ($th->getCode() == 23000) {
                return ["status" => false, "http-code" => 400, "message" => "Email j?? cadastrado...", "data" => []];

            }
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Responsible method to update post
     * @author Iago <iagooliveira09@outlook.com>
     * @return array|string
     */
    static public function update_status_post($params)
    {
        try {
            $conn = new config();
            $pdo = $conn->conn();

            $id_post = $params['id_post'];

            $accept_value = $params["accept_post"];
            $pdo->beginTransaction();
            $query = "UPDATE
                        posts 
                        SET
                        accept_post = :accept_post
                    WHERE id_post = :id_post
                        ";

            $data = $pdo->prepare($query);

            $data->bindValue(":id_post", $id_post);
            $data->bindValue(":accept_post", $accept_value);

            if (!$data->execute()) {
                $pdo->rollBack();
                return ["status" => false, "http-code" => 400, "message" => "Ooops, Error Update Post", "data" => []];
            }

            $pdo->commit();

            return ["status" => true, "http-code" => 200, "message" => "Visibilidade de post alterada com sucesso!", "data" => []];
        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }

    }


    /**
     * Responsible method to register tag in post
     * @author Iago <iagooliveira09@outlook.com>
     * @param int $id_post
     * @param string $tag
     * @return void
     */
    static public function insert_tag(int $id_post, string $tag) : void
    {

        if (!$id_post || !@$tag) exit;

        try {
            $conn = new config();
            $pdo = $conn->conn();

            $query = "INSERT INTO tags 
                        (id_post, tag) values
                        (:id_post, :tag)
                    ";

            $res = $pdo->prepare($query);

            $res->bindValue(':id_post', $id_post);
            $res->bindValue(':tag',     $tag);

            $res->execute();

        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }


        
    }


    /**
     * Responsible method to register post
     * @param int|string $id_post
     * @param array $photos_base64
     * @author Iago <iagooliveira09@outlook.com>
     * @return array
     */
    static public function image_base64_arq($id_post, $photos_base64)
    {
        chmod (PATH_UPLOAD_ARCHIVES, 755);
        $conn = new config();
        $pdoo = $conn->conn();

        $url_archives = [];

        try {
            foreach ($photos_base64 as $key => $photo_base64) {

                $photo_base64 = trim($photo_base64["file"]);
                if (substr($photo_base64, 0, 4) != 'data') {
                    $photo_base64 = "data:image/jpeg;base64,{$photo_base64}";
                }

                $name_archive = bin2hex(random_bytes(5)) . '_time_' . time() . '_archive_' . str_pad($id_post, 6, '0', STR_PAD_LEFT) . '.jpg';
                @file_put_contents(PATH_UPLOAD_ARCHIVES . "{$name_archive}", file_get_contents($photo_base64));
                $url_archives[$key] = "http://localhost:8001/" . PATH_UPLOAD_ARCHIVES . $name_archive;

                $query = "INSERT
                INTO archives (
                    id_post, 
                    name_archive, 
                    url_archive 
                    ) VALUES (
                    :id_post,
                    :name_archive,
                    :url_archive
                    )
                ";


                $data = $pdoo->prepare($query);
                $data->bindValue(":id_post", $id_post);
                $data->bindValue(":name_archive", $name_archive);
                $data->bindValue(":url_archive", $url_archives[$key]);

                
                if (!$data->execute())
                    return ["status" => false, "http-code" => 400, "message" => "Ooops, Error Add Archive", "data" => []];
            }

            return ["status" => true, "data" => [
                    "name_archive" => $name_archive,
                    "path_archive" => $url_archives
                ]];
        }
        catch (\Throwable $th) {
            return ["status" => false, "data" => []];
        }


    }
}