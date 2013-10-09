RestFull API using CodeIgniter &amp; Doctrine 2 with a working example of a Login
==================

This is a working example of how to use the CodeIgniter RestFull API along with Doctrine.

It is meant to show you how how to build a login system with CI RESTAPI (which might be tricky since you have to extend at least one controller from the original CI_controller rather than from the REASTAPI controller).
Also, using Doctrine with CodeIgniter RESTAPI is tricky since some of the features are conflicting each other.
I had to ask a few questions on StackOverflow to get it working (see below).

The App is divided into 2 differents folder: the front side and the back side, completely seprataed from each other (yeah, we're talking about an API, so basically you will have the view on some device and the back side on your server)

So, in order to get it working, you should follow the following steps:
1 - Clone the repo wherever you want
2 - Go in the root directory /application/config/database.php and adapt it to your need (DB credentials and table)
3 - Create a Database for your project
4 - Open the terminal or gitbash on W and generate methods from entities (see commands below)
5 - Generate Proxies (See command Below)
6 - Generate the table automatically (see Below as well) those 3 commands are from Doctrine functionnalities
7 - You should now be able to access the application, from you browser access your localhost/Folder of your application/front/login.php
8 - Register as a new user
9 - Login ! you have now stored a Private key in both your cache and your database, you can do an alert to display it and see it is the same as the one in your DB
10 - Log out. The key is removed
ENJOY! You have now a full login system working with CI and Doctrine, you can, then, use the CI RestFull API as shown in the Documentation. It's pretty straightforward. Lokk at the controller key.php and look how it works

Also, please read the following ressources to understand how it works, these are the ressources I've been using to set up this project:

Officials:
Doctrine2 ressources (we are usig the ORM): http://www.doctrine-project.org/
CodeIgniter ressources: http://ellislab.com/codeigniter

CodeIgniter and Doctrine 2:
http://docs.doctrine-project.org/en/2.0.x/cookbook/integrating-with-codeigniter.html
http://www.joelverhagen.com/blog/2011/05/setting-up-codeigniter-2-with-doctrine-2-the-right-way/
http://wildlyinaccurate.com/integrating-doctrine-2-with-codeigniter-2

CodeIgniter RestAPI (very easy to use):
https://github.com/philsturgeon/codeigniter-restserver
http://net.tutsplus.com/tutorials/php/working-with-restful-services-in-codeigniter-2/
http://blip.tv/nerdy-adventures-of-phil-sturgeon/set-up-a-rest-api-with-codeigniter-4917931

A few topics that helped me using Doctrine 2 with CI (I't relevant to note that I had to modify one file in Doctrine2):
http://stackoverflow.com/questions/18424146/why-are-my-symfony-doctrine2-entites-different-from-my-ci-d2-ones-ultimately/18426615?noredirect=1#18426615
http://stackoverflow.com/questions/18429762/why-is-dbal-returning-a-sql-syntax-error?noredirect=1#comment27077727_18429762

I strongly recommand installing Composer to update Doctrine and CI to the latest versions.


At least here are a few useful commands to use with doctrine: