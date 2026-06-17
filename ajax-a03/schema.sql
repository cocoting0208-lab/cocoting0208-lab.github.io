CREATE TABLE vote_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    creator VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vote_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    FOREIGN KEY (event_id) REFERENCES vote_events(id) ON DELETE CASCADE
);

CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    item_id INT NOT NULL,
    voter_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (event_id) REFERENCES vote_events(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES vote_items(id) ON DELETE CASCADE,

    UNIQUE KEY unique_vote(event_id, voter_name)
);