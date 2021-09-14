import React, { useState, useEffect, useMemo, useRef } from "react"
import BookDataService from "../services/BookService"
import { useTable, useSortBy, usePagination } from "react-table"
import Alert from 'react-bootstrap/Alert'

const BookListBackendPaginate = (props) => {
  const [books, setBooks] = useState([])
  const [searchKeyword, setSearchKeyword] = useState("")
  const bookRef = useRef()
  const [message, setMessage] = useState("")

  // Pagination
  const [nextPageNumber, setNextPageNumber] = useState("")
  const [previousPageNumber, setPreviousPageNumber] = useState("")
  const [currentPageNumber, setCurrentPageNumber] = useState(1)
  const [maxPageNumber, setMaxPageNumber] = useState(0)

  // Display current page count amount
  const [fromDataCount, setFromDataCount] = useState(0)
  const [toDataCount, setToDataCount] = useState(0)
  const [maxDataCount, setMaxDataCount] = useState(0)

  bookRef.current = books

  const onChangeSearchKeyword = (e) => {
    const searchKeyword = e.target.value
    setSearchKeyword(searchKeyword)
  }

  const retrieveBooks = (pageNumber, searchKeyword, sortBy, newSearch) => {
    if (newSearch) {
      // Pagination resets for new search
      setNextPageNumber(null)
      setPreviousPageNumber(null)
      pageNumber = null
      setCurrentPageNumber(1)
    }

    BookDataService.search(pageNumber, searchKeyword, sortBy)
      .then((response) => {
        // Reset page number
        setNextPageNumber(null)
        setPreviousPageNumber(null)
        setMaxPageNumber(response.data.content.data.last_page)

        // Get data count from backend
        setFromDataCount(response.data.content.data.from)
        setToDataCount(response.data.content.data.to)
        setMaxDataCount(response.data.content.data.total)

        if (pageNumber)
          setCurrentPageNumber(pageNumber)

        if (response.data.content.data.next_page_url)
          setNextPageNumber(response.data.content.data.current_page + 1)

        if (response.data.content.data.prev_page_url)
          setPreviousPageNumber(response.data.content.data.current_page - 1)

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
    prepareRow,
    state: { sortBy }
  } = useTable({
      columns,
      data: books,
      manualSortBy: true,
      autoResetPage: false,
      autoResetSortBy: false,
    },
    useSortBy,
    usePagination
  )

  useEffect(() => {
    retrieveBooks(currentPageNumber, searchKeyword, sortBy, false)
  }, [sortBy])

  return (
    <div className="list row">
      {message ?
        <Alert variant="success" closeLabel="x" onClose={() => setMessage(null)} dismissible>
          {message}
        </Alert>
      : ''}
      <p className="red">Expect pagination and sorting from backend</p>
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
              onClick={() => retrieveBooks(null, searchKeyword, sortBy, true)}
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
          <span>
            Showing{' '}
            <strong>{fromDataCount}</strong> - <strong>{toDataCount}</strong> of <strong>{maxDataCount}</strong>
          </span>
          <br /><br />
          <span>
            Page{' '}
            <strong>{currentPageNumber} of {maxPageNumber}</strong>
          </span>{' '}
          <button className="btn btn-sm btn-primary" onClick={() => retrieveBooks(previousPageNumber, searchKeyword, sortBy, false)} disabled={!previousPageNumber}>◀</button>
          <button className="btn btn-sm btn-secondary" onClick={() => retrieveBooks(nextPageNumber, searchKeyword, sortBy, false)} disabled={!nextPageNumber}>▶</button>
        </div>
      </div>
    </div>
  )
}

export default BookListBackendPaginate