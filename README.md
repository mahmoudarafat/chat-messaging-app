# chat-messaging-app

# Follow the tutorial instruction comes with the project for detailed instructions.

#After downloading and extracting this repo, go to the root folder.
#Ensure, you have a Composer, XAMPP installed.
#in root folder open the terminal and run this command
# composer install
#this will install all the project dependencies.
#then run this command
# php artisan key:generate
#this will generate a new key for project security and developing.
# now, config the database from .env file
# php artisan migrate
#now, install passport
# php artisan passport:install
#then serve the project
# php artisan serve
#now in browser go to the localhost url generated to view the project.
#You can register new account or login to your existing account.
#now,after login, you see the list of other users.
#click on the username in the list to start chatting with him.
#in each chat view you find the messages between you and the other users only.
# Postman test file included in public/postman folder
#you will just need to change the auth token saved in the environment 
