<?php

class UserModel extends BaseModel{

    public function __construct()
    {
        $this->db = new PDODatabase;
        $this->init();
    }

    public function init(): void {

    }

    public function getAll(): array{
        return [];
    }

    public function getById(string $id): ?array {
        return null;
    }


    public function add(array $data): bool{
        $this->db->query("INSERT INTO Utilisateur (nom, prenom, email, telephone, mot_de_passe, verification_token, token_expiry ) VALUES (:nom, :prenom, :email, :telephone, :mot_de_passe, :verification_token, :token_expiry)");
       
        $this->db->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
        $this->db->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
        $this->db->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $this->db->bindValue(':telephone', $data['telephone'], PDO::PARAM_STR);
        $this->db->bindValue(':mot_de_passe', $data['mot_de_passe'], PDO::PARAM_STR);
        $this->db->bindValue(':verification_token', $data['verification_token'], PDO::PARAM_STR);
        $this->db->bindValue(':token_expiry', $data['token_expiry'], PDO::PARAM_STR);
        
        return $this->db->execute();
    }


    public function update(string $id, array $data): bool{
        return true;
    }


    public function delete(string $id): bool{
        return true;
    }


}


?>