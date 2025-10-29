<?php

class BookModel extends BaseModel {

    public function __construct()
    {
        parent::__construct();
    }

    public function init(): void {

    }

    public function getAll(): array {
        $this->db->query("SELECT * FROM book");
        $this->db->execute();
        return $this->db->results();
    }

    public function getById(string $id): ?array{

        $this->db->query("SELECT * FROM book WHERE id = :id");

        $this->db->bindValue(':id', $id, PDO::PARAM_INT);

        $this->db->execute();

        $book = $this->db->result();

        return $book ?: null;
    }

    public function add(array $data): bool {

        $this->db->query("INSERT INTO book (isbn, title, author) VALUES (:isbn, :title, :author)");

        $this->db->bindValue(':isbn', $data['isbn'], PDO::PARAM_STR);
        $this->db->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $this->db->bindValue(':author', $data['author'], PDO::PARAM_STR);

        
        return $this->db->execute();
    }

    function update(string $id, array $data): bool{
        
        $this->db->query("
            UPDATE book 
            SET 
                isbn = :isbn,
                title = :title,
                author = :author
            WHERE id = :id
        ");

        
        $this->db->bindValue(':isbn', $data['isbn'], PDO::PARAM_STR);
        $this->db->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $this->db->bindValue(':author', $data['author'], PDO::PARAM_STR);
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);

       
        return $this->db->execute();
    }

    function delete(string $id): bool{
       
        $this->db->query("DELETE FROM book WHERE id = :id");
        $this->db->bindValue(':id', $id, PDO::PARAM_INT);

        return $this->db->execute();
    }
}

?>