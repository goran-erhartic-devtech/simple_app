# SIMPLE APP

- After cloning the project to local folder, open the terminal in the root of the project and run 'composer install' (make sure to have Composer installed on local machine)

- Navigate to the 'public' folder and run the PHP built in server: php -S localhost:80

- In the browser go to this address: localhost

- Routes that are currently working (view) are '/', '/employees', and '/employee/{id}'

- Implemented CRUD:
    - GET   - '/employees'          (returns all employees from DB)
    - GET   - '/employee/{id}'      (return employee by ID)
    - POST  - '/employee'           (create new employee: Name(str), Age(int), Project(str), Department(str), isActive(tinyint))
    - PUT   - '/employee/{id}'      (edit employee with ID: Name(str), Age(int), Project(str), Department(str), isActive(tinyint))
    - DELETE - '/employee/{id}'     (delete employee by ID)
    
    
    ** PUT and POST through 'x-www-form-urlencoded' 
    ** Name and Age are set to be NotNull