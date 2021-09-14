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

const search = (page, keyword, sortBy) => {
  let orderField = 'id'
  let orderClause = 'asc'

  if (sortBy.length > 0) {
    orderField = sortBy[0]['id']
    orderClause = sortBy[0]['desc'] ? 'desc' : 'asc'
  }

  if (page)
    return http.get(`/books/search?page=${page}&keyword=${keyword}&order_field=${orderField}&order_clause=${orderClause}`)

  if (keyword)
    return http.get(`/books/search?keyword=${keyword}&order_field=${orderField}&order_clause=${orderClause}`);

  return http.get(`/books/search?order_field=${orderField}&order_clause=${orderClause}`)
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