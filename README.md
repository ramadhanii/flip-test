# ABOUT
This repo is created in purposed to complete technical test for Flip.id.

# How to run
This service support 2 driver to store data, file or Mysql. If you want to use file please skip the first step.

### 1. Configure database
Before we start, if you want to use MySQL as your data storage, please take a look at the config file (CONFIG.php) and enhance the constant value for **DB_HOST**, **DB_PORT**, **DB_NAME**, **DB_USERNAME**, and **DB_PASSWORD**, then run these query:
```
CREATE DATABASE IF NOT EXISTS flip_dhani;
CREATE TABLE IF NOT EXISTS disburses (
    id BIGINT PRIMARY KEY,
    amount BIGINT NOT NULL,
    status enum('PENDING', 'SUCCESS', 'FAILED'),
    `timestamp` timestamp,
    bank_code VARCHAR(10) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    beneficiary_name VARCHAR(255),
    remark VARCHAR(255),
    receipt tinytext,
    time_served timestamp,
    fee int not null
)  ENGINE=INNODB;
CREATE INDEX disburses_amount_IDX USING BTREE ON flip_dhani.disburses (amount);
CREATE INDEX disburses_bank_code_IDX USING BTREE ON flip_dhani.disburses (bank_code);
CREATE INDEX disburses_account_number_IDX USING BTREE ON flip_dhani.disburses (account_number);
CREATE INDEX disburses_time_served_IDX USING BTREE ON flip_dhani.disburses (time_served);
CREATE INDEX disburses_timestamp_IDX USING BTREE ON flip_dhani.disburses (`timestamp`);
CREATE INDEX disburses_status_IDX USING BTREE ON flip_dhani.disburses (status);
CREATE INDEX disburses_beneficiary_name_IDX USING BTREE ON flip_dhani.disburses (beneficiary_name);
```

### 2. Run the service 
For running the service, run this script on your terminal:
```
php -S localhost:8000 ./index.php
```
*Note: If you change the port, please consider to check _dummy-disburse-request.php_ file, and change the constant value.

### 3. Run the job for checking the disbursement status
Run this script on your terminal:
```
php job-check-status.php
```

### 4. Make a request
For making a request you can open your favorite RESTAPI client such as Postman, Insomnia, etc. and send a request with detail below:

```http
POST http://localhost:8000 HTTP/1.1
Content-Type: application/x-www-form-urlencoded

Attribute:
- `bank_code`
- `account_number`
- `amount`
- `remark`
```

or simply by running dummy request on your terminal:
```
php dummy-disburse-request.php
```

### 5. Display all requested data
Open your browser and type http://localhost:8000/?module=all-data