import React, { useState } from "react"
import BookDataService from "../services/BookService"
import Alert from 'react-bootstrap/Alert'
import BookInputForm from "../Utils/BookInputForm"
import LaravelValidationAlert from "../Utils/LaravelValidationAlert"

const AddBook = props => {
  const initialBookState = {
    title: "",
    author: ""
  }
  const [book, setBook] = useState(initialBookState)
  const [submitted, setSubmitted] = useState(false)
  const [message, setMessage] = useState("")
  const [validationResError, setValidationResError] = useState("")

  const saveBook = () => {
    setMessage(null)
    if (! book.title || ! book.author) {
      setMessage('Kindly fill up all the fields.')
      return
    }

    if (window.confirm('Are you sure you wish to add this book?')) {
      BookDataService.create(book)
        .then(response => {
          setBook({
            id: response.data.content.data.id,
            title: response.data.content.data.title,
            author: response.data.content.data.author
          })
          setSubmitted(true)
        })
        .catch(error => {
          setValidationResError(error.response.data.content.error)
        })
    }
  }

  const newBook = () => {
    setBook(initialBookState)
    setSubmitted(false)
  }

  return (
    <div className="submit-form">
      {submitted ? (
        <div className="row g-3 w-50">
          <h4>You submitted successfully!</h4>
          <div className="col-sm-10 text-end">
            <button className="btn btn-primary me-md-2" onClick={() => props.history.push("/books-fe-paginate")}>
              Back to Listing
            </button>
            <button className="btn btn-success" onClick={newBook}>
              Add New
            </button>
          </div>
        </div>
      ) : (
        <div>
          <h3>Add Book Detail</h3>
          {message ?
            <Alert variant="warning" closeLabel="x" onClose={() => setMessage(null)} dismissible>
              {message}
            </Alert>
          : ''}
          <LaravelValidationAlert
            setValidationResError={setValidationResError}
            validationResError={validationResError}>
          </LaravelValidationAlert>

          <BookInputForm book={book} setBook={setBook}></BookInputForm>

          <div className="w-50 text-end">
            <button
              onClick={saveBook}
              className="btn btn-success">
              Submit
            </button>
          </div>
        </div>
      )}
    </div>
  )
}

export default AddBook