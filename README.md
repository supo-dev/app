# Supo.dev - The Social Media For The Terminal.

## API Features:

### Registration (end)
- [POST] **/users - Register New User**
- [DELETE] **/users - Delete User Account**

### Login & Session Management (end)
- [GET] **/sessions - Check Current Session**
- [POST] **/sessions - Login**
- [DELETE] **/sessions - Logout**

### Profile Management

- [GET] **/users/{user_id} - Get User Profile by Username**
- [PUT] **/users/{user_id} - Update User Profile**

### Feed Management

- [GET] **/feeds/{feed_type} - Get Feed (feed_type can be 'home' or 'explore')**

### Post Management

- [GET] **/posts/{post_id} - Get Post by ID** DONE 
- [POST] **/posts - Create New Post** DONE
- [DELETE] **/posts/{post_id} - Delete Post by ID** DONE

### Like Management

- [POST] **/likes/{post_id} - Like a Post**
- [DELETE] **/likes/{post_id} - Unlike a Post**

### Follow Management

- [POST] **/follows/{user_id} - Follow a User**
- [DELETE] **/follows/{user_id} - Unfollow a User**
