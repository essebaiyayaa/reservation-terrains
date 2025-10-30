<?php

class BookController extends BaseController{

    public function index(): void {

        $bookModel = $this->loadModel("BookModel");
        /** @var BookModel $bookModel */
        $books = $bookModel->getAll();

        $this->renderView('Books', ["books" => $books]);

    }

    public function show(string $id): void {
        
        $bookModel = $this->loadModel("BookModel");
        /** @var BookModel $bookModel */
        $book = $bookModel->getById($id);
        
        $this->renderView('Book', ["book" => $book], $book['title']);
    }


    public function create(): void{
         
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $bookModel = $this->loadModel("BookModel");
            $data = [
                "title" => $_POST["title"],
                "author" => $_POST["author"],
                "isbn" => $_POST['isbn']
            ];
            /** @var BookModel $bookModel */
            $bookModel->add($data);
        
        }
        
        $this->renderView('AddBook');
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