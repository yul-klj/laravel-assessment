import http from "../http-common";

const generate = (type, field) => {
  if (! field) {
    return http.post(`/export?type=${type}`);
  }
  return http.post(`/export?type=${type}&field=${field}`);
};

const retrieve = (id) => {
  return http.get(`/export/${id}`);
};

const ExportService = {
  generate,
  retrieve
};

export default ExportService;