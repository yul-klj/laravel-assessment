import http from "../http-common";

const getAll = () => {
  return http.get("/books");
};

const get = (id) => {
  return http.get(`/book/${id}`);
};

const create = (data) => {
  return http.post("/book", data);
};

const update = (id, data) => {
  return http.put(`/book/${id}`, data);
};

const remove = (id) => {
  return http.delete(`/book/${id}`);
};

const search = (token, keyword) => {
  if (token)
    return http.get(`/books/search?cursor=${token}`)

  if (keyword)
    return http.get(`/books/search?keyword=${keyword}`);

  return http.get(`/books/search`)
};

const BookService = {
  getAll,
  get,
  create,
  update,
  remove,
  search
};

export default BookService;