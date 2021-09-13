import React, { useState, useEffect, useMemo, useRef } from "react"
import BookDataService from "../services/BookService"
import { useTable, useSortBy, usePagination } from "react-table"
import Alert from 'react-bootstrap/Alert'

const BookListBackendPaginate = (props) => {
  const [books, setBooks] = useState([])
  const [searchKeyword, setSearchKeyword] = useState("")
  const bookRef = useRef()
  const [message, setMessage] = useState("")

  const [nextPageToken, setNextPageToken] = useState("")
  const [previousPageToken, setPreviousPageToken] = useState("")

  bookRef.current = books

  useEffect(() => {
    retrieveBooks(null, null)
  }, [])

  const onChangeSearchKeyword = (e) => {
    const searchKeyword = e.target.value
    setSearchKeyword(searchKeyword)
  }

  const retrieveBooks = (pageToken, searchKeyword) => {
    if (! pageToken) {
      setNextPageToken(null)
      setPreviousPageToken(null)
    }

    BookDataService.search(pageToken, searchKeyword)
      .then((response) => {
        setNextPageToken(null)
        setPreviousPageToken(null)
        if (response.data.content.data.next_page_url)
          setNextPageToken(response.data.content.data.next_page_url.toString().split('=')[1])

        if (response.data.content.data.prev_page_url)
          setPreviousPageToken(response.data.content.data.prev_page_url.toString().split('=')[1])

        setBooks(response.data.content.data.data)
      })
      .catch((e) => {
        console.log(e)
      })
  }

  const bookDetail = (rowIndex) => {
    const id = bookRef.current[rowIndex].id

    props.history.push("/book/" + id)
  }

  const deleteBook = (rowIndex) => {
    const id = bookRef.current[rowIndex].id

    if (window.confirm('Are you sure you wish to delete this book?')) {
      BookDataService.remove(id)
        .then((response) => {
          props.history.push("/books-be-paginate")

          let newBook = [...bookRef.current]
          newBook.splice(rowIndex, 1)

          setBooks(newBook)
          setMessage('Book deleted successfully')
        })
        .catch((e) => {
          console.log(e)
        })
    }
  }

  const columns = useMemo(
    () => [
      {
        Header: "Title",
        accessor: "title",
      },
      {
        Header: "Author",
        accessor: "author",
      },
      {
        Header: "Actions",
        accessor: "actions",
        Cell: (props) => {
          const rowIdx = props.row.id
          return (
            <div className="btn-toolbar" role="toolbar">
              <div className="btn-group me-2" role="group">
                <span onClick={() => bookDetail(rowIdx)}>
                  <i className="far fa-edit action mr-2"></i>
                </span>
              </div>
              <div className="btn-group me-2" role="group">
                <span onClick={() => deleteBook(rowIdx)}>
                  <i className="fas fa-trash action"></i>
                </span>
              </div>
            </div>
          )
        },
      },
    ],
    []
  )

  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    page,
    state: {pageIndex},
    prepareRow,
  } = useTable({
      columns,
      data: books
    },
    useSortBy,
    usePagination
  )

  return (
    <div className="list row">
      {message ?
        <Alert variant="success" closeLabel="x" onClose={() => setMessage(null)} dismissible>
          {message}
        </Alert>
      : ''}
      <p className="red">Expect pagination from backend, and sorting is on current view table sort only</p>
      <div className="col-md-12">
        <div className="input-group mb-3">
          <input
            type="text"
            className="form-control"
            placeholder="Search by keyword"
            value={searchKeyword}
            onChange={onChangeSearchKeyword}
          />
          <div className="input-group-append">
            <button
              className="btn btn-outline-secondary"
              type="button"
              onClick={() => retrieveBooks(null, searchKeyword)}
            >
              Search
            </button>
          </div>
        </div>
      </div>
      <div className="col-md-12 list">
        <table
          className="table table-striped table-bordered"
          {...getTableProps()}
        >
          <thead>
            {headerGroups.map((headerGroup) => (
              <tr {...headerGroup.getHeaderGroupProps()}>
                {headerGroup.headers.map((column) => (
                  <th {...column.getHeaderProps(column.getSortByToggleProps())}>
                    {column.render("Header")}
                    <span>
                      {column.isSorted ? (! column.isSortedDesc ? '⬆' : '⬇') : ''}
                    </span>
                  </th>
                ))}
              </tr>
            ))}
          </thead>
          <tbody {...getTableBodyProps()}>
            {page.map((row, i) => {
              prepareRow(row)
              return (
                <tr {...row.getRowProps()}>
                  {row.cells.map((cell) => {
                    return (
                      <td {...cell.getCellProps()}>{cell.render("Cell")}</td>
                    )
                  })}
                </tr>
              )
            })}
          </tbody>
        </table>
        <div className="flexbox text-end">
          <button className="btn btn-sm btn-primary" onClick={() => retrieveBooks(previousPageToken, searchKeyword)} disabled={!previousPageToken}>◀</button>
          <button className="btn btn-sm btn-secondary" onClick={() => retrieveBooks(nextPageToken, searchKeyword)} disabled={!nextPageToken}>▶</button>
        </div>
      </div>
    </div>
  )
}

export default BookListBackendPaginate