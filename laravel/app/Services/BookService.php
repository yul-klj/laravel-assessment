<?php

namespace App\Services;

use App\Repositories\BookRepository;

/**
 * Class Book Service
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
     * @param bookRepository $bookRepository book repository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Get all Book
     *
     * @return Book
     */
    public function allBook()
    {
        $books = $this->bookRepository->getAll();

        return $books;
    }

    /**
     * Get book detail
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
        $bookDetail = $this->bookDetailRepository->create($data);

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
     * Create Book
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
     * @param string $keyword Search book keyword
     * @return array
     */
    public function search(string $keyword)
    {
        $searchFields = ['title', 'author'];
        $searchMappedFields = [];
        array_map(function ($value) use (&$searchMappedFields, $keyword) {
            $searchMappedFields[$value] = $keyword;
            return $searchMappedFields;
        }, $searchFields);

        $books = $this->bookRepository->search($searchMappedFields);

        return $books->toArray();
    }
}
