### Telegram-bot-php
This is my first project.  
I developed this bot for 3 month, starting with no knoledge about PHP, MySQL and Telegram API. 
This telegram bot was written using only PHP7 and MySQL, it comunicates with Telegram via its API and with payment systems using their  API. 
 
Telegram bot creates account of a new user, accepts payments in Bitcoin (APIBTC) and electronic USD via several payment systems (payeer,   adv cash, perfect money) and create a new deposit for each user.   
Then it calculated estimated profit and put some amount on user account every day.  
/HBOT/Count/AddPers.php - here the amount is calculated by cron tab.  
It also send automatically daily profit to each user on demand through payment gateways.  
/HBOT/DB/ - Database class and settings are located here.  
Main part of the bot and classed are located in /HBOT/TelegramSpecial/  

To use this bot you need at least a fast and reliable hosting, or VPS.   
Then, load all the files to the parent directory.  
You need PHP 7 and MySQL.  
Create a new telegram bot and set a new token.   
Define it in /HBOT/TelegramAPI/bset.php  
Then create an acounts in all payment system you need and set the values in /HBOT/Funds/  
Set all the walues you need in /config/  
Create a new cron tab for /HBOT/cron.php  for once a minute.  
Here you go.  
