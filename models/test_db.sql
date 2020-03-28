

DROP TABLE IF EXISTS events;

CREATE TABLE events(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  'start_date' datetime NOT NULL,
  end_date datetime NOT NULL,
  'text' TEXT DEFAULT NULL
);


INSERT INTO events('start_date', 'end_date', 'text')
VALUES (CURRENT_TIMESTAMP, datetime('now', '+1 hour'), "test meeting");