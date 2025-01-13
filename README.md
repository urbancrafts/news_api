

## Project setup guide

1. clone the repository into your local environment
2. ensure you have composer running on your manchine then open the cloned project in your VS code editor using windows command, shell or terminal depending on your Operating system. To do so on windows, open your command and type cd directory name to locate the path of the downloaded cloned project and type code . to open the project in the VS code editor.
3. Once opened in the editor ensure to create a .env file which I will later on paste its contents below, locate the view menu at the top bar and click on the terminal to open the terminal tool then type in "composer update". This command will help installing the Laravel denpencies which are all located in the vendor directory.
4. Once the above is done, then it is necessary to copy and paste the below content into the .env file in the project as it is not usually allowed to be pushed to the github repository cause it is considered to be the entire application configuration file and for security reasons would not be part of any commit. This file includes API credention as well as database credentails which I will be including below.
5. APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:7E82BDcFMSW0OmEztMYg+PWsy+B0uKMWN67uuToVsh8=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=articles
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120  

NY_TIMES_API_KEY=73AqY4A7Gi3ZL0FUjb6cmoxQTY3uF0xX
THE_GUARDIAN_API_KEY=7db17eac-e054-4b0f-872e-711164cbed7f
NEWSAPI_DOTORG_KEY=3b5da69e15c64ec0af0b085c94a8d32a

6. after pasting the the above content, ensure your databade credentials are used correctly in setting up your local MyQSL database machine. Afterwards, type "php artisan migrate" on your terminal to migrate your database schema to your MySQL server.
7. The next step would be to start your Laravel server with "php artisan serve" command, open a new terminal by click on the + sign on the top of your terminal and select Git bash if that is what you're using or powershell and on newly opned terminal type "php artisan articles:poll" which will manually pull articles from the source integrated and it also updates hourly.

## Note
the three credentials below are for the three integrated sources

NY_TIMES_API_KEY=73AqY4A7Gi3ZL0FUjb6cmoxQTY3uF0xX
THE_GUARDIAN_API_KEY=7db17eac-e054-4b0f-872e-711164cbed7f
NEWSAPI_DOTORG_KEY=3b5da69e15c64ec0af0b085c94a8d32a


Thanks you.



The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
