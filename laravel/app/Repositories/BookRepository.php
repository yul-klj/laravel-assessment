<?php

namespace App\Repositories;

use App\Models\Book;

/**
 * Class BookRepository
 *
 * @property string $title  Book title
 * @property string $author Book Author
 *
 * @package App\Repositories
 * @author Yul <yul_klj@hotmail.com>
 */
class BookRepository
{
    /**
     * Set Pagination default amount
     *
     */
    const PAGINATE = 10;

    /**
     * Load model into repository
     *
     * @return Book
     */
    protected function getModel()
    {
        return new Book();
    }

    /**
     * Get Book by id
     *
     * @param int $id Book id
     * @return Book
     */
    public function getById($id)
    {
        $bookModel = $this->getModel();

        return $bookModel::find($id);
    }

    /**
     * Get All Books
     *
     * @return array
     */
    public function getAll()
    {
        $bookModel = $this->getModel();

        return $bookModel::paginate(self::PAGINATE);
    }

    /**
     * Create Book
     *
     * @param array $data Book data
     * @return Book
     * @throws \Exception
     */
    public function create(array $data)
    {
        $bookModel = $this->getModel();

        $bookModel->fill($data);
        $bookModel->save();

        return $bookModel;
    }

    /**
     * Update book
     *
     * @param Book  $book book object
     * @param array $data data to be updated
     * @return Book
     * @throws \Exception
     */
    public function update(Book $book, array $data)
    {
        $book->fill($data);
        $book->save();

        return $book;
    }

    /**
     * Delete book
     *
     * @param Book $book book object
     * @return Book
     * @throws \Exception
     */
    public function delete(Book $book)
    {
        $book->delete();

        return true;
    }

    /**
     * Search book
     *
     * @param array $params search object key mapping, key = field, value = keyword
     * @return Book
     * @throws \Exception
     */
    public function search(array $params = [])
    {
        $bookModel = $this->getModel();
        foreach ($params as $key => $value) {
            $bookModel = $bookModel->orWhere($key, 'LIKE', "%$value%");
        }

        return $bookModel->paginate(self::PAGINATE);
    }
}
