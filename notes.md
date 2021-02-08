# Implemented Features/ Used Technologies

## Required (all)

- All Users:
    - Register
    - Login/Logout
    - Edit Profile

- User that found a pet:
    - Add pet for adoption (with one image at least and 4 maximum)
    - Edit a pet post  
    - Delete a pet post
    - See adoption proposals
    - See questions/messages about a pet
    - Accept/Reject proposal

- Users looking for a pet:
    - Search for a pet using several search criteria
    - Add pets to a favourites list
    - Ask questions about a pet listed for adoption
    - Propose to adopt a pet
    - List previous proposals

- Other requirements that were fulfilled
    - The following technologies are all used: HTML, CSS, PHP, Javascript, Ajax/JSON, PDO/SQL (using sqlite)
    - Code Quality: all code was validated, structured and commented
    - File organization: actions, database, css, javascript, api, images, includes, pages, templates and utils (with sub folders when needed)

    - Security
        - *XSS* (Cross-Site Scripting)
            - If input contains unexpected characters we reject it (using preg_match in all php actions / regex)
            - When showing untrusted data we encode it first using htmlspecialchars() - when showing messages/descriptions and before inserting data in the database (in php actions)

        - *CSRF* (Cross-site Request Forgery)
            - We Generate a random token per session, store this token as a session variable and use it in important requests like: edit/delete profile, edit/delete pet, add pet and accept/reject proposal; verifying in their respective actions if the token is correct

        - *SQL Injection* - prepare/execute

        - *Passwords Hash using salt and validation*: hash and validate passwords in PHP is by using the password-hash and password-verify functions, which generate their own salt

        - php validation of all user input 
            - We use isset and functions that, using regex, verify if the input respects the expected type
            - We check if user has permissions to execute the request he made


## Extra

- The number of pending proposals will be shown when the user signs in (near proposals) - with Ajax
- Chat using Ajax - when a message is sent to a user about a pet, a new conversation is created between the owner and the one who sent the message, allowing interaction between them about that specific pet 
- Pets can be adopted or available, showing different options accordingly (edit/propose/send message or none if the pet was adopted already)
- Search pets uses Ajax so that the page doesn't need to be refreshed when the user changes the search filters
- Responsive design
