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
     * @param string $orderByField  order by field
     * @param string $orderByClause order by clause
     * @return Book
     */
    public function getAll(
        string $orderByField = 'id',
        string $orderByClause = 'ASC'
    ) {
        $bookModel = $this->getModel();
        $bookModel = $bookModel->orderBy($orderByField, $orderByClause);

        return $bookModel->get();
    }

    /**
     * Get All Books data for specific field(s)
     *
     * @param array $fields Fields to retrive from db
     * @return Book
     */
    public function getAllByFields(array $fields = [])
    {
        $bookModel = $this->getModel();

        return $bookModel->get($fields);
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
     * Update Book
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
     * Delete Book
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
     * Search Book
     *
     * @param string|null $keyword       search keyword for return in pagination
     * @param array       $params        search mapping, key = field, value = keyword
     * @param string      $orderByField  order by field
     * @param string      $orderByClause order by clause
     * @return Book
     * @throws \Exception
     */
    public function search(
        $keyword,
        array $params = [],
        string $orderByField = 'id',
        string $orderByClause = 'ASC'
    ) {
        $bookModel = $this->getModel();
        foreach ($params as $key => $value) {
            $bookModel = $bookModel->orWhere($key, 'LIKE', "%$value%");
        }
        $bookModel = $bookModel->orderBy($orderByField, $orderByClause);

        return $bookModel->paginate(self::PAGINATE)
            ->appends([
                'order_field' => $orderByField,
                'order_clause' => $orderByClause,
                'keyword' => $keyword
            ]);
    }

    /**
     * Validate Book field exist
     *
     * @param string $fieldName Field name to validate
     * @return boolean
     */
    public function validDatabaseField(string $fieldName)
    {
        return in_array($fieldName, Book::ALL_FIELDS);
    }

    /**
     * Get all related db field
     *
     * @return array
     */
    public function getAllRelatedDbField()
    {
        return Book::ALL_FIELDS;
    }
}
