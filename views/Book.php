
<div>
    <h1>Book</h1>
    <!-- Get extracted data from renderView method -->
    <ul>
        <!-- Display the book's ID -->
        <li>ID: <?= $book->id ?></li>
        <!-- Display the book's ISBN -->
        <li>ISBN: <?= $book->isbn ?></li>
        <!-- Display the book's title -->
        <li>Title: <?= $book->title ?></li>
        <!-- Display the book's author -->
        <li>Author: <?= $book->author ?></li>
        <!-- Display the date the book was added -->
        <li>Date Added: <?= $book->date_added ?></li>
    </ul>
   
    
</div>