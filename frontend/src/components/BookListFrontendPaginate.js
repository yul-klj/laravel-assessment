import React, { useState, useEffect, useMemo, useRef } from "react"
import BookDataService from "../services/BookService"
import { useTable, useSortBy, usePagination, useGlobalFilter } from "react-table"
import Alert from 'react-bootstrap/Alert'

const BookListFrontendPaginate = (props) => {
  const [books, setBooks] = useState([])
  const [searchKeyword, setSearchKeyword] = useState("")
  const bookRef = useRef()
  const [message, setMessage] = useState("")

  const [nextPageToken, setNextPageToken] = useState("")
  const [previousPageToken, setPreviousPageToken] = useState("")

  bookRef.current = books

  useEffect(() => {
    retrieveBooks()
  }, [])

  const onChangeSearchKeyword = (e) => {
    const searchKeyword = e.target.value
    setSearchKeyword(searchKeyword)
  }

  const retrieveBooks = () => {
    BookDataService.getAll()
      .then((response) => {
        setBooks(response.data.content.data)
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
          props.history.push("/books-fe-paginate")

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
    nextPage,
    previousPage,
    canNextPage,
    canPreviousPage,
    pageOptions,
    state: {pageIndex},
    prepareRow,
    state,
    setGlobalFilter
  } = useTable({
      columns,
      data: books
    },
    useGlobalFilter,
    useSortBy,
    usePagination
  )

  const { globalFilter } = state

  return (
    <div className="list row">
      {message ?
        <Alert variant="success" closeLabel="x" onClose={() => setMessage(null)} dismissible>
          {message}
        </Alert>
      : ''}
      <p className="red">Expect data being loaded in frontend, frontend will do sorting and search, sorting based on full set data</p>
      <div className="col-md-12">
        <div className="input-group mb-3">
          <input
            type="text"
            className="form-control"
            placeholder="Search by keyword"
            value={globalFilter}
            onChange={(e) => setGlobalFilter(e.target.value || undefined)}
          />
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
            Page{' '}
            <strong>{pageIndex + 1} of {pageOptions.length}</strong>
          </span>{' '}
          <button className="btn btn-sm btn-primary" onClick={() => previousPage()} disabled={!canPreviousPage}>◀</button>
          <button className="btn btn-sm btn-secondary" onClick={() => nextPage()} disabled={!canNextPage}>▶</button>
        </div>
      </div>
    </div>
  )
}

export default BookListFrontendPaginate