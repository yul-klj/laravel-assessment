import React, { useState, useEffect } from "react"
import BookDataService from "../services/BookService"
import Alert from 'react-bootstrap/Alert'
import BookInputForm from "../Utils/BookInputForm"
import LaravelValidationAlert from "../Utils/LaravelValidationAlert"

const BookDetail = props => {
  const initialBookState = {
    id: null,
    title: "",
    author: ""
  }
  const [currentBook, setCurrentBook] = useState(initialBookState)
  const [message, setMessage] = useState("")
  const [validationResError, setValidationResError] = useState("")

  const getBookDetail = id => {
    BookDataService.get(id)
      .then(response => {
        setCurrentBook(response.data.content.data)
      })
      .catch(e => {
        console.log(e)
        setCurrentBook(null)
      })
  }

  useEffect(() => {
    getBookDetail(props.match.params.id)
  }, [props.match.params.id])

  const updateBook = () => {
    setMessage(null)
    if (! currentBook.title || ! currentBook.author) {
      setMessage('Kindly fill up all the fields.')
      return false
    }

    if (window.confirm('Are you sure you wish to update this book?')) {
      BookDataService.update(currentBook.id, currentBook)
        .then(response => {
          setMessage("The book was updated successfully!")
        })
        .catch(error => {
          setValidationResError(error.response.data.content.error)
        })
    }
  }

  const deleteBook = () => {
    if (window.confirm('Are you sure you wish to delete this book?')) {
      BookDataService.remove(currentBook.id)
        .then(response => {
          props.history.push("/books-fe-paginate")
        })
        .catch(e => {
          console.log(e)
          setCurrentBook(null)
        })
    }
  }

  return (
    <div>
      {currentBook ? (
        <div className="edit-form">
          <h4>Book Detail</h4>
          {message ?
            <Alert variant="warning" closeLabel="x" onClose={() => setMessage(null)} dismissible>
              {message}
            </Alert>
          : ''}
          <LaravelValidationAlert
            setValidationResError={setValidationResError}
            validationResError={validationResError}>
          </LaravelValidationAlert>

          <BookInputForm book={currentBook} setBook={setCurrentBook}></BookInputForm>

          <div className="d-grid gap-2 d-md-flex justify-content-md-end w-50 text-end">
            <button
              className="btn btn-sm btn-primary me-md-2"
              onClick={() => props.history.push("/books-fe-paginate")}
              >
              Back to Listing
            </button>
            <button
              className="btn btn-sm btn-danger me-md-2"
              onClick={deleteBook}
              >
              Delete
            </button>
            <button
              type="submit"
              className="btn btn-sm btn-success"
              onClick={updateBook}
              >
              Update
            </button>
          </div>
        </div>
      ) : (
        <div>
          <br />
          <p>Book does not exist, please click a Book on listing...</p>
        </div>
      )}
    </div>
  )
}

export default BookDetail