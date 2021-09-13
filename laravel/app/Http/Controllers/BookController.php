<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookService;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUrlRequest;
use App\Http\Requests\BookGetAllRequest;
use App\Http\Requests\BookSearchRequest;
use App\Http\Requests\BookUpdateRequest;

/**
 * Class BookController
 *
 * @package App\Http\Controllers
 * @author  Yul <yul_klj@hotmail.com>
 */
class BookController extends Controller
{
    /**
     * Create Book
     *
     * @param BookCreateRequest $request Book request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(BookCreateRequest $request)
    {
        $inputs = $request->only(['title', 'author']);
        $book = app(BookService::class)->create($inputs);

        return $this->respondAccomplished('Book created', $book);
    }

    /**
     * Get all book
     *
     * @param BookGetAllRequest $request Book request
     * @return \Illuminate\Http\Response
     */
    public function all(BookGetAllRequest $request)
    {
        $orderField = $request->input('order_field', 'id');
        $orderClause = $request->input('order_clause', 'asc');
        $books = app(BookService::class)->getAllBook($orderField, $orderClause);
        return $this->respondAccomplished('Books retrived', $books);
    }

    /**
     * Get Single Book Detail
     *
     * @param int            $id      Book id
     * @param BookUrlRequest $request Book request
     * @return \Illuminate\Http\Response
     */
    public function get(int $id, BookUrlRequest $request)
    {
        $book = app(BookService::class)->getBookDetail($id);

        if (empty($book)) {
            return $this->errorNotFound();
        } else {
            return $this->respondAccomplished('Book found', $book);
        }
    }

    /**
     * Update Single Book Detail
     *
     * @param int               $id      Book id to update
     * @param BookUpdateRequest $request Book request
     *
     * @return array|\Illuminate\Http\Response
     */
    public function update(int $id, BookUpdateRequest $request)
    {
        $bookData = $request->only(['title', 'author']);
        $book = app(BookService::class)->update($id, $bookData);
        if (empty($book)) {
            return $this->errorNotFound();
        }

        return $this->respondAccomplished('Book updated', $book);
    }

    /**
     * Delete Book
     *
     * @param int            $id      Book id to delete
     * @param BookUrlRequest $request Book request
     * @return array|\Illuminate\Http\Response
     */
    public function delete(int $id, BookUrlRequest $request)
    {
        $success = app(BookService::class)->delete($id);
        if (! $success) {
            return $this->errorNotFound();
        }
        return $this->respondAccomplished();
    }

    /**
     * Search Book
     *
     * @param BookUrlRequest $request Book request
     * @return array|\Illuminate\Http\Response
     */
    public function search(BookSearchRequest $request)
    {
        $keyword = $request->input('keyword', null);
        $orderField = $request->input('order_field', 'id');
        $orderClause = $request->input('order_clause', 'asc');
        $books = app(BookService::class)->search(
            $keyword,
            $orderField,
            $orderClause
        );

        return $this->respondAccomplished('Searched book result', $books);
    }
}
