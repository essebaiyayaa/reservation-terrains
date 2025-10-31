<?php

class AuthController extends BaseController {
    public function index(): void {
        $this->renderView('Auth/Register', []);
    }

    public function show(string $id): void {
        
        $bookModel = $this->loadModel("BookModel");
        /** @var BookModel $bookModel */
        $book = $bookModel->getById($id);
        
        $this->renderView('Book', ["book" => $book], $book['title']);
    }


    public function create(): void{
        $this->index();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $userModel = $this->loadModel("UserModel");

            $nom            = trim($_POST["nom"] ?? '');
            $prenom         = trim($_POST["prenom"] ?? '');
            $email          = trim($_POST["email"] ?? '');
            $telephone      = trim($_POST["telephone"] ?? '');
            $mot_de_passe   = $_POST["mot_de_passe"] ?? '';
            
            $errors = [];

            if (empty($nom))         $errors[] = "Le nom est obligatoire.";
            if (empty($prenom))      $errors[] = "Le prénom est obligatoire.";
            if (empty($email))       $errors[] = "L'adresse e-mail est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse e-mail n'est pas valide.";
            if (empty($telephone))   $errors[] = "Le numéro de téléphone est obligatoire.";
            if (!preg_match('/^[0-9]{8,15}$/', $telephone)) $errors[] = "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.";
            if (empty($mot_de_passe)) $errors[] = "Le mot de passe est obligatoire.";
            if (strlen($mot_de_passe) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";


            if (!empty($errors)) {
                $this->renderView('Auth/Register', ["errors" => $errors]);
                exit;
            }

            $hashedPassword = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            $verification_token = Utils::generateRandomInt(8);
            $token_expiry_minutes = 30;

            
            $data = [
                "nom" => htmlspecialchars($nom),
                "prenom" => htmlspecialchars($prenom),
                "email" => htmlspecialchars($email),
                "telephone" => htmlspecialchars($telephone),
                "mot_de_passe" => $hashedPassword,
                "verification_token" => $verification_token,
                "token_expiry" => Utils::generateVerificationTokenExpiry($token_expiry_minutes)
            ];
            /** @var UserModel $userModel */
           
            $exec = $userModel->add($data); 
            
            if($exec){

                Utils::sendVerificationEmail(
                    htmlspecialchars($email),
                    htmlspecialchars($prenom),
                    $verification_token, 
                    $token_expiry_minutes
                );

                session_start();

                $_SESSION['account_email'] = htmlspecialchars($email);

                //TODO: Redirection to Verification page    

                exit;
            }else{
                $this->renderView('Errors/Error', [
                    "message" => "Impossible de créer le compte utilisateur.",
                    "errors" => $errors
                ]);
            }
        
        }
        
    }


    public function edit(string $id): void{
        
     
        $bookModel = $this->loadModel("BookModel");
        
       
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
             /** @var BookModel $bookModel */

             $data = [
                "title" => $_POST["title"],
                "author" => $_POST["author"],
                "isbn" => $_POST['isbn']
            ];
            
            $bookModel->update($id, $data);
            
        }
        
         /** @var BookModel $bookModel */
        // $book = $bookModel->getById($id);
        // $book = "hello";

        // echo var_dump($book);
        
        $this->renderView('UpdateBook', ["book" => ""]);
    
    }

    public function delete(string $id): void{
        $bookModel = $this->loadModel("BookModel");
        /** @var BookModel $bookModel */
        $bookModel->delete($id);
    }
}



?>