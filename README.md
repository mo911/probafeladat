## Steps for Local Setup  
*(assuming Docker and Docker Compose are installed on the machine)*  

1. Navigate to the cloned folder.  
2. Run the following command:  
   ```bash
   docker-compose up -d
3. Create the database with the following command:
  ```bash
  docker exec -it proba-database mysql -u root -p -e "CREATE DATABASE welove_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  ```

4. Import the database via the command line: 
  a. Enter the container:
    ```bash
    docker exec -it probafeladat-app bash
  b. Run the following command inside the container:
    ```bash
    mysql -h proba-database -u root -p welove_test < welovetest_20250112.sql
5. Open the browser and navigate to: 127.0.0.1:8080
