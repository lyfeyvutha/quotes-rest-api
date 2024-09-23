## Quotes REST API

**Author:** Chealyfey Vutha

**Description:**

This project provides a RESTful API for managing quotations, including both famous quotes and user-submitted ones. It allows users to retrieve, create, update, and delete quotes, authors, and categories.

**Features:**

* **CRUD Operations (Create, Read, Update, Delete):** Manage quotes, authors, and categories using standard HTTP methods (GET, POST, PUT, DELETE).
* **Flexible Filtering:** Retrieve quotes based on author, category, or both.
* **Random Quotes (Optional):** (For the extra challenge) Allows retrieving a random quote meeting specific criteria (author, category, or neither).
* **Database:** Utilizes PostgreSQL for robust data storage and querying.
* **Framework:** Leverages PHP for efficient API development.
* **Deployment:** Deployed to Render.com at: `https://inf653-midterm-project-ykdy.onrender.com`

**Usage:**

1. Access the API at its deployed URL: [invalid URL removed].
2. Refer to the API documentation below for specific endpoints and request/response formats.

**API Documentation:**

**GET Requests:**

* `/quotes/`: Retrieves all quotes.
* `/quotes/?id=[id]`: Retrieves a specific quote by ID.
* `/quotes/?author_id=[author_id]`: Retrieves all quotes by a specific author ID.
* `/quotes/?category_id=[category_id]`: Retrieves all quotes in a specific category ID.
* `/quotes/?author_id=[author_id]&category_id=[category_id]`: Retrieves quotes from a specific author and category (requires extra challenge implementation).
* `/quotes/?random=true` (Optional): Retrieves a random quote (requires extra challenge implementation).
* `/authors/`: Retrieves all authors.
* `/authors/?id=[id]`: Retrieves a specific author by ID.
* `/categories/`: Retrieves all categories.
* `/categories/?id=[id]`: Retrieves a specific category by ID.

**POST Requests:**

* `/quotes/`: Creates a new quote with quote, author_id, and category_id parameters in the request body (JSON format).
* `/authors/`: Creates a new author with author parameter in the request body (JSON format).
* `/categories/`: Creates a new category with category parameter in the request body (JSON format).

**PUT Requests:**

* `/quotes/`: Updates a quote with id, quote, author_id, and category_id parameters in the request body (JSON format).
* `/authors/`: Updates an author with id and author parameter in the request body (JSON format).
* `/categories/`: Updates a category with id and category parameter in the request body (JSON format).

**DELETE Requests:**

* `/quotes/[id]`: Deletes a specific quote by ID.
* `/authors/[id]`: Deletes a specific author by ID.
* `/categories/[id]`: Deletes a specific category by ID.

**Response Formats:**

* Successful requests return a JSON object with relevant data (e.g., retrieved quote, author, category, or success message).
* Error responses return a JSON object with an error message.


**Contributing:**

Feel free to contribute to this project by forking the repository on GitHub and creating pull requests.

**License:**

This project is licensed under the (insert your preferred license here).

**Important Notes:**

* You may need to create an account on Render.com to access the project's management console if you want to make changes to the deployment.
* Consider adding instructions on how to set up the project locally for development purposes (if applicable).
