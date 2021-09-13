const BookInputForm = ({book, setBook}) => {
    const handleInputChange = event => {
        const { name, value } = event.target
        setBook({ ...book, [name]: value })
    }
    return(
        <form>
            <div className="input-group mb-3 w-50">
                <span className="input-group-text" htmlFor="title">Title<span className="red">*</span></span>
                <input
                type="text"
                className="form-control"
                id="title"
                name="title"
                value={book.title}
                onChange={handleInputChange}
                />
            </div>
            <div className="input-group mb-3 w-50">
                <span className="input-group-text" htmlFor="author">Author<span className="red">*</span></span>
                <input
                type="text"
                className="form-control"
                id="author"
                name="author"
                value={book.author}
                onChange={handleInputChange}
                />
            </div>
        </form>
    )
}

export default BookInputForm