# Supo.dev - The Social Media For The Terminal.

## API Features:

### Post Management

- [GET] **/posts/{post_id} - Get Post by ID** DONE
- [POST] **/posts - Create New Post** DONE
- [DELETE] **/posts/{post_id} - Delete Post by ID** DONE

### Follow Management

- [POST] **/follows/{user_id} - Follow a User** DONE
- [DELETE] **/follows/{user_id} - Unfollow a User** DONE

### Like Management

- [POST] **/likes/{post_id} - Like a Post** DONE
- [DELETE] **/likes/{post_id} - Unlike a Post** DONE

### Registration (end)
- [POST] **/users - Register New User**
- [DELETE] **/users - Delete User Account**

### Login & Session Management (end)
- [GET] **/sessions - Check Current Session**
- [POST] **/sessions - Login**
- [DELETE] **/sessions - Logout**

### Profile Management

- [GET] **/users/{user_id} - Get User Profile by Username** DONE
- [PUT] **/users/{user_id} - Update User Profile**

### Feed Management

- [GET] **/feeds/{feed_type} - Get Feed (feed_type can be 'home' or 'explore')**
