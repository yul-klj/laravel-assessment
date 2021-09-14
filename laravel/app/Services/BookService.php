<?php

namespace App\Services;

use App\Repositories\BookRepository;

/**
 * Class BookService
 *
 * @package App\Services
 * @author Yul <yul_klj@hotmail.com>
 */
class BookService
{
    private $bookRepository;

    /**
     * BookService constructor.
     *
     * @param BookRepository $bookRepository book repository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Get all Book
     *
     * @param string $orderByField  order by field
     * @param string $orderByClause order by clause
     * @return array
     */
    public function getAllBook(
        string $orderByField = 'id',
        string $orderByClause = 'asc'
    ) {
        $books = $this->bookRepository->getAll($orderByField, $orderByClause);

        return $books->toArray();
    }

    /**
     * Get Book detail
     *
     * @param int $id book id to be retrive
     * @return array
     */
    public function getBookDetail(int $id)
    {
        $bookDetail = $this->bookRepository->getById($id);

        return $bookDetail->toArray();
    }

    /**
     * Create Book
     *
     * @param array $data Validated data to be inserted
     * @return array
     */
    public function create(array $data)
    {
        $bookDetail = $this->bookRepository->create($data);

        return $bookDetail->toArray();
    }

    /**
     * Update Book detail
     *
     * @param int   $id   Book id to update
     * @param array $data Validated data to be inserted
     * @return array
     */
    public function update(int $id, array $data)
    {
        $bookDetail = $this->bookRepository->getById($id);
        if (empty($bookDetail)) {
            return null;
        }
        $bookDetail = $this->bookRepository->update($bookDetail, $data);

        return $bookDetail->toArray();
    }

    /**
     * Delete Book
     *
     * @param int $id Get specific book id to perform delete
     * @return boolean
     */
    public function delete(int $id)
    {
        $bookDetail = $this->bookRepository->getById($id);
        if (empty($bookDetail)) {
            return false;
        }
        return $this->bookRepository->delete($bookDetail);
    }

    /**
     * Search Book
     *
     * @param string|null $keyword       Search book keyword
     * @param string      $orderByField  order by field
     * @param string      $orderByClause order by clause
     * @return array
     */
    public function search(
        $keyword = null,
        string $orderByField = 'id',
        string $orderByClause = 'asc'
    ) {
        $searchFields = ['title', 'author'];
        $searchMappedFields = [];

        if (! empty($keyword)) {
            array_map(function ($value) use (&$searchMappedFields, $keyword) {
                $searchMappedFields[$value] = $keyword;
                return $searchMappedFields;
            }, $searchFields);
        }

        $books = $this->bookRepository->search(
            $keyword,
            $searchMappedFields,
            $orderByField,
            $orderByClause
        );

        return $books->toArray();
    }
}
