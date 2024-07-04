
* **Docker** - if you don't have it yet, follow the https://docs.docker.com/install/#supported-platforms
* **Docker Compose** - refer to the official documentation for the https://docs.docker.com/compose/install
* **Git Bash** - If your OS is not Linux please download Git Bash
  > ##### All Make commands needs to be run in Git Bash
* **Make** - Windows `choco install make` & Linux/Mac `apt-get -y install make`

> ### Fresh Installation Guide (Windows)

#### Step #1: Clone

* Clone the project from GitHub
```bash
git clone https://github.com/aldinosaid/d16ae99cc04d1c888b5a9b0dc30bebae.git 
cd d16ae99cc04d1c888b5a9b0dc30bebae/src/
composer install 
cd .. 
```

#### Step #2: First time installation

```bash
docker-compose -f local.yml build
```

#### Step #3: Starting docker compose

```bash
docker-compose -f local.yml up -d
```

#### Step #4: Importing table using Admine

* Access the url `http://localhost:8080`
* Fill the mandatory using this fields

| Field        | Key           |
|--------------|---------------|
| System       | PostgreSQL    |
| Server       | postgres      |
| Username     | admin         |
| Password     | aldinosaid!23 |
| Database     | email_service |

* Click the button import, and choose the file.
* Click the execute button.

#### Step #5: Setup SMTP Server
* Find this one settings on `/src/config.php`.
* You can skip this step if You're not to change the configuration of SMTP Server.
* You can modify this one config if You have SMTP server configuration by Your self.

#### Step #6: REST API Documentation

> ### Route

```bash
POST /register
POST /authorize
POST /validate
POST /send
POST /status
```

> ### Register client

* Request
```bash
curl --location 'http://localhost/index.php/register' \
--header 'Content-Type: application/json' \
--data-raw '{
    "username" : "client@example.com",
    "password" : "allyoucaneat"
}'
```
* Response
```bash
{"data":{"message":"Your account created successful","username":"client@example.com","password":"allyoucaneat","status":"success"}}
```
> ### Authorization
* You will get the token result after authorization 
* Request
```bash
curl --location 'http://localhost/index.php/authorize' \
--header 'Content-Type: application/json' \
--data-raw '{
    "username" : "client@example.com",
    "password" : "allyoucaneat"
}'
```
* Response
```bash
{"data":{"message":"Authentication Successful","status":"success","token":"ajEwbUZpdW40NVpOMWhsRmI1cWgxS1JQem5mYVNLNEstYWxkaW5vc2FpZEBnbWFpbC5jb20tN2I0YTc1ZGQyZjA4YjIyOWY2OTE0OTlkMzBjNmE5Nzc3OWIyZTgyNQ=="}}
```

> ### Validation

* You must be validation the token before You want to use.
* Request
```bash
curl --location 'http://localhost/index.php/validate' \
--header 'Content-Type: application/json' \
--data '{
    "token" : "ajEwbUZpdW40NVpOMWhsRmI1cWgxS1JQem5mYVNLNEstYWxkaW5vc2FpZEBnbWFpbC5jb20tN2I0YTc1ZGQyZjA4YjIyOWY2OTE0OTlkMzBjNmE5Nzc3OWIyZTgyNQ=="
}'
```

* Response
```bash
{"data":{"message":"Your token has been
validated","status":"success","token":"ajEwbUZpdW40NVpOMWhsRmI1cWgxS1JQem5mYVNLNEstYWxkaW5vc2FpZEBnbWFpbC5jb20tN2I0YTc1ZGQyZjA4YjIyOWY2OTE0OTlkMzBjNmE5Nzc3OWIyZTgyNQ=="}}
```

> ### Sending message request

* If You want to sending message request, You must be fill the mandatory field of the parameters.
* from
* name
* reply_to
* reply_name
* to
* address_name
* subject
* html_content

* request
```bash
curl --location 'http://localhost/index.php/send' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer ajEwbUZpdW40NVpOMWhsRmI1cWgxS1JQem5mYVNLNEstYWxkaW5vc2FpZEBnbWFpbC5jb20tN2I0YTc1ZGQyZjA4YjIyOWY2OTE0OTlkMzBjNmE5Nzc3OWIyZTgyNQ==' \
--data-raw '{
    "from" : "aldinosaid@gmail.com",
    "name" : "Aldino Said",
    "reply_to" : "aldinosaid@gmail.com",
    "reply_name" : "Aldino Said",
    "to" : "example@gmail.com",
    "address_name" : "John Doe",
    "subject" : "Sending email testing",
    "html_content" : "<b>Hello World</b>"
}'
```
* Response
```bash
{"data":{"message":"Your message has been queued","uuid":"2db35945ac473347be6cf3be1c646b2f1bccdfa6","status":"success"}}
```

> ### Check message status

* You can check the message status using the field uuid.
* You can find the uuid from the response when You sending the Sending message request.

* Request

```bash
curl --location 'http://localhost/index.php/status' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer ajEwbUZpdW40NVpOMWhsRmI1cWgxS1JQem5mYVNLNEstYWxkaW5vc2FpZEBnbWFpbC5jb20tN2I0YTc1ZGQyZjA4YjIyOWY2OTE0OTlkMzBjNmE5Nzc3OWIyZTgyNQ==' \
--data '{
    "uuid" : "2db35945ac473347be6cf3be1c646b2f1bccdfa6"
}'
```

* Response
```bash
{"data":{"uuid":"2db35945ac473347be6cf3be1c646b2f1bccdfa6","email":{"from":"aldinosaid@gmail.com","nama":"Aldino
Said","reply_to":"aldinosaid@gmail.com","reply_name":"Aldino Said","to":"example@gmail.com","address_name":"John
Doe","subject":"Sending email testing","html_content":"<b>Hello World<\ /b>"},"created_at":"2024-07-04
        20:10:54","status":"success"}}
```
##