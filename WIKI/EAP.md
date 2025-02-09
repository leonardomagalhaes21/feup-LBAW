# EAP: Architecture Specification and Prototype

> Project vision.

The main vision of AskUni is to create a collaborative platform where students, teachers and researchers can easily share, discuss and exchange ideas and resources on academic subjects. Users can post, comment and engage with content through a simple liking system, promoting a supportive and dynamic academic community.

## A7: Web Resources Specification

This artifact outlines the web API developed for the project, detailing the required resources, their properties, and the corresponding JSON responses. The API supports Create, Read, Update and Delete (CRUD) operations (where applicable) for each resource, ensuring seamless interaction between components.

### 1. Overview

> This section defines an overview of the web application's modules.

| Modules | Description |
| --------- | ----------- |
| M01: Authentication | Handles user login, registration, password recovery and logout processes. |
| M02: Individual Profile | Manages user profiles, including personal information and account deletion. |
| M03: Navigation and Browsing | Searching and browsing of content across the platform. |
| M04: Posts | Manages the creation, editing, deleting and viewing of posts. |
| M05: Notifications and Interactions | Handles user notifications and interactions |
| M06: Administration | Provides administrative controls and user management. |

*Table 1: AskUni Modules Overview*
 
### 2. Permissions

| Identifier | Name | Description |
| --------- | ----------- | ----------- |
| PUB | Public | A visitor or unauthenticated user. Can view public questions and answers. |
| USR | User | An authenticated user. Can ask questions, answer and interact with posts. |
| ADM | Administrator | A platform administrator with full control over the system, including user management. |
| OWN | Owner | The creator/owner of a specific post or profile. Can edit or delete their own content. |

*Table 2: AskUni Permissions*

### 3. OpenAPI Specification

> This section includes the [OpenAPI specification](https://gitlab.up.pt/lbaw/lbaw2425/lbaw24153/-/blob/main/openapi/a7_openapi.yaml) in YAML format for the vertical prototype.

```yaml
openapi: 3.0.0

info:
  version: '1.0'
  title: 'AskUni Web API'
  description: 'Web Resources Specification (A7) for AskUni'

servers:
- url: https://lbaw.24153.lbaw.fe.up.pt
  description: Production server

externalDocs:
  description: Find more info here.
  url: https://gitlab.up.pt/lbaw/lbaw2425/lbaw24153/-/wikis/EAP

tags:
  - name: 'M01: Authentication'
  - name: 'M02: Individual Profile' 
  - name: 'M03: Navigation and Browsing'
  - name: 'M04: Posts'
  - name: 'M05: Notifications and Interactions'
  - name: 'M06: Administration' 


################ AUTHENTICATION ################

### Login ###

paths:

  /login:
    get:
      operationId: R101
      summary: 'R101: Login Form'
      description: 'Provide login form. Access: PUB'
      tags:
        - 'M01: Authentication'
      responses:
        '200':
          description: 'Ok. Show log-in UI'
    post:
      operationId: R102
      summary: 'R102: Login Action'
      description: 'Processes the login form submission. Access: PUB'
      tags:
        - 'M01: Authentication'
 
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                email:
                  type: string
                password:
                  type: string
              required:
                - email
                - password
 
      responses:
        '302':
          description: 'Redirect after processing the login credentials.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect home page.'
                  value: '/home'
                302Error:
                  description: 'Failed authentication. Redirect to login form.'
                  value: '/login'
 
### Logout ###

  /logout:
    post:
      operationId: R103
      summary: 'R103: Logout Action'
      description: 'Logout the current authenticated used. Access: USR, ADM'
      tags:
        - 'M01: Authentication'
      responses:
        '302':
          description: 'Redirect after processing logout.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful logout. Redirect to login form.'
                  value: '/login'

### REGISTER ###

  /register:
    get:
      operationId: R104
      summary: 'R104: Register Form'
      description: 'Provide new user registration form. Access: PUB'
      tags:
        - 'M01: Authentication'
      responses:
        '200':
          description: 'Ok. Show sign-up UI'

    post:
      operationId: R105
      summary: 'R105: Register Action'
      description: 'Processes the new user registration form submission. Access: PUB'
      tags:
        - 'M01: Authentication'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                username:
                  type: string
                email:
                  type: string
                password:
                  type: string
                confirm_password:
                  type: string
              required:
                - name
                - username
                - email
                - password
                - confirm_password

      responses:
        '302':
          description: 'Redirect after processing the new user information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to home page.'
                  value: '/home'
                302Failure:
                  description: 'Failed authentication. Redirect to register form.'
                  value: '/register'

################ INDIVIDUAL PROFILE ################

### View Profile ###

  /users/{id}:
    get:
      operationId: R201
      summary: 'R201: View user profile'
      description: 'Show the individual user profile. Access: PUB'
      tags:
        - 'M02: Individual Profile'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Ok. Show profile UI'
        '302':
          description: 'Redirect if user does not exist.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Failure:
                  description: 'User not found.'
                  value: '/home'
    
    delete:
      operationId: R202
      summary: 'R202: Delete user profile'
      description: 'Processes the user profile deletion. Access: USR, ADM'
      tags:
        - 'M02: Individual Profile'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '302':
          description: 'Redirect after processing the user profile deletion.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful profile deletion. Redirect to login form.'
                  value: '/login'
                302Failure:
                  description: 'Failed profile deletion. Redirect to user profile.'
                  value: '/users/{id}'

### Edit Profile ###

  /users/edit-profile:
    get:
      operationId: R203
      summary: 'R203: Edit user profile form'
      description: 'Show the individual user profile edit form. Access: OWN, ADM'
      tags:
        - 'M02: Individual Profile'

      responses:
        '200':
          description: 'Ok. Show edit profile UI.'
        '401':
          description: 'Unauthorized. Redirect to user profile.'
          headers:
            Location:
              schema:
                type: string
              examples:
                401Unauthorized:
                  description: 'User is not authorized to edit profile.'
                  value: '/users/{id}'
        
    put:
      operationId: R204
      summary: 'R204: Edit user profile action'
      description: 'Processes the user profile edit form submission. Access: OWN, ADM'
      tags:
        - 'M02: Individual Profile'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                username:
                  type: string
                email:
                  type: string
                password:
                  type: string
                confirm_password:
                  type: string
                description:
                  type: string
                image:
                  type: string
                  format: binary
              required:
                - name
                - username
                - email
                - description

      responses:
        '302':
          description: 'Redirect after processing the user profile edit information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful profile edit. Redirect to user profile.'
                  value: '/users/{id}'
                302Failure:
                  description: 'Failed profile edit. Redirect to edit profile form.'
                  value: '/users/edit-profile'


################ NAVIGATION AND BROWSING ################

### Home Page ###

  /home:
    get:
      operationId: R301
      summary: 'R301: View Home Page'
      description: 'Show the home page. Access: PUB'
      tags:
        - 'M03: Navigation and Browsing'
      responses:
        '200':
          description: 'Ok. Show home page UI.'
  
  /feed:
    get:
      operationId: R302
      summary: 'R302: View Feed'
      description: 'Show the feed page. Access: USR, ADM'
      tags:
        - 'M03: Navigation and Browsing'
      responses:
        '200':
          description: 'Ok. Show feed page UI.'
  
### Search Questions ###

  /questions/search:
    get:
      operationId: R303
      summary: 'R303: Search Questions'
      description: 'Search for questions using keywords or phrases, supporting both exact matches and full-text search. Returns the results UI. Access: PUB'
      tags:
        - 'M03: Navigation and Browsing'
      parameters:
        - in: query
          name: query
          schema:
            type: string
          description: 'Search query for questions. Supports exact phrase matches and full-text search capabilities.'
        - in: query
          name: exact_match
          schema:
            type: integer
            enum: [0, 1]
          description: 'Flag to indicate if the search should be an exact match or not.'
        - in: query
          name: page
          schema:
            type: integer
          description: 'Page number for pagination.'
      responses:
        '200':
          description: 'Ok. Show search results UI.'
        '400':
          description: 'Bad Request. Invalid query parameters.'

  /api/questions/search:
    get:
      operationId: R304
      summary: 'R304: Search Questions'
      description: 'Search for questions using keywords or phrases, supporting both exact matches and full-text search. Returns the results as JSON. Access: PUB'
      tags:
        - 'M03: Navigation and Browsing'
      parameters:
        - in: query
          name: query
          schema:
            type: string
          description: 'Search query for questions. Supports exact phrase matches and full-text search capabilities.'
        - in: query
          name: exact_match
          schema:
            type: integer
            enum: [0, 1]
          description: 'Flag to indicate if the search should be an exact match or not.'
        - in: query
          name: page
          schema:
            type: integer
          description: 'Page number for pagination.'
      responses:
        '200':
          description: 'Ok. Show search results as JSON.'
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    posts_id:
                      type: integer
                    title:
                      type: string
                    date:
                      type: string
                      format: date-time
                    user_id:
                      type: integer
                    username:
                      type: string
              example:
                - posts_id: 1
                  title: "Effective Study Techniques for Engineering Mathematics?"
                  date: "2023-10-01 12:00:00"
                  user_id: 1
                  username: "John Doe"
                - posts_id: 2
                  title: "Resources for Understanding Circuit Analysis?"
                  date: "2023-10-02 15:30:00"
                  user_id: 2
                  username: "Jane Smith"
        '400':
          description: 'Bad Request. Invalid query parameters.'
 

################ POSTS ################

### Questions ###

  /questions/{id}:
    get:
      operationId: R401
      summary: 'R401: View Question'
      description: 'Show the individual question UI. Access: PUB'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Ok. Show question UI.'
        '302':
          description: 'Redirect if question does not exist.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Failure:
                  description: 'Question does not exist.'
                  value: '/home'
    
    put:
      operationId: R402
      summary: 'R402: Edit Question Action'
      description: 'Processes the question edit form submission. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                title:
                  type: string
                content:
                  type: string
              required:
                - title
                - content

      responses:
        '302':
          description: 'Redirect after processing the question edit information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful question edit. Redirect to question.'
                  value: '/questions/{id}'
                302Failure:
                  description: 'Failed question edit. Redirect to edit question form.'
                  value: '/questions/{id}/edit'
    delete:
      operationId: R403
      summary: 'R403: Delete Question'
      description: 'Processes the question deletion. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '302':
          description: 'Redirect after processing the question deletion.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful question deletion. Redirect to questions page.'
                  value: '/questions'
                302Failure:
                  description: 'Failed question deletion. Redirect to question.'
                  value: '/questions/{id}'


  /questions:
    get:
      operationId: R404
      summary: 'R404: Browse Questions'
      description: 'List questions for browsing. Access: PUB'
      tags:
        - 'M04: Posts'
      
      responses:
        '200':
          description: 'Ok. Show questions list UI.'
    
    post:
      operationId: R405
      summary: 'R405: New Question Action'
      description: 'Processes the question submission form. Access: USR, ADM'
      tags:
        - 'M04: Posts'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                title:
                  type: string
                content:
                  type: string
              required:
                - title
                - content

      responses:
        '302':
          description: 'Redirect after processing the question submission.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful question submission. Redirect to questions.'
                  value: '/questions'
                302Failure:
                  description: 'Failed question submission. Redirect to new question form.'
                  value: '/questions/create'
    
  /questions/create:
    get:
      operationId: R406
      summary: 'R406: New Question Form'
      description: 'Show the question submission form. Access: USR, ADM'
      tags:
        - 'M04: Posts'

      responses:
        '200':
          description: 'Ok. Show new question form UI.'

  /questions/{id}/edit:
    get:
      operationId: R407
      summary: 'R407: Edit Question Form'
      description: 'Show the question edit form. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Ok. Show edit question UI.'
        '403':
          description: 'Forbidden. User is not authorized to edit question.'
        '404':
          description: 'Not Found. Question does not exist.'


### Answers ###

  /questions/{id}/answers:
    post:
      operationId: R408
      summary: 'R408: Answer Action'
      description: 'Processes the answer submission form. Access: USR, ADM'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: question_id
          schema:
            type: integer
          required: true

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                content:
                  type: string
              required:
                - content

      responses:
        '302':
          description: 'Redirect after processing the answer submission.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful answer submission. Redirect to question.'
                  value: '/questions/{question_id}'
                302Failure:
                  description: 'Failed answer submission. Redirect to answer form.'
                  value: '/questions/{question_id}/answers/create'

  /questions/{id}/answers/create:
    get:
      operationId: R409
      summary: 'R409: Answer Form'
      description: 'Show the answer submission form. Access: USR, ADM'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: question_id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Ok. Show answer form UI.'
        '404':
          description: 'Not Found. Question does not exist.'

  /answers/{id}:
    put:
      operationId: R410
      summary: 'R410: Edit Answer Action'
      description: 'Processes the answer edit form submission. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                content:
                  type: string
              required:
                - content

      responses:
        '302':
          description: 'Redirect after processing the answer edit information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful answer edit. Redirect to question.'
                  value: '/questions/{question_id}'
                302Failure:
                  description: 'Failed answer edit. Redirect to edit answer form.'
                  value: '/answers/{id}/edit'
    
    delete:
      operationId: R411
      summary: 'R411: Delete Answer'
      description: 'Processes the answer deletion. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '302':
          description: 'Redirect after processing the answer deletion.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful answer deletion. Redirect to question.'
                  value: '/questions/{question_id}'
                302Failure:
                  description: 'Failed answer deletion. Redirect to question.'
                  value: '/questions/{question_id}'


  /answers/{id}/edit:
    get:
      operationId: R412
      summary: 'R412: Edit Answer Form'
      description: 'Show the answer edit form. Access: OWN'
      tags:
        - 'M04: Posts'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Ok. Show edit answer UI.'
        '403':
          description: 'Forbidden. User is not authorized to edit answer.'
        '404':
          description: 'Not Found. Answer does not exist.'

      

################ NOTIFICATIONS AND INTERACTIONS ################

  /api/notifications:
    get:
      operationId: R501
      summary: 'R501: Get User Notifications'
      description: 'List user notifications. Returns the results as JSON. Access: USR, ADM'
      tags:
        - 'M05: Notifications and Interactions'

      responses:
        '200':
          description: 'Ok. Show user notifications.'
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: integer
                    content:
                      type: string
                    date:
                      type: string
                      format: date-time
              example:
                - id: 1
                  content: "Your question has received a new answer. "
                  date: "2023-10-01 12:30:00"
                - id: 2
                  content: "Congratulations! You have earned a new badge: First Correct Answer!"
                  date: "2023-10-01 13:00:00"
        '404':
          description: 'User not found.'
          content:
            application/json:
              schema:
                type: object
                properties:
                  error:
                    type: string
              example:
                error: "User not found."

  /api/notifications/{id}:
    put:
      operationId: R502
      summary: 'R502: Mark Notification as Read'
      description: 'Processes the notification status update. Access: USR, ADM'
      tags:
        - 'M05: Notifications and Interactions'

      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: 'Notification marked as read successfully.'
          content:
            application/json:
              schema:
                type: object
                properties:
                  message:
                    type: string
              example:
                message: 'Notification marked as read.'
        '404':
          description: 'Notification not found or unauthorized.'
          content:
            application/json:
              schema:
                type: object
                properties:
                  error:
                    type: string
              example:
                error: 'Notification not found or unauthorized.'

  /api/notifications/mark-all-read:
    put:
      operationId: R503
      summary: 'R503: Mark All Notifications as Read'
      description: 'Marks all user notifications as read. Access: USR, ADM'
      tags:
        - 'M05: Notifications and Interactions'
      responses:
        '200':
          description: 'All notifications marked as read successfully.'
          content:
            application/json:
              schema:
                type: object
                properties:
                  message:
                    type: string
              example:
                message: 'All notifications marked as read.'
        '404':
          description: 'No notifications found.'
          content:
            application/json:
              schema:
                type: object
                properties:
                  error:
                    type: string
              example:
                error: 'No notifications found.'


################ ADMINISTRATION ################

  /admin/users/{id}:
    delete:
      operationId: R601
      summary: 'R601: Delete User'
      description: 'Delete a user by ID. Access: ADM'
      tags:
        - 'M06: Administration'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
          description: 'ID of the user to delete'
      responses:
        '302':
          description: 'Redirect after processing the user deletion.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful user deletion. Redirect to dashboard.'
                  value: '/admin/dashboard'
                302Failure:
                  description: 'Failed user deletion. Redirect to dashboard.'
                  value: '/admin/dashboard'

  /admin/dashboard:
    get:
      operationId: R602
      summary: 'R602: Admin Dashboard'
      description: 'Show the admin dashboard. Access: ADM'
      tags:
        - 'M06: Administration'
      responses:
        '200':
          description: 'Ok. Show admin dashboard UI.'
        '302':
          description: 'Redirect if user is not an admin.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Unauthorized:
                  description: 'User is not an admin.'
                  value: '/home'

```

---


## A8: Vertical prototype

The Vertical Prototype encompasses the implementation of features deemed necessary in the common and theme requirements of the project. In alignment with this, we have focused on implementing all high-priority user stories, as detailed in the sections below.
The primary objective of this artifact is to validate the proposed architecture and allow us to gain practical experience with the technologies used in the project.
As recommended, the implementation builds upon the LBAW Framework, integrating work across all layers of the application architecture.

### 1. Implemented Features

#### 1.1. Implemented User Stories

> This section includes the user stories implemented in the vertical prototype. 

| User Story reference | Name               | Priority | Responsible | Description |
| -------------------- | --------- | ----------- | ------------------ | ----------------------------------------------------- |
| US01 | Sign Up | High | Tiago Pinto | As a Visitor, I want to create an account, so that I can authenticate into the platform. |
| US02 | Sign In | High | Tiago Pinto |  As a Visitor, I want to authenticate into the system, so that I can ask questions and participate. |
| US04 | View Home Page | High | João Martinho |  As a User, I want to view the home page, so that I can start navigating the website. |
| US05 | View Top Questions | High | João Martinho | As a User, I want to view the top questions, so that I can see the most trending questions. |
| US06 | Browse Questions | High | João Martinho | As a User, I want to browse through all questions, so that I can explore various topics of interest. |
| US07 | View Question Details | High | Tiago Pinto | As a User, I want to view the details of a question, so that I can see the full question, answers and comments. |
| US08 | Search Questions | High | Leonardo Teixeira | As a User, I want to search for questions using both exact matches and full-text search, so that I can find specific questions. |
| US15 | View Personal Feed | High | João Martinho | As an Authenticated User, I want to view a personalized feed, so that I can see content relevant to my interests and interactions. |
| US16 | Post Question | High | Tiago Pinto |  As an Authenticated User, I want to post a question, so that I can seek answers or advice from the community. |
| US17 | Post Answer | High | Tiago Pinto | As an Authenticated User, I want to post answers to questions, so that I can contribute my knowledge and help others. |
| US18 | View My Questions | High | Leonardo Teixeira |  As an Authenticated User, I want to view all the questions I have posted, so that I can track and manage my contributions. |
| US19 | View My Answers | High | Leonardo Teixeira | As an Authenticated User, I want to view all the answers I have posted, so that I can track my engagement with the community. |
| US20 | View Profile | High | Leonardo Teixeira | As an Authenticated User, I want to view my profile, so that I can see my personal information. |
| US21 | Edit Profile | High | Leonardo Teixeira | As an Authenticated User, I want to edit my profile, so that I can keep my information up-to-date. |
| US22 | Manage Notifications | High | Leonardo Teixeira | As an Authenticated User, I want to receive and view my notifications regarding answers to my questions, votes on my content and questions/tags that I follow, so that I can stay updated and improve my experience on the platform. |
| US23 | See Personal Score | High | Leonardo Teixeira | As an Authenticated User, I want my score to be updated based on votes on my questions and answers, so that I can track my reputation and contributions to the platform. |
| US24 | Log Out | High | Tiago Pinto | As an Authenticated User, I want to log out of my account, so that I can exit the platform safely. |
| US38 | Edit Question | High | João Martinho | As a Question Author, I want to edit my question, so that I can improve clarity or correct any mistakes. |
| US39 | Delete Question | High | João Martinho | As a Question Author, I want to delete my question, so that I can remove it if it's no longer relevant. |
| US42 | Edit Answer | High | João Martinho | As an Answer Author, I want to edit my answer, so that I can improve clarity or correct any mistakes. |
| US43 | Delete Answer | High | João Martinho | As an Answer Author, I want to delete my answer, so that I can remove it if it's no longer relevant. |
| US49 | Administer User Accounts | High | Tiago Pinto | As an Admin, I want to search, view, edit and create user accounts, so that I can effectively manage users on the platform. |

*Table 3: User stories implemented in the vertical prototype*

#### 1.2. Implemented Web Resources

> Identify the web resources that were implemented in the prototype.  

> Module M01: Authentication

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R101: Login Form       | GET /login                     |
| R102: Login Action     | POST /login                    |
| R103: Logout Action    | POST /logout                   |
| R104: Register Form    | GET /register                  |
| R105: Register Action  | POST /register                 |

*Table 4: Authentication implementation*


> Module M02: Individual Profile

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R201: View User Profile| GET /users/{id}                |
| R202: Delete User Profile | DELETE /users/{id}          |
| R203: Edit User Profile Form | GET /users/edit-profile  |
| R204: Edit User Profile Action | PUT /users/edit-profile|

*Table 5: Individual Profile implementation*

> Module M03: Navigation and Browsing

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R301: View Home Page   | GET /home                      |
| R302: View Feed        | GET /feed                      |
| R303: Search Questions (UI) | GET /questions/search     |
| R304: Search Questions (JSON) | GET /api/questions/search |

*Table 6: Navigation and Browsing implementation*

> Module M04: Posts

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R401: View Question    | GET /questions/{id}            |
| R402: Edit Question Action | PUT /questions/{id}        |
| R403: Delete Question  | DELETE /questions/{id}         |
| R404: Browse Questions | GET /questions                 |
| R405: New Question Action | POST /questions             |
| R406: New Question Form | GET /questions/create         |
| R407: Edit Question Form | GET /questions/{id}/edit     |
| R408: Answer Action    | POST /questions/{id}/answers   |
| R409: Answer Form      | GET /questions/{id}/answers/create |
| R410: Edit Answer Action | PUT /answers/{id}            |
| R411: Delete Answer    | DELETE /answers/{id}           |
| R412: Edit Answer Form | GET /answers/{id}/edit         |

*Table 7: Posts implementation*

> Module M05: Notifications and Interactions

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R501: Get User Notifications | GET /api/notifications   |
| R502: Mark Notification as Read | PUT /api/notifications/{id} |
| R503: Mark All Notifications as Read | PUT /api/notifications/mark-all-read |

*Table 8: Notifications and Interactions implementation*

> Module M06: Administration

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R601: Delete User      | DELETE /admin/users/{id}       |
| R602: Admin Dashboard  | GET /admin/dashboard           |

*Table 9: Administration implementation*


### 2. Prototype

You can find the prototype Docker image in GitLab's Container Registry, ready to be launched with the following command:
```
docker run -d --name lbaw24153 -p 8001:80 gitlab.up.pt:5050/lbaw/lbaw2425/lbaw24153
```
The prototype will be available at localhost:8001

|Type of User | Email | Password |
|---|---|---
| Regular | john_doe@fe.up.pt | 1234 |
| Admin | admin_user@fe.up.pt | hashed_password_admin |

*Table 10: AskUni Credentials*

The code is available at: https://gitlab.up.pt/lbaw/lbaw2425/lbaw24153

---


## Revision history

Changes made to the first submission:
* Create EAP 05/11/2024
* Start A7 24/11/2024
* Finish A7 24/11/2024
* Start A8 24/11/2024
* Finish A8 24/11/2024


---

GROUP153, 24/11/2024

* Tiago Pinto, up202206280@up.pt 
* Leonardo Teixeira, up202208726@up.pt (Editor)
* João Martinho, up202204883@up.pt 
