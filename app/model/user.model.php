<?php
use App\Interfaces\UserInterface;
use Firebase\JWT\JWT;

class UserModel implements UserInterface
{
    
    /**
     * Responsible method to login user
     * @author Iago <iagooliveira09@outlook.com>
     */
    static public function login(array $params = null): array
    {

        try {
            $conn = new config();
            
            $pdo = $conn->conn();
            $query = "SELECT * FROM user WHERE email = :email";
            $data = $pdo->prepare($query);
            $data->bindValue(':email', $params['email']);
            
            if (!$data->execute())
            return ["status" => true, "http-code" => 200, "message" => "Users not found...", "data" => []];
            $data = $data->fetch(PDO::FETCH_ASSOC);
            
            if (!$data ||@count($data) <= 0 || !password_verify($params['password'], $data['password']))
                return ["status" => false, "http-code" => 400, "message" => "Email ou senha incorretos..", "data" => []];

            // aqui vai gerar o token:
            $payload = [
                "email" => $data['email'],
                "name"  => $data['name'],
                "role_permission" => $data["role_permission"],
                "id_user" => $data["id_user"],
                'iat' => time(),
                "exp" => time() + 5000
            ];

            
            $token = JWT::encode($payload ,$_ENV['JWT_KEY'], "HS256");

            $response['token'] = $token;
            return ["status" => true, "http-code" => 200, "message" => "", "data" => $response];
        }catch (\Throwable $th) {
                throw new Exception($th->getMessage(), $th->getCode());
            }
    }
    /**
     * Responsible method to list user
     * @author Iago <iagooliveira09@outlook.com>
     */
    static public function list_user(array $params = null): array
    {

        try {
            
            //Case search
            @$params['user_id'] ? $id_user = $params['user_id'] : $id_user = "";

            $id_user ? $filter_search = " WHERE id_user in ($id_user)" : $filter_search = "";
            $conn = new config();

            $pdo = $conn->conn();
            $query = "SELECT user.id_user AS ID_USER,
                             user.name AS NAME,
                             user.email AS EMAIL,
                             user.role_permission AS ROLE_PERMISSION
                         FROM user $filter_search";

            $data = $pdo->query($query);

            if (!$data->execute())
                return ["status" => true, "http-code" => 200, "message" => "Users not found...", "data" => []];
            $data = $data->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }


        if (count($data) <= 0)
            return ["status" => true, "http-code" => 200, "message" => "Users not found...", "data" => []];

        return ["status" => true, "http-code" => 200, "message" => "", "data" => $data];

    }


    /**
     * Responsible method to register user
     * @author Iago <iagooliveira09@outlook.com>
     * @return array
     */
    static public function insert_user(array $params = null)
    {

        if (!@$params['name'] || !@$params['email'] || !@$params['password']) {
            return ["status" => false, "http-code" => 400, "message" => "Por favor, preencha os campos...", "data" => []];
        }
        
        try {
            $password = password_hash($params['password'], PASSWORD_DEFAULT);

            $conn = new config();
            $pdo = $conn->conn();

            $query = "INSERT INTO user 
                                    (name, email, password, role_permission) values
                                    (:name, :email, :password, :role_permission)
                    ";

            $res = $pdo->prepare($query);

            $res->bindValue(':name',            $params['name']);
            $res->bindValue(':email',           $params['email']);
            $res->bindValue(':password',        $password);
            $res->bindValue(':role_permission', 'client');

            if (!$res->execute())
                return ["status" => false, "http-code" => 400, "message" => "Usu??rio n??o cadastrado", "data" => []];
            
            return ["status" => true, "http-code" => 200, "message" => "Usu??rio registrado com sucesso!", "data" => []];

        }
        catch (\Throwable $th) {
            if ($th->getCode() == 23000) {
                return ["status" => false, "http-code" => 400, "message" => "Email j?? cadastrado...", "data" => []];

            }
            throw new Exception($th->getMessage(), $th->getCode());
        }


        
    }
}