<?php
    
    require_once("config.php");

    // Create the Users table
    $createUsersTableSQL = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($createUsersTableSQL) === TRUE) {
        echo "Users table created successfully<br>";
    } else {
        echo "Error creating Users table: " . $conn->error . "<br>";
    }

    // Create the Categories table
    $createCategoriesTableSQL = "
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL
    )";

    if ($conn->query($createCategoriesTableSQL) === TRUE) {
        echo "Categories table created successfully<br>";
    } else {
        echo "Error creating Categories table: " . $conn->error . "<br>";
    }

    // Create the Contacts table
    $createContactsTableSQL = "
    CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        category_id INT,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50),
        email VARCHAR(100) NOT NULL,
        address VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )";

    if ($conn->query($createContactsTableSQL) === TRUE) {
        echo "Contacts table created successfully<br>";
    } else {
        echo "Error creating Contacts table: " . $conn->error . "<br>";
    }

    // Close the database connection
    $conn->close();
?>
