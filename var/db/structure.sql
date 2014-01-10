CREATE TABLE services (
  id INTEGER PRIMARY KEY,
  ts INTEGER DEFAULT CURRENT_TIMESTAMP,
  name TEXT
);

CREATE TABLE resources (
  id INTEGER PRIMARY KEY,
  service INTEGER,
  name TEXT,
  format INTEGER,
  FOREIGN KEY(service) REFERENCES service(id)
);

CREATE TABLE cache (
  id INTEGER PRIMARY KEY,
  resource INTEGER,
  format INTEGER,
  progress REAL,
  ts INTEGER DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(resource) REFERENCES resource(id)
);

CREATE TABLE configuration (
  var TEXT PRIMARY KEY,
  contents TEXT
);
