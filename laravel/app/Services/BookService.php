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
     * @param array $data Validated data to be inserted
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
     * @param array $data Validated data to be inserted
     * @return Book
     */
    public function bookDetail(int $id)
    {
        $bookDetail = $this->bookRepository->getById($id);

        return $bookDetail;
    }

    /**
     * Create Book
     *
     * @param array $data Validated data to be inserted
     * @return Book
     */
    public function create(array $data)
    {
        $book = $this->bookRepository->create($data);

        return $book;
    }

    /**
     * Update Book detail
     *
     * @param int   $id   Book id to update
     * @param array $data Validated data to be inserted
     * @return Book
     */
    public function update(int $id, array $data)
    {
        $bookDetail = $this->bookRepository->getById($id);
        if (empty($bookDetail)) {
            return null;
        }
        $bookDetail = $this->bookRepository->update($bookDetail, $data);

        return $bookDetail;
    }

    /**
     * Create Book
     *
     * @param int $id Get specific book id to perform delete
     * @return Book
     */
    public function delete(int $id)
    {
        $bookDetail = $this->bookRepository->getById($id);
        if (empty($bookDetail)) {
            return false;
        }
        return $this->bookRepository->delete($bookDetail);
    }
}