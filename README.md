# Simple Voting System
The task is to build a simple voting system that allows a user to vote, only once, on a
choice of 4 items. The 4 items can be fixed, and do not need to be content managed.
You should use email verification to ensure that the user is legitimate, and put into place
rules to ensure only one vote per email address can be logged. Alongside registering the
user's vote, their IP address and estimated location should also be stored.
Storing votes should be queued, as this tool is expecting high traffic.
At the end of each day 23:59, an email should be sent to the default poll results email, with the
totals of the scores.
The user interface should be simple and clean, with the front-end built in Vue.js and
TailwindCSS (or your preferred CSS framework) and back-end build using Laravel (API with
Sanctum auth)

### Installation Guide
Run command: git clone https://github.com/SumAnna/simple-vote.git<br />
Run command: cd simple-vote<br />
Edit .env.example file and rename it to .env<br />
Run command: composer install or pjp composer.phar install<br />
Run command: php artisan key:generate<br />
Run command: php artisan migrate<br />
Run command: php artisan db:seed<br />
Run command: npm run dev<br />
To run tests run the command: php artisan test<br />
To listen to scheduled commands locally run: php artisan queue:work<br />
