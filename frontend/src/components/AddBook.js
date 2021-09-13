import React, { useState } from "react"
import BookDataService from "../services/BookService"
import { useHistory } from "react-router-dom"
import Alert from 'react-bootstrap/Alert'
import StructureLaravelValidationError from "./StructureLaravelValidationError"

const AddBook = props => {
  const initialBookState = {
    id: null,
    title: "",
    author: ""
  }
  const [book, setBook] = useState(initialBookState)
  const [submitted, setSubmitted] = useState(false)
  const [message, setMessage] = useState("")
  const [validationResError, setValidationResError] = useState("")

  const handleInputChange = event => {
    const { name, value } = event.target
    setBook({ ...book, [name]: value })
  }

  const saveBook = (e) => {
    // e.preventDefault()
    var data = {
      title: book.title,
      author: book.author
    }

    setMessage(null)
    if (! data.title || ! data.author) {
      setMessage('Kindly fill up all the fields.')
      return
    }

    if (window.confirm('Are you sure you wish to add this book?')) {
      BookDataService.create(data)
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
          {validationResError ?
            <Alert variant="warning" closeLabel="x" onClose={() => setValidationResError("")} dismissible>
              <Alert.Heading>Validation Error</Alert.Heading>
              <StructureLaravelValidationError errorData={validationResError}></StructureLaravelValidationError>
            </Alert>
          : ''}
          <div className="input-group mb-3 w-50">
            <span className="input-group-text" htmlFor="title">Title<span className="red">*</span></span>
            <input
              type="text"
              className="form-control"
              id="title"
              required
              value={book.title}
              onChange={handleInputChange}
              name="title"
            />
          </div>

          <div className="input-group mb-3 w-50">
            <span className="input-group-text" htmlFor="author">Author<span className="red">*</span></span>
            <input
              type="text"
              className="form-control"
              id="author"
              required
              value={book.author}
              onChange={handleInputChange}
              name="author"
            />
          </div>

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