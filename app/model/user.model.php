<?php
session_start();
use App\Interfaces\UserInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        
            if (count($data) <= 0 || !password_verify($params['password'], $data['password']))
                return ["status" => false, "http-code" => 400, "message" => "Email ou senha incorretos..", "data" => []];

            // aqui vai gerar o token:
            $payload = [
                "email" => $data['email'],
                "name"  => $data['name'],
                "role_permission" => $data["role_permission"],
                "id_user" => $data["id_user"],
                'iat' => time(),
                "exp" => time() + 60
            ];

            
            $token = JWT::encode($payload ,$_ENV['JWT_KEY'], "HS256");

            $response['token'] = $token;
            $_SESSION['token'] = $token;
            return ["status" => true, "http-code" => 200, "message" => "", "data" => $response];
        }catch (\Throwable $th) {
                throw new Exception($th->getMessage(), $th->getCode());
            }
    }
    /**
     * Responsible method to logout user
     * @author Iago <iagooliveira09@outlook.com>
     */
    static public function logout(array $params = null): array
    {

        try {
            session_unset();
            session_destroy();
            return ["status" => true, "http-code" => 200, "message" => "", "data" => []];
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
            $query = "SELECT * FROM user $filter_search";

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
            $res->bindValue(':role_permission', 2);

            if (!$res->execute())
                return ["status" => false, "http-code" => 400, "message" => "Usuário não cadastrado", "data" => []];
            
            return ["status" => true, "http-code" => 200, "message" => "Usuário registrado com sucesso!", "data" => []];

        }
        catch (\Throwable $th) {
            if ($th->getCode() == 23000) {
                return ["status" => false, "http-code" => 400, "message" => "Email já cadastrado...", "data" => []];

            }
            throw new Exception($th->getMessage(), $th->getCode());
        }


        
    }
}